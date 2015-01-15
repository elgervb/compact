<?php
namespace compact\handler\impl;

use compact\Context;
use compact\handler\IHander;

/**
 * Handler for a 500
 *
 * @author elger
 */
class InternalErrorHandler implements IHander
{

    /**
     * (non-PHPdoc)
     *
     * @see \compact\handler\IHander::handle()
     */
    public function handle($object)
    {
        $errorMsg = '<h1>Exception : </h1><p>' . $object->getMessage() . '</p> <pre>' . $object->getTraceAsString() . '</pre>';
        // check for a layout
        $layout = Context::get()->getService(Context::SERVICE_LAYOUT);
        if ($layout) {
            $layout->{'body'} = $errorMsg;
            $object = $layout;
        }
        
        $responce = Context::get()->http()->getResponse();
        $responce->setStatusCode(404);
        $responce->setContentType('text/html');
        if ($object instanceof IView){
            $responce->getWriter()->write($object->render());
        }
        else{
            $responce->getWriter()->write($errorMsg);
        }
    }

    /**
     * (non-PHPdoc)
     *
     * @see \compact\handler\IHander::accept()
     */
    public function accept($object)
    {
        if ($object instanceof \Exception) {
            return true;
        } elseif (is_numeric($object) && (int) $object === 500) {
            return true;
        }
        return false;
    }
}