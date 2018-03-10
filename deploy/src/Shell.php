<?php
/**
 * Created by PhpStorm.
 * User: ikhaldeev
 * Date: 10.03.18
 * Time: 0:50
 */

namespace deploy;


use phpseclib\Net\SCP;
use phpseclib\Net\SSH2;

class Shell
{
    /**
     * Execute given command locally
     *
     * @param string $command
     * @return bool
     */
    public function exec($command)
    {
        exec($command, $output, $return);

        return $return == 0;
    }

    /**
     * Establish SSH connection
     *
     * @param array $sshParams
     * @return SSH2
     */
    public function connect($sshParams)
    {
        $ssh = new SSH2($sshParams['host']);
        if (!$ssh->login($sshParams['username'], $sshParams['password'])) {
            throw new \RuntimeException('SSH login failed');
        }

        return $ssh;
    }

    /**
     * Transfer file via established connection
     *
     * @param SSH2 $connection
     * @param string $sourceFile
     * @param string $destinationFile
     */
    public function transfer($connection, $sourceFile, $destinationFile)
    {
        $scp = new SCP($connection);
        $scp->put($destinationFile, $sourceFile, SCP::SOURCE_LOCAL_FILE);
    }

    /**
     * Execute command on remote server
     *
     * @param SSH2 $connection
     * @param string $command
     * @return bool
     */
    public function execInside($connection, $command)
    {
        return $connection->write($command);
    }

    /**
     * Construct path from given list of dirs
     *
     * @param mixed ...$dirs
     * @return string
     */
    public static function path(...$dirs)
    {
        $result = "";

        foreach ($dirs as $dir) {
            $result .= $dir;
            if (substr($result, -1) != '/') {
                $result .= '/';
            }
        }

        return $result;
    }
}