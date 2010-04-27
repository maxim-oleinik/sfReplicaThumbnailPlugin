<?php

/**
 * Helper
 *
 * @package    sfReplicaThumbnail
 * @author     Maxim Oleinik <maxim.oleinik@gmail.com>
 */
require_once(sfConfig::get('sf_symfony_lib_dir') . '/helper/TagHelper.php');


    /**
     * Get thumbnail image tag
     *
     * @param  string                      $type
     * @param  Replica_ImageProxy_Abstract $proxy
     * @param  array                       $attributes - Additional tag attributes
     * @return null|string                             - Image tag
     */
    function thumbnail($type, Replica_ImageProxy_Abstract $proxy, array $attributes = array())
    {
        $path = thumbnail_path($type, $proxy);

        // Render tag
        if ($path) {
            $attributes['src'] = $path;
            if (!isset($attributes['alt'])) {
                $attributes['alt'] = '';
            }
            return tag('img', $attributes);
        }
    }


    /**
     * Get thumbnail path
     *
     * @param  string                      $type
     * @param  Replica_ImageProxy_Abstract $proxy
     * @return null|string                             - Relative path to thumbnail
     */
    function thumbnail_path($type, Replica_ImageProxy_Abstract $proxy)
    {
        $config = sfReplicaThumbnail::getConfig($type);

        // Has image
        if ($proxy->getUid()) {

            sfReplicaThumbnail::loadMacro($type, $config['macro']);

            // If image not found return null
            try {
                $proxy->setMimeType($config['mimetype']);
                if (null !== $config['quality']) {
                    $proxy->setQuality($config['quality']);
                }
                return sfConfig::get('app_thumbnail_dir') . '/' . Replica::cache()->get($type, $proxy);

            } catch (Replica_Exception_ImageNotInitialized $e) {
                if ($config['required']) {
                    throw $e;
                }
                return;
            }

        // Default image
        } else if (isset($config['default'])) {
            return $config['default'];
        }
    }
