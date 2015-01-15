<?php
namespace compact\utils;

use compact\logging\Logger;

/**
 *
 * @author eaboxt
 *        
 */
class JsonUtils
{

    const CONTENT_TYPE = "application/json";

    /**
     * Returns the parsed JSON
     *
     * @param $aJsonString String            
     *
     * @return ArrayObject
     */
    public static function decode($aJsonString)
    {
        $decode = json_decode($aJsonString, true);
        
        if ($decode == null) {
            throw new \RuntimeException("JSON error occured");
        }
        return new \ArrayObject($decode);
    }
    
    /**
     * Decodes a JSON string into an object.
     * 
     * @param string $aJsonString The JSON string to parse
     * @param object $aObject [optional] The object to will with the json values
     * 
     * @return object
     */
    public static function decodeObject($aJsonString, $aObject = null){
        $decode = json_decode($aJsonString, true);
        
        if ($aObject !== null){
            foreach ($decode as $key => $value){
                $aObject->{$key} = $value;
            }
            
            return $aObject;
        }
        
        return $decode;
    }

    /**
     * Encodes an object or array into a json string
     *
     * @param
     *            aEncodey mixed
     *            
     * @return string the encoded array
     */
    public static function encode($aEncode)
    {
        if ($aEncode instanceof \Iterator) {
            return self::encodeIterator($aEncode);
        } elseif ($aEncode instanceof \IteratorAggregate) {
            return self::encodeIterator($aEncode->getIterator());
        }
        
        $json = json_encode($aEncode);
        Logger::get()->logFinest("Encoding JSON: object length: " . mb_strlen($json));
        
        return $json;
    }

    /**
     * Encodes an Iterator into a json string
     *
     * @param $aIterator Iterator            
     *
     * @return string the encoded array
     */
    public static function encodeIterator(\Iterator $aIterator)
    {
        $result = array();
        foreach ($aIterator as $item) {
            if ($item instanceof \SplFileInfo) {
                $result[] = ModelUtils::fileToModel($item);
            } else {
                $result[] = $item;
            }
        }
        
        return self::encode($result);
    }
}
