<?php
namespace compact\upload;

use compact\logging\Logger;

class UploadOptions
{
	/**
	 *
	 * @var \SplFileInfo
	 */
	private $uploadDir;
	
	/**
	 *
	 * @var array
	 */
	private $allowedMimetypes = array();
	
	/**
	 *
	 * @var int
	 */
	private $maxSize;
	
	/**
	 *
	 * @var int
	 */
	private $maxFiles = 1;
	
	/**
	 *
	 * @var int
	 */
	private $maxTotalSize;
	
	/**
	 *
	 * @var boolean
	 */
	private $allowOverwrite = false;
	
	/**
	 * Creates a new UploadOptions
	 */
	public function __construct()
	{
	    //
	}
	
	/**
	 * Add a mimetype to the allowed mimetype list
	 *
	 * @param $aMimetype string
	 */
	public function addMimetype( $aMimetype )
	{
		$this->allowedMimetypes[] = $aMimetype;
	}
	
	/**
	 *
	 * @return boolean
	 */
	public function getAllowOverwrite()
	{
		return $this->allowOverwrite;
	}
	
	/**
	 *
	 * @return \SplFileInfo
	 */
	public function getUploadDir()
	{
		return $this->uploadDir;
	}
	
	/**
	 *
	 * @return int
	 */
	public function getMaxFiles()
	{
		return $this->maxFiles;
	}
	
	/**
	 *
	 * @return int
	 */
	public function getMaxSize()
	{
		return $this->maxSize;
	}
	
	/**
	 *
	 * @return int
	 */
	public function getMaxTotalSize()
	{
		return $this->maxTotalSize;
	}
	
	/**
	 *
	 * @return array with all mimetypes
	 */
	public function getMimetypes()
	{
		return $this->allowedMimetypes;
	}
	
	/**
	 *
	 * @param $allowOverwrite boolean
	 */
	public function setAllowOverwrite( $allowOverwrite )
	{
		$this->allowOverwrite = $allowOverwrite;
	}
	
	/**
	 *
	 * @param aMaxFiless int
	 */
	public function setMaxFiles( $aMaxFiles )
	{
		$iniMaxFileUploadValue = ini_get( "max_file_uploads" );
		if ($iniMaxFileUploadValue < $aMaxFiles)
		{
			Logger::get()->logWarning("Setting max_file_uploads to " . $aMaxFiles . " has no effect as this setting in php.ini is set to " . $iniMaxFileUploadValue);
		}
		
		$this->maxFiles = $aMaxFiles;
	}
	
	/**
	 * Sets the max upload size for this upload batch (all size together)
	 *
	 * @param $maxTotalSize int in bytes
	 */
	public function setMaxTotalSize( $maxTotalSize )
	{
		$this->maxTotalSize = $maxTotalSize;
	}
	
	/**
	 * Sets the max file size (in bytes) for each upload
	 *
	 * @param $maxSize int the max size in bytes
	 */
	public function setMaxSize( $aMaxSize )
	{
		$this->maxSize = $aMaxSize;
	}
	
	/**
	 *
	 * @param $allowedMimetypes string
	 *
	 * @see addMimetype(string $aMimetype)
	 */
	public function setMimetype( $aMimetype )
	{
		assert( 'is_string($aMimetype)' );
		
		$this->addMimetype( $aMimetype );
	}
	
	/**
	 *
	 * @param $allowedMimetypes array
	 */
	public function setMimetypes( array $aMimetypes )
	{
		if ($this->allowedMimetypes === null)
		{
			$this->allowedMimetypes = $aMimetypes;
		}
		else
		{
			foreach ($aMimetypes as $mimetype)
			{
				$this->addMimetype( $mimetype );
			}
		}
	}
	
	
	/**
	 *
	 * @param $uploadDir \SplFileInfo
	 */
	public function setUploadDir(\SplFileInfo $uploadDir )
	{
		$this->uploadDir = $uploadDir;
	}
}
