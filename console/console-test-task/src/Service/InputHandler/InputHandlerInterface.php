<?php

namespace App\Service\InputHandler;

interface InputHandlerInterface
{
    public function handle(string $path): ?array;
}