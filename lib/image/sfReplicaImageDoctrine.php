<?php

/**
 * Doctrine image proxy
 *
 * @package    sfReplicaThumbnail
 * @author     Maxim Oleinik <maxim.oleinik@gmail.com>
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
        $_model,
        $_record;


    /**
     * Construct
     *
     * Usage:
     *   1. new sfReplicaImageDoctrine("Article", 12)
     *   2. new sfReplicaImageDoctrine($articleRecord)
     *
     * @param string|Doctrine_Record $model   - Model name
     * @param int                    $imageId - Record ID
     */
    public function __construct($model, $imageId = null)
    {
        if (is_object($model)) {

            if ($model instanceof Doctrine_Record) {
                $this->_record = $model;

                $key = $this->_record->getTable()->getIdentifier();
                $this->_id    = (int) $this->_record->get($key);

                $this->_model = get_class($this->_record);

            } else {
                $class = get_class($model);
                throw new InvalidArgumentException(__METHOD__.": Expected instance of `Doctrine_Record`, got `{$class}`");
            }

        } else {
            $this->_id    = (int) $imageId;
            $this->_model = (string) $model;
        }
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
     * Get record
     *
     * @return Doctrine_Record|false
     */
    public function getRecord()
    {
        if ($this->_id && null === $this->_record) {
            $this->_record = Doctrine::getTable($this->_model)->find($this->_id);
        }
        return $this->_record;
    }


    /**
     * Load image
     *
     * @param  Replica_Image_Abstract $image
     * @return void
     */
    protected function _loadImage(Replica_Image_Abstract $image)
    {
        $record = $this->getRecord();
        if ($record) {
            $image->loadFromString($record->get($this->_field));
        }

        return $image;
    }

}
