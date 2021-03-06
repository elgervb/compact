<?php
namespace compact\http;

use compact\logging\Logger;
use compact\io\writer\BufferedStreamWriter;
use compact\Context;

/**
 * The Http response
 */
class HttpResponse
{

    /**
     *
     * @var The differents defined status code by RFC 2616 {@link http://www.faqs.org/rfcs/rfc2616}
     */
    private static $HTTP_STATUS_CODES = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported'
    );

    /**
     * The charset of the response
     *
     * @var string
     */
    private $charSet = "UTF-8";

    /**
     * The content type of the response
     *
     * @var string
     */
    private $contentType = "text/html";

    /**
     * The actual http headers
     *
     * @var array
     */
    private $headers;

    /**
     * Flag to keep track if the response headers have already been send
     *
     * @var boolean
     */
    private $isFlushed = false;

    /**
     * The status code of the response
     *
     * @var int
     */
    private $statusCode = 200;

    /**
     *
     * @var BufferedStreamWriter
     */
    private $streamWriter;

    /**
     * Creates a new HttpResponse
     */
    public function __construct()
    {
        $this->streamWriter = new BufferedStreamWriter("php://output");
    }

    /**
     * Add a new header
     *
     * @param string $aHeader            
     * @param string $aValue            
     */
    public function addHeader($aHeader, $aValue)
    {
        $this->headers[$aHeader] = $aValue;
    }

    /**
     * Set headers for disabeling browser cache
     */
    public function disableCache()
    {
        $this->setHeader("Cache-Control", "no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
        $this->setHeader("Expires", gmdate('D, d M Y H:i:s', time() - 86400) . ' GMT');
        $this->setHeader("Pragma", "no-cache");
    }

    /**
     * Flushes the output, first time this method is called the headers will also be send.
     *
     * @return void
     */
    public function flush()
    {
        if ($this->isFlushed === false) {
            // send response headers
            $this->sendResponseHeader();
            $this->sendContentTypeHeader();
            $this->sendHeaders();
            
            $this->isFlushed = true;
        }
        
        // send output
        $this->streamWriter->flush();
    }

    /**
     *
     * @return string
     */
    public function getCharSet()
    {
        return $this->charSet;
    }

    /**
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Returns the writer for outputting
     *
     * @return \compact\io\writer\StreamWriter
     */
    public function getWriter()
    {
        return $this->streamWriter;
    }

    /**
     * Checks if the headers have already been send
     *
     * @return boolean
     */
    public function isHeaderSend()
    {
        return headers_sent();
    }

    /**
     * Redirects the user to the given url
     *
     * @param
     *            string aLocation
     */
    public function redirect($aLocation)
    {
        Logger::get()->logFine("Redirecting to: " . $aLocation);
        $this->statusCode = (($this->statusCode >= 300) && ($this->statusCode < 400)) ? $this->statusCode : 302;
        
        $this->sendHeader("Location: " . $aLocation, true, $this->statusCode);
    }

    /**
     * Sends the response containing the statuscode (default: 200 OK)
     */
    private function sendResponseHeader()
    {
        $reason = (array_key_exists($this->statusCode, self::$HTTP_STATUS_CODES)) ? self::$HTTP_STATUS_CODES[$this->statusCode] : null;
        
        $this->sendHeader('HTTP/1.1 ' . $this->statusCode . ' ' . $reason, true, $this->statusCode);
    }

    /**
     * Sends the content type (default: Content-Type: text/html;charset= UTF-8)
     */
    private function sendContentTypeHeader()
    {
        $this->sendHeader('Content-Type: ' . $this->contentType . ';charset=' . $this->charSet);
    }

    /**
     * Internal method to send the complete header, make **SURE** the header is correct
     *
     * @param string $aCompleteHeader            
     * @param boolean $aReplace
     *            [optional]
     * @param $aStatusCode [optional]            
     */
    protected function sendHeader($aCompleteHeader, $aReplace = false, $aStatusCode = null)
    {
        if ($aStatusCode !== null) {
            header($aCompleteHeader, $aReplace, $aStatusCode);
        } else {
            header($aCompleteHeader, $aReplace);
        }
    }

    /**
     * Send the Http Headers
     */
    private function sendHeaders()
    {
        if (count($this->headers) > 0) {
            foreach ($this->headers as $key => $value) {
                $this->sendHeader($key . ": " . $value, true);
            }
        }
    }

    /**
     *
     * @param string $charSet            
     */
    public function setCharSet($charSet)
    {
        $this->charSet = $charSet;
    }

    /**
     *
     * @param string $contentType            
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * Set the CORS headers
     *
     * @see http://www.w3.org/TR/cors/
     * @see http://www.nczonline.net/blog/2010/05/25/cross-domain-ajax-with-cross-origin-resource-sharing/
     * @see http://enable-cors.org/
     * @see http://www.html5rocks.com/en/tutorials/cors/
     * 
     * TODO https://github.com/elgervb/compact/issues/32
     */
    public function setCORSHeaders()
    {
        $httpContext = Context::get()->http();
        $request = $httpContext->getRequest();
        $response = $httpContext->getResponse();
        
        $response->addHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, HEAD, PATCH, OPTIONS');
        
        // origin
        $origin = $request->getHeader('Origin');
        if ($origin)
            $response->addHeader('Access-Control-Allow-Origin', $origin); // or use '*' to allow all
                                                                              
        // custom headers
        $xHeaders = $request->getHeader('Access-Control-Request-Headers');
        if ($xHeaders){
            $response->addHeader('Access-Control-Allow-Headers', $xHeaders);
        }
        
        $response->addHeader('Access-Control-Allow-Credentials', 'true');
        
        // change preflight request
        $response->addHeader('Access-Control-Max-Age', 1800);
    }

    /**
     * Enable caching through setting http headers
     *
     * @param int $aTimeInMinutes
     *            The time to add caching in minutes
     * @param string $aLastModifiedGmDate
     *            = null
     */
    public function setEnableCache($aTimeInSeconds = 3600, $aLastModifiedGmDate = null)
    {
        $this->setHeader("Cache-Control", "maxage=" . $aTimeInSeconds . ", must-revalidate");
        $this->setHeader("Expires", gmdate('D, d M Y H:i:s', time() + $aTimeInSeconds) . ' GMT');
        $this->setHeader("Pragma", "public");
        $this->setHeader("Last-Modified", gmdate('D, d M Y H:i:s', ($aLastModifiedGmDate) ? $aLastModifiedGmDate : time() - $aTimeInSeconds) . ' GMT');
    }

    public function setHeader($aHeader, $aValue)
    {
        $this->headers[$aHeader] = $aValue;
    }

    /**
     *
     * @param int $statusCode            
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }
}