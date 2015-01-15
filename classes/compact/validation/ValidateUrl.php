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
class ValidateUrl extends Validator
{

	/**
	 * 
	 * @param string $aFieldName
	 */
	public function __construct($aFieldName)
	{
		parent::__construct($aFieldName);
	}

	/**
	 * Validates an url
	 *
	 * @return void
	 */
	public function validate( $aUrl )
	{
		if ($this->isEmpty( $aUrl ))
		{
			return;
		}

		Logger::get()->logFine( "Validate url: " . $aUrl );

		$result = preg_match( "/^(http|https|ftp):\/\/((www\.)?.+\.([a-z]{2,4}|museum|travel)|localhost)(\/|\/.+)?(#.+|\?.+)?$/", $aUrl );

		if ($result <= 0)
		{
		    // TODO use translation
			throw new ValidationException( "Veld " .$this->getFieldname(). " moet een geldige url bevatten." );
		}
	}
}