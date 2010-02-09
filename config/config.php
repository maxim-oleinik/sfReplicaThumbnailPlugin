<?php

// Set cache manager
$dir = sfConfig::get('sf_web_dir') . DIRECTORY_SEPARATOR . sfConfig::get('app_thumbnail_dir');
Replica::setCacheManager(new Replica_Macro_CacheManager($dir));
