<?php
namespace compact\io\writer;

/**
 * Test class for BufferedStreamWriter.
 * Generated by PHPUnit on 2014-11-19 at 14:46:30.
 */
class BufferedStreamWriterTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var BufferedStreamWriter
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new BufferedStreamWriter("php://output");
        $this->object->open();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {}

    /**
     * @covers compact\io\writer\BufferedStreamWriter::__destruct
     *
     * @todo Implement test__destruct().
     */
    public function test__destruct()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * @covers compact\io\writer\BufferedStreamWriter::close
     *
     * @todo Implement testClose().
     */
    public function testClose()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * @covers compact\io\writer\BufferedStreamWriter::flush
     *
     * @todo Implement testFlush().
     */
    public function testFlush()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    /**
     * @covers compact\io\writer\BufferedStreamWriter::write
     */
    public function testWrite()
    {
        $write = "line1-line2";
        
        ob_start();
        $this->object->write($write);
        $this->object->flush();
        $output = ob_get_clean();
        
        $this->object->close();
        $this->assertEquals($write, $output);
    }

    /**
     * @covers compact\io\writer\BufferedStreamWriter::write
     */
    public function testWriteWithoutFlush()
    {
        $write = "line1-line2";
        
        ob_start();
        $this->object->write($write);
        $output = ob_get_clean();
        
        $this->object->close();
        $this->assertEquals("", $output, "The output should be empty");
    }

    /**
     * @covers compact\io\writer\BufferedStreamWriter::write
     */
    public function testWriteWithFLushTwice()
    {
        $write = "line1-line1";
        
        ob_start();
        $this->object->write($write);
        $this->object->flush();
        $output = ob_get_clean();
        
        $this->object->close();
        $this->assertEquals($write, $output);
        
        $write = "line2-line2";
        ob_start();
        $this->object->write($write);
        $this->object->flush();
        $output = ob_get_clean();
        
        $this->object->close();
        $this->assertEquals($write, $output);
    }
}
