<?php
namespace compact\repository\impl;

use compact\repository\ISearchCriteria;

class SearchCriteria implements ISearchCriteria
{

    private $where = array();

    private $startIndex;

    private $orderBy;

    private $offset;

    public function __construct()
    {
        //
    }

    /**
     * Factory method to create a new SearchCriteria
     *
     * @return SearchCriteria
     */
    public static function create()
    {
        return new SearchCriteria();
    }

    /**
     * (non-PHPdoc)
     *
     * @see compact\repository.ISearchCriteria::getWhere()
     */
    public function getWhere()
    {
        return $this->where;
    }

    /**
     * (non-PHPdoc)
     *
     * @see compact\repository.ISearchCriteria::getStartIndex()
     */
    public function getStartIndex()
    {
        return $this->startIndex;
    }

    /**
     * (non-PHPdoc)
     *
     * @see compact\repository.ISearchCriteria::getOffset()
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * (non-PHPdoc)
     *
     * @see compact\repository.ISearchCriteria::getOrderBy()
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * Sets the query limit
     *
     * @param $aStartIndex int            
     * @param $aOffset int            
     */
    public function limit($aStartIndex, $aOffset)
    {
        $this->startIndex = $aStartIndex;
        $this->offset = $aOffset;
    }

    /**
     * (non-PHPdoc)
     *
     * @see compact\repository.ISearchCriteria::orderBy()
     */
    public function orderBy($aOrderBy)
    {
        $this->orderBy = $aOrderBy;
    }

    /**
     * (non-PHPdoc)
     *
     * @see compact\repository.ISearchCriteria::where()
     */
    public function where($aField, $aValue)
    {
        $this->where[$aField] = $aValue;
        
        return $this;
    }
}