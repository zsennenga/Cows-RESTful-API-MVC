<?php

namespace CowsAPIModels;

class HttpModel	{
	
	protected $curl;
	protected $method;
	
	public function setCookieFile($file)	{
		setOption(CURLOPT_COOKIEJAR, $file);
		setOption(CURLOPT_COOKIEFILE, $file);
	}
	
	public function setMethod($method)	{
		$this->method = $method;
		if ($method != "POST")	{
			$this->curl->set_option(CURLOPT_POSTFIELDS, array());
		}
	}
	
	public function setPostParameters($params)	{
		$this->curl->set_option(CURLOPT_POSTFIELDS, $params);
	}
	
	protected function setURL($url)	{
		$curl->setOption(CURLOPT_URL, $url);
	}
	
	public function execute()	{
		$this->curl->setOption(CURLOPT_CUSTOMREQUEST, $method);
		return $this->curl->execute();
	}
}