<?php

/**
 * Doctrine image proxy
 */
class sfReplicaImageDoctrine extends Replica_ImageProxy_Abstract
{
    /**
     * Record field with binary data
     */
    protected $_field = 'bin';

    /**
     * Record data
     */
    protected
        $_id,
        $_model;


    /**
     * Construct
     *
     * @param string $model   - Model name
     * @param int    $imageId - Record ID
     */
    public function __construct($model, $imageId)
    {
        $this->_id    = (int) $imageId;
        $this->_model = $model;
    }


    /**
     * Get unique image ID
     *
     * @return string
     */
    public function getUid()
    {
        if ($this->_id) {
            return $this->_model . '::' . $this->_id;
        }
    }


    /**
     * Load image
     *
     * @param  Replica_Image_Abstract $image
     * @return void
     */
    protected function _loadImage(Replica_Image_Abstract $image)
    {
        $src = Doctrine::getTable($this->_model)->find($this->_id);
        if ($src) {
            $image->loadFromString($src->get($this->_field));
        }

        return $image;
    }

}
