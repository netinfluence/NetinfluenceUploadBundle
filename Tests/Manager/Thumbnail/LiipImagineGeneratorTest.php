<?php

namespace Netinfluence\UploadBundle\Tests\Manager\Thumbnail;

use Netinfluence\UploadBundle\Manager\Thumbnail\LiipImagineManager;
use Netinfluence\UploadBundle\Model\FormFile;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

/**
 * Class LiipImagineGeneratorTest
 */
class LiipImagineGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LiipImagineManager
     */
    private $sut;

    /**
     * @var CacheManager
     */
    private $cacheManager;
    
    public function setUp()
    {
        $cacheManager = \Phake::mock('Liip\ImagineBundle\Imagine\Cache\CacheManager');
        \Phake::when($cacheManager)->getBrowserPath(
            'url/img.jpg',
            'ni_ub_thumbnail',
            array(
                'thumbnail' => array(
                    'mode'  => 'outbound',
                    'size'  => array(120, 90)
                )
            )
        )->thenReturn('url/t0.jpg');
        \Phake::when($cacheManager)->getBrowserPath(
            'url/img.jpg',
            'ni_ub_thumbnail',
            array(
                'thumbnail' => array(
                    'mode'  => 'outbound',
                    'size'  => array(120, 120)
                )
            )
        )->thenReturn('url/t1.jpg');
        \Phake::when($cacheManager)->getBrowserPath(
            'url/img2.jpg',
            'ni_ub_thumbnail',
            $this->anything()
        )->thenReturn('url/t2.jpg');

        $logger = \Phake::mock('Psr\Log\LoggerInterface');

        $this->cacheManager = $cacheManager;

        $this->sut = new LiipImagineManager($cacheManager, $logger);
    }

    public function tearDown()
    {
        $this->sut = null;
    }

    public function test_it_generates_url()
    {
        $file = new FormFile();
        $file->setPath('url/img.jpg');

        $this->assertEquals('url/t0.jpg', $this->sut->getThumbnailUrl($file, array(120, 90)));
        $this->assertEquals('url/t1.jpg', $this->sut->getThumbnailUrl($file, array(120, 120)));

        $file2 = new FormFile();
        $file2->setPath('url/img2.jpg');

        $this->assertEquals('url/t2.jpg', $this->sut->getThumbnailUrl($file2, array(120, 120)));
    }

    public function test_it_removes_thumbnails_for_file()
    {
        $file = new FormFile();
        $file->setPath('url/img.jpg');

        $this->sut->removeThumbnails($file);

        \Phake::verify($this->cacheManager, \Phake::times(1));
    }

    public function test_it_removes_thumbnails_by_path()
    {
        $this->sut->removeThumbnailsByPath('url/img.jpg');

        \Phake::verify($this->cacheManager, \Phake::times(1));
    }
}
