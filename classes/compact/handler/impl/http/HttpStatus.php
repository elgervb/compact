<?php
namespace compact\handler\impl\http;

/**
 * @author eaboxt
 */
class HttpStatus
{
	private $httpCode;
	private $content;
	
	/**
	 * Create a new HttpError
	 * 
	 * @param $aHttpCode int The HTTP status code
	 * @param $aContent mixed the content to send to the browser
	 */
	public function __construct( $aHttpCode, $aContent = null)
	{
		$this->httpCode = $aHttpCode;
		$this->content = $aContent;
	}
	
	/**
	 * Returns the content
	 * 
	 * @return mixed or null when not set
	 */
	public function getContent(){
		return $this->content;
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