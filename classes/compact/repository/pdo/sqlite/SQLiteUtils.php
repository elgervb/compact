<?php
namespace compact\repository\pdo\sqlite;

use compact\repository\pdo\sqlite\SQLiteDynamicModelConfiguration;

/**
 * 
 * @author elger
 *
 */
class SQLiteUtils
{

    /**
     * Returns a newly created SQLiteRepository with a dynamic model
     * configuration
     *
     * @param $aDBPath \SplFileInfo           
     * @param $aTableName string           
     *
     * @return SQLiteRepository
     */
    public static function createDynamicRepository (\SplFileInfo $aDBPath, $aTableName)
    {
        return new SQLiteRepository(
                new SQLiteDynamicModelConfiguration($aTableName), 
                "sqlite:" . $aDBPath->getPathname());
    }
}
