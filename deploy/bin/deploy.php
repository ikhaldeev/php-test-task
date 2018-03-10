<?php
/**
 * Created by PhpStorm.
 * User: ikhaldeev
 * Date: 10.03.18
 * Time: 23:36
 */

use Composer\Autoload\ClassLoader;

/** @var ClassLoader $loader */
$loader = require __DIR__.'/../vendor/autoload.php';

/** @var array $config */
$config = require __DIR__.'/../config/config.php';
$options = getopt("", [
    'command::',
    'appVersion::',
    'deployId::',
]);

$config = array_merge($config, $options);

$shell = new \deploy\Shell();
$deploy = new \deploy\Deploy($shell, $config);

if ($options['command'] === \deploy\Deploy::COMMAND_DEPLOY) {
    echo $deploy->deploy(), PHP_EOL;
} else if ($options['command'] === \deploy\Deploy::COMMAND_ROLLBACK) {
    $deploy->rollbackTo($options['deployId']);
    echo "Done", PHP_EOL;
}

