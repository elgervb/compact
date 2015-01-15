<?php
namespace compact;

interface IAppContext
{

    /**
     * Register all custom handlers to be used in this app, using the main context
     *
     * @param \compact\Context $ctx
     */
    public function handlers(\compact\Context $ctx);
    
    /**
     * Create all routes for the app
     *
     * @param \compact\routing\Router $router            
     */
    public function routes(\compact\routing\Router $router);

    /**
     * Register all services to be used in this app, using the main context
     *
     * @param \compact\Context $ctx            
     */
    public function services(\compact\Context $ctx);
}
