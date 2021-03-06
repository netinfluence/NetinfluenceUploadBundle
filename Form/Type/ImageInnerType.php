<?php

namespace Netinfluence\UploadBundle\Form\Type;

use Netinfluence\UploadBundle\Form\DataTransformer\BooleanToHiddenTransformer;
use Netinfluence\UploadBundle\Manager\Thumbnail\ThumbnailManagerInterface;
use Netinfluence\UploadBundle\Model\FormFile;
use Netinfluence\UploadBundle\Model\UploadableInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
    const DEFAULT_DATA_CLASS = FormFile::class;
    const DATA_CLASS_REQUIRED_INTERFACE = UploadableInterface::class;

    /**
     * @var ThumbnailManagerInterface
     */
    protected $thumbnailManager;

    public function __construct(ThumbnailManagerInterface $thumbnailManager)
    {
        $this->thumbnailManager = $thumbnailManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('path', HiddenType::class)
            ->add(
                $builder
                    // this more complex syntax is required when adding a transformer
                    ->create('temporary', HiddenType::class, array(
                        'empty_data' => false,
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
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => self::DEFAULT_DATA_CLASS,
            'required' => false,
            'thumbnail_height' => 120,
            'thumbnail_width' => 120,
        ));

        $dataClass = self::DEFAULT_DATA_CLASS;
        $interface = self::DATA_CLASS_REQUIRED_INTERFACE;

        $resolver->setAllowedValues('data_class', function ($value) use ($dataClass, $interface) {
            if (!is_a($value, $interface, true)) {
                // We throw an Exception for a more precise feedback than OptionResolver one
                throw new \Exception(sprintf(
                    'Form type "ImageType" must be mapped to objects implementing %s. Wrong value "%s" received for "data_class".',
                    $interface,
                    $dataClass
                ));
            }

            return true;
        });

        $resolver->setDefined(array('extra_fields'));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        // If there is already persisted data behind this form, we must have a server-generated thumbnail
        $data = $form->getData();
        if ($data && $data->getPath() && true !== $data->isTemporary()) {
            $view->vars['thumbnail_url'] = $this->thumbnailManager->getThumbnailUrl($data, array(
                $options['thumbnail_width'], $options['thumbnail_height'],
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'netinfluence_upload_image_inner';
    }
}
