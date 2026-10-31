<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ gs('site_name') ?? 'Platform' }} - Under Maintenance</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Outfit', sans-serif;
            background: #0F0F0F;
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        .bg-orb { position: absolute; border-radius: 50%; filter: blur(120px); opacity: 0.15; animation: float 8s ease-in-out infinite; }
        .bg-orb-1 { width: 500px; height: 500px; background: #ef4444; top: -20%; left: -10%; }
        .bg-orb-2 { width: 400px; height: 400px; background: #3b82f6; bottom: -20%; right: -10%; animation-delay: 3s; }
        @keyframes float { 0%,100% { transform: translateY(0) scale(1); } 50% { transform: translateY(-30px) scale(1.05); } }
        .container { position: relative; z-index: 10; text-align: center; padding: 3rem; max-width: 600px; }
        .icon-wrap { width: 100px; height: 100px; background: rgba(239,68,68,0.1); border-radius: 2rem; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 2rem; border: 1px solid rgba(239,68,68,0.1); }
        .icon-wrap .material-symbols-rounded { font-size: 3rem; color: #ef4444; }
        h1 { font-size: 3rem; font-weight: 900; letter-spacing: -0.05em; margin-bottom: 1rem; line-height: 1.1; }
        .maintenance-content { font-size: 0.75rem; font-weight: 700; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 0.2em; line-height: 1.8; max-width: 400px; margin: 0 auto 3rem; }
        .maintenance-content p { color: inherit; }
        .pulse-dot { display: inline-flex; align-items: center; gap: 0.75rem; background: rgba(255,255,255,0.05); padding: 0.75rem 1.5rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,0.05); }
        .pulse-dot span:first-child { width: 8px; height: 8px; background: #ef4444; border-radius: 50%; animation: pulse-glow 2s ease-in-out infinite; }
        .pulse-dot span:last-child { font-size: 0.6rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.3em; color: rgba(255,255,255,0.5); }
        @keyframes pulse-glow { 0%,100% { opacity: 1; box-shadow: 0 0 0 0 rgba(239,68,68,0.4); } 50% { opacity: 0.6; box-shadow: 0 0 0 10px rgba(239,68,68,0); } }
        img { display: block; margin: 0 auto; max-width: 100%; height: auto; }
    </style>
</head>
<body>
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>
    <div class="container">
        @if(@$maintenance->data_values->image)
            <div style="margin-bottom: 3rem; display: flex; justify-content: center;">
                <img src="{{ getImage(getFilePath('maintenance') . '/' . @$maintenance->data_values->image, getFileSize('maintenance')) }}" alt="Maintenance" style="max-width: 300px; border-radius: 2rem; box-shadow: 0 20px 50px rgba(0,0,0,0.5);">
            </div>
        @else
            <div class="icon-wrap">
                <span class="material-symbols-rounded">construction</span>
            </div>
        @endif
        
        <h1>We'll Be Right Back</h1>
        
        <div class="maintenance-content">
            {!! @$maintenance->data_values->description ?? 'Our platform is currently undergoing scheduled maintenance. We\'re working hard to bring you an even better experience.' !!}
        </div>

        <div class="pulse-dot">
            <span></span>
            <span>Systems upgrading at {{ gs('site_name') }}</span>
        </div>
    </div>
</body>
</html>
