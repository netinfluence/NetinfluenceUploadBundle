<?php

namespace Netinfluence\UploadBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Netinfluence\UploadBundle\Validation\ImageConstraints;

/**
 * Class ImageCollectionType
 */
class ImageCollectionType extends AbstractType
{
    const CHILD_TYPE = 'netinfluence_upload_image_inner';
    const PROTOTYPE_NAME = '__name__';

    /**
     * @var ImageConstraints
     */
    protected $constraints;

    public function __construct(ImageConstraints $constraints)
    {
        $this->constraints = $constraints;
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //We will pass our children some of our options
        $childrenOptions = array_merge(array(
            'thumbnail_height' => $options['thumbnail_height'],
            'thumbnail_width'  => $options['thumbnail_width'],
        ), $options['options']);

        // We need a prototype
        $prototype = $builder->create(self::PROTOTYPE_NAME, self::CHILD_TYPE, array_replace(array(
            'label' => self::PROTOTYPE_NAME.'label__',
        ), $childrenOptions));
        $builder->setAttribute('prototype', $prototype->getForm());

        $resizeListener = new ResizeFormListener(
            self::CHILD_TYPE,
            $childrenOptions,
            true, // allow add
            $options['allow_delete'],
            true // delete empty in SF 2.4
        );

        $builder->addEventSubscriber($resizeListener);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['max_files'] = $options['max_files'];
        $view->vars['allow_delete'] = $options['allow_delete'];

        // those will be used by children form too
        $view->vars['thumbnail_height'] = $options['thumbnail_height'];
        $view->vars['thumbnail_width'] = $options['thumbnail_width'];

        $view->vars['image_constraints'] = $this->constraints;

        $view->vars['prototype'] = $form->getConfig()->getAttribute('prototype')->createView($view);
    }

    /**
     * @inheritdoc
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'allow_delete' => true,
            'max_files' => 0,
            'options'   => array(),
            'thumbnail_height' => 120,
            'thumbnail_width' => 120
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'netinfluence_upload_image_collection';
    }
}
