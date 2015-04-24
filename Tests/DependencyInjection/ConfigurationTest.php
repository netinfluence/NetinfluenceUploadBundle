<?php

namespace Netinfluence\UploadBundle\Tests\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\AbstractConfigurationTestCase;
use Netinfluence\UploadBundle\DependencyInjection\Configuration;

/**
 * Class ConfigurationTest
 */
class ConfigurationTest extends AbstractConfigurationTestCase
{
    /**
     * @return Configuration
     */
    public function getConfiguration()
    {
        return new Configuration();
    }

    public function test_empty_is_not_ok()
    {
        $this->assertConfigurationIsInvalid(
            array(
                array() // no values at all
            )
        );
    }

    public function test_it_requires_filesystems()
    {
        $this->assertConfigurationIsValid(
            array(
                array(
                    'filesystems' => array(
                        'sandbox'   => 'someId',
                        'final'     => 'anotherId'
                    ),
                    'validation' => array()
                )
            )
        );
    }

    public function test_it_accepts_validation()
    {
        $this->assertConfigurationIsValid(
            array(
                array(
                    'filesystems' => array(
                        'sandbox'   => 'someId',
                        'final'     => 'anotherId'
                    ),
                    'validation' => array(
                        'image' => array(
                            'NotNull' => null,
                            'File'  => array()
                        )
                    )
                )
            )
        );
    }

    public function test_it_accepts_overwrite()
    {
        $this->assertConfigurationIsValid(
            array(
                array(
                    'filesystems' => array(
                        'sandbox'   => 'someId',
                        'final'     => 'anotherId'
                    ),
                    'overwrite'  => false,
                    'validation' => array()
                )
            )
        );
    }

    public function test_it_has_default_values()
    {
        $this->assertProcessedConfigurationEquals(array(
            array(
                'filesystems'   => array(
                    'sandbox'   => 'someId',
                    'final'     => 'anotherId'
                ),
                'validation' => array()
            )
        ), array(
            'filesystems'   => array(
                'sandbox'   => 'someId',
                'final'     => 'anotherId'
            ),
            'overwrite'     => true,
            'validation'    => array(
                'image'     => array(
                    'NotNull'   => array(),
                    'Image' => array(
                        'maxSize' => '10M',
                        'mimeTypes' => array('image/gif', 'image/jpg', 'image/jpeg', 'image/png', 'image/bmp', 'image/x-windows-bmp')
                    )
                )
            )
        ));
    }

    public function test_it_merges_validation_values()
    {
        $this->assertProcessedConfigurationEquals(array(
            array(
                'filesystems'   => array(
                    'sandbox'   => 'someId',
                    'final'     => 'anotherId'
                ),
                'validation'    => array(
                    'image'     => array(
                        'NotNull'   => array(),
                        'Image' => array(
                            'maxSize' => '5M',
                            'minHeight' => 1024
                        )
                    )
                )
            )
        ), array(
            'filesystems'   => array(
                'sandbox'   => 'someId',
                'final'     => 'anotherId'
            ),
            'overwrite'     => true,
            'validation'    => array(
                'image'     => array(
                    'NotNull'   => array(),
                    'Image' => array(
                        'maxSize' => '5M',
                        'minHeight' => 1024
                    )
                )
            )
        ));
    }
}
