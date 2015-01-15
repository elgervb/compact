<?php
namespace compact\repository;

use compact\mvvm\IModel;

/**
 * Model repository to persist models
 */
interface IModelRepository
{
	/**
	 * Factory method to create a new, empty model of the type this configuration can handle
	 *
	 * @return IModel
	 */
	public function createModel();
	
	/**
	 * Returns a new ISearchCriteria
	 *
	 * @return ISearchCriteria
	 */
	public function createSearchCriteria();
	
	/**
	 * Deletes the given model
	 *
	 * @param $aModel IModel The model to be deleted
	 *       
	 * @return boolean true when the delete was successfull, false when not
	 */
	public function delete( IModel $aModel );
	
	/**
	 * Returns the model configuration for this repository
	 *
	 * @return IModelConfiguration
	 */
	public function getModelConfiguration();
	
	
	/**
	 * Reads a model by it's key
	 *
	 * @param $aPk int The primary key of the model
	 *       
	 * @return IModel The model as read from the repository, or null when no models where found
	 */
	public function read( $aPk );
	
	/**
	 * Saves the model
	 *
	 * @param $aModel IModel
	 *
	 * @return true when the save (insert or update) was successfull, false when not
	 *        
	 * @throws ValidationException on validation errors
	 */
	public function save( IModel $aModel );
	
	/**
	 * Saves multiple models in one go
	 *
	 * @param $aList \Iterator A list with models
	 *
	 * @return true when the save (insert or update) was successfull, false when not
	 *
	 * @throws ValidationException on validation errors
	 */
	public function saveAll( \Iterator $aList );
	
	/**
	 * Returns all models in this repository
	 *
	 * @param $aSearchCriteria ISearchCriteria = null Optional search criteria
	 *
	 * @return \ArrayIterator
	 */
	public function search( ISearchCriteria $aSearchCriteria = null );
}