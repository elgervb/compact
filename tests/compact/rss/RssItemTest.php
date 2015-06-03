<?php
namespace compact\rss;

class RssItemTest extends \PHPUnit_Framework_TestCase
{
    public function testGetFieldNames(){
        $obj = new RssItem();
        
        $result = $obj->getFieldNames();
        $this->assertEquals(10, count($result), "Count of fieldnames is not correct");   
    }
    
    public function testSet(){
        $obj = new RssItem();
    
        $obj->set("one", "two");
        $this->assertEquals("two", $obj->get("one"), "Get value is not correct");
    }
}