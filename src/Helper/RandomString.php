<?php

namespace App\Helper;

class RandomString {
    public static function generate ($length = 10)
    {
        $characters = '123456789ABCDEFG';
        $charactersLength = strlen($characters);

        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}