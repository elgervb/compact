<?php
namespace compact\repository\pdo;

use compact\repository\IModelRepository;
use compact\repository\pdo\PDOModelConfiguration;
use compact\repository\impl\SearchCriteria;
use compact\mvvm\IModel;
use compact\repository\ISearchCriteria;

abstract class AbstractPDORepository implements IModelRepository
{
	/**
	 *
	 * @var PDOModelConfiguration
	 */
	private $modelConfig;
	/**
	 *
	 * @var \PDO
	 */
	private $pdo;
	
	/**
	 * Creates a new AbstractPDORepository
	 *
	 * @param $aModelConfiguration PDOModelConfiguration
	 * @param $aDsn string or a PDO object
	 * @param $aUsername string [optional]
	 * @param $aPassword string [optional]
	 * @param $aOptions string [optional]
	 */
	public function __construct( PDOModelConfiguration $aModelConfiguration, $aDsn, $aUsername = null, $aPassword = null, $aOptions = null )
	{
		$this->modelConfig = $aModelConfiguration;
		if ($aDsn instanceof \PDO)
		{
			$this->pdo = $aDsn;
		}
		else
		{
			$this->pdo = new \PDO( $aDsn, $aUsername, $aPassword, $aOptions );
		}
	}
	
	/**
	 * Returns the PDO driver for child classes to use
	 *
	 * @return \PDO
	 */
	public function getDriver()
	{
		return $this->pdo;
	}
	
	/**
	 * Factory method to create a new, empty model of the type this configuration can handle
	 *
	 * @return IModel
	 */
	public function createModel()
	{
		return $this->modelConfig->createModel();
	}
	
	/**
	 * Returns a new ISearchCriteria
	 *
	 * @return ISearchCriteria
	 */
	public function createSearchCriteria()
	{
		return new SearchCriteria();
	}
	
	/**
	 * Deletes the given model
	 *
	 * @param $aModel IModel The model to be deleted
	 *       
	 * @return boolean true when the delete was successfull, false when not
	 */
	public function delete( IModel $aModel )
	{
		throw new \RuntimeException( "No implemented " . __METHOD__ );
	}
	
	/**
	 * Returns the model configuration for this repository
	 *
	 * @return PDOModelConfiguration
	 */
	public function getModelConfiguration()
	{
		return $this->modelConfig;
	}
	
	/**
	 * Reads a model by it's key
	 *
	 * @param $aPk int The primary key of the model
	 *       
	 * @return IModel The model as read from the repository, or null when no models where found
	 */
	public function read( $aPk )
	{
		throw new \RuntimeException( "No implemented " . __METHOD__ );
	}
	
	/**
	 * Saves the model
	 *
	 * @param $aModel IModel
	 *
	 * @return true when the save (insert or update) was successfull, false when not
	 *        
	 * @throws ValidationException on validation errors
	 */
	public function save( IModel $aModel )
	{
		throw new \RuntimeException( "No implemented " . __METHOD__ );
	}
	
	/**
	 * Saves multiple models in one go
	 *
	 * @param $aList \Iterator A list with models
	 *       
	 * @return true when the save (insert or update) was successfull, false when not
	 *        
	 * @throws ValidationException on validation errors
	 */
	public function saveAll(\Iterator $aList )
	{
		throw new \RuntimeException( "No implemented " . __METHOD__ );
	}
	
	/**
	 * Returns all models in this repository
	 *
	 * @param $aSearchCriteria ISearchCriteria = null Optional search criteria
	 *       
	 * @return \ArrayIterator
	 */
	public function search( ISearchCriteria $aSearchCriteria = null )
	{
		throw new \RuntimeException( "No implemented " . __METHOD__ );
	}
}