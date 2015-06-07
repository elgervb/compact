<?php
namespace compact\rss;

class RssChannelTest extends \PHPUnit_Framework_TestCase
{
    public function testGetFieldNames(){
        $obj = new RssChannel();
        
        $result = $obj->getFieldNames();
        $this->assertEquals(19, count($result), "Count of fieldnames is not correct");   
    }
    
    public function testSet(){
        $obj = new RssChannel();
    
        $obj->set("one", "two");
        $this->assertEquals("two", $obj->get("one"), "Get value is not correct");
    }
}