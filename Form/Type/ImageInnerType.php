<?php

namespace Netinfluence\UploadBundle\Form\Type;

use Netinfluence\UploadBundle\Form\DataTransformer\BooleanToHiddenTransformer;
use Netinfluence\UploadBundle\Generator\ThumbnailGeneratorInterface;
use Netinfluence\UploadBundle\Validation\ImageConstraints;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

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
     * @var ThumbnailGeneratorInterface
     */
    protected $thumbnailGenerator;

    /**
     * @var ImageConstraints
     */
    protected $constraints;

    public function __construct(ThumbnailGeneratorInterface $thumbnailGenerator, ImageConstraints $constraints)
    {
        $this->thumbnailGenerator   = $thumbnailGenerator;
        $this->constraints          = $constraints;
    }

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
            'data_class'    => self::DEFAULT_DATA_CLASS,
            'required'      => false,
            'thumbnail_height' => 120,
            'thumbnail_width' => 120
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
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        // If there is already persisted data behind this form, we must have a server-generated thumbnail
        $data = $form->getData();
        if ($data && $data->getPath() && true !== $data->isTemporary()) {
            $view->vars['thumbnail_url'] = $this->thumbnailGenerator->getUrl($data, array(
                $options['thumbnail_width'], $options['thumbnail_height']
            ));
        }

        $view->vars['thumbnail_height'] = $options['thumbnail_height'];
        $view->vars['thumbnail_width'] = $options['thumbnail_width'];

        $view->vars['image_constraints'] = $this->constraints;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'netinfluence_upload_image_inner';
    }
}
