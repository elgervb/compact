<?php
namespace compact\repository;

interface ISearchCriteria
{
	/**
	 * Returns the start index for limiting the search result
	 * 
	 * @return int the start index or null when not set
	 */
	public function getStartIndex();
	
	/**
	 * Returns the offset
	 * @return int the offset or null when not set
	 */
	public function getOffset();
	
	/**
	 * Returns the order by string
	 * @return string
	 */
	public function getOrderBy();

	/**
	 * Returns the where fields
	 *
	 * @return array
	 */
	public function getWhere();
	
	/**
	 * Order by a field
	 * @param string $aFieldname the fieldname to order by
	 */
	public function orderBy($aFieldname);
	
	/**
	 * Adds a where clause
	 *
	 * @param $aField string
	 * @param $aValue mixed
	 *
	 * @return ISearchCriteria
	 */
	public function where( $aField, $aValue );
	
}