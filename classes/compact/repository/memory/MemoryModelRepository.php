<?php
namespace compact\memory\repository;

use compact\repository\IModelConfiguration;
use compact\repository\impl\SearchCriteria;
use compact\mvvm\IModel;
use compact\repository\ISearchCriteria;
/**
 * Model repository which keeps models in memory
 * @author elger
 */
class MemoryModelRepository implements IModelRepository
{
	/**
	 * The store to store all models, the primary key is used for the key
	 *
	 * @var \ArrayObject
	 */
	private $store;
	/**
	 *
	 * @var IModelConfiguration
	 */
	private $configuration;
	
	/**
	 * Create a new MemoryModelRepository
	 *
	 * @param $aModelConfiguration IModelConfiguration
	 */
	public function __construct( IModelConfiguration $aModelConfiguration )
	{
		$this->configuration = $aModelConfiguration;
		$this->store = new \ArrayObject();
	}
	
	/**
	 * Factory method to create a new, empty model of the type this configuration can handle
	 *
	 * @return IModel
	 */
	public function createModel()
	{
		return $this->getModelConfiguration()->createModel();
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see core\mvc.IModelRepository::createSearchCriteria()
	 */
	public function createSearchCriteria()
	{
		return new SearchCriteria();
	}
	
	/**
	 * Returns the model configuration for this repository
	 *
	 * @return IModelConfiguration
	 */
	public function getModelConfiguration()
	{
		return $this->configuration;
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see IModelRepository::read()
	 */
	public function save( IModel $aModel )
	{
		$configuration = $this->getModelConfiguration();
		$pkField = $configuration->getKey();
		
		if ($aModel->get( $pkField ) === null || $aModel->get( $pkField ) === "")
		{
			// insert
			$pk = $this->getNextKey();
			$aModel->set( $pkField, $pk );
			
			$configuration->validate( $aModel );
			$this->store->offsetSet( $pk, $aModel );
		}
		else
		{
			// update
			$configuration->validate( $aModel );
			assert( '$this->store->offsetExists($aModel->get( $pkField ))' );
			$this->store->offsetSet( $aModel->get( $pkField ), $aModel );
		}
		
		return true;
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see core\mvc.IModelRepository::saveAll()
	 */
	public function saveAll( \Iterator $aList )
	{
		$result = false;
		foreach ($aList as $model)
		{
			assert( '$model instanceof \core\mvc\IModel' );
			$result = $this->save( $model );
		}
		
		return $result;
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
		if ($aSearchCriteria === null || $aSearchCriteria->getStartIndex() === null || $aSearchCriteria->getOffset() === null)
		{
			return $this->store->getIterator();
		}
		
		return new \LimitIterator( $this->store->getIterator(), $aSearchCriteria->getStartIndex(), $aSearchCriteria->getOffset() );
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see IModelRepository::read()
	 */
	public function delete( IModel $aModel )
	{
		$configuration = $this->getModelConfiguration();
		$pk = $aModel->get( $configuration->getKey() );
		
		if ($this->store->offsetExists( $pk ))
		{
			$this->store->offsetUnset( $pk );
		}
		
		return true;
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see IModelRepository::read()
	 */
	public function read( $aPk )
	{
		$result = null;
		
		if ($this->store->offsetExists( $aPk ))
		{
			$result = $this->store->offsetGet( $aPk );
		}
		
		return $result;
	}
	
	/**
	 * Returns the next (new) primary key
	 *
	 * @return int the new primary key
	 */
	private function getNextKey()
	{
		if ($this->store->count() == 0)
		{
			return 0;
		}
		else
		{
			$pk = 0;
			foreach ($this->store as $key => $value)
			{
				assert( 'is_int($key)' );
				
				if ($key > $pk)
				{
					$pk = $key;
				}
			}
			
			return ++ $pk;
		}
	}
}