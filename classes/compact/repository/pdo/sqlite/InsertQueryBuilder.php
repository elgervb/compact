<?php
namespace compact\repository\pdo\sqlite;

use compact\mvvm\IModel;
use compact\repository\pdo\PDOModelConfiguration;

class InsertQueryBuilder
{
	private $query;
	
	public function __construct( PDOModelConfiguration $aModelConfiguration, IModel $aModel )
	{
		$this->query = "INSERT INTO " . $aModelConfiguration->getTable();
		$this->addFields( $aModelConfiguration->getFieldNames( $aModel ) );
	}
	
	/**
	 * Add fields to the insert query
	 *
	 * @param $aFields array
	 */
	private function addFields( array $aFields )
	{
		// FIELDS
		$this->query .= " (";
		$i = 1;
		foreach ($aFields as $field)
		{
			$this->query .= " " . $field;
			if ($i < count( $aFields ))
			{
				$this->query .= ","; // last fields should not end with a ,
			}
			
			$i ++;
		}
		$this->query .= ")";
		
		// VALUES
		$this->query .= " VALUES(";
		$i = 1;
		foreach ($aFields as $field)
		{
			$this->query .= "?";
			if ($i < count( $aFields ))
			{
				$this->query .= ","; // last fields should not end with a ,
			}
			
			$i ++;
		}
		$this->query .= ")";
	}
	
	public function toString()
	{
		return $this->query;
	}
}