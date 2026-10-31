# Reels Upload Worker

Browser -> Worker -> Bunny Storage streaming gateway.

## Secrets (never expose to browser)

```bash
wrangler secret put BUNNY_STORAGE_ACCESS_KEY
wrangler secret put WORKER_SIGNING_SECRET  # must match Laravel WORKER_SIGNING_SECRET
wrangler secret put BUNNY_STORAGE_ZONE     # e.g. your bunny storage zone name
# Optional: BUNNY_STORAGE_HOST (default storage.bunnycdn.com, or de.storage.bunnycdn.com)
```

## Deploy

```bash
npm install
npm run deploy
```

Then set Cloudflare route: `upload.binteoapp.com/upload/*` -> this worker.

## Laravel

Set in `.env`:
```
WORKER_UPLOAD_URL=https://upload.binteoapp.com
WORKER_SIGNING_SECRET=<same as worker secret>
```

## Verify
Worker only accepts:
- PUT /upload?path=reels/UID/RID/UUID.mp4&token=HMAC&expiry=TIMESTAMP&op=upload|final
- HMAC = HMAC_SHA256(WORKER_SIGNING_SECRET, "v1|userId|reelId|path|op|expiry")
- Streams request.body to Bunny without buffering.
