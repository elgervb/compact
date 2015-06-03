<?php
namespace compact\handler\impl\rss;

use compact\handler\IHander;
use compact\Context;
use compact\rss\Rss;

/**
 * Handler for the \compact\rss\Rss object. This will return Rss with the right content type to the browser.
 *
 * @author eaboxt
 *        
 */
class JsonHandler implements IHander
{
    
    /*
     * (non-PHPdoc) @see \compact\handler\IHander::accept()
     */
    public function accept($object)
    {
        return $object instanceof Rss;
    }
    
    /*
     * (non-PHPdoc) @see \compact\handler\IHander::handle()
     */
    public function handle($object)
    {
        assert( '$object instanceof compact\rss\Rss' );
        
        /* @var $object Rss */
        
        $context = Context::get();
        $response = Context::get()->http()->getResponse();
        $response->setContentType("application/rss+xml");
        
        
        return $object->toXml();
    }
}
