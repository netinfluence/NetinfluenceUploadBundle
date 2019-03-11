<?php

namespace Netinfluence\UploadBundle\Model;

/**
 * Class FormFile
 * Model used to map objects behind the ImageType form
 */
class FormFile implements UploadableInterface
{
    /**
     * @var string path to file, as used by Gaufrette FS
     */
    protected $path;

    /**
     * @var bool is this path temporary (to sandbox) or not
     */
    protected $temporary;

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     */
    public function isTemporary()
    {
        return $this->temporary;
    }

    /**
     * {@inheritdoc}
     */
    public function setTemporary($temporary)
    {
        $this->temporary = $temporary;
    }
}
