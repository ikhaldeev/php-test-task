<?php

namespace App\Service;

class DataFormatter
{
    /**
     * @param array $data
     * @param string $key
     */
    public static function sortByKey(array &$data, string $key)
    {
        if (!in_array($key, array_keys($data[0]))) {
            return;
        }

        usort($data, function ($a, $b) use ($key) {
            return ($a[$key] <= $b[$key]) ? -1 : 1;
        });
    }
}