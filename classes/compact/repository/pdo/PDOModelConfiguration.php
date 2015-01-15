<?php
namespace compact\repository\pdo;

use compact\repository\DefaultModelConfiguration;
use compact\repository\pdo\sqlite\PDOModel;

/**
 *
 * @author elger
 */
class PDOModelConfiguration extends DefaultModelConfiguration
{

    /**
     *
     * @var string the table name
     */
    private $tableName;

    /**
     * Creates a new PDOModelConfiguration
     *
     * @param $aTableName string
     *            the table name
     * @param $aModelClassName string
     *            the model classname as string
     * @param $aPrimaryKeyFieldName string
     *            the primary key fieldname
     */
    public function __construct($aTableName, $aModelClassName, $aPrimaryKeyFieldName = "id")
    {
        parent::__construct($aModelClassName, $aPrimaryKeyFieldName);
        
        $this->tableName = $aTableName;
    }


    /**
     * Returns the table name these model refer to
     *
     * @return string the table name
     */
    public function getTable()
    {
        return $this->tableName;
    }

    /**
     * Returns the fieldtype
     *
     * @param $aFieldName string            
     */
    public function getFieldType($aFieldName)
    {
        if ($aFieldName === null) {
            return \PDO::PARAM_NULL;
        }
        if ($aFieldName === $this->getKey()) {
            return \PDO::PARAM_INT;
        }
        return \PDO::PARAM_STR;
    }
}
