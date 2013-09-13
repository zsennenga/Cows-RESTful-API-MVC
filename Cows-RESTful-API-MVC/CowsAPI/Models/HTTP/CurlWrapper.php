<?php

namespace CowsAPI\Models\HTTP;

/**
 * Basic OO curl wrapper
 * @author its-zach
 *
 */
class CurlWrapper implements CurlInterface	{
	private $handle = null;
	
	/**
	 * Sets up a couple of core options necessary for our purpose (user agent, return transfer etc)
	 */
	public function __construct() {
		$this->handle = curl_init();
		
		curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->handle, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->handle, CURLOPT_SSL_VERIFYPEER, false);
		
		curl_setopt($this->handle, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.72 Safari/537.36");
		curl_setopt($this->handle, CURLOPT_AUTOREFERER, true );
	}
	
	public function setOption($name, $value) {
		curl_setopt($this->handle, $name, $value);
	}
	
	/**
	 * Executes a curl request, throws exceptions in case of error
	 * @throws \RuntimeException
	 * @return unknown
	 */
	public function execute() {
		$out = curl_exec($this->handle);
		if ($out === false) throw new \RuntimeException("Unable to connect.");
		if (strlen($out) == 0) throw new \RuntimeException("Response was empty.");
		return $out;
	}
	/**
	 * 
	 * @codeCoverageIgnore
	 */
	public function getInfo($name) {
		return curl_getinfo($this->handle, $name);
	}
	/**
	 * @codeCoverageIgnore
	 */
	public function close() {
		curl_close($this->handle);
	}
}