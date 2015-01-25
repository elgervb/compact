<?php
namespace compact\validation;

use compact\validation\Validator;
use compact\logging\Logger;
use compact\translations\Translator;

/**
 * Validates an e-mail address
 *
 * @package validation
 */
class ValidateEmail extends Validator
{
	/**
	 * Creates a new ValidateEmail
	 */
	public function __construct( $aFieldName )
	{
		parent::__construct( $aFieldName );
	}
	
	/**
	 * Validates a e-mail address
	 *
	 * @param $aEmail string The email address to validate
	 */
	public function validate( $aEmail )
	{
		if ($this->isEmpty( $aEmail ))
		{
			return;
		}
		
		Logger::get()->logFine( "Validate email address: " . $aEmail );
		
		if (! preg_match( "/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,6}$/i", $aEmail ))
		{
			throw new ValidationException( Translator::translate( ITranslationBundle::ERR_VAL_EMAIL, $this->getTranslation() ) );
		}
	}
}