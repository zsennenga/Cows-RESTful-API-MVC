<?php

namespace CowsAPI\Views;

abstract class BaseView	{
	protected $responseCode;
	protected $statusCode;
	protected $message;
	
	protected $template;
	
	protected $logger;
	
	public final function __construct($log, $template)	{
		$this->logger = $log;
		$this->template = $template;
		$this->statusCode = 0;
		$this->responseCode = 200;
		$this->message = "";
	}
	
	public function setMessage($m)	{
		$this->message = $m;
	}
	
	public function setStatus($s)	{
		$this->statusCode = $s;
	}
	
	public function setResponse($r)	{
		$this->responseCode = $r;
	}
	
	abstract function render();
}