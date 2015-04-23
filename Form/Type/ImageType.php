<?php

namespace Netinfluence\UploadBundle\Form\Type;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

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

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['max_files'] = 1; // single image form
    }
}
