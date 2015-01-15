<?php
namespace compact\repository\pdo\sqlite;

use compact\mvvm\impl\Model;
/**
 * Model resulting from an SQLite query.
 * This also takes the datatype of the column into account
 * 
 * @author elger
 *        
 */
class PDOModel extends Model
{
	private $fields = array();
	
	/**
	 * 
	 * @param PDOModelConfiguration $aConfig
	 */
	public function __construct( SQLiteDynamicModelConfiguration $aConfig )
	{
		foreach ($aConfig->getFieldNames($this) as $field){
			$this->fields[$field] = $aConfig->getFieldType($field);
		}
	}
	
	public function __set( $aKey, $aValue )
	{
		if (!array_key_exists($aKey, $this->fields)){
			$type = \PDO::PARAM_STR;
		}
		else{
			$type = $this->fields[$aKey];
		}
		
		switch($type){
			case  \PDO::PARAM_BOOL :
				parent::__set($aKey, (bool)$aValue);
				break;
			case  \PDO::PARAM_INT :
					parent::__set($aKey, (int)$aValue);
					break;
			case SQLiteDynamicModelConfiguration::PDO_PARAM_TIMESTAMP :
				parent::__set($aKey, (double)$aValue);
				break;
			default :
				parent::__set($aKey,$aValue);
		}
	}
}