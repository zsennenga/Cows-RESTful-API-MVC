<?php

namespace CowsAPIControllers;

class NoRoute extends BaseController	{
	public function GET()	{
		
		$siteId = $this->route->getParam('siteId');
		$eventId = $this->route->getParam('eventId');
		
		if (!$this->serviceFactory->validateSiteId($siteId))	{
			$this->updateView("Invalid Site Id", 1 , 400);
			return;	
		}
		
		if (isset($eventId))	{
			$event = $this->serviceFactory->getEventById($eventId);
			if (isset($event))	{
				$this->updateView("Event Not Found", 1, 400);
				return;
			}
			$this->updateView($event);
		}
		else	{
			$events = $this->serviceFactory->getEvents();
			$this->updateView($events);
		}
	}
	
	public function POST()	{
		
		$siteId = $this->route->getParam('siteId');
		
		if (!$this->serviceFactory->validateSiteId($siteId))	{
			$this->updateView("Invalid Site Id", 1 , 400);
			return;
		}
		
		if (!$this->serviceFactory->checkSession())	{
			$this->updateView("Invalid session for site " . $siteId , 1 , 401);
			return;
		}
		try	{
			$params = $this->serviceFactory->buildEventParams();
		}
		catch (\Exception $e)	{
			$this->updateView($e->getMessage(), 1, 400);
			return;
		}
		try	{
			$eventId = $this->serviceFactory->createEvent($params);
		}
		catch (\Exception $e)	{
			$this->updateView($e->getMessage(), 1, 500);
			return;
		}
		
		$this->updateView($eventId);
	}
	
	public function DELETE()	{
		
		$siteId = $this->route->getParam('siteId');
		$eventId = $this->route->getParam('eventId');
		
		if (!$this->serviceFactory->validateSiteId($siteId))	{
			$this->updateView("Invalid Site Id", 1 , 400);
			return;
		}
		
		if (isset($eventId))	{
			if (!$this->serviceFactory->deleteEvent($eventId))	{
				$this->updateView("Unable to delete event", 1, 403);
			}
		}
	}
}
?>