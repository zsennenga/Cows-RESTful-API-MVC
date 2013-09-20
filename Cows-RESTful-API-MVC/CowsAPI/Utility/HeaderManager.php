<?php

namespace CowsAPI\Utility;
/**
 * 
 * Parses values from the headers and then returns them
 * 
 * @author its-zach
 */
class HeaderManager {

	protected $headers;
	protected $pubKey;
	protected $timeStamp;
	protected $signature;
	
	
	public function __construct()	{
		$this->headers = function_exists('apache_request_headers') ? apache_request_headers() : null;
	}
	
	public function setHeaders($h)	{
		$this->headers = $h;
	}
	
	public function parseAuth()	{
		if (!isset($this->headers['Authorization']))	{
			return false;
		}
		$this->parseAuthHeader($this->headers['Authorization']);
		return true;
	}
	
	private function parseAuthHeader($auth)	{
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