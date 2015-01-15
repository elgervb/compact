<?php
namespace compact\repository\pdo\sqlite;

use compact\repository\pdo\sqlite\SQLiteRepository;
use compact\repository\pdo\PDOModelConfiguration;
use compact\mvvm\IModel;
use compact\repository\pdo\AbstractPDORepository;
use compact\mvvm\impl\Model;
{

    /**
     * Read only repository for the master SQLite database
     *
     * @author elger
     *        
     */
    class SQLiteMasterRepository extends AbstractPDORepository
    {

        const TBL = 'sqlite_master';

        private $repository;

        /**
         * Creates a new SQLiteMasterRepository, based on an existing SQLiteRepository
         *
         * @param $aRepository SQLiteRepository            
         */
        public function __construct(SQLiteRepository $aRepository)
        {
            parent::__construct(new PDOModelConfiguration(self::TBL, 'compact\\repository\\pdo\\sqlite\\SQLiteMasterModel'), $aRepository->getDriver());
            
            $this->repository = $aRepository;
        }

        /**
         * (non-PHPdoc)
         *
         * @see core\mvc\impl\repository.AbstractPDORepository::delete()
         */
        public function delete(IModel $aModel)
        {
            throw new \PDOException("The SQLiteMasterRepository is read only");
        }

        /**
         *
         * @see core\mvc\impl\repository.AbstractPDORepository::getDriver()
         * @return \PDO
         */
        public function getDriver()
        {
            return $this->repository->getDriver();
        }

        /**
         * Returns the table info
         *
         * @param string $aTableName            
         *
         * @return SQLiteMasterModel
         */
        public function getTableInfo($aTableName)
        {
            $query = "SELECT * FROM " . self::TBL . " WHERE tbl_name = :tbl_name";
            
            $sth = $this->getDriver()->prepare();
            $sth->bindValue(':tbl_name', $aTableName);
            $sth->execute();
            
            $result = $sth->fetchObject("core\\mvc\\impl\\repository\\SQLiteMasterModel");
            
            if ($result === false) {
                $errorInfo = $sth->errorInfo();
                throw new \Exception($errorInfo[1], $errorInfo[0]);
            }
            
            return $result;
        }

        /**
         * Returns the index list for a table
         *
         * @param string $aTableName            
         *
         * @return SQLiteMasterModel
         */
        public function getIndexList($aTableName)
        {
            $sth = $this->getDriver()->query("PRAGMA index_list ( " . $aTableName . ")");
            
            return $sth->fetchObject("core\\mvc\\impl\\repository\\IndexListModel");
        }

        /**
         * Returns a list of column models for a database
         *
         * @param string $aTableName            
         *
         * @return ArrayObject with ColumnModel objects
         */
        public function getColumnsInfo($aTableName)
        {
            $sth = $this->getDriver()->query("PRAGMA table_info ( '" . $aTableName . "')");
            
            return new \ArrayObject($sth->fetchAll(\PDO::FETCH_CLASS, "compact\\repository\\pdo\\sqlite\\ColumnModel"));
        }

        /**
         * Return all tables
         *
         * @return \ArrayObject
         */
        public function getTables()
        {
            return $this->getInfo('table');
        }

        /**
         * Return all indexes
         *
         * @return \ArrayObject
         */
        public function getIndexes()
        {
            return $this->getInfo('index');
        }

        /**
         * Return all views
         *
         * @return \ArrayObject
         */
        public function getViews()
        {
            return $this->getInfo('view');
        }

        /**
         * Return all triggers
         *
         * @return \ArrayObject
         */
        public function getTriggers()
        {
            return $this->getInfo('trigger');
        }

        private function getInfo($aType = null)
        {
            $query = "SELECT * FROM " . self::TBL;
            if ($aType !== null) {
                $query .= " WHERE type = :type";
            }
            $query .= " AND name NOT LIKE 'sqlite_%';";
            
            $sth = $this->getDriver()->prepare($query);
            if ($aType !== null) {
                $sth->bindValue(':type', $aType);
            }
            
            $sth->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->getModelConfiguration()
                ->getModelClassName());
            $sth->execute();
            $result = $sth->fetchAll();
            
            if ($result === false) {
                $errorInfo = $sth->errorInfo();
                throw new \Exception($errorInfo[1], $errorInfo[0]);
            }
            
            return new \ArrayObject($result);
        }

        /**
         * (non-PHPdoc)
         *
         * @see core\mvc\impl\repository.AbstractPDORepository::save()
         */
        public function save(IModel $aModel)
        {
            throw new \PDOException("The SQLiteMasterRepository is read only");
        }

        /**
         * (non-PHPdoc)
         *
         * @see core\mvc\impl\repository.AbstractPDORepository::saveAll()
         */
        public function saveAll(\Iterator $aList)
        {
            throw new \PDOException("The SQLiteMasterRepository is read only");
        }
    }

    class SQLiteMasterModel extends Model
    {

        /**
         * The type of this element (table, index, view, trigger)
         *
         * @var string
         */
        const TYPE = "type";

        /**
         * The name of this element
         *
         * @var string
         */
        const NAME = "name";

        /**
         * The name of the table belonging to this row
         *
         * @var string
         */
        const TBL_NAME = "tbl_name";

        /**
         * ?
         *
         * @var int
         */
        const ROOTPAGE = "rootpage";

        /**
         * The CREATE SQL statement
         *
         * @var string
         */
        const SQL = "sql";
    }
}