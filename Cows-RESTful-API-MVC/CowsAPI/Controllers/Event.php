<?php

namespace CowsAPI\Controllers;

class Event extends BaseController	{
	
	public function GET()	{
		
		if (isset($this->eventId))	{
			try	{
				$event = $this->serviceFactory->getEventById($this->eventId);
			}
			catch (\Exception $e)	{
				$this->updateView($e->getMessage(), ERROR_CURL, 500);
				return $e->getMessage();
			}
			if (!isset($event))	{
				$this->updateView("Event Not Found", ERROR_PARAMETERS, 400);
				return "Event Not Found";
			}
			$this->updateView($event);
			return $event;
		}
		else	{
			try	{
				$events = $this->serviceFactory->getEvents();
			}
			catch(\Exception $e)	{
				$this->updateView($e->getMessage(), ERROR_CURL, 500);
				return $e->getMessage();
			}
			
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
			$newEventId = $this->serviceFactory->createEvent($params);
		}
		catch (\Exception $e)	{
			$this->updateView($e->getMessage(), 1, 500);
			return $e->getMessage();
		}
		
		$this->updateView($newEventId);
		return $newEventId;
	}
	
	public function DELETE()	{
		try	{
			if (!$this->serviceFactory->deleteEvent($this->eventId))	{
				$this->updateView("Unable to delete event", 1, 403);
				return "Unable to delete event";
			}
		} catch (Exception $e)	{
			$this->updateView($e->getMessage(), 1, 500);
			return $e->getMessage();
		}
		return "";
	}
}
?>