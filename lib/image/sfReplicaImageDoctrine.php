<?php

/**
 * Doctrine image proxy
 */
class sfReplicaImageDoctrine extends Replica_ImageProxy
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
        return $this->_model . '::' . $this->_id;
    }


    /**
     * Load image
     *
     * @param  Replica_ImageAbstract $image
     * @return void
     */
    protected function _loadImage(Replica_ImageAbstract $image)
    {
        $src = Doctrine::getTable($this->_model)->find($this->_id);
        if ($src) {
            $image->loadFromString($src->get($this->_field));
        }

        return $image;
    }

}
