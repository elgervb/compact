<?php
namespace core\validation;

use compact\logging\Logger;
use compact\validation\Validator;
/**
 *
 * @package validation
 */
class ValidateYear extends Validator
{
	
	private $minYear;
	private $maxYear;
	
	/**
	 * Creates a new TValidateNumeric
	 * 
	 * @param $aFieldName string
	 * @param $aMinYear int
	 * @param $aMaxYear int
	 */
	public function __construct( $aFieldName, $aMinYear = 0, $aMaxYear = 9999 )
	{
		parent::__construct( $aFieldName );
		$this->minYear = $aMinYear;
		$this->maxYear = $aMaxYear;
	}
	
	/**
	 * Validates a year
	 * 
	 * @return void
	 */
	public function validate( $aYear )
	{
		if ($this->isEmpty( $aYear ))
		{
			return;
		}
		
		Logger::get()->logFine( "Validate year: " . $aYear );
		
		/**
		 * Check for int value
		 */
		if (! is_numeric( $aYear ))
		{
			throw new ValidationException( "Veld " . $this->getFieldname() . " moet numeriek zijn. Nu: " . $aYear );
		}
		if (! empty( $this->minYear ))
		{
			if ($aYear < $this->minYear)
			{
				throw new ValidationException( "Veld " . $this->getFieldname() . " moet na " . $this->minYear . " liggen." );
			}
		}
		if (! empty( $this->maxYear ))
		{
			if ($aYear > $this->maxYear)
			{
				throw new ValidationException( "Veld " . $this->getFieldname() . " moet voor " . ($this->maxYear + 1) . " liggen." );
			}
		}
	}
}