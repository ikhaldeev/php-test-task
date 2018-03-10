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

    private $connection;

    public function __construct($shell, $config)
    {
        $this->shell = $shell;
        $this->config = $config;
    }

    private function getConnection()
    {
        if ($this->connection === null) {
            $this->connection = $this->shell->connect($this->config['sshParams']);
        }

        return $this->connection;
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

        $connection = $this->getConnection();

        $this->shell->transfer($connection, '/tmp/deploy.zip', '/tmp/deploy.zip');

        $path = Shell::path($this->config['destinationDir'], $deployId);
        $tmpPath = Shell::path('/tmp', $deployId);

        $this->shell->execInside($connection, "mkdir {$tmpPath}");
        $this->shell->execInside($connection, "unzip /tmp/deploy.zip -d {$tmpPath}");
        $this->shell->execInside($connection, "cp -rf {$tmpPath}* {$path}");
    }

    public function install($deployId)
    {
        $connection = $this->getConnection();

        $path = Shell::path($this->config['destinationDir'], $deployId);
        $current = Shell::path($this->config['destinationDir'], 'current');

        $this->shell->execInside($connection, "cd {$path}");
        $this->shell->execInside($connection, "composer install --no-dev");
        $this->shell->execInside($connection, "ln -sf {$path} {$current}");
        $this->shell->execInside($connection, "service php-fpm reload");
    }
}