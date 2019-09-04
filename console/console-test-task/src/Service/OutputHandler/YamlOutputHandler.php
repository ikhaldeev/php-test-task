<?php


namespace App\Service\OutputHandler;

use Symfony\Component\Yaml\Yaml;

class YamlOutputHandler implements OutputHandlerInterface
{
    /**
     * @param array $data
     * @param string $path
     */
    public function handle(array $data, string $path): void
    {
        $yaml = Yaml::dump($data);
        file_put_contents($path . '/output.yaml', $yaml);
        return;
    }
}