<?php

namespace App\Lib;

use App\Constants\Status;
use App\Models\Extension;

class Captcha{

    public static function reCaptcha(){
        $reCaptcha = Extension::where('act', 'google-recaptcha2')->where('status', Status::ENABLE)->first();
        return $reCaptcha ? $reCaptcha->generateScript() : null;
    }

    public static function customCaptcha($width = '100%', $height = 46, $bgColor = '#003'){

        $textColor = '#'. (function_exists('gs') ? gs('base_color') : '000');
        $captcha = Extension::where('act', 'custom-captcha')->where('status', Status::ENABLE)->first();
        if (!$captcha) {
            return 0;
        }
        $code = rand(100000, 999999);
        $char = str_split($code);
        $ret = '<link href="https://fonts.googleapis.com/css?family=Henny+Penny&display=swap" rel="stylesheet">';
        $ret .= '<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />';
        $ret .= '<div class="custom-captcha-container" style="display: flex; flex-direction: column; gap: 12px; width: ' . $width . ';">';
        $ret .= '<div class="captcha-img-wrapper" style="position: relative; height: ' . $height . 'px; line-height: ' . $height . 'px; width: 100%; text-align: center; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; font-size: ' . ($height - 15) . 'px; font-weight: bold; letter-spacing: 15px; font-family: \'Henny Penny\', cursive; -webkit-user-select: none; user-select: none; display: flex; justify-content: center; align-items: center; overflow: hidden; box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.05);">';
        
        $colors = ['#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6', '#ec4899', '#0f172a', '#b91c1c'];
        
        foreach ($char as $value) {
            $randomColor = $colors[array_rand($colors)];
            $ret .= '<span style="display: inline-block; -webkit-transform: rotate(' . rand(-35, 35) . 'deg); color: ' . $randomColor . '; text-shadow: 1px 1px 0px rgba(255,255,255,0.5);">' . $value . '</span>';
        }
        
        $ret .= '<button type="button" class="captcha-reload-btn" onclick="reloadCaptcha(this)" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); width: 36px; height: 36px; border-radius: 10px; background: #ffffff; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #334155; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.05); z-index: 10;"><span class="material-symbols-rounded" style="font-size: 20px; font-variation-settings: \'FILL\' 0, \'wght\' 600, \'GRAD\' 0, \'opsz\' 24;">refresh</span></button>';
        $ret .= '</div>';
        
        $ret .= '<div class="captcha-input-wrapper" style="position: relative;">';
        $ret .= '<input type="text" name="captcha" placeholder="Enter security code" class="captcha-entry-field" style="width: 100%; height: 52px; padding: 0 20px; background: #f1f5f9; border: 2px solid transparent; border-radius: 12px; font-weight: 700; color: #1e293b; outline: none; transition: all 0.2s;" onfocus="this.style.borderColor=\'#3b82f6\';this.style.background=\'#fff\'" onblur="this.style.borderColor=\'transparent\';this.style.background=\'#f1f5f9\'" required>';
        $ret .= '</div>';

        $captchaSecret = hash_hmac('sha256', $code, $captcha->shortcode->random_key->value);
        $ret .= '<input type="hidden" name="captcha_secret" value="' . $captchaSecret . '">';
        $ret .= '</div>';

        if (!request()->ajax()) {
            $ret .= '<script>
                function reloadCaptcha(btn) {
                    const container = btn.closest(".custom-captcha-container");
                    btn.classList.add("fa-spin");
                    fetch("' . route('captcha.reload') . '")
                        .then(response => response.text())
                        .then(html => {
                            const newContent = new DOMParser().parseFromString(html, "text/html").body.firstChild;
                            container.replaceWith(newContent);
                        });
                }
            </script>';
        }

        return $ret;

    }

    public static function verify(){
        $gCaptchaPass = self::verifyGoogleCaptcha();
        $cCaptchaPass = self::verifyCustomCaptcha();
        if ($gCaptchaPass && $cCaptchaPass) {
            return true;
        }
        return false;
    }

    public static function verifyGoogleCaptcha(){
        $pass = true;
        $googleCaptcha = Extension::where('act', 'google-recaptcha2')->where('status', Status::ENABLE)->first();
        if ($googleCaptcha) {
            $resp = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$googleCaptcha->shortcode->secret_key->value."&response=".request()['g-recaptcha-response']."&remoteip=".getRealIP()), true);
            if (!$resp['success']) {
                $pass = false;
            }
        }
        return $pass;
    }

    public static function verifyCustomCaptcha(){
        $pass = true;
        $customCaptcha = Extension::where('act', 'custom-captcha')->where('status', Status::ENABLE)->first();
        if ($customCaptcha) {
            $captchaSecret = hash_hmac('sha256', request()->captcha, $customCaptcha->shortcode->random_key->value);
            if ($captchaSecret != request()->captcha_secret) {
                $pass = false;
            }
        }
        return $pass;
    }

}
