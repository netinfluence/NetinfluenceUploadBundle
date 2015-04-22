<?php

namespace Netinfluence\UploadBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

/**
 * Class ImageCollectionType
 */
class ImageCollectionType extends AbstractType
{
    const CHILD_TYPE = 'netinfluence_upload_image_inner';
    const PROTOTYPE_NAME = '__name__';

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // We need a prototype
        $prototype = $builder->create(self::PROTOTYPE_NAME, self::CHILD_TYPE, array_replace(array(
            'label' => self::PROTOTYPE_NAME.'label__',
        ), $options['options']));
        $builder->setAttribute('prototype', $prototype->getForm());

        $resizeListener = new ResizeFormListener(
            self::CHILD_TYPE,
            $options['options'],
            true, // allow add
            true, // allow delete
            true // delete empty in SF 2.4
        );

        $builder->addEventSubscriber($resizeListener);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['max_files'] = $form->getConfig()->getAttribute('max_files');

        $view->vars['prototype'] = $form->getConfig()->getAttribute('prototype')->createView($view);
    }

    /**
     * @inheritdoc
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'max_files' => 0,
            'options'   => array()
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
