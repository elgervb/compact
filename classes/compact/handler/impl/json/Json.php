<?php
namespace compact\handler\impl\json;
/**
 * @author eaboxt
 */
class Json
{
    private $object;
    
    public function __construct( $aObject )
    {
        $this->object = $aObject;
    }
    
    public function getObject()
    {
        return $this->object;
    }
}
