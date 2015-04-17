<?php

namespace Netinfluence\UploadBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class FileType
 */
class FileType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('path', 'hidden')
            ->add('temporary', 'hidden')
        ;
    }

    /**
     * @inheritdoc
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'required'  => false,
            'data_class' => 'Netinfluence\UploadBundle\Model\FormFile'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'netinfluence_upload_file';
    }
}
