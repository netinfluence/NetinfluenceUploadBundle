<?php

namespace Netinfluence\UploadBundle\Tests\Manager\Sandbox;

use Netinfluence\UploadBundle\Manager\Sandbox\SandboxManager;

/**
 * Class SandboxManagerTest
 */
class SandboxManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Gaufrette\Filesystem
     */
    private $filesystem;

    /**
     * @var \Netinfluence\UploadBundle\Manager\Thumbnail\ThumbnailManagerInterface
     */
    private $thumbnailManager;

    /**
     * @var SandboxManager
     */
    private $sut;
    
    public function setUp()
    {
        $this->filesystem = \Phake::mock('Gaufrette\Filesystem');

        // Array of files keys to return
        $files = array();
        $date = new \Datetime();
        $interval = new \DateInterval('P1D');
        for ($i = 0; $i < 3; $i++) {
            $folder = $date->format('Y-m-d');

            $files[] = $folder.'/img0.jpg';
            $files[] = $folder.'/img1.jpg';
            $files[] = $folder.'/img2.jpg';

            $date->sub($interval);
        }

        \Phake::when($this->filesystem)->keys()->thenReturn($files);

        $this->thumbnailManager = \Phake::mock('Netinfluence\UploadBundle\Manager\Thumbnail\ThumbnailManagerInterface');

        $logger = \Phake::mock('Psr\Log\LoggerInterface');

        $this->sut = new SandboxManager($this->filesystem, $this->thumbnailManager, $logger);
    }
    
    public function tearDown()
    {
        $this->sut = null;
    }

    public function test_it_clears_all_files()
    {
        $this->assertEquals(9, $this->sut->clear(0));

        \Phake::verify($this->filesystem, \Phake::times(9));
        \Phake::verify($this->thumbnailManager, \Phake::times(9));
    }

    public function test_it_skips_today()
    {
        $this->assertEquals(6, $this->sut->clear(1));

        \Phake::verify($this->filesystem, \Phake::times(6));
        \Phake::verify($this->thumbnailManager, \Phake::times(6));
    }

    public function test_it_handles_grace_days()
    {
        $this->assertEquals(3, $this->sut->clear(/* by default it is 2 */));

        \Phake::verify($this->filesystem, \Phake::times(3));
        \Phake::verify($this->thumbnailManager, \Phake::times(3));
    }
}
