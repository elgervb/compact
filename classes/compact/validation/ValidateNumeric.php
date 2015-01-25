<?php
namespace compact\validation;

use compact\validation\Validator;
use compact\logging\Logger;

/**
 *
 * @package validation
 */
class ValidateNumeric extends Validator
{
	
	private $minValue;
	
	private $maxValue;
	
	/**
	 *
	 * @param $aFieldName The fieldname to validate
	 * @param $minValue The minimum value
	 * @param $maxValue The maximum value
	 */
	public function __construct( $aFieldName, $minValue = 0, $maxValue = NULL )
	{
		parent::__construct( $aFieldName );
		
		$this->minValue = $minValue;
		$this->maxValue = $maxValue;
	}
	
	/**
	 * Validates a password
	 *
	 * @return void
	 */
	public function validate( $aInt )
	{
		if ($this->isEmpty( $aInt ))
		{
			return;
		}
		
		Logger::get()->logFine( "Validate numeric: " . $aInt );
		
		/**
		 * Check for int value
		 */
		if (! is_numeric( $aInt ))
		{
			throw new ValidationException( $aInt . " is not numeric" );
		}
		if (! empty( $this->minValue ))
		{
			if ($aInt < $this->minValue)
			{
				throw new ValidationException( "Minumum value is " . $this->minValue );
			}
		}
		if (! empty( $this->maxValue ))
		{
			if ($aInt > $this->maxValue)
			{
				throw new ValidationException( "Maximum value is " . $this->maxValue );
			}
		}
	}
}