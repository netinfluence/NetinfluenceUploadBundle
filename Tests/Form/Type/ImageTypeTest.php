<?php

namespace Netinfluence\UploadBundle\Tests\Form\Type;

use Netinfluence\UploadBundle\Form\Type\ImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ImageTypeTest
 */
class ImageTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ImageType
     */
    private $sut;

    public function setUp()
    {
        $this->sut = new ImageType();
    }

    public function tearDown()
    {
        $this->sut = null;
    }

    public function test_it_set_default_options()
    {
        $resolver = new OptionsResolver();
        $this->sut->setDefaultOptions($resolver);

        $options = $resolver->resolve();

        $this->assertEquals(false, $options['required']);
        $this->assertEquals(ImageType::DEFAULT_DATA_CLASS, $options['data_class']);
    }

    public function test_it_validates_data_class_option()
    {
        $resolver = new OptionsResolver();
        $this->sut->setDefaultOptions($resolver);

        $options =  $resolver->resolve(array(
            'data_class' => 'Netinfluence\UploadBundle\Model\FormFile'
        ));

        $this->assertEquals('Netinfluence\UploadBundle\Model\FormFile', $options['data_class']);

        $this->setExpectedException('\Exception');

        $resolver->resolve(array(
            'data_class' => 'Netinfluence\UploadBundle\Model\TemporaryFile'
        ));
    }
}
