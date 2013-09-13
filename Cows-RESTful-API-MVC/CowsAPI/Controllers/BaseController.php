<?php

namespace CowsAPI\Controllers;

use \CowsAPI\Models\ServiceFactory;

abstract class BaseController	{
	
	protected $eventId;
	protected $serviceFactory;
	private $view;
	/**
	 * 
	 * Build the controller
	 * 
	 * @param view $view
	 * @param event id $eventId
	 * @param ServiceFactory $serviceFactory
	 */
	public final function __construct($view, $eventId, ServiceFactory $serviceFactory)	{
		$this->view = $view;
		$this->serviceFactory = $serviceFactory;
		$this->eventId = $eventId;	
	}
	/**
	 * 
	 * Update the parameters of the current view.
	 * 
	 * @param string $message
	 * @param string $responseCode
	 * @param string $statusCode
	 */
	public function updateView($message = null, $responseCode = null, $statusCode = null)	{
		if (isset($message)) $this->view->setMessage($message);
		if (isset($responseCode)) $this->view->setResponse($responseCode);
		if (isset($statusCode)) $this->view->setStatus($statusCode);
	}
	/**
	 * Sets curl's cookiejar to contain the session cookies for this public key, if any
	 * @codeCoverageIgnore
	 */
	public function authCows()	{
		$this->serviceFactory->authCowsSession();
	}
}