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
    /**
     * By default, behind the form we will have an instance of this object
     */
    const INNER_TYPE = 'netinfluence_upload_image';
    const DATA_CLASS_REQUIRED_INTERFACE = 'Netinfluence\UploadBundle\Model\UploadableInterface';

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $resizeListener = new ResizeFormListener(
            'netinfluence_upload_image',
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
    }

    /**
     * @inheritdoc
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'max_files' => 0,
            'options' => array()
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
