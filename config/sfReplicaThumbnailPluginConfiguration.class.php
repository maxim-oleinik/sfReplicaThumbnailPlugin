<?php

/**
 * sfReplicaThumbnailPluginConfiguration
 *
 * @author  Maxim Oleinik <maxim.oleinik@gmail.com>
 */
class sfReplicaThumbnailPluginConfiguration extends sfPluginConfiguration
{
    /**
     * Initialize
     *
     * @see sfPluginConfiguration
     */
    public function initialize()
    {
        $dir = sfConfig::get('sf_web_dir') . DIRECTORY_SEPARATOR
             . ltrim(sfConfig::get('app_thumbnail_dir'), '\\/');
        Replica::setCacheManager(new Replica_Macro_CacheManager($dir));
    }


    /**
     * Exclude test classes from autoload
     */
    public function filterAutoloadConfig(sfEvent $event, array $config)
    {
        $config = parent::filterAutoloadConfig($event, $config);

        $libConfig =& $config['autoload'][$this->name.'_lib'];
        $libConfig['exclude'] = array('test');

        return $config;
    }

}
