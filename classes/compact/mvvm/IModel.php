<?php
namespace compact\mvvm;

interface IModel
{

    /**
     * Returns the value of the field
     *
     * @param $aField string            
     *
     * @return mixed the value, or null when the value for this key has not been set
     */
    public function get($aField);

    /**
     * Checks if a field is empty
     *
     * @param $aField string
     *            The field name
     * @return boolean
     */
    public function isEmpty($aField);

    /**
     * Sets a model's field
     *
     * @param $aField string            
     * @param $aValue mixed            
     */
    public function set($aField, $aValue);
}