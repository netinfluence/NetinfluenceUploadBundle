<?php

namespace Netinfluence\UploadBundle\Tests\Form\Type;

use Netinfluence\UploadBundle\Form\Type\ImageType;
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

        $thumbnailGenerator = \Phake::mock('Netinfluence\UploadBundle\Generator\ThumbnailGeneratorInterface');
        $this->sut = new ImageType($thumbnailGenerator);
    }

    public function tearDown()
    {
        $this->sut = null;
    }

    public function test_it_has_1_max_file()
    {
        $form = $this->factory->create($this->sut);
        $view = $form->createView();

        $this->assertEquals(1, $view->vars['max_files']);
    }
}
