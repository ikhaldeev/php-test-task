<?php

namespace App\Service\OutputHandler;

class OutputHandlerFactory
{
    public function createOutputHandler(string $outputType): ?OutputHandlerInterface
    {
        $outputType = mb_strtolower($outputType);

        switch ($outputType) {
            case 'html':
                $handler = new HtmlOutputHandler();
                break;
            case 'json':
                $handler = new JsonOutputHandler();
                break;
            case 'xml':
                $handler = new XmlOutputHandler();
                break;
            case 'yaml':
                $handler = new YamlOutputHandler();
                break;
            default:
                $handler = null;
                break;
        }

        return $handler;
    }
}