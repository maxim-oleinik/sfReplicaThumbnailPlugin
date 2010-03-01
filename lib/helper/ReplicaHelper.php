<?php

/**
 * Helper
 *
 * @package    sfReplicaThumbnail
 * @author     Maxim Oleinik <maxim.oleinik@gmail.com>
 */
require_once(sfConfig::get('sf_symfony_lib_dir') . '/helper/TagHelper.php');


    /**
     * Get thumbnail path
     *
     * @param  string                      $type
     * @param  Replica_ImageProxy_Abstract $proxy
     * @param  array                       $attributes - Additional tag attributes
     * @return string - relative path to thumbnail
     */
    function thumbnail($type, Replica_ImageProxy_Abstract $proxy, array $attributes = array())
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
                if ($config['required']) {
                    throw $e;
                }
                return;
            }

        // Default image
        } else if (isset($config['default'])) {
            $path = $config['default'];

        // No image
        } else {
            return;
        }


        // Render tag
        $attributes['src'] = $path;
        if (!isset($attributes['alt'])) {
            $attributes['alt'] = '';
        }
        return tag('img', $attributes);
    }
