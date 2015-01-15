<?php
namespace compact\validation;

use compact\validation\Validator;
use compact\logging\Logger;
use core\validation\ValidationException;

/**
 * Validates an e-mail address
 *
 * @package validation
 */
class ValidateDutchTelNumber extends Validator
{

	/**
	 *
	 */
	public function __construct( $aFieldName )
	{
		parent::__construct($aFieldName);
	}

	/**
	 * Validates the mobile phone number
	 */
	public function validate( $aTelNr )
	{
		if ($this->isempty( $aTelNr ))
		{
			return;
		}

		Logger::get()->logFine("Validate dutch telephone number: " . $aTelNr);

		if (! preg_match( '/^(0[0-9]{2}-[0-9]{8}$)|(0[0-9]{3}-[0-9]{7})$/', $aTelNr ))
		{
		     // TODO use translation
			throw new ValidationException( "Veld " .$this->getFieldName(). "moet een geldig telefoonnummer bevatten. Formaat: 000-0000000." );
		}

	}

}