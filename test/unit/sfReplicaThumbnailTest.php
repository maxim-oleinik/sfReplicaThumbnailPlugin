<?php

class sfReplicaThumbnailTest extends PHPUnit_Framework_TestCase
{
    /**
     * TearDown
     */
    public function tearDown()
    {
        sfConfig::clear();
        Replica::removeAll();
    }


    /**
     * Get config
     */
    public function testGetConfig()
    {
        $expected = array(
            'default'  => null,
            'mimetype' => 'image/png',
            'macro' => array('macro' => array(1,2,3)),
        );

        sfConfig::set('app_thumbnail_types', array('logo' => $expected));

        $config = sfReplicaThumbnail::getConfig('logo');
        $this->assertEquals($expected, $config);
    }


    /**
     * Get config: set defaults
     */
    public function testGetConfigDefaults()
    {
        $data = array(
            'macro' => array('macro' => array(1,2,3)),
        );
        sfConfig::set('app_thumbnail_types', array('logo' => $data));

        $expected = array_merge(array(
            'default'  => null,
            'mimetype' => 'image/png',
        ), $data);


        $config = sfReplicaThumbnail::getConfig('logo');
        $this->assertEquals($expected, $config);
    }


    /**
     * Get config: Exception if type not found
     */
    public function testGetConfigExceptionIfTypeNotFound()
    {
        $this->setExpectedException('Exception', 'Unknown type');
        sfReplicaThumbnail::getConfig('logo');
    }


    /**
     * Get config: Expection if macro not defined
     */
    public function testGetConfigExceptionIfMacroNotDefined()
    {
        sfConfig::set('app_thumbnail_types', array('logo' => 'some text'));

        $this->setExpectedException('Exception', 'Expected macro definition');
        sfReplicaThumbnail::getConfig('logo');
    }


    /**
     * Load macro
     */
    public function testLoadMacro()
    {
        $this->assertFalse(Replica::hasMacro('logo'), 'Registry is empty');

        $config = array('Replica_Macro_ThumbnailFit' => array($width=10, $height=20));
        sfReplicaThumbnail::loadMacro('logo', $config);

        $this->assertTrue(Replica::hasMacro('logo'), 'Macro is initialized');
        $this->assertType('Replica_Macro_ThumbnailFit', $macro = Replica::getMacro('logo'));
        $this->assertEquals(array(
            'maxWidth'  => $width,
            'maxHeight' => $height,
        ), $macro->getParameters());
    }


    /**
     * Load macro once
     */
    public function testLoadMacroOnce()
    {
        $this->assertFalse(Replica::hasMacro('logo'), 'Registry is empty');

        // first
        $config = array('Replica_Macro_Null' => array());
        sfReplicaThumbnail::loadMacro('logo', $config);

        // second
        $config = array('Replica_Macro_ThumbnailFit' => array($width=10, $height=20));
        sfReplicaThumbnail::loadMacro('logo', $config);

        $this->assertTrue(Replica::hasMacro('logo'), 'Macro is initialized');
        $this->assertType('Replica_Macro_Null', $macro = Replica::getMacro('logo'));
    }

}
