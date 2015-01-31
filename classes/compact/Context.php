<?php
namespace compact;

use compact\http\HttpContext;
use compact\routing\Router;
use compact\handler\AssertHandler;
use compact\handler\ErrorHandler;
use compact\logging\Logger;
use compact\logging\recorder\impl\FileRecorder;
use compact\handler\ExceptionHandler;
use compact\handler\IHander;

class Context
{

    const SERVICE_APPCONTEXT = "appcontextService";

    const SERVICE_ASSERTION = "assertionService";

    const SERVICE_AUTH= "authService";

    const SERVICE_LOGGING = "loggingService";

    const SERVICE_ERROR = "errorHandlingService";

    const SERVICE_EXCEPTION = "exceptionHandlingService";

    const SERVICE_LAYOUT = "layoutService";
    const SERVICE_TRANSATOR = "translatorService";

    /**
     *
     * @var Context
     */
    private static $INSTANCE;

    /**
     * Key value cache
     *
     * @var array
     */
    private static $CACHE = array();

    /**
     * The path to the base of the site
     *
     * @var \SplFileInfo
     */
    private $basePath;

    /**
     * Factories to create framework objects
     *
     * @var \ArrayObject
     */
    private $service;

    /**
     * Handlers for controller responses
     *
     * @var \ArrayObject
     */
    private $handlers;

    /**
     *
     * @var \compact\http\HttpContext
     */
    private $httpContext;

    /**
     *
     * @var \compact\routing\Router;
     */
    private $router;

    /**
     * Constructor
     *
     * @throws Exception when singleton is not respected
     */
    public function __construct()
    {
        if (self::$INSTANCE !== null) {
            throw new \Exception("This is a singleton, !");
        }
        self::$INSTANCE = $this;
        $this->service = new \ArrayObject();
        $this->handlers = new \ArrayObject();
    }

    /**
     * Add a new handler to handle controller responses
     *
     * @param IHander $handler            
     *
     * @return Context $this for chaining
     */
    public function addHandler(IHander $handler)
    {
        $this->handlers->append($handler);
        
        return $this;
    }

    /**
     * Add a factory for a specific key.
     * Retrieve the factory later with the this key
     *
     * @param string $aFor            
     * @param \Closure $aFn
     *            The factory to add the service object
     */
    public function addService($aFor, \Closure $aFn)
    {
        $this->service->offsetSet($aFor, $aFn);
    }

    /**
     * Returns the path to the base of the site
     *
     * @param $aAppend string
     *            = null Append a path to the base path
     *            
     * @return \SplFileInfo
     */
    public function basePath($aAppend = null)
    {
        if ($aAppend !== null) {
            return new \SplFileInfo($this->basePath->getPathname() . "/" . $aAppend);
        }
        return $this->basePath;
    }

    /**
     * Factory method to retrieve the Context singleton
     *
     * @return \compact\Context
     */
    public static function get()
    {
        if (self::$INSTANCE === null) {
            // create a new context
            self::$INSTANCE = new Context();
            self::$INSTANCE->basePath = new \SplFileInfo(dirname($_SERVER["SCRIPT_FILENAME"]));
            
            date_default_timezone_set("CET");
            mb_language('uni');
            ini_set('default_charset', 'utf-8');
            mb_internal_encoding("UTF-8");
        }
        return self::$INSTANCE;
    }

    /**
     * Returns the appcontext
     * 
     * @return \compact\IAppContext
     */
    public function getAppContext()
    {
        return $this->getService(Context::SERVICE_APPCONTEXT);
    }

    /**
     * Returns a handler for an object
     *
     * @param mixed $for            
     *
     * @return \compact\handler\IHandler |NULL
     */
    public function getHandler($for)
    {
        /* @var $handler \compact\handler\IHandler */
        foreach ($this->handlers as $handler) {
            
            if ($handler->accept($for)) {
                return $handler;
            }
        }
        return null;
    }

    /**
     * Returns a service that was registered under the key
     *
     * @param string $aFor            
     * @return mixed NULL
     */
    public function getService($aFor)
    {
        if ($this->service->offsetExists($aFor)) {
            $service = $this->service->offsetGet($aFor);
            
            // check if the service is still a closure (factory).
            if ($service instanceof \Closure) {
                $impl = $service();
                $this->service->offsetSet($aFor, $impl);
                return $impl;
            } else {
                return $service;
            }
        }
        
        return null; // nothing found
    }

    /**
     * Returns the http context
     *
     * @return \compact\http\HttpContext
     */
    public function http()
    {
        if ($this->httpContext === null) {
            $this->httpContext = new HttpContext();
        }
        
        return $this->httpContext;
    }

    /**
     * Checks if the page has been requested locally
     *
     * @return boolean
     */
    public function isLocal()
    {
        return in_array($this->http()
            ->getRequest()
            ->getUserIP(), array(
            "127.0.0.1",
            null,
            '',
            '::1'
        ));
    }

    /**
     * Returns the router
     *
     * @return \compact\routing\Router
     */
    public function router()
    {
        if ($this->router === null) {
            $this->router = new Router();
        }
        
        return $this->router;
    }

    /**
     * Returns the site url
     *
     * @return string
     */
    public static function siteUrl()
    {
        if (! isset(self::$CACHE['siteUrl'])) {
            $request = Context::get()->http()->getRequest();
            
            $requestUri = $request->server("REQUEST_URI");
            $urlPart = $request->server("PHP_SELF");
            
            // check if we've rewritten index.php ...
            if (! preg_match("/.*index\.php.*/", $requestUri)) {
                $urlPart = str_replace("index.php", "", $urlPart);
            } else {
                $match = preg_match("/(.*index\.php).*/", $urlPart, $matches);
                $urlPart = $matches[1];
            }
            
            // remove trailing slash
            if ($urlPart === '/' || (strlen($urlPart) > 1 && $urlPart[strlen($urlPart) - 1] === "/")) {
                $urlPart = substr($urlPart, 0, strlen($urlPart) - 1);
            }
            
            self::$CACHE['siteUrl'] = $request->getScheme($request) . "://" . $request->getHost($request) . $urlPart;
        }
        
        return self::$CACHE['siteUrl'];
    }
}
