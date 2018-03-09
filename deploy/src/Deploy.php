<?php
/**
 * Created by PhpStorm.
 * User: ikhaldeev
 * Date: 10.03.18
 * Time: 0:28
 */

namespace deploy;


class Deploy
{
    /**
     * @var Shell
     */
    private $shell;

    /**
     * @var array
     */
    private $config;

    public function __construct($shell, $config)
    {
        $this->shell = $shell;
        $this->config = $config;
    }

    public function prepare()
    {
        $path = Shell::path($this->config['workingDir'], $this->config['appDir']);

        $this->shell->exec("cd {$path}");
        $this->shell->exec("git fetch --all --tags");
        $this->shell->exec("git checkout tags/{$this->config['appVersion']}");
    }

    public function transfer($deployId)
    {
        $this->shell->exec("cd {$this->config['workingDir']}");
        $this->shell->exec("zip -r /tmp/deploy.zip {$this->config['appDir']}");

        $connection = $this->shell->connect($this->config['sshParams']);

        $this->shell->transfer($connection, '/tmp/deploy.zip', '/tmp/deploy.zip');

        $path = Shell::path($this->config['destinationDir'], $deployId);
        $tmpPath = Shell::path('/tmp', $deployId);

        $this->shell->execInside($connection, "mkdir {$tmpPath}");
        $this->shell->execInside($connection, "unzip /tmp/deploy.zip -d {$tmpPath}");
        $this->shell->execInside($connection, "cp -rf {$tmpPath}* {$path}");
    }
}