<?php

namespace CowsAPI\Models\DomainObjects;

use Symfony\Component\Process\Exception\InvalidArgumentException;
use CowsAPI\Exceptions\CowsException;
use CowsAPI\Exceptions\ParameterException;
use CowsAPI\Exceptions\InvalidDocumentException;
class EventJsonParser extends GenericParser {
	
	protected $eventTitle;
	protected $buildingAndRoom;
	
	public function setEventTitle($e)	{
		$this->eventTitle = $e;
	}
	
	public function setBuildingRoom($b)	{
		$this->buildingAndRoom = $b;
	}
	/**
	 * Finds the event id for the given title and event/room
	 */
	public function parse($doc)	{
		$out = json_decode($doc,true);
		
		if (!is_array($out))	{
			throw new InvalidDocumentException(ERROR_PARAMETERS,"Invalid json given to find eventId. Event was created without error. Check Cows.", 500);
		}
		//Look for a matching title/room
		foreach ($out['e'] as $event)	{
			if ($event[0]['t'] == $this->eventTitle &&
			$event[0]['l'] == "R" . $this->buildingAndRoom)	{
				return $event[0]['i'];
			}
		}
		throw new CowsException(ERROR_COWS,"Could not find event id. Event was created without error. Check COWS.", 500);
	}

}
?>