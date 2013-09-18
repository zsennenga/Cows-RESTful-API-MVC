<?php

namespace CowsAPI\Exceptions;
/**
 * Exception for CowsAPI classes
 * @author its-zach
 * @codeCoverageIgnore
 */
class BaseException extends \Exception {

	protected $statusCode;
	protected $message;
	protected $responseCode;
	
	public function __construct($statusCode, $message, $responseCode)	{
		$this->statusCode = $statusCode;
		$this->message = $message;
		$this->responseCode = $responseCode;
	}
	
	public function getStatus()	{
		return $this->statusCode;
	}
	
	public function getMyMessage()	{
		return $this->message;
	}
	
	public function getResponseCode()	{
		return $this->responseCode;
	}

}
?>