<?php
namespace compact\handler\impl\http;

use compact\handler\IHander;
use compact\Context;

/**
 *
 * @author eaboxt
 *        
 */
class HttpStatusHandler implements IHander
{
    /*
     * (non-PHPdoc) @see \compact\handler\IHander::accept()
     */
    public function accept($object)
    {
        return $object instanceof HttpStatus;
    }
    
    /*
     * (non-PHPdoc) @see \compact\handler\IHander::handle()
     */
    public function handle($object)
    {
        /* @var $object \compact\handler\impl\http\HttpStatus */
        $context = Context::get();
        $response = Context::get()->http()->getResponse();
        
        $response->setStatusCode( $object->getHttpCode() );
        
        if ($object->getContent())
        {
            $handler = Context::get()->getHandler( $object->getContent() );
            if ($handler)
            { /* @var $handler \compact\handler\IHander */
                return $handler->handle( $object->getContent() );
            }
        }
        
        return $object;
    }
}