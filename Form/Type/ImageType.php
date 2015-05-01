<?php

namespace Netinfluence\UploadBundle\Form\Type;

use Netinfluence\UploadBundle\Manager\Thumbnail\ThumbnailManagerInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Netinfluence\UploadBundle\Validation\ImageConstraints;

/**
 * Class ImageType
 * The "real" form class is InnerType
 * We extend it so we have can "decorate" the inner form in templating easily
 */
class ImageType extends ImageInnerType
{
    /**
     * @var ImageConstraints
     */
    protected $constraints;

    public function __construct(ThumbnailManagerInterface $thumbnailManager, ImageConstraints $constraints)
    {
        parent::__construct($thumbnailManager);

        $this->constraints = $constraints;
    }

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
        parent::buildView($view, $form, $options);

        $view->vars['max_files'] = 1; // single image form

        $view->vars['thumbnail_height'] = $options['thumbnail_height'];
        $view->vars['thumbnail_width'] = $options['thumbnail_width'];

        $view->vars['image_constraints'] = $this->constraints;
    }
}
