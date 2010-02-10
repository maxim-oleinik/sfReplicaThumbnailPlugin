<?php

    /**
     * Get thumbnail path
     *
     * @param  string                      $type
     * @param  Replica_ImageProxy_Abstract $proxy
     * @return string - relative path to thumbnail
     */
    function thumbnail($type, Replica_ImageProxy_Abstract $proxy)
    {
        $config = sfReplicaThumbnail::getConfig($type);

        // Has image
        if ($proxy->getUid()) {

            sfReplicaThumbnail::loadMacro($type, $config['macro']);

            // If image not found return null
            try {
                $path = sfConfig::get('app_thumbnail_dir') . '/'
                      . Replica::cache()->get($type, $proxy, $config['mimetype']);
            } catch (Replica_Exception_ImageNotInitialized $e) {
                return;
            }

        // Default image
        } else if (isset($config['default'])) {
            $path = $config['default'];

        // No image
        } else {
            return;
        }

        return sprintf('<img src="%s" alt="" />', $path);
    }
