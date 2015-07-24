<?php
namespace compact\mvvm\impl;

use compact\logging\Logger;
use compact\mvvm\IModel;

/**
 * Lightweight object which contains data
 */
class Model implements IModel
{

    /**
     * Automatical method to call methods
     *
     * @param $aName string            
     * @param $aArguments array            
     *
     * @return Model for chaining
     */
    public function __call($aName, array $aArguments)
    {
        Logger::get()->logWarning("Calling non existing method " . $aName);
        return $this;
    }

    /**
     * Returns a registered key
     *
     * @param $key string            
     * @return mixed
     */
    public function __get($aKey)
    {
        if (! isset($this->{$aKey})) {
            $result = null;
        } else {
            $result = $this->{$aKey};
        }
        
        return $result;
    }

    /**
     * Sets the value for a field.
     * Setter will be used when available.
     *
     * @param $aKey string            
     * @param $aValue mixed            
     */
    public function __set($aKey, $aValue)
    {
        $aValue = $this->utf8($aValue);
        
        $method = 'set' . ucwords($aKey);
        if (method_exists($this, $method)) {
            $this->$method($aValue);
        } else {
            $this->$aKey = $aValue;
        }
    }

    /**
     * Convert a value into UTF-8
     * 
     * @param mixed $aValue
     * 
     * @return mixed same type of input
     */
    private function utf8($aValue)
    {
        if (is_array($aValue) || is_object($aValue)){
            foreach ($aValue as $key => $value){
                // handle both objects and arrays
                if (is_object($aValue)){
                    $aValue->{$key} = $this->utf8($value);
                }
                else{
                    $aValue[$key] = $this->utf8($value);
                }
            }
            return $aValue;
        }
        if (mb_detect_encoding($aValue, 'UTF-8', true) != 'UTF-8') {
            return utf8_encode($aValue);
        }
        return $aValue;
    }

    /**
     * (non-PHPdoc)
     *
     * @see compact\mvc\IModel::get()
     */
    public function get($aKey)
    {
        return $this->{$aKey};
    }

    /**
     * (non-PHPdoc)
     *
     * @see compact\mvc\IModel::isEmpty()
     */
    public function isEmpty($aField)
    {
        return empty($this->{$aField});
    }

    /**
     * (non-PHPdoc)
     *
     * @see compact\mvc\IModel::set()
     */
    public function set($aKey, $aValue)
    {
        $method = 'set' . ucwords($aKey);
        if (method_exists($this, $method)) {
            $this->$method($this->utf8($aValue));
        } else {
            $this->$aKey = $aValue;
        }
    }
}