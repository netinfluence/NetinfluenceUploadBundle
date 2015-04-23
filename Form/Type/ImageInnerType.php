<?php

namespace Netinfluence\UploadBundle\Form\Type;

use Netinfluence\UploadBundle\Form\DataTransformer\BooleanToHiddenTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ImageInnerType
 * The Image "undecorated" form
 */
class ImageInnerType extends AbstractType
{
    /**
     * By default, behind the form we will have an instance of this object
     */
    const DEFAULT_DATA_CLASS = 'Netinfluence\UploadBundle\Model\FormFile';
    const DATA_CLASS_REQUIRED_INTERFACE = 'Netinfluence\UploadBundle\Model\UploadableInterface';

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('path', 'hidden')
            ->add(
                $builder
                    // this more complex syntax is required when adding a transformer
                    ->create('temporary', 'hidden', array(
                        'empty_data' => false
                    ))
                    // We add a transformer to be sure there is no type screw-up
                    ->addViewTransformer(new BooleanToHiddenTransformer())
            )
        ;

        // We allow user to modify, typically add more fields, using a callback
        if (isset($options['extra_fields']) && is_callable($options['extra_fields'])) {
            call_user_func($options['extra_fields'], $builder, $options);
        }
    }

    /**
     * @inheritdoc
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => self::DEFAULT_DATA_CLASS,
            'required'  => false
        ));

        $dataClass = self::DEFAULT_DATA_CLASS;
        $interface = self::DATA_CLASS_REQUIRED_INTERFACE;

        // Important: use 2.3 syntax, passing an array, it will convert internally
        $resolver->setAllowedValues(array(
            'data_class' => function($value) use ($dataClass, $interface) {
                if (! is_a($value, $interface, true)) {
                    // We throw an Exception for a more precise feedback than OptionResolver one
                    throw new \Exception(sprintf(
                        'Form type "netinfluence_upload_image" must be mapped to objects implementing %s. Wrong value "%s" received for "data_class".',
                        $interface, $dataClass
                    ));
                }

                return true;
            }
        ));

        $resolver->setOptional(array('extra_fields'));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'netinfluence_upload_image_inner';
    }
}
