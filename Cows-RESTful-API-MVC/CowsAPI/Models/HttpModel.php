<?php

namespace CowsAPIModels;

abstract class HttpModel	{
	
	protected $curl;
	protected $method;
	
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
		return $this->curl->execute();
	}
}