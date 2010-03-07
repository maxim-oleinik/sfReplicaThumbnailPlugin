<?php
require_once(dirname(__FILE__) . '/../bootstrap.php');


/**
 * Helper test
 *
 * @author  Maxim Oleinik <maxim.oleinik@gmail.com>
 */
class ReplicaHelperTest extends sfReplicaThumbnailTestCase
{
    /**
     * SetUp
     */
    public function setUp()
    {
        parent::setUp();

        sfConfig::set('app_thumbnail_dir', '/upload');
        Replica::setCacheManager(new Replica_Macro_CacheManager('/save/dir'));
        $this->_setConf('logo');
    }


    /**
     * Set plugin config
     *
     * @param  string $type - thumbnail name
     * @param  array  $data - thumbnail conf
     * @return void
     */
    private function _setConf($type, array $data = array())
    {
        $default = array(
            'macro' => array('Replica_Macro_Null' => array()),
        );
        $data = array_merge($default, $data);
        sfConfig::set('app_thumbnail_types', array($type => $data));
    }


    /**
     * No src image
     */
    public function testNoImage()
    {
        $this->assertNull(thumbnail('logo', new Replica_ImageProxy_FromFile('')));
    }


    /**
     * Return default image if no src image
     */
    public function testDefaultImage()
    {
        $this->_setConf('logo', array(
            'default' => $path = '/path/to/default/image',
        ));

        $expected = sprintf('<img src="%s" alt="" />', $path);
        $this->assertEquals($expected, thumbnail('logo', new Replica_ImageProxy_FromFile('')));
    }


    /**
     * Get thumbnail
     */
    public function testGetTumbnail()
    {
        $this->_setConf('logo', array(
            'mimetype' => 'image/jpg',
            'quality'  => 25,
        ));

        $proxy = new Replica_ImageProxy_FromFile('/some/file');
        $proxy->setMimeType('image/jpg');
        $proxy->setQuality(25);

        $cache = $this->getMock('Replica_Macro_CacheManager', array('get'), array('/save/dir'));
        $cache->expects($this->once())
              ->method('get')
              ->with('logo', $proxy)
              ->will($this->returnValue($path = 'path/to/thumbnail'));
        Replica::setCacheManager($cache);

        $expected = sprintf('<img src="/upload/%s" alt="" />', $path);
        $this->assertEquals($expected, thumbnail('logo', new Replica_ImageProxy_FromFile('/some/file')));
    }


    /**
     * Failed to load image
     */
    public function testFailedToLoadImage()
    {
        $this->assertNull(thumbnail('logo', new Replica_ImageProxy_FromFile('/unknown/file')));
    }


    /**
     * Exception if source image required
     */
    public function testSourceImageRequired()
    {
        $this->_setConf('logo', array(
            'required' => true,
        ));

        $this->setExpectedException('Replica_Exception_ImageNotInitialized');
        thumbnail('logo', new Replica_ImageProxy_FromFile('/unknown/file'));
    }


    /**
     * Tag attributes
     */
    public function testTagAttributes()
    {
        $this->_setConf('logo', array(
            'default' => $path = '/path/to/default/image',
        ));
        $attr = array(
            'alt'     => 'Image alt',
            'class'   => 'myClass',
            'title'   => 'Image title',
        );

        $expected = sprintf('<img alt="%s" class="%s" title="%s" src="%s" />',
            $attr['alt'], $attr['class'], $attr['title'], $path
        );
        $this->assertEquals($expected, thumbnail('logo', new Replica_ImageProxy_FromFile(''), $attr));
    }
}
