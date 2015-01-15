<?php
namespace compact\handler\impl\json;

use compact\handler\IHander;
use compact\Context;
use compact\utils\JsonUtils;
use compact\handler\impl\json\Json;

/**
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
        return $object instanceof Json;
    }
    
    /*
     * (non-PHPdoc) @see \compact\handler\IHander::handle()
     */
    public function handle($object)
    {
        assert( '$object instanceof compact\handler\impl\json\Json' );
        
        $result = null;
        /* @var $object \core\mvc\impl\json\Json */
        $context = Context::get();
        $response = Context::get()->http()->getResponse();
        $response->setContentType(JsonUtils::CONTENT_TYPE);
        
        $object = $object->getObject();
        if (is_object( $object ))
        {
            if ($object instanceof \IteratorAggregate){
                /* @var $object \IteratorAggregate */
                $result = JsonUtils::encodeIterator( $object->getIterator() );
            }
            elseif ($object instanceof \Iterator)
            {
                $result = JsonUtils::encodeIterator( $object );
            }
            else
            {
                $result = JsonUtils::encode($object);
            }
        }
        else
        {
            if (is_array($object))
            {
                $result = JsonUtils::encode( $object );
            }
            else
            {
                throw new \Exception( 'JSon encoding of a non-object is not supported' );
            }
        }
        
        if ($result !== null)
        {
            $response->getWriter()->write( $result );
        }
        
        return $result;
    }
}
