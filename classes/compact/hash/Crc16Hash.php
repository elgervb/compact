<?php
namespace compact\hash;

class Crc16Hash implements IHashMethod
{

	/**
	 * Creates a new Crc16Hash
	 */
	public function __construct()
	{
		//
	}

	/**
	 *
	 * @param String $aString The string to hash
	 * @return String the hashed string
	 * @see IHashMethod::encrypt()
	 */
	public function encrypt( $aString )
	{
		$crc = 0xFFFF;
		for ($i = 0; $i < strlen( $aString ); $i ++)
		{
			$x = (($crc >> 8) ^ ord( $aString[$i] )) & 0xFF;
			$x ^= $x >> 4;
			$crc = (($crc << 8) ^ ($x << 12) ^ ($x << 5) ^ $x) & 0xFFFF;
		}
		return $crc;
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