<?php

namespace CowsAPI\Controllers;

/**
 * Controller for the /event path
 * @author its-zach
 *
 */
class Event extends BaseController	{
	
	public function GET()	{
		
		if (isset($this->eventId))	{
			try	{
				$event = $this->serviceFactory->getEventById($this->eventId);
			} catch (\CowsAPI\Exceptions\BaseException $e) {
				$this->updateView($e->getMyMessage(), $e->getStatus() , $e->getResponseCode());
				return $e->getMessage();
			} catch (\Exception $e)	{
				$this->updateView($e->getMessage(), ERROR_COWS, 400);
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
			} catch (\CowsAPI\Exceptions\BaseException $e) {
				$this->updateView($e->getMyMessage(), $e->getStatus() , $e->getResponseCode());
				return $e->getMessage();
			} catch (\Exception $e)	{
				$this->updateView($e->getMessage(), ERROR_COWS, 400);
				return $e->getMessage();
			}
			
			$this->updateView($events);
			return $events;
		}
	}
	
	public function POST()	{
		
		if (!$this->serviceFactory->checkSession())	{
			$this->updateView("Invalid session" , ERROR_CAS , 401);
			return "Invalid session";
		}
		try	{
			$params = $this->serviceFactory->buildEventParams();
		} catch (\CowsAPI\Exceptions\BaseException $e) {
			$this->updateView($e->getMyMessage(), $e->getStatus() , $e->getResponseCode());
			return $e->getMessage();
		} catch (\Exception $e)	{
			$this->updateView($e->getMessage(), ERROR_COWS, 400);
			return $e->getMessage();
		}
		try	{
			$newEventId = $this->serviceFactory->createEvent($params);
		} catch (\CowsAPI\Exceptions\BaseException $e) {
			$this->updateView($e->getMyMessage(), $e->getStatus() , $e->getResponseCode());
			return $e->getMessage();
		} catch (\Exception $e)	{
			$this->updateView($e->getMessage(), ERROR_COWS, 400);
			return $e->getMessage();
		}
		
		$this->updateView(array('eventId' => $newEventId));
		return $newEventId;
	}
	
	public function DELETE()	{
		try	{
			if (!$this->serviceFactory->deleteEvent($this->eventId))	{
				$this->updateView("Unable to delete event", ERROR_PARAMETERS, 403);
				return "Unable to delete event";
			}
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