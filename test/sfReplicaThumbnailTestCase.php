<?php

class sfReplicaThumbnailTestCase extends PHPUnit_Framework_TestCase
{
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
