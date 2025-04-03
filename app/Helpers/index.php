<?php

use App\Models\Setting;

if (!function_exists('site_setting')) {
    function site_setting($key = null)
    {
        $setting =  Setting::first() ?? new Setting;

        if ($key) {
            try {
                return $setting->$key;
            } catch (\Throwable $th) {
                return "";
            }
        }

        return $setting;
    }
}

if (!function_exists('get_location')) {
    function get_location($ip)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://geo-ip.onoffapp.net/json/' . $ip);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $data = curl_exec($ch);
        curl_close($ch);

        return json_decode($data);
    }
}
