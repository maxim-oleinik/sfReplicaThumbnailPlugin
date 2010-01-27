<?php


    function thumbnail($type, $imageId, $model)
    {
        if ($imageId) {
            $path = sfConfig::get('app_thumbnail_dir') . '/' . Replica_Macro_Cache::get($type, new myImageFromDababase($imageId, $model));

#        } else if ($default = sfConfig::get('app_default_thumbnail_'.$type)) {
#            $path = $default;

        } else {
            return;
        }

        return sprintf('<img src="%s" alt="" />', $path);
    }
