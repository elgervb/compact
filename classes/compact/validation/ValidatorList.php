<?php
namespace core\validation;

use compact\validation\Validator;
/**
 * Validation list to execute multiple validations
 *
 * @package validation
 */
class ValidatorList extends Validator
{
	
	/**
	 * @var ArrayObject
	 */
	private $validators;

	/**
	 * Creates a new ValidatorList
	 */
	public function __construct()
	{
		$this->validators = new \ArrayObject();
	}

	/**
	 * Registers a new validator
	 *
	 * @param Validator $aValidator
	 */
	public function register( Validator $aValidator )
	{
		$this->validators->append( $aValidator );
	}

	/**
	 * Validates all registered validators
	 *
	 * @throws ValidationException on validation error
	 */
	public function validate( $aValidation )
	{
		$exception = "";
		
		/* @var $validator Validator */
		foreach ($this->validators as $validator)
		{
			try
			{
				$validator->validate( $aValidation );
			} catch (ValidationException $e)
			{
				$exception .= $e->getMessage() . "\n";
			}
		}
		
		if ($exception !== "")
		{
			throw new ValidationException( trim( $exception ) );
		}
	}
}
