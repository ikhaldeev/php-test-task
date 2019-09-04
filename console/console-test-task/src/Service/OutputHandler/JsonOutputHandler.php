<?php

namespace App\Service\OutputHandler;

class JsonOutputHandler implements OutputHandlerInterface
{
    /**
     * @param array $data
     * @param string $path
     */
    public function handle(array $data, string $path): void
    {
        $json = json_encode($data);
        file_put_contents($path . '/output.json', $json);
        return;
    }
}