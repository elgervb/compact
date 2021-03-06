<?php
namespace compact\repository;

use compact\mvvm\IModel;

/**
 * Configuration for a model.
 * The configuration handles the validation of a model, states which is the primary key and the fieldnames
 *
 * @author elger
 */
interface IModelConfiguration
{

    /**
     * Factory method to create a new, empty model of the type this configuration can handle
     *
     * @return IModel
     */
    public function createModel();

    /**
     * Returns all fieldnames for the model
     *
     * @param IModel $aModel
     *            The model from which to return the fieldnames
     *            
     * @return array of strings the fieldnames, never null
     */
    public function getFieldNames(IModel $aModel);

    /**
     * Returns the primary key fieldname under which the model can be stored by the repository
     *
     * @return mixed
     */
    public function getKey();

    /**
     * Validates the given model
     *
     * @param IModel $aModel            
     *
     * @return void
     *
     * @throws ValidationException when validation of the model fails
     */
    public function validate(IModel $aModel);
}