<?php
namespace compact\handler;

interface IHander
{

    /**
     * Checks if this handler can handle the object supplied, after which the handle() method will be called
     *
     * @param
     *            mixed the controller response
     *            
     * @return boolean true if it can be handled, false if not
     */
    public function accept($object);

    /**
     * Handle the object, use the accept() method to check if the object can be handled
     *
     * @param mixed $object            
     *
     * @return mixed
     */
    public function handle($object);
}