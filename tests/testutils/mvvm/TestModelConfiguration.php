<?php
namespace testutils\mvvm;

use compact\repository\IModelConfiguration;
use compact\mvvm\IModel;
use compact\repository\DefaultModelConfiguration;

/**
 *
 * @author eaboxt
 */
class TestModelConfiguration extends DefaultModelConfiguration
{

    /**
     * Test model configuration
     *
     * Maps to classes\compact\testutils\mvvm\TestModel
     */
    public function __construct()
    {
        parent::__construct('testutils\mvvm\TestModel');
    }
}