<?php

namespace CowsAPI\Models\DomainObjects;

use CowsAPI\Models\DomainObjects\RSS\CowsRss;
use CowsAPI\Models\DomainObjects\RSS\EventSequence;
class RssParser extends GenericParser  {
	
	protected $timeStart;
	protected $timeEnd;
	
	public function setTimeBound($start, $end)	{
		if (strtotime($start) === false || strtotime($end) === false)	{
			throw new \CowsAPI\Exceptions\ParameterException(ERROR_PARAMETERS, "Invalid time range", 400);
		}
		else if (strtotime($start) > strtotime($end))	{
			throw new \CowsAPI\Exceptions\ParameterException(ERROR_PARAMETERS, "Start time must be before End time", 400);
		}
		$this->timeStart = $start;
		$this->timeEnd = $end;
	}
	public function parse($doc)	{
		$cows = new CowsRss();
		$cows->setFeedData($doc);
		$data = $cows->getData();
		if (isset($this->timeStart))	{
			$start = strtotime($this->timeStart, time());
			$end = strtotime($this->timeEnd, time());
			$sequence = EventSequence::createSequenceFromArrayTimeBounded($data,$start,$end);
		}
		else	{
			$sequence = new EventSequence($data);
		}
		return $sequence->toArray();
	}

}
?>