<?php
namespace compact\mvvm;

use compact\Context;
use compact\logging\Logger;
use compact\handler\AssertHandler;
use compact\handler\ErrorHandler;
use compact\logging\recorder\impl\FileRecorder;
use compact\handler\ExceptionHandler;
use compact\logging\recorder\impl\BufferedFileRecorder;
use compact\handler\impl\ViewHandler;
use compact\handler\IHander;
use compact\handler\impl\PageNotFoundHandler;
use compact\handler\impl\InternalErrorHandler;
use compact\logging\recorder\impl\CompositeLogRecorder;
use compact\logging\decorator\impl\HtmlLogDecorator;
use compact\logging\recorder\impl\ScreenRecorder;
use compact\translations\Translator;
use compact\translations\bundle\impl\Translations_EN;
use compact\logging\decorator\impl\UserContextDecorator;
use compact\handler\impl\http\HttpStatusHandler;

class FrontController
{

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initApp();
    }

    private function handle404($result = null)
    {
        // check for a 404 handler
        $handler = Context::get()->getHandler(404);
        if ($handler) {
            $handler->handle($result);
        }
    }

    /**
     * Init the application context.
     */
    private function initApp()
    {
        // first regster some default handlers, AppContext can override these
        Context::get()->addHandler(new ViewHandler());
        Context::get()->addHandler(new HttpStatusHandler());
        Context::get()->addHandler(new PageNotFoundHandler());
        Context::get()->addHandler(new InternalErrorHandler());
        
        // add and init default services
        $this->addDefaultServices(Context::get());
        Context::get()->getService(Context::SERVICE_ASSERTION);
        Context::get()->getService(Context::SERVICE_ERROR);
        Context::get()->getService(Context::SERVICE_LOGGING);
        Context::get()->getService(Context::SERVICE_EXCEPTION);
        Context::get()->getService(Context::SERVICE_TRANSATOR);
        
        $className = 'app\AppContext';
        if (class_exists($className, true)) {
            
            /* @var $appCtx IAppContext */
            $appCtx = new $className();
            // register the appcontext as a service
            Context::get()->addService(Context::SERVICE_APPCONTEXT, function () use($appCtx)
            {
                return $appCtx;
            });
            $appCtx->services(Context::get());
            $appCtx->handlers(Context::get());
            $appCtx->routes(Context::get()->router());
            
            // Log here as the appContext should init logging
            Logger::get()->logFinest('Including app context: ' . $className);
        } else {
            Logger::get()->logFinest('No AppContext found');
        }
    }

    /**
     * Add the default services when the are not yet added elsewhere
     */
    private function addDefaultServices(\compact\Context $ctx)
    {
        // assertions
        if (! $ctx->getService(Context::SERVICE_ASSERTION)) {
            $ctx->addService(Context::SERVICE_ASSERTION, function ()
            {
                Context::get()->isLocal() ? AssertHandler::enable() : AssertHandler::disable();
                return null;
            });
        }
        // error handler
        if (! $ctx->getService(Context::SERVICE_ERROR)) {
            $ctx->addService(Context::SERVICE_ERROR, function ()
            {
                return new ErrorHandler(- 1, Context::get()->isLocal(), true, './app/logs/error.log');
            });
        }
        // logging
        if (! $ctx->getService(Context::SERVICE_LOGGING)) {
            $ctx->addService(Context::SERVICE_LOGGING, function ()
            {
                $path = Context::get()->basePath('/app/logs/app-' . date('Ymd', time()) . '.log');
                if (Context::get()->isLocal()) {
                    $recorder = new FileRecorder(new \SplFileInfo($path), new UserContextDecorator());
                    return new Logger($recorder, Logger::ALL);
                } else {
                    return new Logger(new BufferedFileRecorder(new \SplFileInfo($path)), Logger::WARNING);
                }
            });
        }
        
        // exception handler
        if (! $ctx->getService(Context::SERVICE_EXCEPTION)) {
            $ctx->addService(Context::SERVICE_EXCEPTION, function ()
            {
                return new ExceptionHandler();
            });
        }
        
        // translations handler
        if (! $ctx->getService(Context::SERVICE_TRANSATOR)) {
            $ctx->addService(Context::SERVICE_TRANSATOR, function ()
            {
                $translator = new Translator();
                $translator->addBundle(new Translations_EN());
                return $translator;
            });
        }
    }

    /**
     * When everything is in place, run the frontcontroller
     */
    public function run()
    {
        $ctx = Context::get();
        $router = $ctx->router();
        $request = $ctx->http()->getRequest();
        
        $result = $router->run($request->getPathInfo(), $request->getRequestMethod());
        
        // if we have a result, then check if we also have a handler registered
        if ($result) {
            $handler = Context::get()->getHandler($result);
            if ($handler) {
                $handler->handle($result);
            } else {
                Logger::get()->logWarning('Could not find a handler for ' . get_class($result));
                
                $this->handle404($result);
            }
        } else {
            Logger::get()->logWarning('No route found for ' . $request->getRequestMethod() . ' ' . $ctx->http()
                ->getRequest()
                ->getPathInfo());
            $this->handle404($result);
        }
        
        $ctx->http()
            ->getResponse()
            ->flush();
    }
}
