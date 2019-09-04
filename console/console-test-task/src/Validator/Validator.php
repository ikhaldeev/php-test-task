<?php

namespace App\Validator;

class Validator
{
    public static function name($name)
    {
        return mb_check_encoding($name, 'UTF-8'); // Не покрывает все случаи невалидной строки https://www.php.net/manual/en/function.mb-check-encoding.php
    }

    public static function url(string $url)
    {
        //TODO Parse url
        return preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url);
    }

    public static function stars(string $stars)
    {
        return (int) $stars > 0 and (int) $stars <= 5;
    }
}