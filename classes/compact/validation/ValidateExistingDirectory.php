<?php
namespace compact\validation;

use compact\validation\Validator;
use compact\logging\Logger;

/**
 * Validates if a path is a valid (existing) directory
 *
 * @package validation
 */
class ValidateExistingDirectory extends Validator
{
	
	/**
	 * Creates a new TValidateExistingDirectory
	 */
	public function __construct( $aFieldName )
	{
		parent::__construct( $aFieldName );
	}
	
	/**
	 * Validates a valid (existing) directory
	 *
	 * @param string aPath The directory to validate
	 */
	public function validate( $aPath )
	{
		if ($this->isEmpty( $aPath ))
		{
			return;
		}
		
		Logger::get()->logFine( "Validate if directory exists: " . $aPath );
		
		if (! is_dir( $aPath ))
		{
			throw new ValidationException( "Not a valid directory" );
		}
	}
}