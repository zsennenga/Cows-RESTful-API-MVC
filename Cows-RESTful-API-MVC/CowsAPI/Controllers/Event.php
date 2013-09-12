<?php

namespace CowsAPI\Controllers;

class NoRoute extends BaseController	{
	public function GET()	{
		
		if (isset($this->eventId))	{
			$event = $this->serviceFactory->getEventById($eventId);
			if (!isset($event))	{
				$this->updateView("Event Not Found", 1, 400);
				return "Event Not Found";
			}
			$this->updateView($event);
			return $event;
		}
		else	{
			$events = $this->serviceFactory->getEvents();
			$this->updateView($events);
			return $events;
		}
	}
	
	public function POST()	{
		
		if (!$this->serviceFactory->checkSession())	{
			$this->updateView("Invalid session" , 1 , 401);
			return "Invalid session";
		}
		try	{
			$params = $this->serviceFactory->buildEventParams();
		}
		catch (\Exception $e)	{
			$this->updateView($e->getMessage(), 1, 400);
			return $e->getMessage();
		}
		try	{
			$eventId = $this->serviceFactory->createEvent($params);
		}
		catch (\Exception $e)	{
			$this->updateView($e->getMessage(), 1, 500);
			return $e->getMessage();
		}
		
		$this->updateView($this->eventId);
		return $eventId;
	}
	
	public function DELETE()	{

		if (!$this->serviceFactory->deleteEvent($this->eventId))	{
			$this->updateView("Unable to delete event", 1, 403);
			return ("Unable to delete event");
		}
		return "";
	}
}
?>