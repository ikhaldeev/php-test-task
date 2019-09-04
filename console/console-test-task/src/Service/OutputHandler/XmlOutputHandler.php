<?php

namespace App\Service\OutputHandler;

use SimpleXMLElement;

class XmlOutputHandler implements OutputHandlerInterface
{
    /**
     * @param array $data
     * @param string $path
     */
    public function handle(array $data, string $path): void
    {
        $xml = new SimpleXMLElement('<root/>');
        array_walk_recursive($data, array($xml, 'addChild'));
        file_put_contents($path . '/output.xml', $xml->asXML());

        return;
    }
}