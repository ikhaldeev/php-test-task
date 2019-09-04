<?php

namespace App\Service\OutputHandler;

interface OutputHandlerInterface
{
    public function handle(array $data, string $path): void;
}