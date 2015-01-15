<?php
namespace compact\utils;

class NullObject
{

    /**
     * Getter for class attributes, returns self to prevent nullpointers
     *
     * @param $aVar string
     *            The attribute
     */
    public function __get($aVar)
    {
        return $this;
    }

    /**
     * When calling non-existing methods, return self to prevent nullpointers
     *
     * @param $aMethodName string            
     * @param $aArguments array            
     */
    public function __call($aMethodName, array $aArguments)
    {
        return $this;
    }

    /**
     * To string
     *
     * @return string
     */
    public function __toString()
    {
        return "";
    }
}