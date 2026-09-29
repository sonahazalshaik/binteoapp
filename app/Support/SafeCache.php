<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * Self-healing cache wrapper.
 *
 * Prevents fatal "call a method on an incomplete object" errors caused by
 * stale/corrupt serialized objects in the cache (e.g. after deploys that
 * change class structures, or when using the database cache driver).
 *
 * Any cached value whose object graph contains an unresolved
 * __PHP_Incomplete_Class is treated as poison: purged and rebuilt fresh.
 */
class SafeCache
{
    public static function remember(string $key, int $ttl, \Closure $callback): mixed
    {
        try {
            $cached = Cache::get($key);
        } catch (\Throwable) {
            $cached = null;
        }

        if ($cached !== null && self::isHealthy($cached)) {
            return $cached;
        }

        if ($cached !== null) {
            try {
                Cache::forget($key);
            } catch (\Throwable) {
            }
        }

        $fresh = $callback();

        try {
            Cache::put($key, $fresh, $ttl);
        } catch (\Throwable) {
        }

        return $fresh;
    }

    public static function isHealthy(mixed $value): bool
    {
        // Collections/iterables must be inspected INSIDE as well —
        // a healthy wrapper can contain poisoned children.
        if ($value instanceof \Traversable) {
            foreach ($value as $item) {
                if (!self::isHealthy($item)) {
                    return false;
                }
            }

            return self::isHealthyObject($value);
        }

        if (is_object($value)) {
            return self::isHealthyObject($value);
        }

        if (is_array($value)) {
            foreach ($value as $item) {
                if (!self::isHealthy($item)) {
                    return false;
                }
            }
        }

        return true;
    }

    private static function isHealthyObject(object $value): bool
    {
        if (get_class($value) === '__PHP_Incomplete_Class') {
            return false;
        }

        return class_exists(get_class($value));
    }
}
