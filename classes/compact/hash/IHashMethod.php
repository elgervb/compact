<?php
namespace compact\hash;

interface IHashMethod
{
	const SHA1 = "sha1";
	/**
	 * Encrypts a string
	 *
	 * @param String $aString The string to hash
	 *
	 * @return String the hashed string
	 */
	public function encrypt( $aString );

	/**
	 * Verifies that a plain string is equal to the encrypted string
	 *
	 * @param String $aPlain
	 * @param String $aEncrypt
	 *
	 * @return boolean
	 */
	public function verify( $aPlain, $aEncrypt );
}