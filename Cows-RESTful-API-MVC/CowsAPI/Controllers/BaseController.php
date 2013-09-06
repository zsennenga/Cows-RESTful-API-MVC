<?php

namespace CowsAPIControllers;

abstract class BaseController	{
	
	protected $route;
	protected $serviceFactory;
	private $view;
	
	public final function __construct($view, $route, $serviceFactory)	{
		$this->view = $view;
		$this->serviceFactory = $serviceFactory;
		$this->route = $route;	
	}
	
	public function updateView($message = null, $responseCode = null, $statusCode = null)	{
		if (isset($message)) $this->view->setMessage($message);
		if (isset($responseCode)) $this->view->setResponse($responseCode);
		if (isset($statusCode)) $this->view->setStatus($statusCode);
	}
}