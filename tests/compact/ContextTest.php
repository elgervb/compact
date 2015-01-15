<?php
namespace classes\compact;

use compact\Context;
use compact\handler\AssertHandler;
use compact\handler\ErrorHandler;
use compact\handler\ExceptionHandler;
use compact\logging\Logger;

class ContextTest extends \PHPUnit_Framework_TestCase
{

    private $object;

    protected function setUp()
    {
        $this->object = Context::get();
    }

    public function testAddDefaultServices()
    {
        $this->assertNull($this->object->getService(Context::SERVICE_ASSERTION), "AssertionHandler should always be null");
        
        $this->assertNotNull($this->object->getService(Context::SERVICE_ERROR));
        $this->assertTrue($this->object->getService(Context::SERVICE_ERROR) instanceof ErrorHandler, "Type " . get_class($this->object->getService(Context::SERVICE_ERROR)));
        
        $this->assertNotNull($this->object->getService(Context::SERVICE_EXCEPTION));
        $this->assertTrue($this->object->getService(Context::SERVICE_EXCEPTION) instanceof ExceptionHandler, "Type " . get_class($this->object->getService(Context::SERVICE_EXCEPTION)));
        
        $this->assertNotNull($this->object->getService(Context::SERVICE_LOGGING));
        $this->assertTrue($this->object->getService(Context::SERVICE_LOGGING) instanceof Logger, "Type " . get_class($this->object->getService(Context::SERVICE_LOGGING)));
    }
}
