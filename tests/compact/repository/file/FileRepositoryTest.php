<?php
namespace compact\repository\file;

use testutils\mvvm\TestModelConfiguration;
use compact\utils\Random;

/**
 * Test class for FileRepository.
 * Generated by PHPUnit on 2015-01-02 at 09:13:18.
 */
class FileRepositoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var FileRepository
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new FileRepository(new TestModelConfiguration(), new \SplFileInfo(sys_get_temp_dir() . '/compact-' . Random::alphaNum(10) . '.repository'));
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {}
    
    /**
     * @covers compact\repository\file\FileRepository::createModel
     */
    public function testCreateModel()
    {
        $model = $this->object->createModel();
        
        $this->assertTrue($model instanceof \compact\mvvm\IModel, 'Got class: ' . get_class($model));
    }
}
