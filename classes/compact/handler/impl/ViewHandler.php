<?php
namespace compact\handler\impl;

use compact\handler\IHander;
use compact\mvvm\IView;
use compact\Context;

/**
 * Handler for views
 *
 * @author elger
 */
class ViewHandler implements IHander
{

    /**
     * (non-PHPdoc)
     * 
     * @see \compact\handler\IHander::handle()
     */
    public function handle($object)
    {
        // check for a layout
        $layout = Context::get()->getService(Context::SERVICE_LAYOUT);
        if ($layout) {
            $layout->{'body'} = is_string($object) ? $object : $object->render();
            $object = $layout;
        }
        
        // render the page
        $responce = Context::get()->http()->getResponse();
        $responce->setStatusCode(200);
        $responce->setContentType('text/html');
        $responce->getWriter()->write(is_string($object) ? $object : $object->render());
    }

    /**
     * (non-PHPdoc)
     *
     * @see \compact\handler\IHander::accept()
     *
     * @param IView $object            
     */
    public function accept($object)
    {
        return is_string($object) || ( is_object($object) && $object instanceof IView);
    }
}