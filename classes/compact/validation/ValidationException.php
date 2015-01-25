<?php
namespace compact\validation;
/**
 * Custom exception class
 * @package validation
 */
class ValidationException extends \Exception
{
	/**
	 * Creates a new TValidationException
	 * 
	 * @param string $aMessage
	 * @param int $aCode = 0
	 * @param Exception $aCause = null
	 */
	public function __construct( $aMessage, $aCode = 0, \Exception $aCause = null )
	{
		assert( '!empty($aMessage)' );
		
		parent::__construct( $aMessage, $aCode, $aCause );
	}
	
	/**
	 * Returns the html message
	 * 
	 * @return string
	 */
	public function getHtmlMessage()
	{
		return nl2br( $this->getMessage() );
	}

}