<?php
namespace compact\utils;
/**
 *
 * @package core
 * @author elger
 */
class Random
{
	
	/**
	 * Creates a random string consisting of alpha numeric values
	 *
	 * @param $aLength int The length of the string to generate
	 *       
	 * @return String random string consisting of alpha numeric values
	 */
	public static function alphaNum( $aLength = 6 )
	{
		assert( '$aLength > 0' );
		$code = "";
		
		$chars = array_merge( range( 'A', 'F' ), range( 'a', 'f' ), range( '0', '9 ' ) );
		
		for ($i = 0; $i < $aLength; $i ++)
		{
			$nr = mt_rand( 0, count( $chars ) - 1 );
			$code .= $chars[$nr];
		}
		
		return $code;
	}
	
	/**
	 * Creates a new GUID (Globally unique identifier) like 4ddb9da0-a641-471d-a926-221a7a33b0ec
	 *
	 * @return string The GUID
	 */
	public static function guid()
	{
		mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = substr($charid, 0, 8).$hyphen
               .substr($charid, 8, 4).$hyphen
               .substr($charid,12, 4).$hyphen
               .substr($charid,16, 4).$hyphen
               .substr($charid,20,12);
        return $uuid;
	}
	
	/**
	 * Creates a random integer value
	 *
	 * @param $aMin int The minimum value
	 * @param $aMax int The maximum value
	 */
	public static function nummeric( $aMin = 1, $aMax = 255 )
	{
		assert( 'is_int($aMin)/* $aMin must be an integer */' );
		
		return mt_rand( $aMin, $aMax );
	}
}