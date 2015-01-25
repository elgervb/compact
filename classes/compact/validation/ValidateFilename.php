<?php
namespace compact\validation;

use compact\validation\Validator;
use compact\logging\Logger;

/**
 * Validates the a given file name does not contain chars that are nog allowed
 * 
 * @package validation
 */
class ValidateFilename extends Validator
{
	private $onlyAlphaNum;
	/**
	 * Constructor
	 * 
	 * @param string $aFieldName The name of the field to validate
	 * @param boolean $aOnlyAlphaNum [optional] whether or not only alpha numeric chars are allowed
	 */
	public function __construct( $aFieldName, $aOnlyAlphaNum = false )
	{
		parent::__construct( $aFieldName );
		
		$this->onlyAlphaNum = $aOnlyAlphaNum;
	}
	
	/**
	 * 
	 * @param  mixed The item to validate
	 * 
	 * @return  void
	 */
	public function validate( $aValidation )
	{
		if ($this->isEmpty( $aValidation ))
		{
			return;
		}
		
		Logger::get()->logFine( "Validate Filename: " . $this->getFieldName() );
		
		$result = false;
		
		if ($this->onlyAlphaNum){
			if (! preg_match( "/[a-z0-9]/i", $aValidation )){
				throw new ValidationException("Field " . $this->getFieldName() . " can only contain alpha numeric chars" );
			}
		}
		else{
			if (preg_match( "/[\\\\\/\;\:\*\?\'\"\<\>\|]+/i", $aValidation )){
				throw new ValidationException("Field " . $this->getFieldName() . " cannot contain chars: \\/:;*?'\"<>|" );
			}
		}
		
	}
}