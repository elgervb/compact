<?php
namespace compact\repository\pdo\sqlite;

use compact\repository\pdo\PDOModelConfiguration;
use compact\repository\pdo\AbstractPDORepository;
use compact\mvvm\IModel;
use compact\logging\Logger;
use compact\utils\Random;
use compact\repository\ISearchCriteria;
use compact\repository\pdo\sqlite\SQLiteMasterRepository;
use compact\validation\ValidationException;

/**
 *
 * @author elger
 */
class SQLiteRepository extends AbstractPDORepository
{

    private $master;

    /**
     *
     * @param $aModelConfiguration PDOModelConfiguration            
     * @param $aDsn string
     *            sqlite:/path/to/db.sqlite
     * @param $aStartQuery string            
     * @param $aOptions string            
     */
    public function __construct(PDOModelConfiguration $aModelConfiguration, $aDsn, $aStartQuery = null, $aOptions = null)
    {
        parent::__construct($aModelConfiguration, $aDsn, null, null, $aOptions);
        // Set errormode to exceptions
        $this->getDriver()->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        // $this->getDriver()->beginTransaction(); // for now default to autocommit mode
        
        if ($aModelConfiguration instanceof IDynamicModelConfiguration) {
            /* @var $aModelConfiguration IDynamicModelConfiguration */
            $aModelConfiguration->setMasterRepository($this->getMasterRepository());
        }
        
        if ($aStartQuery) {
            $this->getDriver()->exec($aStartQuery);
        }
    }

    /**
     * Deletes the given model
     *
     * @param $aModel IModel
     *            The model to be deleted
     *            
     * @return boolean true when the delete was successfull, false when not
     */
    public function delete(IModel $aModel)
    {
        /* @var $config PDOModelConfiguration */
        $config = $this->getModelConfiguration();
        
        $q = "DELETE FROM " . $config->getTable() . " WHERE " . $config->getKey() . " = :id;";
        
        $pdo = $this->getDriver();
        $sth = $pdo->prepare($q);
        $sth->bindValue(':id', $aModel->get($config->getKey()), $config->getFieldType($config->getKey()));
        
        Logger::get()->logFine("SQL: " . $q . " with param " . $aModel->get($config->getKey()));
        
        $result = $sth->execute();
        
        return $result/* && $pdo->commit()*/;
    }

    /**
     * (non-PHPdoc)
     *
     * @see compact\repository\pdo.AbstractPDORepository::save()
     * 
     * @throws ValidationException on validation errors
     * @throws \PDOException on error saving the model
     */
    public function save(IModel $aModel)
    {
        /* @var $config PDOModelConfiguration */
        $config = $this->getModelConfiguration();
        
        if ($aModel->get($config->getKey()) === null) {
            $qb = new InsertQueryBuilder($config, $aModel);
        } else {
            $qb = new UpdateQueryBuilder($config, $aModel);
        }
        
        // insert a GUID when config has a guid
        if (in_array('guid', $config->getFieldNames($aModel))) {
            $aModel->set('guid', Random::guid());
        }
        
        // insert a GUID when config has a guid
        if (in_array('timestamp', $config->getFieldNames($aModel))) {
            $aModel->set('timestamp', time());
        }
        
        $config->validate($aModel);
        
        $pdo = $this->getDriver();
        $query = $qb->toString();
        Logger::get()->logFinest($query);
        $sth = $pdo->prepare($query);
        $i = 1;
        foreach ($config->getFieldNames($aModel) as $fieldName) {
            if (! $aModel->get($fieldName)) {
                $type = \PDO::PARAM_NULL;
            } else {
                $type = $config->getFieldType($fieldName);
            }
            $sth->bindValue($i ++, $aModel->get($fieldName), $type);
        }
        
        if ($qb instanceof UpdateQueryBuilder) {
            $pkName = $config->getKey();
            $sth->bindValue($i ++, $aModel->get($pkName), $config->getFieldType($pkName));
        }
        
        $result = $sth->execute();
        
        if ($qb instanceof InsertQueryBuilder) {
            $id = $pdo->lastInsertId();
            if (! $id) {
                throw new \PDOException("Last inserted ID was null");
            }
            
            $pkName = $config->getKey();
            if ($aModel->isEmpty($pkName)) {
                $aModel->set($pkName, $id);
            }
        }
        
        if (! $result) {
            throw new \PDOException("Unable to save model " . get_class($aModel));
        }
        
        return $result/* && $pdo->commit()*/;
    }

    public function read($aPk)
    {
        /* @var $config PDOModelConfiguration */
        $config = $this->getModelConfiguration();
        
        $q = "SELECT * from " . $config->getTable() . " where " . $config->getKey() . " = :id;";
        
        $pdo = $this->getDriver();
        $sth = $pdo->prepare($q);
        $sth->bindValue(':id', $aPk, $config->getFieldType($config->getKey()));
        
        $args = array();
        $args[] = $config;
        
        $sth->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $config->getModelClassName(), $args);
        $result = $sth->execute();
        if (! $result)
            throw new \PDOException("Could not execute query " . $q);
        $result = $sth->fetchAll();
        
        $count = count($result);
        if ($count > 0) {
            if ($count === 1) {
                return $result[0];
            } else {
                Logger::get()->logWarning("Found more than 1 model for PK " . $aPk . ". Returning the first one...");
                return $result[0];
            }
        }
        
        return null;
    }

    /**
     * (non-PHPdoc)
     *
     * @see compact\repository\pdo.AbstractPDORepository::search()
     */
    public function search(ISearchCriteria $aSc = null)
    {
        /* @var $config PDOModelConfiguration */
        $config = $this->getModelConfiguration();
        
        // select from ..
        $q = "SELECT * FROM " . $config->getTable();
        // where
        if ($aSc != null && $aSc->getWhere()) {
            $first = true;
            foreach ($aSc->getWhere() as $key => $value) {
                $q .= $first ? " WHERE " : " AND ";
                $q .= $key . " = :" . $key;
                $first = false;
            }
        }
        // order by
        if ($aSc != null && $aSc->getOrderBy()) {
            $q .= " ORDER BY " . $aSc->getOrderBy();
        }
        // limit
        if ($aSc != null && $aSc->getStartIndex() !== null && $aSc->getOffset() !== null) {
            $q .= " LIMIT " . $aSc->getStartIndex() . ", " . $aSc->getOffset();
        }
        
        $sth = $this->getDriver()->prepare($q);
        
        $scArgs = "";
        if ($aSc) {
            foreach ($aSc->getWhere() as $key => $value) {
                $sth->bindValue(':' . $key, $value, $config->getFieldType($key));
                $scArgs .= "(" . $config->getFieldType($key) . ")" . $key . ":" . $value . " ";
            }
        }
        Logger::get()->logAll("Execute query " . $q . " with args " . $scArgs);
        
        $args = array();
        $args[] = $config;
        
        $sth->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $config->getModelClassName(), $args);
        $sth->execute();
        $result = $sth->fetchAll();
        
        if (false === $result) {
            throw new \PDOException("Could not fetch" . $config->getModelClassName());
        }
        
        return new \ArrayObject($result);
    }

    /**
     * Returns the master SQLite model repository
     *
     * @return SQLiteMasterRepository
     */
    public function getMasterRepository()
    {
        if ($this->master === null) {
            $this->master = new SQLiteMasterRepository($this);
        }
        
        return $this->master;
    }
}
