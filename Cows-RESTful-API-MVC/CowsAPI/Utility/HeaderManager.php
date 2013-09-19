<?php

namespace CowsAPI\Utility;
/**
 * 
 * Parses values from the headers and then returns them
 * 
 * @author its-zach
 * @codeCoverageIgnore
 */
class HeaderManager {

	protected $headers;
	protected $pubKey;
	protected $timeStamp;
	protected $signature;
	
	
	public function __construct($headers)	{
		$this->headers = $headers;
		$this->parseAuthHeader($headers['Authorization']);
	}
	
	public function parseAuthHeader($auth)	{
		$params = explode("|", $auth);
		$this->pubKey = $params[0];
		$this->timeStamp = $params[1];
		$this->signature = $params[2];
	}

	public function getResponseClass()	{
		return 'JSON';
	}
	
	public function getPublicKey()	{
		return $this->pubKey;
	}
	
	public function getTimeStamp()	{
		return $this->timeStamp;
	}
	
	public function getSignature()	{
		return $this->signature;
	}
}
?>