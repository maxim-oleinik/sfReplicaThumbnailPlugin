<?php
require_once(dirname(__FILE__) . '/../bootstrap.php');


/**
 * Test Model
 */
class sfReplicaImageDoctrineTest_Model extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('file', 'blob', null, array(
         'type' => 'blob',
         'notnull' => true,
         ));
    }
}


/**
 * Test Table
 */
class sfReplicaImageDoctrineTest_ModelTable extends Doctrine_Table
{
    public function find()
    {
        $img = new sfReplicaImageDoctrineTest_Model;
        $img->set('file', file_get_contents(DIR_SF_REPLICA.'/lib/vendor/Replica/test/fixtures/input/gif_16x14'));
        return $img;
    }
}


/**
 * Test image proxy
 */
class sfReplicaImageDoctrineTest_ImageProxy extends sfReplicaImageDoctrine
{
    protected $_field = 'file';
}


/**
 * Test
 */
class sfReplicaImageDoctrineTest extends sfReplicaThumbnailTestCase
{
    /**
     * Get UID
     */
    public function testGetUid()
    {
        $img = new sfReplicaImageDoctrine('Image', 12);
        $this->assertEquals('Image::12', $img->getUid());
    }


    /**
     * Empty UID if no ID
     */
    public function testEmptyUid()
    {
        $img = new sfReplicaImageDoctrine('Image', false);
        $this->assertNull($img->getUid());
    }


    /**
     * Get Image
     */
    public function testGetImage()
    {
        $proxy = new sfReplicaImageDoctrineTest_ImageProxy('sfReplicaImageDoctrineTest_Model', 12);
        $image = $proxy->getImage();

        $this->assertType('Replica_Image_Gd', $image);
        $this->assertTrue($image->isInitialized(), 'Image is loaded');
    }

}
