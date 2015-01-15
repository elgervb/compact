<?php
namespace compact\validation;

use compact\validation\Validator;
use core\validation\ValidationException;
use compact\logging\Logger;

/**
 * Validates an 06 telephone number
 *
 * @package validation
 */
class Validate06Number extends Validator
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
		if ($this->isEmpty( $aTelNr ))
		{
			return;
		}

		Logger::get()->logFine("Validate mobile telephone number: " . $aTelNr);

		if (! preg_match( '/^06-[0-9]{8}$/', $aTelNr ))
		{
		    // TODO use translation
			throw new ValidationException( "Veld " . $this->getFieldName() . "moet een geldig 06 nummer bevatten. Formaat: 06-00000000." );
		}

	}

}