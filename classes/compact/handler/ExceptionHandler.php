<?php
namespace compact\handler;

use compact\Context;
use compact\logging\Logger;

/**
 *
 * @author elger
 */
class ExceptionHandler
{

    /**
     * Constructor
     */
    public function __construct()
    {
        set_exception_handler(array(
            $this,
            "handle"
        ));
    }

    /**
     * Handles the exception
     *
     * @param $aException Exception            
     */
    public function handle(\Exception $aException)
    {
        Logger::get()->logError("Handle exception " . get_class($aException) . " " . $aException->getMessage());
        Logger::get()->logError($aException->getTraceAsString());
        if ($aException->getPrevious()) {
            Logger::get()->logError($aException->getPrevious()
                ->getTraceAsString());
        }
        $ctx = Context::get();
        $statusCode = $this->getStatusCodeForException($aException);
        $response = $ctx->http()->getResponse();
        $response->setStatusCode($statusCode);
        
        if ($ctx->isLocal()) {
            $handler = $ctx->getHandler($statusCode);
            if ($handler) {
                $handler->handle($aException);
            } else {
                $response->getWriter()->write('<h1> Exception : </h1><p>' . $aException->getMessage() . '</p> <pre>' . $aException->getTraceAsString() . '</pre>');
            }
        }
        
        $response->flush();
    }

    /**
     * Returns the status code of the exception
     *
     * @param \Exception $aException            
     *
     * @return int the http status code
     */
    private function getStatusCodeForException(\Exception $aException)
    {
        return 500;
    }
}
