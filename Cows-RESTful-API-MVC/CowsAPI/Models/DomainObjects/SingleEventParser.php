<?php

namespace CowsAPI\Models\DomainObjects;

class SingleEventParser extends HTMLParser {

	protected $doc;
	
	public function parse($doc)	{
		$retArray = array(
				"title" => "",
				"category" => "",
				"startDate" => "",
				"endDate" => "",
				"startTime" => "",
				"endTime" => "",
				"building" => "",
				"room" => ""
		);
		
		$this->setupDoc($doc);
		$retArray['category'] 	= 	$this->getField('//div[@class="EventTypeName"]/div[@class="display-field"]');
		$retArray['startDate'] 	= 	$this->getField('//div[@class="StartDate"]/div[@class="display-field"]/span[@class="date"]');
		$retArray['startTime'] 	= 	$this->getField('//div[@class="StartDate"]/div[@class="display-field"]/span[@class="time"]');
		$retArray['endDate']	= 	$this->getField('//div[@class="EndDate"]/div[@class="display-field"]/span[@class="date"]');
		$retArray['endTime']	= 	$this->getField('//div[@class="EndDate"]/div[@class="display-field"]/span[@class="time"]');
		$retArray['building'] 	= 	$this->getField('//div[@class="BuildingName"]/div[@class="display-field"]');
		$retArray['room'] 		= 	$this->getField('//div[@class="RoomName"]/div[@class="display-field"]');
		$retArray['title'] 		= 	trim($this->getField('//div[@id="event-dialog"]', "title"));
		
		return $retArray;
	}

}
?>