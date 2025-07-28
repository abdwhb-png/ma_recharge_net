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

if (!function_exists('receiver_type')) {
    function receiver_type($str)
    {
        $factory = app(\Illuminate\Contracts\Validation\Factory::class);

        $isEmail = !$factory->make(
            ['field' => $str],
            ['field' => 'email']
        )->fails();

        return $isEmail ? 'email' : 'telegram';
    }
}

if (!function_exists('swap_adjacent_random_char')) {
    function swap_adjacent_random_char($str)
    {
        $str = (string) $str; // S'assurer que c'est une chaîne
        $chars = str_split($str);
        $length = count($chars);

        // Trouver toutes les positions valides (non-espace)
        $validPositions = [];
        foreach ($chars as $i => $char) {
            if ($char !== ' ') {
                $validPositions[] = $i;
            }
        }

        if (count($validPositions) < 2) {
            return $str; // Pas assez de caractères pour échanger
        }

        // Choisir une position non-espace aléatoirement
        $pos = $validPositions[array_rand($validPositions)];

        // Déterminer la position d'échange (adjacente et non-espace)
        $swapPos = null;

        if ($pos > 0 && $chars[$pos - 1] !== ' ' && ($pos == $length - 1 || random_int(0, 1) === 0)) {
            $swapPos = $pos - 1;
        } elseif ($pos < $length - 1 && $chars[$pos + 1] !== ' ') {
            $swapPos = $pos + 1;
        }

        if ($swapPos === null) {
            return $str; // Aucun échange possible
        }

        // Échange
        [$chars[$pos], $chars[$swapPos]] = [$chars[$swapPos], $chars[$pos]];

        return implode('', $chars);
    }
}
