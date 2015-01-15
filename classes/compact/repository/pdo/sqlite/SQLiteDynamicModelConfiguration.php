<?php
namespace compact\repository\pdo\sqlite;

use compact\repository\pdo\sqlite;
use compact\mvvm\IModel;
use compact\repository\pdo\sqlite\SQLiteMasterRepository;
use compact\mvvm\impl\validation\ModelFieldsNotNullValidator;
use compact\repository\pdo\PDOModelConfiguration;
/**
 *
 * @author elger
 */
class SQLiteDynamicModelConfiguration extends PDOModelConfiguration implements IDynamicModelConfiguration
{
	const PDO_PARAM_TIMESTAMP = 999;
	/**
	 * @var SQLiteMasterRepository
	 */
	private $masterConfiguration;
	private $fieldNames;
	/**
	 *
	 * @var \ArrayList
	 */
	private $columns;
	
	/**
	 * Creates a new SQLiteDynamicModelConfiguration
	 *
	 * @param $aTableName string
	 * @param $aModelClass string
	 * @param $aPrimaryKeyFieldName string
	 */
	public function __construct( $aTableName, $aPrimaryKeyFieldName = "id" )
	{
		parent::__construct( $aTableName, "compact\\repository\\pdo\\sqlite\\PDOModel", $aPrimaryKeyFieldName );
	}
	
	/**
	 * Factory method to create a new, empty model of the type this configuration can handle
	 *
	 * @return IModel
	 */
	public function createModel()
	{
	    $className = $this->getModelClassName();
	    return new $className($this);
	}
	
	/**
	 *
	 * @see compact\repository\IModelConfiguration::getFieldNames()
	 */
	public function getFieldNames( IModel $aModel )
	{
		if ($this->fieldNames === null)
		{
			// get the fieldnames from the database definition
			$columns = $this->getColumns();
			$fields = array();
			foreach ($columns as $column)
			{
				/* @var $column ColumnModel */
				$fields[] = $column->get( ColumnModel::COLUMN_NAME );
			}
			$this->fieldNames = $fields;
		}
		return $this->fieldNames;
	}
	
	/**
	 *
	 * @return SQLiteMasterRepository
	 *
	 * @see IDynamicModelConfiguration::getMasterRepository()
	 *
	 */
	public function getMasterRepository()
	{
		assert( '$this->masterConfiguration !== null' );
		return $this->masterConfiguration;
	}
	
	/**
	 * Returns the fieldtype
	 *
	 * @param $aFieldName string
	 */
	public function getFieldType( $aFieldName )
	{
		$columns = $this->getColumns();
		$type = null;
		foreach ($columns as $column)
		{
			/* @var $column ColumnModel */
			if ($column->get( ColumnModel::COLUMN_NAME ) === $aFieldName)
			{
				$type = $column->get( ColumnModel::DATA_TYPE );
				break;
			}
		}
		
		switch (strtolower($type))
		{
			case 'integer':
			case 'numeric':
			case 'double':
			case 'float':
			case 'real':
				return \PDO::PARAM_INT;
			case 'bool':
				return \PDO::PARAM_BOOL;
			case 'blob':
				return \PDO::PARAM_LOB;
			case 'text' :
				if (strtolower($aFieldName) === 'timestamp'){
					return self::PDO_PARAM_TIMESTAMP;
				}
			default:
				return \PDO::PARAM_STR;
		}
	}
	
	/**
	 *
	 * @param $aMaster SQLiteMasterRepository
	 *
	 * @see IDynamicModelConfiguration::setMasterRepository()
	 *
	 */
	public function setMasterRepository( SQLiteMasterRepository $aMaster )
	{
		$this->masterConfiguration = $aMaster;
	}
	
	private function getColumns()
	{
		if ($this->columns === null)
		{
			$this->columns = $this->getMasterRepository()->getColumnsInfo( $this->getTable() );
		}
		
		return $this->columns;
	}
	/**
	 *
	 * @see compact\repository\IModelConfiguration::validate()
	 */
	public function validate( IModel $aModel )
	{
		// get the fieldnames from the database definition
		$columns = $this->getColumns();
		$notnullFields = array();
		foreach ($columns as $column)
		{
			/* @var $column ColumnModel */
			if ($column->get( ColumnModel::NOT_NULL ))
			{
				$notnullFields[] = $column->get( ColumnModel::COLUMN_NAME );
			}
		}
		
		if (count( $notnullFields ) > 0)
		{
			$validator = new ModelFieldsNotNullValidator( $notnullFields );
			$validator->validate( $aModel );
		}
	}
}