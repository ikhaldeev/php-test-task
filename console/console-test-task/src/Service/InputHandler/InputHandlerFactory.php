<?php

namespace App\Service\InputHandler;

class InputHandlerFactory
{
    public function createInputHandler(string $type): InputHandlerInterface
    {
        switch ($type) {
            case 'csv':
                $handler = new CsvInputHandler();
                break;
            default:
                $handler = null;
                break;
        }

        return $handler;
    }
}