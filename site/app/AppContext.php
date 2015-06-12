<?php
namespace app;

use compact\IAppContext;
use compact\Context;
use compact\routing\Router;
use compact\mvvm\impl\ViewModel;

/**
 * The Application Context for elgervanboxtel.nl
 * 
 * @author eaboxt
 */
class AppContext implements IAppContext
{

	/**
	 * (non-PHPdoc)
	 * @see \compact\IAppContext::routes()
	 */
    public function routes(Router $router)
    {
    	$router->add("^/$", function(){
    	    return "ROOT";
    	});
    	
	    /**
	     * Errors
	     */
	    $router->add(404, function(){
	    	return new ViewModel('404.html');
	    });
	    
    	$router->add(500, function(){
    		return new ViewModel('500.html');
    	});
    }

    /**
     * (non-PHPdoc)
     * @see \compact\IAppContext::handlers()
     */
    public function handlers(Context $ctx)
    {
        //
    }

    /**
     * (non-PHPdoc)
     * @see \compact\IAppContext::services()
     */
    public function services(Context $ctx)
    {
    	//
    }
}
