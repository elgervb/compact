<?php
namespace compact\mvvm\impl\validation;

use compact\validation\Validator;
use compact\mvvm\IModel;

abstract class ModelValidator extends Validator
{
	/**
	 * Validates a model
	 *
	 * @param $aModel IModel
	 */
	protected abstract function doValidate( IModel $aModel );
	
	/**
	 *
	 * @param $aValidation IModel
	 */
	final public function validate( $aValidation )
	{
		assert( 'is_object($aValidation) /* $aValidation should be an object  */' );
		assert( '$aValidation instanceof core\mvc\IModel /* $aValidation should be an IModel  */' );
		
		$this->doValidate( $aValidation );
	}

}