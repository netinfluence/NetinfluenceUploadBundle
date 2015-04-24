<?php

namespace Netinfluence\UploadBundle\Tests\Generator;
use Netinfluence\UploadBundle\Generator\LiipImagineGenerator;
use Netinfluence\UploadBundle\Model\FormFile;

/**
 * Class LiipImagineGeneratorTest
 */
class LiipImagineGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LiipImagineGenerator
     */
    private $sut;
    
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

        $this->sut = new LiipImagineGenerator($cacheManager);
    }

    public function tearDown()
    {
        $this->sut = null;
    }

    public function test_it_generates_url()
    {
        $file = new FormFile();
        $file->setPath('url/img.jpg');

        $this->assertEquals('url/t0.jpg', $this->sut->getUrl($file, array(120, 90)));
        $this->assertEquals('url/t1.jpg', $this->sut->getUrl($file, array(120, 120)));

        $file2 = new FormFile();
        $file2->setPath('url/img2.jpg');

        $this->assertEquals('url/t2.jpg', $this->sut->getUrl($file2, array(120, 120)));
    }
}
