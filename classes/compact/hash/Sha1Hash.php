<?php
namespace compact\hash;

class Sha1Hash implements IHashMethod
{

	/**
	 * Creates a new Sha1Hash
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Hashes a string with the sha1 algorithm. The returned value is a 40-character hexadecimal number.
	 *
	 * @param String $aString The string to hash
	 * @return String the hashed string: 40-character hexadecimal number
	 * @see IHashMethod::encrypt()
	 */
	public function encrypt( $aString )
	{
		return sha1( $aString );
	}

	/**
	 *
	 * @param String $aPlain
	 * @param String $aEncrypt
	 * @return boolean
	 * @see IHashMethod::verify()
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

?>