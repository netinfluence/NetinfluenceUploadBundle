<?php

namespace Netinfluence\UploadBundle\Form\Type;

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
