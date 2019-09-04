<?php

namespace App\Service\InputHandler;

class CsvInputHandler implements InputHandlerInterface
{
    /**
     * @param string $path
     * @return array|null
     */
    public function handle(string $path): ?array
    {
        if (!file_exists($path) or !is_readable($path)) {
            return null;
        }

        $header = null;
        $data = [];

        if (($handle = fopen($path, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $row = array_combine($header, $row);
                    if ($row) {
                        $data[] = $row;
                    }
                }
            }
            fclose($handle);
        }

        return $data;
    }
}