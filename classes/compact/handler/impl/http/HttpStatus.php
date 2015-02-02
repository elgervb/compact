<?php
namespace compact\handler\impl\http;

/**
 *
 * @author eaboxt
 */
class HttpStatus
{

    const STATUS_200_OK = 200;

    const STATUS_201_CREATED = 201;

    const STATUS_204_NO_CONTENT = 204;

    const STATUS_401_UNAUTHORIZED = 401;

    const STATUS_404_NOT_FOUND = 404;

    const STATUS_500_INTERNAL_SERVER_ERROR = 500;

    const STATUS_501_NOT_IMPLEMENTED = 501;

    private $httpCode;

    private $content;

    private $extraHeaders;

    /**
     * Create a new HttpError
     *
     * @param $aHttpCode int
     *            The HTTP status code
     * @param $aContent mixed
     *            the content to send to the browser
     */
    public function __construct($aHttpCode, $aContent = null, array $extraHeaders = null)
    {
        $this->httpCode = $aHttpCode;
        $this->content = $aContent;
        $this->extraHeaders = $extraHeaders;
    }

    /**
     * Returns the content
     *
     * @return mixed or null when not set
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Returns the extra http headers to add to the response
     * 
     * @return array
     */
    public function getExtraHeaders()
    {
        return $this->extraHeaders;
    }

    /**
     * Returns the HTTP status code
     *
     * @return int the HTTP status code, NEVER null
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }
}