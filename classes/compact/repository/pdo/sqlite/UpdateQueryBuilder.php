<?php
namespace compact\repository\pdo\sqlite;

use compact\repository\pdo\PDOModelConfiguration;
use compact\mvvm\IModel;
/**
 * @author elger
 */
class UpdateQueryBuilder
{
	private $query;
	public function __construct( PDOModelConfiguration $aModelConfiguration, IModel $aModel )
	{
		$this->query = "UPDATE  " . $aModelConfiguration->getTable() . ' SET ';
		$this->addFields( $aModelConfiguration->getFieldNames( $aModel ) );
		$this->query .= ' WHERE ' . $aModelConfiguration->getKey() . ' = ?';
	}
	
	/**
	 * Add fields to the insert query
	 *
	 * @param $aFields array
	 */
	private function addFields( array $aFields )
	{
		// FIELDS
		$i = 1;
		foreach ($aFields as $field)
		{
			$this->query .= " " . $field . "= ?";
			if ($i < count( $aFields ))
			{
				$this->query .= ","; // last fields should not end with a ,
			}
			
			$i ++;
		}
	}
	
	public function toString()
	{
		return $this->query;
	}
}