<?php
namespace compact\hash;
/**
 * Use the MD5 hashing method
 *
 */
class Md5Hash implements IHashMethod
{

	/**
	 * Creates a new Md5Hash
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
		return md5( $aString );
	}

	/**
	 * @see IHashMethod::verify()
	 *
	 * @param String $aPlain The plain string
	 * @param String $aEncrypt The md5 string
	 *
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