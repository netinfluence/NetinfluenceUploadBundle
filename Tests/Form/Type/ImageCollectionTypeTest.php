<?php

namespace Netinfluence\UploadBundle\Tests\Form\Type;

use Netinfluence\UploadBundle\Form\Type\ImageCollectionType;
use Netinfluence\UploadBundle\Form\Type\ImageInnerType;
use Netinfluence\UploadBundle\Form\Type\ImageType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Netinfluence\UploadBundle\Validation\ImageConstraints;

/**
 * Class ImageCollectionTypeTest
 */
class ImageCollectionTypeTest extends TypeTestCase
{
    /**
     * @var ImageCollectionType
     */
    private $sut;

    public function setUp()
    {
        parent::setUp();

        $constraints = new ImageConstraints(array());

        $this->sut = new ImageCollectionType($constraints);
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
        $thumbnailGenerator = \Phake::mock('Netinfluence\UploadBundle\Manager\Thumbnail\ThumbnailManagerInterface');

        $imageType = new ImageInnerType($thumbnailGenerator);

        return array(
            new PreloadedExtension(array(
                ImageType::class => $imageType
            ), array())
        );
    }

    public function test_it_submits_data_to_file_collection()
    {
        $form = $this->factory->create($this->sut);

        $form->submit(array(
            array(
                'path' => 'some/path/image.jpg',
                'temporary' => 1
            ),
            array(
                'path' => 'some/path/image2.jpg',
                'temporary' => 0
            )
        ));

        $this->assertTrue($form->isSynchronized());

        $data = $form->getData();

        $this->assertCount(2, $data);

        $this->assertEquals('Netinfluence\UploadBundle\Model\FormFile', get_class($data[0]));
        $this->assertEquals('some/path/image.jpg', $data[0]->getPath());
        $this->assertEquals(true, $data[0]->isTemporary());

        $this->assertEquals('Netinfluence\UploadBundle\Model\FormFile', get_class($data[1]));
        $this->assertEquals('some/path/image2.jpg', $data[1]->getPath());
        $this->assertEquals(false, $data[1]->isTemporary());
    }

    public function test_it_has_max_file_option()
    {
        $form = $this->factory->create($this->sut);
        $view = $form->createView();

        // default value
        $this->assertEquals(0, $view->vars['max_files']);
        $this->assertEquals(true, $view->vars['allow_delete']);

        $form = $this->factory->create($this->sut, null, array(
            'allow_delete' => false,
            'max_files' => 3
        ));
        $view = $form->createView();

        $this->assertEquals(3, $view->vars['max_files']);
        $this->assertEquals(false, $view->vars['allow_delete']);
    }
}
