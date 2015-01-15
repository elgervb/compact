<?php
namespace compact\handler\impl;

use compact\Context;
use compact\handler\IHander;

/**
 * Handler for a 404
 *
 * @author elger
 */
class PageNotFoundHandler implements IHander
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
            $layout->{'body'} = "<h1>404 Page not found</h1>";
            $object = $layout;
        }
        
        $responce = Context::get()->http()->getResponse();
        $responce->setStatusCode(404);
        $responce->setContentType('text/html');
        if ($object instanceof IView){
            $responce->getWriter()->write($object->render());
        }
        else{
            $responce->getWriter()->write('<h1>404 page not found</h1>');
        }
    }

    /**
     * (non-PHPdoc)
     *
     * @see \compact\handler\IHander::accept()
     */
    public function accept($object)
    {
        if (is_numeric($object) && (int) $object === 404) {
            return true;
        }
        return false;
    }
}