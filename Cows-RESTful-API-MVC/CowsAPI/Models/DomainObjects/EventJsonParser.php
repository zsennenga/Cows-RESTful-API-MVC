<?php

namespace CowsAPI\Models\DomainObjects;

use Symfony\Component\Process\Exception\InvalidArgumentException;
use CowsAPI\Exceptions\CowsException;
use CowsAPI\Exceptions\ParameterException;
use CowsAPI\Exceptions\InvalidDocumentException;
class EventJsonParser extends GenericParser {
	
	public function parse($doc)	{
		$out = json_decode($out,true);
		
		if (!is_array($out))	{
			throw new InvalidDocumentException(ERROR_PARAMETERS,"Invalid json given to find eventId", 500);
		}
		//Look for a matching title/room
		foreach ($out['e'] as $event)	{
			if ($event[0]['t'] == $params['EventTitle'] &&
			$event[0]['l'] == "R" . $params['BuildingAndRoom'])	{
				return $event[0]['i'];
			}
		}
		throw new CowsException(ERROR_COWS,"Could not find eventId", 500);
	}

}
?>