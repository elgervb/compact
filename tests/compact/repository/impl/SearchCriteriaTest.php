<?php
namespace compact\repository\impl;

/**
 * Test class for SearchCriteria.
 * Generated by PHPUnit on 2015-01-02 at 09:13:18.
 */
class SearchCriteriaTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var SearchCriteria
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = SearchCriteria::create();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {}

    /**
     * @covers compact\repository\impl\repository\SearchCriteria::create
     */
    public function testCreate()
    {
        $result = SearchCriteria::create();
        
        $this->assertTrue($result instanceof SearchCriteria);
    }
}
