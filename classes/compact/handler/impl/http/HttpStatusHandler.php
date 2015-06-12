<?php
namespace compact\handler\impl\http;

use compact\handler\IHander;
use compact\Context;
use compact\logging\Logger;

/**
 * Handle HTTP status codes: 200, 301, 302, 400, 500, etc
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
        
        Logger::get()->logFine("Got http status " . $object->getHttpCode() );
        
        // add extra headers
        $extraHeaders = $object->getExtraHeaders();
        if ($extraHeaders){
            foreach ($extraHeaders as $header => $value){
                $response->addHeader($header, $value);
            }
        }
        
        switch($object->getHttpCode()){
        	case 301:
        	case 302:
        	    return $this->handleRedirect($object);
        	default:
        	    return $this->handleDefault($object);
        }
    }
    
    /**
     * Handle the default. Just try to look for a template and return it
     * 
     * @param unknown $object
     * @return \compact\handler\mixed|\compact\handler\impl\http\HttpStatus
     */
    private function handleDefault(HttpStatus $object){
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
    
    private function handleRedirect(HttpStatus $object){
        Context::get()->http()->getResponse()->redirect($object->getContent());
        
        return $object;
    }
}