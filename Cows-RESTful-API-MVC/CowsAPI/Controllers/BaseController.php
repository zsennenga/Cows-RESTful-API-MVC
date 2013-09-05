<?php

namespace CowsAPIControllers;

abstract class BaseController	{
	
	protected $route;
	protected $serviceFactory;
	
	protected $message;
	protected $responseCode;
	protected $statusCode;
	
	public final function __construct($view, $route, $serviceFactory)	{
		$this->view = $view;
		$this->serviceFactory = $serviceFactory;
		$this->route = $route;	
	}
	
	public function updateView()	{
		if (isset($this->message)) $view->setMessage($this->message);
		if (isset($this->responseCode)) $view->setResponse($this->responseCode);
		if (isset($this->statusCode)) $view->setStatus($this->statusCode);
		$view->setCallback($this->serviceFactory->getParam('callback'));
	}
}