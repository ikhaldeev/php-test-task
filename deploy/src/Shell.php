<?php
/**
 * Created by PhpStorm.
 * User: ikhaldeev
 * Date: 10.03.18
 * Time: 0:50
 */

namespace deploy;


class Shell
{
    public function exec($command)
    {

    }

    /**
     * @param array $sshParams
     */
    public function connect($sshParams)
    {

    }

    public function transfer($connection, $sourceFile, $destinationFile)
    {

    }

    public function execInside($connection, $command)
    {

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