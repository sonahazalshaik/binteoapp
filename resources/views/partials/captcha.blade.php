@php
    // Captcha placeholder – integrate Google reCAPTCHA or custom captcha here when needed.
    $captcha = gs('captcha') ?? null;
@endphp

@if($captcha && $captcha->status)
    {{-- Render captcha widget here --}}
@endif
