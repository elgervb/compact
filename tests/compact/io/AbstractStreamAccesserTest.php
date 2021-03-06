<?php
namespace compact\io\reader;

/**
 * Test class for StreamReader.
 * Generated by PHPUnit on 2015-06-12 at 15:36:46.
 */
class AbstractStreamAccesserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var StreamReader
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        // open up this file for reading
        $this->object = new StreamReader(__FILE__);
        $this->object->open();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        // do not close, but let the destructor of the AbstractStreamAccesser kick in
    }
    
    public function testRewind(){
        $content1 = $this->object->readLine();
        $this->object->rewind();
        $content2 = $this->object->readLine();
        
        $this->assertEquals($content1, $content2, "Contents should be equal");
    }
}