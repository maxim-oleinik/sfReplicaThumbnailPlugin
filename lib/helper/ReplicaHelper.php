<?php

    /**
     * Get thumbnail path
     *
     * @param  string                      $type
     * @param  Replica_ImageProxy_Abstract $proxy
     * @return string - path to thumbnail
     */
    function thumbnail($type, Replica_ImageProxy_Abstract $proxy)
    {
        $config = sfReplicaThumbnail::getConfig($type);

        // Has image
        if ($proxy->getUid()) {

            sfReplicaThumbnail::loadMacro($type, $config['macro']);

            // TODO: Catch exeption if src not found
            $path = sfConfig::get('app_thumbnail_dir') . '/'
                  . Replica::cache()->get($type, $proxy, $config['mimetype']);

        // Default image
        } else if (isset($config['default'])) {
            $path = $config['default'];

        // No image
        } else {
            return;
        }

        return sprintf('<img src="%s" alt="" />', $path);
    }
