<?php

namespace Netinfluence\UploadBundle\Model;

/**
 * Class FormFile
 * Model used to map objects behind the netinfluence_upload_file form
 */
class FormFile
{
    /**
     * @var string path to file, as used by Gaufrette FS
     */
    protected $path;

    /**
     * @var boolean is this path temporary (to sandbox) or not
     */
    protected $temporary;

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return boolean
     */
    public function isTemporary()
    {
        return $this->temporary;
    }

    /**
     * @param boolean $temporary
     */
    public function setTemporary($temporary)
    {
        $this->temporary = $temporary;
    }
}
