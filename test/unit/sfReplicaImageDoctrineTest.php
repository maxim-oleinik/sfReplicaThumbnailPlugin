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
    public $log = '';

    public function find()
    {
        $arg = func_get_arg(0);
        $this->log .= sprintf('find(%d)', $arg);

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
     * SetUp
     */
    public function setUp()
    {
        Doctrine::getTable('sfReplicaImageDoctrineTest_Model')->log = '';
    }


    /**
     * Init proxy
     */
    public function testInitProxy()
    {
        // Uid
        $img = new sfReplicaImageDoctrine('sfReplicaImageDoctrineTest_Model', 12);
        $this->assertEquals('sfReplicaImageDoctrineTest_Model::12', $img->getUid());

        // getRecord
        $this->assertType('sfReplicaImageDoctrineTest_Model', $record = $img->getRecord());
        $this->assertEquals('find(12)', $record->getTable()->log);
    }


    /**
     * Init proxy with empty ID
     */
    public function testInitProxyWithEmptyId()
    {
        // Uid
        $img = new sfReplicaImageDoctrine('sfReplicaImageDoctrineTest_Model', false);
        $this->assertNull($img->getUid(), 'Uid');

        // getRecord
        $this->assertNull($img->getRecord(), 'Record');
        $this->assertEquals('', Doctrine::getTable('sfReplicaImageDoctrineTest_Model')->log,
            'No table calls');
    }


    /**
     * Init proxy with record
     */
    public function testInitProxyWithRecord()
    {
        $record = Doctrine::getTable('sfReplicaImageDoctrineTest_Model')->find(15);
        $record->set('id', 15);
        $this->setUp(); // Clear log

        // Uid
        $proxy = new sfReplicaImageDoctrineTest_ImageProxy($record);
        $this->assertEquals('sfReplicaImageDoctrineTest_Model::15', $proxy->getUid());

        // getRecord
        $this->assertSame($record, $proxy->getRecord());
        $this->assertEquals('', $record->getTable()->log);
    }


    /**
     * Init proxy with record exception
     */
    public function testInitProxyWithRecordException()
    {
        $this->setExpectedException('Exception', 'Expected instance of `Doctrine_Record`');
        new sfReplicaImageDoctrineTest_ImageProxy(new StdClass);
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
        $this->assertEquals(16, $image->getWidth(), 'Width');
        $this->assertEquals(14, $image->getHeight(), 'Height');
    }


    /**
     * Get Image from cached record
     */
    public function testGetImageFromCachedRecord()
    {
        $record = Doctrine::getTable('sfReplicaImageDoctrineTest_Model')->find(20);
        $record->set('id', 20);
        $this->setUp(); // Clear log

        $proxy = new sfReplicaImageDoctrineTest_ImageProxy($record);
        $image = $proxy->getImage();

        $this->assertSame($record, $proxy->getRecord());
        $this->assertEquals('', $record->getTable()->log);

        $this->assertType('Replica_Image_Gd', $image);
        $this->assertTrue($image->isInitialized(), 'Image is loaded');
        $this->assertEquals(16, $image->getWidth(), 'Width');
        $this->assertEquals(14, $image->getHeight(), 'Height');
    }

}
