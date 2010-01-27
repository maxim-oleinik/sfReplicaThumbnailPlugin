<?php

/**
 * Исходное изображение для генерации превью
 *
 * Загружает картинку из БД
 */
class sfReplicaImageDoctrine extends Replica_ImageProxy
{
    private
        $_id,
        $_model;


    /**
     * Конструктор
     *
     * @param  int $imageId - ID изображения в БД
     */
    public function __construct($imageId, $model)
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
     * Получить изображение из БД и создать GD-ресурс
     *
     * @return GD-resource
     */
    protected function _createImage()
    {
        $image = new Replica_ImageGd;

        $src = Doctrine::getTable($this->_model)->find($this->_id);
        if ($src) {
            $image->loadFromString($src->getBin());
        }

        return $image;
    }

}
