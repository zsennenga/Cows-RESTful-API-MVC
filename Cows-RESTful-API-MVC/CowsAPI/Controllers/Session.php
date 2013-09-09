<?php

namespace CowsAPIControllers;

class NoRoute extends BaseController	{
	public function POST()	{
		$siteId = $this->route->getParam('siteId');
		
		if (!$this->serviceFactory->validateSiteId($siteId))	{
			$this->updateView("Invalid Site Id", 1 , 400);
			return;
		}
		try {
			$ticket = $this->serviceFactory->getServiceTicket();
		} catch (Exception $e) {
			$this->updateView($e->getMessage(), 1, 400);
		}
		try {
			$this->serviceFactory->createSession($ticket);
		} catch (Exception $e) {
			$this->updateView($e->getMessage(), 1, 400);
		}
		
		$this->updateView(null, 0, 201);
		
	}
	
	public function DELETE()	{
		$siteId = $this->route->getParam('siteId');
		
		if (!$this->serviceFactory->checkSession()) return;
		
		if (!$this->serviceFactory->validateSiteId($siteId))	{
			$this->updateView("Invalid Site Id", 1 , 400);
			return;
		}
		try {
			$ticket = $this->serviceFactory->destroySession();
		} catch (Exception $e) {
			$this->updateView($e->getMessage(), 1, 400);
		}
	}
}
?>