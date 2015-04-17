<?php
namespace compact\hash;
/**
 * crc32 hash
 *
 * @author Administrator
 *
 */
class Crc32Hash implements IHashMethod
{

	/**
	 * Creates a new Crc32Hash
	 */
	public function __construct()
	{
		//
	}

	/**
	 * @see IHashMethod::encrypt()
	 *
	 * @param String $aString
	 * @return String
	 */
	public function encrypt( $aString )
	{
		return crc32( $aString );
	}

	/**
	 * @see IHashMethod::verify()
	 *
	 * @param String $aPlain
	 * @param String $aEncrypt
	 * @return boolean
	 */
	public function verify( $aPlain, $aEncrypt )
	{
		$result = false;

		if ($this->encrypt( $aPlain ) === $aEncrypt)
		{
			$result = true;
		}

		return $result;
	}

}
