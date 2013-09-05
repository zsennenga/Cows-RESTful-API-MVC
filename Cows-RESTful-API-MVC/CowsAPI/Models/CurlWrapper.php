<?php

namespace CowsAPIUtility;

class CurlWrapper implements \CurlInterface	{
	private $handle = null;
	
	public function CurlWrapper() {
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
	
	public function execute() {
		return curl_exec($this->handle);
	}
	
	public function getInfo($name) {
		return curl_getinfo($this->handle, $name);
	}
	
	public function close() {
		curl_close($this->handle);
	}
}