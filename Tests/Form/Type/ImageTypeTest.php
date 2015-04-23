<?php

namespace Netinfluence\UploadBundle\Tests\Form\Type;

use Netinfluence\UploadBundle\Form\Type\ImageType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class ImageTypeTest
 */
class ImageTypeTest extends TypeTestCase
{
    /**
     * @var ImageType
     */
    private $sut;

    public function setUp()
    {
        parent::setUp();

        $this->sut = new ImageType();
    }

    public function tearDown()
    {
        $this->sut = null;
    }

    /**
     * We have to register our other type
     * @return array
     */
    protected function getExtensions()
    {
        $imageType = new ImageType();

        return array(
            new PreloadedExtension(array(
                $imageType->getName() => $imageType
            ), array())
        );
    }

    public function test_it_has_1_max_file()
    {
        $form = $this->factory->create($this->sut);
        $view = $form->createView();

        $this->assertEquals(1, $view->vars['max_files']);
    }
}
