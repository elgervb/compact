<?php
namespace compact\validation;

use compact\validation\Validator;
use compact\logging\Logger;

/**
 * Validates the a given file name does not exist on the given path
 *
 * @package validation
 */
class ValidateFileNotExists extends Validator
{
	private $basePath;
	private $suffix;
	private $prefix;
	/**
	 * Constructor
	 *
	 * @param $aFieldName string The name of the field to validate
	 * @param $aOnlyAlphaNum boolean [optional] whether or not only alpha numeric chars are allowed
	 * @param $aFilePrefix string an optional file prefix
	 * @param $aFileSuffix string an optional file suffix
	 */
	public function __construct( $aFieldName,\SplFileInfo $aBasePath, $aFilePrefix = "", $aFileSuffix = "" )
	{
		parent::__construct( $aFieldName );
		
		$this->basePath = $aBasePath;
		$this->prefix = $aFilePrefix;
		$this->suffix = $aFileSuffix;
	}
	
	/**
	 * @param string $aFileName
	 * 
	 * @return string the filename including the optional pre- and suffix
	 */
	private function getFileName($aFileName){
		return $this->prefix .$aFileName.$this->suffix;
	}
	
	
	/**
	 *
	 * @param mixed The item to validate
	 *       
	 * @return void
	 */
	public function validate( $aFileName )
	{
		if ($this->isEmpty( $aFileName ))
		{
			return;
		}
		
		Logger::get()->logFine( "Validate that file " . $this->getFileName($aFileName) . " does not exists on path " . $this->basePath->getPathname() );
		
		if (file_exists( $this->basePath->getPathname() . DIRECTORY_SEPARATOR . $this->getFileName($aFileName) ))
		{
			throw new ValidationException( "The file " . $this->getFileName($aFileName) . " already exists." );
		}
	
	}
}