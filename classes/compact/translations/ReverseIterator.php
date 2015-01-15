<?php
namespace compact\translations;

class ReverseIterator implements \IteratorAggregate
{
	/**
	 *
	 * @var Traversable
	 */
	private $iterator;
	
	/**
	 *
	 * @var boolean
	 */
	private $_preserve_keys;
	
	/**
	 *
	 * @param $iterator Traversable
	 * @param $preserve_keys boolean
	 */
	public function __construct( \Traversable $iterator, $preserve_keys = false )
	{
		$this->iterator = $iterator;
		$this->_preserve_keys = $preserve_keys;
	}
	
	/**
	 *
	 * @see IteratorAggregate::getIterator()
	 */
	public function getIterator()
	{
		$array = iterator_to_array( $this->iterator, $this->_preserve_keys );
		$reverse = array_reverse( $array, $this->_preserve_keys );
		
		return new \ArrayIterator( $reverse );
	}
}