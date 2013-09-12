<?php

namespace CowsAPI\Controllers;

use CowsAPI\Models\ServiceFactory;

abstract class BaseController	{
	
	protected $eventId;
	protected $serviceFactory;
	private $view;
	
	public final function __construct($view, $eventId, ServiceFactory $serviceFactory)	{
		$this->view = $view;
		$this->serviceFactory = $serviceFactory;
		$this->eventId = $eventId;	
	}
	
	public function updateView($message = null, $responseCode = null, $statusCode = null)	{
		if (isset($message)) $this->view->setMessage($message);
		if (isset($responseCode)) $this->view->setResponse($responseCode);
		if (isset($statusCode)) $this->view->setStatus($statusCode);
	}
	
	public function authCows()	{
		$this->serviceFactory->authCowsSession();
	}
}