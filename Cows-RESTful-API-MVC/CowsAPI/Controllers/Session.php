<?php

namespace CowsAPI\Controllers;

class NoRoute extends BaseController	{
	public function POST()	{
		
		try {
			$ticket = $this->serviceFactory->getServiceTicket();
		} catch (Exception $e) {
			$this->updateView($e->getMessage(), ERROR_CAS, 400);
			return $e->getMessage();
		}
		
		try {
			$this->serviceFactory->createSession($ticket);
		} catch (Exception $e) {
			$this->updateView($e->getMessage(), ERROR_COWS, 400);
			return $e->getMessage();
		}
		
		$this->updateView(null, 0, 201);
		return "";
		
	}
	
	public function DELETE()	{
		if (!$this->serviceFactory->checkSession()) return "";
		
		try {
			$ticket = $this->serviceFactory->destroySession();
		} catch (Exception $e) {
			$this->updateView($e->getMessage(), ERROR_COWS, 400);
			return $e->getMessage();
		}
		return "";
	}
}
?>