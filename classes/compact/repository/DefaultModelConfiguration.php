<?php
namespace compact\repository;

use compact\mvvm\IModel;
use compact\repository\IModelRepository;

/**
 * Default model configuration.
 * Defines no validators (that can be done in child classes), creates new models and get field names based on the constants defined in the model class
 *
 * @author elger
 *        
 */
class DefaultModelConfiguration implements IModelConfiguration
{

    private $modelClassName;

    private $pkFieldName;

    private $idGeneration = "id";

    /**
     * Creates a DefaultModelConfiguration
     *
     * @param $aModelClassName string
     *            The classname of the model
     * @param $aPrimaryKeyFieldName string
     *            [optional] The primary key of the model (only used when persisting the model)
     */
    public function __construct($aModelClassName, $aPrimaryKeyFieldName = "id")
    {
        $this->modelClassName = $aModelClassName;
        $this->setPrimaryKeyFieldName($aPrimaryKeyFieldName);
       
        assert('class_exists( $aModelClassName, true ) /* Classname $aModelClassName could not be loaded */');
    }

    /**
     * Factory method to create a new, empty model of the type this configuration can handle
     *
     * @return IModel
     */
    public function createModel()
    {
        $className = $this->modelClassName;
        return new $className();
    }

    /**
     *
     * @see core\mvc\IModelConfiguration::getFieldNames()
     */
    public function getFieldNames(IModel $aModel)
    {
        $reflector = new \ReflectionClass($aModel);
        return $reflector->getConstants();
    }

    /**
     * (non-PHPdoc)
     *
     * @see core\mvc.IModelConfiguration::getIdGeneration()
     */
    public function getIdGeneration()
    {
        return $this->idGeneration;
    }

    /**
     * Returns the name of the underlying model class
     */
    public function getModelClassName()
    {
        return $this->modelClassName;
    }

    /**
     *
     * @see core\mvc\IModelConfiguration::getKey()
     */
    public function getKey()
    {
        return $this->pkFieldName;
    }

    /**
     * Sets the way the id for the primary key is generated
     *
     * @param $aGeneration string
     *            id or guid
     */
    public function setIdGeneration($aGeneration)
    {
        $this->idGeneration = $aGeneration;
    }

    /**
     * Sets the primary key field name
     *
     * @param $aPrimaryKeyFieldName string            
     */
    public function setPrimaryKeyFieldName($aPrimaryKeyFieldName)
    {
        $this->pkFieldName = $aPrimaryKeyFieldName;
    }

    /**
     *
     * @see core\mvc\IModelConfiguration::validate()
     */
    public function validate(IModel $aModel)
    {
        // no validation
    }
}