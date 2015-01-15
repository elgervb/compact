<?php
namespace compact\repository\pdo\sqlite;

use compact\repository\pdo\sqlite\SQLiteMasterRepository;

interface IDynamicModelConfiguration
{
	/**
	 * @return SQLiteMasterRepository
	 */
	public function getMasterRepository();
	
	/**
	 * Sets the master repository
	 * 
	 * @param SQLiteMasterRepository $aMaster
	 */
	public function setMasterRepository(SQLiteMasterRepository $aMaster);
}