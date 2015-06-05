<?php
namespace compact\handler\impl;

use compact\Context;
use compact\handler\IHander;

/**
 * Handler error pages
 *
 * @author elger
 */
class ErrorPageHandler implements IHander
{

    /**
     * (non-PHPdoc)
     *
     * @see \compact\handler\IHander::handle()
     */
    public function handle($statusCode)
    {
        $responce = Context::get()->http()->getResponse();
        $responce->setStatusCode($statusCode);
        
        $view = Context::get()->router()->run($statusCode, 'GET');
        if ($view){
        	$responce->setContentType('text/html');
            $responce->getWriter()->write($view);
        }
    }

    /**
     * (non-PHPdoc)
     *
     * @see \compact\handler\IHander::accept()
     */
    public function accept($object)
    {
        if (is_numeric($object) && (int) $object >= 400 && (int) $object <= 505 ) {
            return true;
        }
        return false;
    }
}