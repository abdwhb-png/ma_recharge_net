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

if (!function_exists('swap_adjacent_random_char')) {
    function swap_adjacent_random_char($char)
    {
        $char = (string) $char; // S'assurer que c'est une chaîne
        $length = strlen($char);

        if ($length < 2) {
            return $char; // Rien à échanger si la chaîne a moins de 2 caractères
        }

        // Générer une position aléatoire
        $pos = random_int(0, $length - 1);

        // Déterminer si on échange avec le précédent ou le suivant
        if ($pos > 0 && ($pos == $length - 1 || random_int(0, 1) == 0)) {
            // Échanger avec le caractère précédent (si possible)
            $swapPos = $pos - 1;
        } else {
            // Échanger avec le caractère suivant (si possible)
            $swapPos = $pos + 1;
        }

        // Convertir la chaîne en tableau pour manipulation
        $chars = str_split($char);

        // Échanger les caractères
        [$chars[$pos], $chars[$swapPos]] = [$chars[$swapPos], $chars[$pos]];

        // Reconvertir en chaîne
        return implode('', $chars);
    }
}
