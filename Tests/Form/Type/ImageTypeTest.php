<?php

namespace Netinfluence\UploadBundle\Tests\Form\Type;

use Netinfluence\UploadBundle\Form\Type\ImageInnerType;
use Netinfluence\UploadBundle\Form\Type\ImageType;
use Netinfluence\UploadBundle\Manager\Thumbnail\ThumbnailManagerInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Netinfluence\UploadBundle\Validation\ImageConstraints;

class ImageTypeTest extends TypeTestCase
{
    /**
     * @var ImageConstraints
     */
    private $imageConstraints;

    /**
     * We have to register our types.
     *
     * @return array
     */
    protected function getExtensions()
    {
        $thumbnailGenerator = \Phake::mock(ThumbnailManagerInterface::class);
        $this->imageConstraints = new ImageConstraints([]);

        return [
            new PreloadedExtension([
                ImageInnerType::class => new ImageInnerType($thumbnailGenerator),
                ImageType::class => new ImageType($thumbnailGenerator, $this->imageConstraints),
            ], [])
        ];
    }

    public function test_it_has_1_max_file()
    {
        $form = $this->factory->create(ImageType::class);
        $view = $form->createView();

        $this->assertEquals(1, $view->vars['max_files']);
    }

    public function test_it_sets_thumbnail_size()
    {
        $form = $this->factory->create(ImageType::class, null, [
            'thumbnail_height' => 90,
            'thumbnail_width'  => 120,
        ]);

        $view = $form->createView();

        // those should also be displayed in the view
        $this->assertEquals(90, $view->vars['thumbnail_height']);
        $this->assertEquals(120, $view->vars['thumbnail_width']);

        $this->assertEquals($this->imageConstraints, $view->vars['image_constraints']);
    }
}
