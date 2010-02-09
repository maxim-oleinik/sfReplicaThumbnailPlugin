<?php

/**
 * Base plugin class
 */
class sfReplicaThumbnail
{
    /**
     * Get config from sfConfig
     * Prepares default values
     *
     * @throws Exception if type not defined or missed required options
     *
     * @param  string $type - thumbnail type
     * @return array
     *           - macro    => array()
     *           - default  => null
     *           - mimetype => image/png
     */
    static public function getConfig($type)
    {
        $types = sfConfig::get('app_thumbnail_types');
        if (!isset($types[$type])) {
            throw new Exception(__METHOD__.": Unknown type `{$type}`");
        }
        $result = $types[$type];
        if (!is_array($result)) {
            $result = array();
        }

        # Macro
        if (empty($result['macro'])) {
            throw new Exception(__METHOD__.": Expected macro definition for `{$type}`");
        }

        # Default
        if (!array_key_exists('default', $result)) {
            $result['default'] = null;
        }

        # MimeType
        if (!array_key_exists('mimetype', $result)) {
            $result['mimetype'] = 'image/png';
        }

        return $result;
    }


    /**
     * Load and register macro if not defined
     *
     * @param  string $type  - thumbnail type
     * @param  array $config - macro definition
     * @return void
     */
    static public function loadMacro($type, array $config)
    {
        if (!Replica::hasMacro($type)) {
            foreach ($config as $class => $args) {
                $reflection = new ReflectionClass($class);
                if ($args && is_array($args)) {
                    $macro = $reflection->newInstanceArgs($args);
                } else {
                    $macro = $reflection->newInstance();
                }

                break; // TODO: chain
            }

            Replica::setMacro($type, $macro);
        }
    }

}
