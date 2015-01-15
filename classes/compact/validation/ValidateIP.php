<?php
namespace compact\validation;

use compact\validation\Validator;
use compact\logging\Logger;
use core\validation\ValidationException;

/** 
 * @package validation
 * @author elger
 */
class ValidateIP extends Validator
{
	
	/**
	 * Creates a new IP validater
	 * 
	 * @param string $aFieldName The name of the field to validate
	 */
	public function __construct( $aFieldName )
	{
		parent::__construct( $aFieldName );
	}
	
	/**
	 * 
	 * @param  mixed The item to validate
	 * 
	 * @return  void
	 * 
	 * @see TValidator::validate()
	 */
	public function validate( $aValidation )
	{
		if ($this->isEmpty( $aValidation ))
		{
			return;
		}
		
		Logger::get()->logFine( "Validate ip: " . $aValidation );
		
		$result = preg_match( "/(\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b)/", $aValidation );
		
		if ($result <= 0)
		{
			throw new ValidationException($aValidation . " is not a valid IP address" );
		}
	}
}