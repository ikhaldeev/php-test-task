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

    private $appSource;
    private $appVersion;

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
            'appSource' => $this->appSource,
            'appVersion' => $this->appVersion,
        ]);
    }

    public function testCanPrepareApp()
    {
        $this->appSource = '/tmp/source';
        $this->appVersion = 'test-tag';

        $this->shell->expects($this->exactly(3))
            ->method('exec')
            ->withConsecutive(
                $this->equalTo("cd {$this->appSource}"),
                $this->equalTo("git fetch --all --tags"),
                $this->equalTo("git checkout tags/{$this->appVersion}")
            );

        $this->getDeploy()->prepare();
    }
}