<?php
require_once(dirname(__FILE__) . '/../bootstrap.php');

/**
 * sfReplicaThumbnail test
 *
 * @author  Maxim Oleinik <maxim.oleinik@gmail.com>
 */
class sfReplicaThumbnailTest extends sfReplicaThumbnailTestCase
{
    /**
     * Get config
     */
    public function testGetConfig()
    {
        $expected = array(
            'default'  => null,
            'required' => true,
            'mimetype' => 'image/png',
            'quality'  => 50,
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
            'required' => false,
            'mimetype' => 'image/png',
            'quality'  => null,
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
        $this->assertInstanceOf('Replica_Macro_Chain', $macro = Replica::getMacro('logo'));
        $this->assertEquals(array(
            'Replica_Macro_ThumbnailFit' => array(
                'maxWidth'  => $width,
                'maxHeight' => $height,
            ),
        ), $macro->getParameters());
    }


    /**
     * Load macro chain
     */
    public function testLoadMacroChain()
    {
        $config = array(
            'Replica_Macro_ThumbnailFit' => array($width=10, $height=20),
            'Replica_Macro_Null'         => array(),
        );
        sfReplicaThumbnail::loadMacro('chain', $config);

        $this->assertTrue(Replica::hasMacro('chain'), 'Macro is initialized');
        $this->assertInstanceOf('Replica_Macro_Chain', $macro = Replica::getMacro('chain'));
        $this->assertEquals(array(
            'Replica_Macro_ThumbnailFit' => array(
                'maxWidth'  => $width,
                'maxHeight' => $height,
            ),
            'Replica_Macro_Null' => array(),
        ), $macro->getParameters());
    }


    /**
     * Load macro once
     */
    public function testLoadMacroOnce()
    {
        // first
        $config = array('Replica_Macro_Null' => array());
        sfReplicaThumbnail::loadMacro('load_once', $config);

        // second
        $config = array('Replica_Macro_ThumbnailFit' => array($width=10, $height=20));
        sfReplicaThumbnail::loadMacro('load_once', $config);

        $this->assertTrue(Replica::hasMacro('load_once'), 'Macro is initialized');
        $this->assertInstanceOf('Replica_Macro_Chain', $macro = Replica::getMacro('load_once'));
        $this->assertEquals(array(
            'Replica_Macro_Null' => array(),
        ), $macro->getParameters());
    }

}
