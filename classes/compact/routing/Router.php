<?php
namespace compact\routing;

use compact\logging\Logger;

class Router
{

    /**
     *
     * @var \ArrayObject
     */
    private $routes;

    public function __construct()
    {
        $this->routes = new \ArrayObject();
    }

    /**
     * Add a new route
     *
     * @param string $aPath
     *            the path regex (slashes will be automatically escaped)
     *            
     * @return \compact\routing\Router for chaining purposes
     */
    public function add($aPath, \Closure $aController, $requestType = 'GET')
    {
        $routes = $this->getRoutes(strtoupper($requestType));
        $routes->offsetSet($aPath, $aController);
        return $this;
    }

    private function getRoutes($for)
    {
        if (! $this->routes->offsetExists($for)) {
            $this->routes->offsetSet($for, new \ArrayObject);
        }
        
        return $this->routes->offsetGet($for);
    }

    /**
     * Executes a controller registered to the path
     *
     * @param string $aPath            
     *
     * @return mixed The result from the controller or <code>null</code>
     */
    public function run($aPath, $requestMethod)
    {
        $routes = $this->getRoutes($requestMethod);
        
        foreach ($routes as $path => $controller) {
            $path = preg_replace("/\//", "\\\/", $path);
            if (preg_match('/' . $path . '/', $aPath, $matches)) {
                
                Logger::get()->logFine('Found route ' . $path);
                array_shift($matches);
                return call_user_func_array($controller, $matches);
            }
        }
        return null;
    }
}