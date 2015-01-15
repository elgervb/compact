<?php
namespace compact\repository\pdo\sqlite;

use compact\mvvm\impl\Model;

/**
 * Table model with fields as in query: PRAGMA table_info (tableName)
 * 
 * @author elger
 */
class ColumnModel extends Model
{
	const CID = "cid";
	/**
	 * The name of the column
	 * @var string
	 */
	const COLUMN_NAME = "name";
	const DATA_TYPE = "type";
	const IS_PK = "pk";
	const NOT_NULL = "notnull";
	const DEFAULT_VALUE = "dflt_value";
}