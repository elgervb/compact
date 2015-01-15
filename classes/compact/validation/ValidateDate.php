<?php
namespace compact\validation;

use compact\validation\Validator;
use compact\logging\Logger;
use core\validation\ValidationException;

/**
 * Validation for dates
 *
 * @author elger
 *
 * @package validation
 */
class ValidateDate extends Validator
{
	
	/**
	 * Creates a new TValidateDate
	 * 
	 * @param String $aFieldName
	 */
	public function __construct( $aFieldName )
	{
		parent::__construct( $aFieldName );
	}
	
	/**
	 *
	 * @param  mixed The item to validate
	 * @return  void
	 * @see TValidator::validate()
	 *
	 * @throws TValidationException on validation error
	 */
	public function validate( $aValidation )
	{
		if ($aValidation !== null)
		{
			$date = str_replace( array("/" , "\\" , "-" , " " , ":"), "", $aValidation );
			
			Logger::get()->logFine( "Validate date: " . $aValidation );
			
			if (! is_numeric( $date ))
			{
			    // TODO use translation
				throw new ValidationException( "Date is not valid" );
			}
		}
	}
}