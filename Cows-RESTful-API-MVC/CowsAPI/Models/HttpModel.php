<?php
namespace CowsApiModels;

abstract class HttpModel	{
	
	private $curl;
	private $method;
	
	abstract function setParams();
	
	public function setCookieFile($file)	{
		setOption(CURLOPT_COOKIEJAR, $file);
		setOption(CURLOPT_COOKIEFILE, $file);
	}
	
	public function setURL($url)	{
		$curl->setOption(CURLOPT_URL, $url);
	}
	
	public function execute()	{
		$this->curl->setOption(CURLOPT_CUSTOMREQUEST, $method);
		$this->curl->execute();
	}
}