<?php
namespace compact\validation;

use compact\validation\Validator;
use compact\logging\Logger;
use compact\translations\Translator;

/**
 * Validates an telephone number
 *
 * @package validation
 */
class ValidateTelephoneNumber extends Validator
{
	
	/**
	 * Creates a new ValidateTelephoneNumber
	 *
	 * @param $aFieldName String The fieldname
	 */
	public function __construct( $aFieldName )
	{
		parent::__construct( $aFieldName );
	}
	
	/**
	 * Validates a phone number
	 */
	public function validate( $aTelNr )
	{
		if ($this->isempty( $aTelNr ))
		{
			return;
		}
		
		Logger::get()->logFine( "Validate telephone number: " . $aTelNr );
		
		// deletion of spaces and - should only leave numbers
		$telNr = str_replace( array("-" , " "), "", $aTelNr );
		
		$result = true;
		
		if (! is_numeric( $telNr ))
		{
			$result = false;
		}
		
		if (strlen( $telNr ) < 7 || strlen( $telNr ) > 15)
		{
			$result = false;
		}
		
		if ($result === false)
		{
			throw new ValidationException( Translator::translate( ITranslationBundle::ERR_VAL_TELNR, $this->getTranslation() ) );
		}
	}

}