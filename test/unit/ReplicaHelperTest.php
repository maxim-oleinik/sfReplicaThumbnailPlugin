<?php
require_once(dirname(__FILE__) . '/../bootstrap.php');


class ReplicaHelperTest extends sfReplicaThumbnailTestCase
{
    /**
     * No src image
     */
    public function testNoImage()
    {
        $conf = array(
            'logo' => array(
                'macro' => array('Replica_Macro_Null' => array()),
            ),
        );
        sfConfig::set('app_thumbnail_types', $conf);

        $this->assertNull(thumbnail('logo', new Replica_ImageProxy_FromFile('')));
    }


    /**
     * Default image if no src image
     */
    public function testDefaultImage()
    {
        $conf = array(
            'logo' => array(
                'default' => $path = '/path/to/default/image',
                'macro' => array('Replica_Macro_Null' => array()),
            ),
        );
        sfConfig::set('app_thumbnail_types', $conf);

        $expected = sprintf('<img src="%s" alt="" />', $path);
        $this->assertEquals($expected, thumbnail('logo', new Replica_ImageProxy_FromFile('')));
    }


    /**
     * Get thumbnail
     */
    public function testGetTumbnail()
    {
        $conf = array(
            'logo' => array(
                'macro' => array('Replica_Macro_Null' => array()),
            ),
        );
        sfConfig::set('app_thumbnail_types', $conf);
        sfConfig::set('app_thumbnail_dir', '/upload');

        $cache = $this->getMock('Replica_Macro_CacheManager', array('get'), array('/save/dir'));
        $cache->expects($this->once())
              ->method('get')
              ->will($this->returnValue($path = 'path/to/thumbnail'));
        Replica::setCacheManager($cache);

        $expected = sprintf('<img src="/upload/%s" alt="" />', $path);
        $this->assertEquals($expected, thumbnail('logo', new Replica_ImageProxy_FromFile('/some/file')));
    }


}
