<?php
namespace compact\upload;

class UploadException extends \Exception
{
	
	/**
	 * Creates a new TUploadException
	 * 
	 * @param string $aMessage
	 * @param int $aCode = 0
	 * @param \Excpetion $aCause
	 */
	public function __construct( $aMessage, $aCode = 0 , $aCause = null )
	{
		parent::__construct( $aMessage, $aCode, $aCause  );
	}
}