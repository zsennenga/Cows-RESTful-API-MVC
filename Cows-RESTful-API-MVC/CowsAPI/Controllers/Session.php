<?php

namespace CowsAPI\Controllers;

/**
 * Controller for the /session path
 * @author its-zach
 *
 */
class Session extends BaseController	{
	public function POST()	{
		
		try {
			$ticket = $this->serviceFactory->getServiceTicket();
		} catch (\CowsAPI\Exceptions\BaseException $e) {
			$this->updateView($e->getMyMessage(), $e->getStatus() , $e->getResponseCode());
			return $e->getMessage();
		} catch (\Exception $e)	{
			$this->updateView($e->getMessage(), ERROR_CAS, 400);
			return $e->getMessage();
		}
		
		try {
			$this->serviceFactory->createSession($ticket);
		} catch (\CowsAPI\Exceptions\BaseException $e) {
			$this->updateView($e->getMyMessage(), $e->getStatus() , $e->getResponseCode());
			return $e->getMessage();
		} catch (\Exception $e)	{
			$this->updateView($e->getMessage(), ERROR_COWS, 400);
			return $e->getMessage();
		}
		
		$this->updateView(null, 0, 201);
		return "";
		
	}
	
	public function DELETE()	{
		if (!$this->serviceFactory->checkSession()) return "";
		
		try {
			$this->serviceFactory->destroySession();
		} catch (\CowsAPI\Exceptions\BaseException $e) {
			$this->updateView($e->getMyMessage(), $e->getStatus() , $e->getResponseCode());
			return $e->getMessage();
		} catch (\Exception $e)	{
			$this->updateView($e->getMessage(), ERROR_COWS, 400);
			return $e->getMessage();
		}
		return "";
	}
}
?>