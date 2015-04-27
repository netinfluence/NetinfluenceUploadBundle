<?php

namespace Netinfluence\UploadBundle\Tests\Validation;
use Netinfluence\UploadBundle\Validation\ImageConstraints;

/**
 * Class ImageConstraintsTest
 */
class ImageConstraintsTest extends \PHPUnit_Framework_TestCase
{
    public function test_it_builds_with_empty_array()
    {
        $sut = new ImageConstraints(array());

        $this->assertEquals(array(), $sut->getConstraints());
        $this->assertNull($sut->getMaxFileSize());
        $this->assertNull($sut->getAcceptedMimes());
    }

    public function test_it_builds_and_parse_image()
    {
        $sut = new ImageConstraints(array(
            'NotNull'   => array(),
            'Image'     => array(
                'maxSize' => '10k',
                'mimeTypes' => array('image/*')
            )
        ));

        $this->assertCount(2, $sut->getConstraints());

        $this->assertEquals(0.010, $sut->getMaxFileSize());
        $this->assertEquals(array('image/*'), $sut->getAcceptedMimes());
    }

    public function test_it_builds_and_parse_full_class()
    {
        $sut = new ImageConstraints(array(
            'Symfony\Component\Validator\Constraints\Image' => array(
                'maxSize' => '10M',
                'mimeTypes' => array('image/*')
            )
        ));

        $this->assertCount(1, $sut->getConstraints());

        $this->assertEquals(10, $sut->getMaxFileSize());
        $this->assertEquals(array('image/*'), $sut->getAcceptedMimes());
    }
}
