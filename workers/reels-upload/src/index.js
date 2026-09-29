/**
 * Reels Upload Worker - Browser -> Worker -> Bunny Storage
 *
 * Security: Verifies HMAC token (v1|userId|reelId|path|op|expiry) signed with WORKER_SIGNING_SECRET.
 * Streams request.body directly to Bunny Storage without buffering (duplex: half).
 * Bunny Storage AccessKey NEVER exposed to browser - only in Worker env.
 *
 * Setup:
 *   wrangler secret put BUNNY_STORAGE_ACCESS_KEY
 *   wrangler secret put WORKER_SIGNING_SECRET  # same as Laravel WORKER_SIGNING_SECRET
 *   wrangler secret put BUNNY_STORAGE_ZONE
 *   Optional: BUNNY_STORAGE_HOST (default storage.bunnycdn.com)
 */

async function hmacSha256(secret, payload) {
  const enc = new TextEncoder();
  const key = await crypto.subtle.importKey('raw', enc.encode(secret), { name: 'HMAC', hash: 'SHA-256' }, false, ['sign']);
  const sig = await crypto.subtle.sign('HMAC', key, enc.encode(payload));
  return Array.from(new Uint8Array(sig)).map(b => b.toString(16).padStart(2, '0')).join('');
}

function isValidPath(path, userId) {
  if (path.includes('..') || path.includes('\\') || path.includes('\0')) return false;
  const m = path.match(/^reels\/(\d+)\/(\d+)\/([a-f0-9\-]{36}\.mp4|final\.mp4|source\.mp4)$/);
  if (!m) return false;
  if (parseInt(m[1], 10) !== parseInt(userId, 10)) return false;
  return true;
}

function corsHeaders(origin) {
  const allowed = ['https://binteoapp.com', 'https://www.binteoapp.com', 'https://binteodemo.of2on.org', 'http://localhost:8000', 'http://127.0.0.1:8000'];
  const allowOrigin = allowed.includes(origin) ? origin : 'https://binteoapp.com';
  return {
    'Access-Control-Allow-Origin': allowOrigin,
    'Access-Control-Allow-Methods': 'PUT, OPTIONS',
    'Access-Control-Allow-Headers': 'Content-Type, Content-Length, X-Requested-With',
    'Access-Control-Max-Age': '86400',
    'Vary': 'Origin',
  };
}

export default {
  async fetch(request, env) {
    const url = new URL(request.url);
    const origin = request.headers.get('Origin') || '';

    // Preflight
    if (request.method === 'OPTIONS') {
      return new Response(null, { status: 204, headers: corsHeaders(origin) });
    }

    // Only PUT /upload allowed
    if (request.method !== 'PUT' || !url.pathname.startsWith('/upload')) {
      return new Response(JSON.stringify({ error: 'Only PUT /upload allowed' }), { status: 405, headers: { 'Content-Type': 'application/json', ...corsHeaders(origin) } });
    }

    const storagePath = url.searchParams.get('path');
    const token = url.searchParams.get('token');
    const expiry = parseInt(url.searchParams.get('expiry') || '0', 10);
    const op = url.searchParams.get('op') || 'upload';

    if (!storagePath || !token || !expiry) {
      return new Response(JSON.stringify({ error: 'Missing path/token/expiry' }), { status: 400, headers: { 'Content-Type': 'application/json', ...corsHeaders(origin) } });
    }

    // Extract userId/reelId from path for verification (reels/{uid}/{rid}/...)
    const pathMatch = storagePath.match(/^reels\/(\d+)\/(\d+)\//);
    if (!pathMatch) {
      return new Response(JSON.stringify({ error: 'Invalid path format' }), { status: 400, headers: { 'Content-Type': 'application/json', ...corsHeaders(origin) } });
    }
    const userId = pathMatch[1];
    const reelId = pathMatch[2];

    // Expiry check (30s leeway)
    if (Math.floor(Date.now() / 1000) > expiry) {
      return new Response(JSON.stringify({ error: 'Token expired' }), { status: 401, headers: { 'Content-Type': 'application/json', ...corsHeaders(origin) } });
    }

    // Path tamper check
    if (!isValidPath(storagePath, userId)) {
      return new Response(JSON.stringify({ error: 'Path validation failed' }), { status: 403, headers: { 'Content-Type': 'application/json', ...corsHeaders(origin) } });
    }

    // Operation check
    if (!['upload', 'final'].includes(op)) {
      return new Response(JSON.stringify({ error: 'Invalid operation' }), { status: 400, headers: { 'Content-Type': 'application/json', ...corsHeaders(origin) } });
    }

    // Content-Length check (105 MB max)
    const contentLength = parseInt(request.headers.get('Content-Length') || '0', 10);
    if (contentLength > 105 * 1024 * 1024) {
      return new Response(JSON.stringify({ error: 'File too large (max 105MB)' }), { status: 413, headers: { 'Content-Type': 'application/json', ...corsHeaders(origin) } });
    }

    // Content-Type check
    const contentType = request.headers.get('Content-Type') || '';
    const allowedTypes = ['video/mp4', 'video/quicktime', 'video/webm', 'video/x-msvideo', 'application/octet-stream'];
    const isAllowedType = allowedTypes.some(t => contentType.includes(t));
    if (contentType && !isAllowedType) {
      // Allow but log - browsers may send video/mp4;octet-stream variations
      // For strict: return 415
    }

    // HMAC verification
    const payload = `v1|${userId}|${reelId}|${storagePath}|${op}|${expiry}`;
    const expected = await hmacSha256(env.WORKER_SIGNING_SECRET, payload);
    if (expected !== token) {
      return new Response(JSON.stringify({ error: 'Invalid token' }), { status: 401, headers: { 'Content-Type': 'application/json', ...corsHeaders(origin) } });
    }

    // Stream to Bunny Storage - never buffer
    const storageHost = env.BUNNY_STORAGE_HOST || 'storage.bunnycdn.com';
    const storageZone = env.BUNNY_STORAGE_ZONE;
    if (!storageZone || !env.BUNNY_STORAGE_ACCESS_KEY) {
      return new Response(JSON.stringify({ error: 'Worker not configured (zone/key)' }), { status: 500, headers: { 'Content-Type': 'application/json', ...corsHeaders(origin) } });
    }

    const bunnyUrl = `https://${storageHost}/${storageZone}/${storagePath}`;

    try {
      const bunnyResponse = await fetch(bunnyUrl, {
        method: 'PUT',
        headers: {
          'AccessKey': env.BUNNY_STORAGE_ACCESS_KEY,
          'Content-Type': contentType || 'video/mp4',
          'Content-Length': contentLength ? String(contentLength) : undefined,
        },
        body: request.body,
        duplex: 'half',
      });

      const body = await bunnyResponse.text();

      // Return Bunny status to browser (with CORS)
      return new Response(body, {
        status: bunnyResponse.status,
        headers: {
          'Content-Type': 'application/json',
          ...corsHeaders(origin),
        },
      });
    } catch (e) {
      return new Response(JSON.stringify({ error: 'Bunny Storage error: ' + e.message }), { status: 502, headers: { 'Content-Type': 'application/json', ...corsHeaders(origin) } });
    }
  },
};
