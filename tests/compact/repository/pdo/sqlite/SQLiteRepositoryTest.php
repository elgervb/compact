<?php
namespace compact\repository\json;

use compact\repository\pdo\sqlite\SQLiteRepository;
use compact\repository\pdo\sqlite\SQLiteDynamicModelConfiguration;
use compact\utils\Random;
use testutils\mvvm\TestModel;
/**
 * Test class for SQLiteRepositoryTest.
 * Generated by PHPUnit on 2015-01-02 at 11:50:54.
 */
class SQLiteRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SQLiteRepository
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->filename = sys_get_temp_dir() . '/compact-' . Random::alphaNum(6) . '.sqlite';
        $query = file_get_contents(__DIR__ . '/TestModel.sqlite');
        $this->object = new SQLiteRepository(new SQLiteDynamicModelConfiguration('test'), "sqlite:" . $this->filename, $query);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        // unlink($this->filename); // permission denied
    }
    
    /**
     * @covers compact\repository\json\JsonRepository::createModel
     */
    public function testCreateModel()
    {
        $model = $this->object->createModel();
    
        $this->assertTrue($model instanceof \compact\mvvm\IModel, 'Got class: ' . get_class($model));
    }
    
    /**
     * (non-PHPdoc)
     *
     * @covers compact\repository\json\JsonRepository::save()
     * 
     * @return IModel to be used in other tests
     */
    public function testSave(){
        $model = $this->object->createModel();
        TestModel::randomData($model);
        
        $this->assertTrue( $this->object->save($model),  'Saving TestModel failed' );
        $this->assertTrue(is_numeric($model->get(TestModel::ID)), 'PrimaryKey not filled in ' . $model->get(TestModel::ID));
        
        return $model;
    }
    
    public function testGUIDGeneration(){
        $model = $this->testSave();
        
        $this->assertTrue($model->get(TestModel::GUID) !== "");
    }
    
    /**
     * (non-PHPdoc)
     *
     * @covers compact\repository\json\JsonRepository::read()
     */
    public function testRead(){
        $model = $this->testSave();
        
        $read = $this->object->read($model->get(TestModel::ID));
        
        $this->assertTrue($model instanceof \compact\mvvm\IModel, 'Got class: ' . get_class($read));
        $this->assertEquals($read->get(TestModel::ID), $model->get(TestModel::ID));
        $this->assertEquals($read->get(TestModel::NUMBER), $model->get(TestModel::NUMBER));
        $this->assertEquals($read->get(TestModel::FIELD1), $model->get(TestModel::FIELD1));
        $this->assertEquals($read->get(TestModel::FIELD2), $model->get(TestModel::FIELD2));
    }
    
    /**
     * (non-PHPdoc)
     *
     * @covers compact\repository\json\JsonRepository::search()
     */
    public function testDelete(){
        // add 3 models
        $model1 = $this->testSave();
        $model2 = $this->testSave();
        $model3 = $this->testSave();
    
        $models = $this->object->search();
        $this->assertEquals($models->count() , 3, 'Found ' . $models->count() . ' models instead of 3' );
        
        // Delete model 2
        $this->object->delete($model2);
        
        // check for models 1 and 3
        $models = $this->object->search();
        $this->assertEquals($models->count() , 2, 'Found ' . $models->count() . ' models instead of 2' );
        
        $this->assertEquals($model1->get(TestModel::ID), $models->offsetGet(0)->get(TestModel::ID));
        $this->assertEquals($model3->get(TestModel::ID), $models->offsetGet(1)->get(TestModel::ID));
    }
    
    /**
     * (non-PHPdoc)
     *
     * @covers compact\repository\json\JsonRepository::search()
     */
    public function testSearch(){
        // add 4 models
        $this->testSave();
        $this->testSave();
        $this->testSave();
        $this->testSave();
        
        $models = $this->object->search();
        
        $this->assertEquals($models->count() , 4, 'Found ' . $models->count() . ' models instead of 4' );
    }
}
