<?php
namespace compact\hash;

class HashFactory
{
	const CRC16 = 'crc16';
	const CRC32 = 'crc32';
	const MD5 = 'md5';
	const SHA1 = 'sha1';
	/**
	 * Creates a new specified hash method
	 *
	 * @param $aHashName The hashname
	 *       
	 * @return IHashMethod The newly created hashmethod
	 */
	public static function createHash( $aHashName )
	{
		switch (strtolower( $aHashName ))
		{
			case self::CRC16	:
				return self::createCrc16Hash();
			
			case self::CRC32	:
				return self::createCrc32Hash();
			
			case self::MD5		:
				return self::createMd5Hash();
			
			case self::SHA1		:
				return self::createSha1Hash();
			
			default:
				throw new \Exception( "Unsupported hash method: " . $aHashName );
		}
	}
	
	/**
	 * Creates a new crc 16 hash
	 *
	 * @return IHashMethod
	 */
	public static function createCrc16Hash()
	{
		return new Crc16Hash();
	}
	
	/**
	 * Creates a new crc 32 hash
	 *
	 * @return IHashMethod
	 */
	public static function createCrc32Hash()
	{
		return new Crc32Hash();
	}
	
	/**
	 * Creates a new md5 hash
	 *
	 * @return IHashMethod
	 */
	public static function createMd5Hash()
	{
		return new Md5Hash();
	}
	
	/**
	 * Creates a new sha1 hash
	 *
	 * @return IHashMethod
	 */
	public static function createSha1Hash()
	{
		return new Sha1Hash();
	}
}