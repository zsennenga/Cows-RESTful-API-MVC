<?php

namespace CowsAPI\Views;

abstract class BaseView	{
	protected $responseCode;
	protected $statusCode;
	protected $message;
	
	protected $template;
	
	protected $logger;
	
	/**
	 * Build the view, set default response parameters
	 * 
	 * @param Log $log
	 * @param Template $template
	 */
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
	/**
	 * Show the view after setting all the parameters
	 * 
	 * @codeCoverageIgnore
	 */
	public function render()	{
		http_response_code($this->responseCode);
		echo $this->template->parse($this->statusCode,$this->message);
		$this->log->setResponse($out);
		echo $out;
	}
}