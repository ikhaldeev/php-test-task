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
        $this->shell->exec("cd {$this->config['appSource']}");
        $this->shell->exec("git fetch --all --tags");
        $this->shell->exec("git checkout tags/{$this->config['appVersion']}");
    }
}