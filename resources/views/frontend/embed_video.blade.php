<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Embed - {{ $video->title }}</title>
    <link href="{{ asset('assets/global/css/plyr.css') }}" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #000; overflow: hidden; height: 100vh; display: flex; align-items: center; justify-content: center; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        .video-wrapper { width: 100%; height: 100%; position: relative; }
        .plyr--full-ui { height: 100%; }
        .author-overlay {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 10;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            background: rgba(15, 15, 15, 0.6);
            backdrop-filter: blur(10px);
            border-radius: 50px;
            text-decoration: none;
            color: #fff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            transform: translateY(-10px);
        }
        .video-wrapper:hover .author-overlay {
            opacity: 1;
            transform: translateY(0);
        }
        .author-img { width: 28px; height: 28px; border-radius: 50%; object-cover: cover; background: #333; }
        .author-name { font-size: 13px; font-weight: 700; letter-spacing: -0.01em; }
        
        /* Plyr Customizations */
        :root { --plyr-color-main: #ff0000; }
        .plyr__control--overlaid { background: rgba(255, 0, 0, 0.8) !important; }
        .plyr__video-wrapper { background: #000; }
    </style>
</head>
<body>
    <div class="video-wrapper">
        <a href="{{ route('preview.channel', $video->user->slug) }}" target="_blank" class="author-overlay">
            <img src="{{ getImage(getFilePath('userProfile') . '/' . $video->user->image) }}" alt="{{ $video->user->channel_name }}" class="author-img">
            <span class="author-name">{{ $video->user->channel_name }}</span>
        </a>

        <video class="player" playsinline controls data-poster="{{ getImage(getFilePath('thumbnail') . '/' . $video->thumb_image) }}">
            @foreach ($video->videoFiles as $file)
                <source src="{{ getVideo($file->file_name, $video) }}" type="video/mp4" size="{{ $file->quality }}" />
            @endforeach
            @foreach ($video->subtitles as $subtitle)
                <track src="{{ getImage(getFilePath('subtitle') . '/' . $subtitle->file) }}"
                    srclang="{{ $subtitle->language_code }}" kind="captions" label="{{ $subtitle->caption }}" default />
            @endforeach
        </video>
    </div>

    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/plyr.js') }}"></script>
    <script>
        const player = new Plyr('.player', {
            controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'captions', 'settings', 'fullscreen'],
            ratio: '16:9',
            hideControls: true
        });
    </script>
</body>
</html>
