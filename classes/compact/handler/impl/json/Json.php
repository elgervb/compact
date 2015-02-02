<?php
namespace compact\handler\impl\json;
/**
 * @author eaboxt
 */
class Json
{
    /**
     * The object to serialize to json
     * @var mixed
     */
    private $object;
    
    /**
     * Creates a new Json object from the $aObject
     * @param mixed $aObject The object to serialize to json. Normally this would be an array or any object.
     */
    public function __construct( $aObject )
    {
        $this->object = $aObject;
    }
    
    /**
     * Returns the json object
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }
}
