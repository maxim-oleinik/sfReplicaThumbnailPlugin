<?php

/**
 * Base plugin TestCase
 *
 * @author  Maxim Oleinik <maxim.oleinik@gmail.com>
 */
class sfReplicaThumbnailTestCase extends PHPUnit_Framework_TestCase
{
    protected $backupGlobals = false;
    protected $backupStaticAttributes = false;
    protected $preserveGlobalState = false;


    /**
     * TearDown
     */
    public function tearDown()
    {
        sfConfig::clear();
        Replica::removeAll();
        Replica::setCacheManager(null);
    }

}
