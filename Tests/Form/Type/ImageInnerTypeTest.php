<?php

namespace Netinfluence\UploadBundle\Tests\Form\Type;

use Netinfluence\UploadBundle\Form\Type\ImageInnerType;
use Netinfluence\UploadBundle\Form\Type\ImageType;
use Netinfluence\UploadBundle\Manager\Thumbnail\ThumbnailManagerInterface;
use Netinfluence\UploadBundle\Model\FormFile;
use Netinfluence\UploadBundle\Model\TemporaryFile;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageInnerTypeTest extends TypeTestCase
{
    /**
     * @var ImageInnerType
     */
    private $sut;

    public function tearDown()
    {
        $this->sut = null;
    }

    public function test_it_set_default_options()
    {
        $resolver = new OptionsResolver();
        $this->sut->configureOptions($resolver);

        $options = $resolver->resolve();

        $this->assertEquals(false, $options['required']);
        $this->assertEquals(ImageType::DEFAULT_DATA_CLASS, $options['data_class']);
    }

    public function test_it_validates_data_class_option()
    {
        $resolver = new OptionsResolver();
        $this->sut->configureOptions($resolver);

        $options =  $resolver->resolve([
            'data_class' => FormFile::class,
        ]);

        $this->assertEquals(FormFile::class, $options['data_class']);

        $this->setExpectedException(\Exception::class);

        $resolver->resolve([
            'data_class' => TemporaryFile::class
        ]);
    }

    public function test_it_submits_data_to_temporary_form_file()
    {
        $form = $this->factory->create(ImageInnerType::class);

        $form->submit([
            'path' => 'some/path/image.jpg',
            'temporary' => 1,
        ]);

        $this->assertTrue($form->isSynchronized());

        $data = $form->getData();
        $this->assertEquals(FormFile::class, get_class($data));
        $this->assertEquals('some/path/image.jpg', $data->getPath());
        $this->assertEquals(true, $data->isTemporary());
    }

    public function test_it_submits_data_to_final_form_file_by_default()
    {
        $form = $this->factory->create(ImageInnerType::class);

        $form->submit([
            'path' => 'some/path/image.jpg',
            // nothing: we should use the default value
        ]);

        $this->assertTrue($form->isSynchronized());

        $data = $form->getData();
        $this->assertEquals(FormFile::class, get_class($data));
        $this->assertEquals('some/path/image.jpg', $data->getPath());
        $this->assertEquals(false, $data->isTemporary());
    }

    public function test_it_generates_thumbnail_url()
    {
        $file = new FormFile();
        $file->setPath('url/img.jpg');

        $form = $this->factory->create(ImageInnerType::class, $file, [
            'thumbnail_height' => 90,
            'thumbnail_width'  => 120,
        ]);

        $view = $form->createView();

        $this->assertEquals('url/thumbnail.jpg', $view->vars['thumbnail_url']);
    }

    /**
     * We have to register our types.
     *
     * @return array
     */
    protected function getExtensions()
    {
        $thumbnailGenerator = \Phake::mock(ThumbnailManagerInterface::class);
        \Phake::when($thumbnailGenerator)->getThumbnailUrl($this->anything(), [120, 90])->thenReturn('url/thumbnail.jpg');

        $this->sut = new ImageInnerType($thumbnailGenerator);

        return array(
            new PreloadedExtension([
                ImageInnerType::class => $this->sut,
            ], [])
        );
    }
}
