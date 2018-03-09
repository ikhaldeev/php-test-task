<?php
/**
 * Created by PhpStorm.
 * User: ikhaldeev
 * Date: 10.03.18
 * Time: 0:30
 */

namespace deploy;


use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeployTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $shell;

    private $workingDir;
    private $appVersion;
    private $appDir;
    private $sshParams;
    private $destinationDir;

    protected function setUp()
    {
        $this->shell = $this->getMockBuilder(Shell::class)
            ->setMethodsExcept()
            ->getMock();
    }

    /**
     * @return Deploy
     */
    private function getDeploy()
    {
        return new Deploy($this->shell, [
            'workingDir' => $this->workingDir,
            'appDir' => $this->appDir,
            'appVersion' => $this->appVersion,
            'sshParams' => $this->sshParams,
            'destinationDir' => $this->destinationDir,
        ]);
    }

    public function testCanPrepareApp()
    {
        $this->workingDir = '/tmp/source';
        $this->appDir = 'name';
        $this->appVersion = 'test-tag';

        $path = Shell::path($this->workingDir, $this->appDir);

        $this->shell->expects($this->exactly(3))
            ->method('exec')
            ->withConsecutive(
                $this->equalTo("cd {$path}"),
                $this->equalTo("git fetch --all --tags"),
                $this->equalTo("git checkout tags/{$this->appVersion}")
            );

        $this->getDeploy()->prepare();
    }

    public function testCanTransferApp()
    {
        $this->workingDir = '/tmp/source';
        $this->appDir = 'name';
        $this->sshParams = [
            'host' => 'test',
            'username' => 'test',
            'password' => 'test'
        ];
        $this->destinationDir = '/data/www';

        $sshConnection = [];

        $deployId = uniqid();

        $this->shell->expects($this->exactly(2))
            ->method('exec')
            ->withConsecutive(
                $this->equalTo("cd {$this->workingDir}"),
                $this->equalTo("zip -r /tmp/deploy.zip {$this->appDir}")
            );

        $this->shell->expects($this->exactly(1))
            ->method('connect')
            ->with($this->equalTo($this->sshParams))
            ->will($this->returnValue($sshConnection));

        $this->shell->expects($this->exactly(1))
            ->method('transfer')
            ->with($this->equalTo($sshConnection), '/tmp/deploy.zip', '/tmp/deploy.zip')
            ->will($this->returnValue($sshConnection));

        $path = Shell::path($this->destinationDir, $deployId);
        $tmpPath = Shell::path('/tmp', $deployId);

        $this->shell->expects($this->exactly(3))
            ->method('execInside')
            ->withConsecutive(
                [$this->equalTo($sshConnection), "mkdir {$tmpPath}"],
                [$this->equalTo($sshConnection), "unzip /tmp/deploy.zip -d {$tmpPath}"],
                [$this->equalTo($sshConnection), "cp -rf {$tmpPath}* {$path}"]
            );

        $this->getDeploy()->transfer($deployId);
    }
}