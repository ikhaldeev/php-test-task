#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use App\Command\ParseHotelsCommand;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new ParseHotelsCommand());
$application->run();
