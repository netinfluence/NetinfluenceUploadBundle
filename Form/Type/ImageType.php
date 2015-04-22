<?php

namespace Netinfluence\UploadBundle\Form\Type;

use Netinfluence\UploadBundle\Form\DataTransformer\BooleanToHiddenTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ImageType
 * The "real" form class is InnerType
 * We extend it so we have can "decorate" the inner form in templating easily
 */
class ImageType extends ImageInnerType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'netinfluence_upload_image';
    }
}
