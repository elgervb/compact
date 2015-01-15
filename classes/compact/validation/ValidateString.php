<?php
namespace compact\validation;

use compact\validation\Validator;
use compact\logging\Logger;
use core\validation\ValidationException;
use compact\translations\Translator;

/**
 * Validates a string
 *
 * @package validation
 */
class ValidateString extends Validator
{
	private $minChars;
	private $maxChars;
	
	/**
	 *
	 * @param $aFieldName string The fieldname to validate
	 * @param $aMinChars int     The min chars the string must have
	 * @param $aMaxChars int     The max chars the string can have
	 */
	public function __construct( $aFieldName, $aMinChars = null, $aMaxChars = null )
	{
		parent::__construct( $aFieldName );
		
		if ($aMinChars != null)
		{
			$this->minChars = $aMinChars;
		}
		if ($aMaxChars != null)
		{
			$this->maxChars = $aMaxChars;
		}
	}
	
	/**
	 * Validates a string
	 *
	 * @exception TValidationException When validation failes
	 * 
	 * @return void
	 */
	public function validate( $aString )
	{
		/**
		 * Check if string is not empty
		 */
		if ($this->isEmpty( $aString ))
		{
			return;
		}
		
		Logger::get()->logFine( "Validate string: " . $aString );
		
		/*
		 * Check for min_chars
		 */
		if (! empty( $this->minChars ))
		{
			if ($this->min_chars( $aString, $this->minChars ))
			{
				
				throw new ValidationException( Translator::translate( ITranslationBundle::ERR_VAL_MIN_CHARS, $this->getTranslation(), $this->minChars ) );
			}
		}
		
		if (! empty( $this->maxChars ))
		{
			if ($this->max_chars( $aString, $this->maxChars ))
			{
				throw new ValidationException( Translator::translate( ITranslationBundle::ERR_VAL_MAX_CHARS, $this->getTranslation(), $this->maxChars ) );
			}
		}
	}
}