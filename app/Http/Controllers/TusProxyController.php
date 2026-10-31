<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * TUS Proxy Controller
 *
 * Proxies TUS upload requests from the browser to Bunny.net.
 * All requests are same-origin (/tus-proxy), which eliminates the
 * mobile Chrome CORS failures seen with direct browser -> Bunny TUS.
 *
 * 409 Conflict handling is offset-aware:
 *  - If Bunny's stored offset matches the client's requested offset,
 *    the chunk is retried as-is.
 *  - If Bunny has partially stored the current chunk, only the missing
 *    remainder of the in-memory chunk body is forwarded.
 *  - If Bunny already has more bytes than this chunk covers, the chunk
 *    is reported as complete (204) without re-sending data.
 *  - If Bunny reports an offset that cannot be reconciled with the
 *    chunk in memory (e.g. an implausible "2" for a multi-MB offset),
 *    the request FAILS SAFELY with a non-retryable HTTP 400 so the
 *    frontend falls back to the server-side direct upload instead of
 *    entering a CREATE -> PATCH -> 409 loop or corrupting the stream.
 */
class TusProxyController extends Controller
{
    private const BUNNY_TUS_ENDPOINT = 'https://video.bunnycdn.com/tusupload';

    /**
     * Maximum number of 409 offset-recovery attempts per PATCH request.
     * After this limit the upload fails with a controlled error instead of
     * retrying forever. The frontend then falls back to direct upload.
     */
    private const MAX_OFFSET_RECOVERY_ATTEMPTS = 3;

    /**
     * Handle TUS POST (create upload) requests.
     *
     * IMPORTANT: Uses withBody() instead of post($url, '') to ensure
     * the body is sent as raw content with the correct Content-Type,
     * not as form-encoded data which would conflict with TUS protocol.
     */
    public function create(Request $request)
    {
        $videoId = $request->header('VideoId');
        $url = self::BUNNY_TUS_ENDPOINT;

        $this->logEvent('create.request', [
            'bunny_video_id' => $videoId,
            'upload_length' => $request->header('Upload-Length'),
            'upload_metadata' => substr((string) $request->header('Upload-Metadata'), 0, 255),
            'user_id' => auth()->id(),
        ]);

        $headers = $this->extractTusHeaders($request);

        try {
            // Use withBody() to send raw empty body with correct Content-Type.
            // Previously: ->post($url, '') which sent form-encoded data,
            // conflicting with Content-Type: application/offset+octet-stream.
            $response = Http::withHeaders($headers)
                ->timeout(30)
                ->connectTimeout(10)
                ->withBody('', 'application/offset+octet-stream')
                ->post($url);

            $this->logEvent('create.response', [
                'bunny_video_id' => $videoId,
                'status' => $response->status(),
                'location' => $response->header('Location'),
                'tus_resumable' => $response->header('Tus-Resumable'),
            ]);

            return $this->proxyResponse($response);
        } catch (\Exception $e) {
            Log::channel('tus')->error('TUS Proxy: CREATE failed', [
                'event' => 'create.exception',
                'bunny_video_id' => $videoId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'timestamp' => now()->toIso8601String(),
            ]);
            return response('Proxy error: ' . $e->getMessage(), 502);
        }
    }

    /**
     * Handle TUS PATCH (upload chunk) requests.
     *
     * Identifier clarification (do not conflate):
     *  - $uploadId (route param) = Bunny TUS SESSION ID from the Location
     *    header returned at creation. It is NOT the video GUID.
     *  - VideoId header          = the Bunny VIDEO GUID (signed into the
     *    AuthorizationSignature). Used only for forwarding/auth headers.
     */
    public function patch(Request $request, string $uploadId)
    {
        $startedAt = microtime(true);
        $url = self::BUNNY_TUS_ENDPOINT . '/' . $uploadId;
        $bunnyVideoGuid = $request->header('VideoId');

        $elapsed = function () use ($startedAt) {
            return (int) round((microtime(true) - $startedAt) * 1000);
        };

        // STAGE 1: request received
        $this->logEvent('patch.controller.entered', [
            'tus_upload_id' => $uploadId,
            'bunny_video_guid' => $bunnyVideoGuid,
            'client_offset' => $this->normalizeOffset($request->header('Upload-Offset')),
            'declared_content_length' => $request->header('Content-Length'),
            'request_content_type' => $request->header('Content-Type'),
            'user_id' => auth()->id(),
            'timestamp' => now()->toIso8601String(),
        ]);

        $clientOffset = $this->normalizeOffset($request->header('Upload-Offset'));

        // STAGE 2: read the full chunk body from php://input directly.
        // Using file_get_contents('php://input') instead of $request->getContent()
        // to bypass any middleware that may have triggered Laravel's input parsing,
        // which can consume/corrupt the raw binary body on some PHP configurations.
        $body = file_get_contents('php://input');
        if ($body === false) {
            // Fallback to Symfony's cached content
            $body = $request->getContent();
        }
        $bodyLength = strlen($body);

        $context = [
            'tus_upload_id' => $uploadId,
            'bunny_video_guid' => $bunnyVideoGuid,
            'client_offset' => $clientOffset,
            'patch_content_length' => $bodyLength,
            'declared_content_length' => $request->header('Content-Length'),
            'user_id' => auth()->id(),
        ];

        if ($clientOffset === null || $bodyLength === 0) {
            $context['body_empty_reason'] = ($bodyLength === 0)
                ? 'php://input returned 0 bytes - possible middleware body consumption'
                : 'Upload-Offset header missing or invalid';
            $this->logEvent('patch.invalid_request', $context);
            return response('Invalid Upload-Offset or empty chunk body', 400);
        }

        // Verify body length matches declared Content-Length
        $declaredLength = (int) $request->header('Content-Length', '0');
        if ($declaredLength > 0 && $bodyLength !== $declaredLength) {
            $this->logEvent('patch.body_length_mismatch', [
                'tus_upload_id' => $uploadId,
                'bunny_video_guid' => $bunnyVideoGuid,
                'declared' => $declaredLength,
                'actual' => $bodyLength,
                'elapsed_ms' => $elapsed(),
            ]);
            // Log but don't fail - use what we have
        }

        $this->logEvent('patch.body_received', [
            'tus_upload_id' => $uploadId,
            'bunny_video_guid' => $bunnyVideoGuid,
            'client_offset' => $clientOffset,
            'body_length' => $bodyLength,
            'elapsed_ms' => $elapsed(),
        ]);

        // Build headers for Bunny - DO NOT include Content-Type here,
        // it will be set by withBody() to avoid Guzzle header conflicts.
        $headers = $this->extractTusHeaders($request);
        $headers['Upload-Offset'] = (string) $clientOffset;
        // Suppress the HTTP "Expect: 100-continue" handshake that Guzzle/cURL
        // auto-adds for bodies >1MB. Some server stacks stall or drop these
        // requests mid-flight, which presents to the browser as a response-less
        // network error ("[object ProgressEvent]", code n/a).
        $headers['Expect'] = '';

        try {
            // STAGE 3: forward to Bunny
            $this->logEvent('bunny.patch.start', [
                'tus_upload_id' => $uploadId,
                'bunny_video_guid' => $bunnyVideoGuid,
                'client_offset' => $clientOffset,
                'body_length' => $bodyLength,
                'elapsed_ms' => $elapsed(),
            ]);

            $response = $this->sendPatchToBunny($url, $headers, $body);

            // STAGE 4: Bunny responded
            $stageContext = array_merge($context, [
                'status' => $response->status(),
                'upload_offset' => $response->header('Upload-Offset'),
                'upload_expires' => $response->header('Upload-Expires'),
                'elapsed_ms' => $elapsed(),
            ]);
            $this->logEvent('bunny.patch.response', $stageContext);

            // ---- 409 Conflict: bounded, offset-aware recovery loop ----
            // Never loops on HEAD alone: every iteration either re-PATCHes with
            // reconciled bytes or exits. Hard-capped by MAX_OFFSET_RECOVERY_ATTEMPTS.
            $recoveryAttempt = 0;

            while ($response->status() === 409 && $recoveryAttempt < self::MAX_OFFSET_RECOVERY_ATTEMPTS) {
                $recoveryAttempt++;
                $context['retry'] = $recoveryAttempt;
                $context['status'] = 409;
                $this->logEvent('patch.conflict', $context);

                $headResponse = Http::withHeaders($headers)
                    ->timeout(15)
                    ->connectTimeout(10)
                    ->head($url);

                $rawHeadOffset = $headResponse->header('Upload-Offset');
                $bunnyOffset = $this->normalizeOffset($rawHeadOffset);

                $context['bunny_head_status'] = $headResponse->status();
                $context['bunny_raw_upload_offset'] = $rawHeadOffset;
                $context['bunny_offset'] = $bunnyOffset;

                // Validate: exists, numeric integer >= 0 (normalizeOffset),
                // and <= Upload-Length where Bunny reports one.
                $uploadLength = $this->normalizeOffset($headResponse->header('Upload-Length'));
                if ($bunnyOffset !== null && $uploadLength !== null && $bunnyOffset > $uploadLength) {
                    $context['upload_length'] = $uploadLength;
                    $context['timestamp'] = now()->toIso8601String();
                    Log::channel('tus')->critical('TUS Proxy: Bunny Upload-Offset EXCEEDS Upload-Length - treating as invalid, failing safely', $context);
                    return response('Bunny Upload-Offset (' . $bunnyOffset . ') exceeds Upload-Length (' . $uploadLength . '); upload aborted safely', 400);
                }

                // Invalid HEAD offset (non-numeric / unparseable): fail safely.
                if ($bunnyOffset === null) {
                    $context['timestamp'] = now()->toIso8601String();
                    Log::channel('tus')->critical('TUS Proxy: Bunny HEAD returned an INVALID Upload-Offset - aborting chunk, server fallback required', $context);
                    return response('Bunny reported an invalid Upload-Offset (' . $rawHeadOffset . '); upload aborted safely', 400);
                }

                // Case A: transient conflict at the same offset - retry whole chunk.
                if ($bunnyOffset === $clientOffset) {
                    $context['retry_mode'] = 'full_chunk_same_offset';

                    $response = $this->sendPatchToBunny($url, $headers, $body);
                }
                // Case B: Bunny stored part of THIS chunk - send only the remainder.
                elseif ($bunnyOffset > $clientOffset && $bunnyOffset < ($clientOffset + $bodyLength)) {
                    $remainder = substr($body, $bunnyOffset - $clientOffset);

                    $partialHeaders = $headers;
                    $partialHeaders['Upload-Offset'] = (string) $bunnyOffset;
                    $context['retry_mode'] = 'partial_chunk_remainder';
                    $context['remainder_length'] = strlen($remainder);

                    $response = $this->sendPatchToBunny($url, $partialHeaders, $remainder);
                }
                // Case C: Bunny already has all bytes of this chunk (and possibly more).
                elseif ($bunnyOffset >= ($clientOffset + $bodyLength)) {
                    $context['retry_mode'] = 'chunk_already_stored';
                    $context['timestamp'] = now()->toIso8601String();
                    $this->logEvent('patch.skip', $context);

                    return response('', 204, [
                        'Upload-Offset' => (string) $bunnyOffset,
                        'Tus-Resumable' => '1.0.0',
                    ]);
                }
                // Case D: Bunny offset BEHIND the client offset (e.g. suspicious
                // "2" while the client is uploading chunk N). The discarded bytes
                // are no longer in memory and MUST NOT be faked. Fail safely so
                // the frontend stops retrying and uses the server fallback.
                else {
                    $context['timestamp'] = now()->toIso8601String();
                    Log::channel('tus')->critical('TUS Proxy: Bunny offset is BEHIND the client offset - unrecoverable via proxy, failing safely to trigger fallback', $context);

                    return response('Bunny Upload-Offset (' . $bunnyOffset . ') is behind the requested offset (' . $clientOffset . '); upload aborted safely', 400);
                }

                if ($response->status() !== 409) {
                    $context['retry_mode'] = ($context['retry_mode'] ?? 'recovery') . '_resolved';
                }
            }

            // Recovery attempts exhausted and Bunny still returns 409.
            if ($response->status() === 409) {
                $context['max_recovery_attempts'] = self::MAX_OFFSET_RECOVERY_ATTEMPTS;
                $context['timestamp'] = now()->toIso8601String();
                Log::channel('tus')->error('TUS Proxy: Offset recovery limit reached - stopping retries, controlled failure', $context);

                return response('Upload offset could not be reconciled after ' . self::MAX_OFFSET_RECOVERY_ATTEMPTS . ' recovery attempts; upload aborted safely', 400);
            }

            $context['status'] = $response->status();
            $context['upload_offset'] = $response->header('Upload-Offset');
            $context['tus_resumable'] = $response->header('Tus-Resumable');
            $context['elapsed_ms'] = $elapsed();

            // STAGE 5: sending the final response back to the browser
            $this->logEvent('patch.response.sent', $context);

            return $this->proxyResponse($response);
        } catch (\Exception $e) {
            $context['error'] = $e->getMessage();
            $context['timestamp'] = now()->toIso8601String();
            Log::channel('tus')->error('TUS Proxy: PATCH failed with exception', $context);
            return response('Proxy error: ' . $e->getMessage(), 502);
        }
    }

    /**
     * Send a PATCH request to Bunny TUS endpoint.
     *
     * Centralised to ensure consistent body handling:
     * - Content-Type is set ONLY by withBody() to avoid Guzzle header conflicts
     * - Expect header is suppressed via both header and cURL option
     * - Timeout is generous (120s) for large chunks on slow connections
     */
    private function sendPatchToBunny(string $url, array $headers, string $body): \Illuminate\Http\Client\Response
    {
        return Http::withHeaders($headers)
            ->timeout(120)
            ->connectTimeout(10)
            ->withBody($body, 'application/offset+octet-stream')
            ->patch($url);
    }

    /**
     * Handle TUS HEAD (check status) requests.
     */
    public function head(Request $request, string $uploadId)
    {
        $url = self::BUNNY_TUS_ENDPOINT . '/' . $uploadId;

        $headers = $this->extractTusHeaders($request);

        try {
            $response = Http::withHeaders($headers)
                ->timeout(15)
                ->connectTimeout(10)
                ->head($url);

            $this->logEvent('head.response', [
                'tus_upload_id' => $uploadId,
                'bunny_video_guid' => $request->header('VideoId'),
                'status' => $response->status(),
                'upload_offset' => $response->header('Upload-Offset'),
                'upload_length' => $response->header('Upload-Length'),
                'user_id' => auth()->id(),
                'timestamp' => now()->toIso8601String(),
            ]);

            return $this->proxyResponse($response);
        } catch (\Exception $e) {
            Log::channel('tus')->error('TUS Proxy: HEAD failed', [
                'event' => 'head.exception',
                'tus_upload_id' => $uploadId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'timestamp' => now()->toIso8601String(),
            ]);
            return response('Proxy error: ' . $e->getMessage(), 502);
        }
    }

    /**
     * Handle TUS OPTIONS (CORS preflight) requests.
     */
    public function options(Request $request)
    {
        $origin = $request->header('Origin') ?: '*';
        return response('', 204, [
            'Tus-Resumable' => '1.0.0',
            'Tus-Version' => '1.0.0',
            'Tus-Extension' => 'creation,creation-with-upload',
            'Access-Control-Allow-Origin' => $origin,
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Methods' => 'POST, PATCH, HEAD, OPTIONS',
            'Access-Control-Allow-Headers' => 'Authorization, AuthorizationSignature, AuthorizationExpire, VideoId, LibraryId, Content-Type, Upload-Offset, Upload-Length, Upload-Metadata, Tus-Resumable, X-CSRF-TOKEN, X-Requested-With',
            'Access-Control-Max-Age' => '86400',
            'Access-Control-Expose-Headers' => 'Upload-Offset, Upload-Length, Location, Tus-Resumable, Upload-Expires',
        ]);
    }

    /**
     * Normalize a TUS offset header into a non-negative integer, or null
     * when the value is missing/non-numeric (e.g. Bunny returning garbage).
     */
    private function normalizeOffset($value): ?int
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        if (!preg_match('/^\d+$/', trim((string) $value))) {
            return null;
        }

        return (int) trim((string) $value);
    }

    /**
     * Structured event log for the TUS proxy.
     */
    private function logEvent(string $event, array $context): void
    {
        $context['event'] = $event;
        $context['timestamp'] = $context['timestamp'] ?? now()->toIso8601String();
        Log::channel('tus')->info('TUS Proxy: ' . $event, $context);
    }

    /**
     * Extract TUS-specific headers from the incoming request.
     *
     * NOTE: Content-Type is NOT included here. Each method sets it
     * via withBody() to avoid Guzzle header conflicts that caused
     * body corruption (only 2 bytes forwarded instead of full chunk).
     */
    private function extractTusHeaders(Request $request): array
    {
        $headers = [];

        $tusHeaders = [
            'AuthorizationSignature',
            'AuthorizationExpire',
            'VideoId',
            'LibraryId',
            'Tus-Resumable',
            'Upload-Length',
            'Upload-Metadata',
        ];

        foreach ($tusHeaders as $header) {
            $value = $request->header($header);
            if ($value !== null) {
                $headers[$header] = $value;
            }
        }

        // Always set Tus-Resumable if not present
        if (!isset($headers['Tus-Resumable'])) {
            $headers['Tus-Resumable'] = '1.0.0';
        }

        return $headers;
    }

    /**
     * Convert Bunny's HTTP response into a Laravel response,
     * forwarding important TUS headers.
     */
    private function proxyResponse($response): \Illuminate\Http\Response
    {
        $origin = request()->header('Origin') ?: '*';
        $headers = [
            'Access-Control-Allow-Origin' => $origin,
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Expose-Headers' => 'Upload-Offset, Upload-Length, Location, Tus-Resumable, Upload-Expires',
        ];

        $forwardHeaders = [
            'Upload-Offset',
            'Upload-Length',
            'Location',
            'Tus-Resumable',
            'Upload-Expires',
        ];

        foreach ($forwardHeaders as $header) {
            $value = $response->header($header);
            if ($value !== null && $value !== '') {
                // Rewrite Location header to point to our proxy instead of Bunny
                if ($header === 'Location') {
                    // Handle absolute URL: https://video.bunnycdn.com/tusupload/abc123
                    if (str_contains($value, 'video.bunnycdn.com')) {
                        $value = str_replace(
                            ['https://video.bunnycdn.com/tusupload', 'http://video.bunnycdn.com/tusupload'],
                            '/tus-proxy',
                            $value
                        );
                    }
                    // Handle relative path: /tusupload/abc123
                    elseif (str_starts_with($value, '/tusupload/')) {
                        $value = str_replace('/tusupload/', '/tus-proxy/', $value);
                    }
                    // Handle bare path: tusupload/abc123
                    elseif (str_starts_with($value, 'tusupload/')) {
                        $value = '/tus-proxy/' . substr($value, strlen('tusupload/'));
                    }
                }
                $headers[$header] = $value;
            }
        }

        return response($response->body(), $response->status(), $headers);
    }
}

