<?php
namespace compact\validation;
use compact\translations\Translator;
/**
 * Validator superclass for validation
 *
 * @package validation
 */
abstract class Validator
{
	/**
	 * The fieldname to validate
	 * 
	 * @var string
	 */
	private $fieldName;
	
	/**
	 * The translation of the field as show in validation errors
	 * 
	 * @var string
	 */
	private $translation;
	
	private $transKey;
	
	/**
	 * Constucts a new Validator object
	 *
	 * @param $aFieldName string|array (of fieldname / translation)
	 */
	public function __construct( $aFieldName )
	{
		if (is_array( $aFieldName ))
		{
			assert( 'count($aFieldName) == 1' );
			foreach ($aFieldName as $key => $value)
			{
				$this->fieldName = $key;
				$this->translation = $value;
			}
		}
		else
		{
			$this->fieldName = $aFieldName;
		}
	}
	
	/**
	 * Sets the transkey to translate the fieldname
	 *
	 * @param $aTransKey string
	 */
	public function setTransKey( $aTransKey )
	{
		$this->transKey = $aTransKey;
	}
	
	/**
	 * Returns the field name for this validator
	 *
	 * @return the fieldname
	 */
	public function getFieldName()
	{
		return $this->fieldName;
	}
	
	/**
	 * Returns the translation of the fieldname
	 *
	 * @return string the translation or the fieldname NEVER null
	 */
	public function getTranslation()
	{
		return Translator::translate( 'field.'.$this->fieldName );
	}
	
	/**
	 * Checks if the fieldname for the validator has already been translated
	 *
	 * @return boolean
	 */
	protected function isTranslated()
	{
		return $this->translation !== null;
	}
	
	/**
	 * Superclass method does nothing override this method to validate
	 *
	 * @param mixed The item to validate
	 *       
	 * @throws TValidationException on failure
	 *        
	 * @return void
	 */
	public abstract function validate( $aValidation );
	
	/**
	 * Checks if a string is empty
	 *
	 * @param $string string
	 * @return boolean
	 */
	protected function isEmpty( $aString )
	{
		if (empty( $aString ))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Checks if item contains at least the number of chars given
	 *
	 * @param $item AbstractField
	 * @param $chars int
	 * @return boolean
	 */
	protected function min_chars( $aString, $chars )
	{
		return strlen( $aString ) < $chars;
	
	}
	
	/**
	 * Checks if item contains at more then the number of chars given
	 *
	 * @param $item AbstractField
	 * @param $chars int
	 * @return boolean
	 */
	protected function max_chars( $aString, $chars )
	{
		return strlen( $aString ) > $chars;
	}
	
	protected function isAlfanum( $aString )
	{
		if (preg_match( '/^[a-zA-Z0-9]+$/', $aString ))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	protected function isAlfanumUnderscore( $aString, $aSpace = true )
	{
		if ($aSpace == true)
		{
			$aString = str_replace( " ", "", $aString );
		}
		
		if (preg_match( '/^[a-zA-Z0-9_]+$/', $aString ))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	protected function isAlfanumDash( $aString, $aSpace = true )
	{
		if ($aSpace == true)
		{
			$aString = str_replace( " ", "", $aString );
		}
		
		if (preg_match( '/^[a-zA-Z0-9_-]+$/', $aString ))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}