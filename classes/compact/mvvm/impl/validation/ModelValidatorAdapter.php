<?php
namespace compact\mvvm\impl\validation;

use compact\mvvm\impl\validation\ModelValidator;
use compact\validation\Validator;
use compact\mvvm\IModel;

class ModelValidatorAdapter extends ModelValidator
{
	/**
	 *
	 * @var Validator
	 */
	private $validator;
	
	/**
	 * Creates a new ModelValidatorAdapter
	 *
	 * @param $aValidator Validator
	 */
	public function __construct( Validator $aValidator )
	{
		parent::__construct( $aValidator->getFieldName() );
		
		$this->validator = $aValidator;
	}
	
	/**
	 * Returns the validator
	 *
	 * @return Validator
	 */
	protected function getValidator()
	{
		return $this->validator;
	}
	
	/**
	 *
	 * @see ModelValidator::doValidate()
	 */
	protected function doValidate( IModel $aModel )
	{
		$field = $this->validator->getFieldName();
		$this->validator->validate( $aModel->{$field} );
	}
}