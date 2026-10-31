<?php

namespace App\Lib;

use Illuminate\Support\Facades\Log;

class EmailDns
{
    /**
     * DNS-independent syntactic check: requires user@domain.tld shape
     * (a dot + 2+ letter TLD). Laravel's email:rfc alone accepts
     * single-label domains like batas@ylcn, so this gate must run
     * BEFORE any MX/DNS lookup — it works even with no internet.
     */
    public static function isWellFormed(string $email): bool
    {
        $email = trim($email);
        if ($email === '' || strlen($email) > 255 || preg_match('/\s/', $email)) {
            return false;
        }
        return (bool) preg_match('/^[a-z0-9._%+-]+@[a-z0-9-]+(\.[a-z0-9-]+)*\.[a-z]{2,}$/i', $email);
    }

    /**
     * Resilient MX check for the app's custom DNS setup.
     *
     * - Returns true when the domain has an MX record (via checkdnsrr or dns_get_record fallback).
     * - Returns false only when DNS itself is working (gmail.com/google.com MX resolves)
     *   but the given domain has no MX — i.e. a genuinely bad mail domain.
     * - Fail-OPEN (returns true) when the local DNS server is down/unreachable,
     *   so valid addresses like ameensonatest@gmail.com are never blocked by a
     *   transient DNS failure on 127.0.0.1 / Laragon.
     */
    public static function hasMailServer(string $domain): bool
    {
        $domain = strtolower(trim($domain));
        if ($domain === '' || !str_contains($domain, '.')) {
            return false;
        }

        // 1. Primary: MX via checkdnsrr
        try {
            if (function_exists('checkdnsrr') && @checkdnsrr($domain, 'MX')) {
                return true;
            }
        } catch (\Throwable $e) {
            // fall through to fallback
        }

        // 2. Fallback: dns_get_record (covers hosts where checkdnsrr is flaky)
        try {
            $records = @dns_get_record($domain, DNS_MX);
            if (is_array($records) && count($records) > 0) {
                return true;
            }
        } catch (\Throwable $e) {
            // fall through to health check
        }

        // 3. Health check: can WE resolve known-good mail domains right now?
        // If not, the local DNS server / network is at fault — fail open.
        try {
            $dnsWorks = false;
            if (function_exists('checkdnsrr')) {
                $dnsWorks = @checkdnsrr('gmail.com', 'MX') || @checkdnsrr('google.com', 'MX');
            }
            if (!$dnsWorks) {
                $fallback = @dns_get_record('gmail.com', DNS_MX);
                $dnsWorks = is_array($fallback) && count($fallback) > 0;
            }
            if (!$dnsWorks) {
                Log::warning("EmailDns: local DNS unreachable, skipping MX enforcement for [{$domain}].");
                return true;
            }
        } catch (\Throwable $e) {
            Log::warning('EmailDns: DNS health check threw, skipping MX enforcement for [' . $domain . '].');
            return true;
        }

        // DNS works but this domain has no MX → genuinely invalid mail domain.
        return false;
    }
}
