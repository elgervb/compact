<?php
namespace compact\mvvm\impl\validation;

use compact\mvvm\impl\validation\ModelValidator;
use compact\mvvm\IModel;
use compact\translations\Translator;
use compact\validation\ValidationException;

/**
 * Validator to check that model fields are not empty
 * @author elger
 *
 */
class ModelFieldsNotNullValidator extends ModelValidator
{
	private $fields = array();
	private $translation = array();
	
	/**
	 * Creates a new ModelFieldsNotNullValidator
	 *
	 * @param $aFields array The fields to validate
	 */
	public function __construct( array $aFields )
	{
		parent::__construct(null);
		
		foreach ($aFields as $value)
		{
			$this->fields[] = $value;
		}
	}
	
	/**
	 * Validates the model
	 *
	 * @param $aModel IModel
	 */
	protected function doValidate( IModel $aModel )
	{
		$result = "";
		foreach ($this->fields as $field)
		{
			if ($aModel->$field === null || ( !is_bool($aModel->$field) && trim( $aModel->$field ) === ""))
			{
				$result .= Translator::translate(ITranslationBundle::ERR_VAL_FIELD_NOT_EMPTY, Translator::translate('field.'.$field)) . "\n";
			}
		}
		
		if ($result !== "")
		{
			throw new ValidationException( rtrim( $result, "\n" ) );
		}
	}
}
