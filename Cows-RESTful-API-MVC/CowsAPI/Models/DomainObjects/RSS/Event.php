<?php
/**
 * event.php
 * 
 * File containing the class event and associated functions
 * 
 */

namespace CowsAPI\Models\DomainObjects\RSS;

require_once('EventSequence.php');
/**
 * 
 * event
 * 
 * Class that contains event data. Is aggregated by an eventSequence.
 * 
 * @author Zachary Ennenga
 *
 */
class Event	{
	/**
	 * Event Title
	 * @var string
	 */
	private $title;
	/**
	 * Event Start Time
	 * @var string
	 */
	private $startTime;
	/**
	 * Event End Time
	 * @var string
	 */
	private $endTime;
	/**
	 * Event Date in the format MM/DD/YY
	 * @var string
	 */
	private $date;
	/**
	 * Event Description
	 * @var string
	 */
	private $description;
	/**
	 * Event location in the format BLDG, Room ####
	 * @var string
	 */
	private $location;
	
	/**
	 * __construct
	 * 
	 * Constructs an event.
	 * 
	 * @param array $event
	 */
	function __construct($event)	{
		date_default_timezone_set('America/Los_Angeles');
		$this->setTitle($event['Title'][0]);
		//Sometimes there are two descriptions, one of which is blank.
		foreach($event['Description'] as $des)	{
			if ($des != "") {
				$this->setDescription($des);
			}
		}
		$this->setTime($event['Starts'][0],$event['Ends'][0]);
		$this->setDate($event['Starts'][0]);
		$this->setLocation($event['Location'][0]);
	}
	/**
	 * isPast
	 * 
	 * Checks if a event occured in the past. 
	 * 
	 * @codeCoverageIgnore
	 * @return boolean
	 */
	function isPast()	{
		return time() > strtotime($this->date . " ". $this->endTime);
	}
	/**
	 * setTitle
	 * 
	 * Sets the Event's title
	 * 
	 * @param string $str
	 */
	function setTitle($str)	{
		$this->title = $str;
	}
	/**
	 * setDescription
	 * 
	 * Sets the event's description
	 * 
	 * @param string $str
	 */
	function setDescription($str)	{
		$this->description = $this->cowsDecode($str);
	}
	/**
	 * cowsDecode
	 *
	 * Descriptions from Cows tend to be doubly encoded and have &nbsp;s hanging around in them. This function parses that all out.
	 *
	 * @param string $str
	 * @return string
	 */
	function cowsDecode($str)	{
		$str = htmlspecialchars_decode($str);
		$str = htmlspecialchars_decode($str);
		$str = strip_tags($str);
		$str = str_replace('&nbsp;', '', $str);
		return $str;
	}
	
	/**
	 * setTime
	 * 
	 * Parses the start/end times and sets them.
	 * 
	 * @param string $start
	 * @param string $end
	 */
	function setTime($start,$end)	{
		$times = array($start,$end);
		$timesFinal = array();
		foreach($times as $str)	{
			$date = explode(" ",$str);
			$str = str_replace($date[0],"",$str);
			$str = str_replace(":00 "," ",$str);
			array_push($timesFinal,$str);
		}
		$this->startTime = $timesFinal[0];
		$this->endTime = $timesFinal[1];
	}
	/**
	 * setDate
	 * 
	 * Parses the date from a start/end time string
	 * 
	 * @param string $str
	 */
	function setDate($str)	{
		$date = explode(" ",$str);
		$this->date = $date[0];
	}
	/**
	 * setLocation
	 * 
	 * Parses out the location of an event from the cows value
	 * 
	 * @param string $str
	 */
	function setLocation($str)	{
		$val = explode(" ",$str);
		$this->location = str_replace("_"," ",$val[0]) . ", Room " . $val[1];
	}

	/**
	 * toArray
	 * 
	 * Creates array with details of a single event
	 * 
	 * @return array
	 */
	function toArray()	{
		$out = array();
		$out['title'] = $this->title;
		$out['startTime']  = $this->startTime;
		$out['endTime']  = $this->endTime;
		$out['date']  = $this->date;
		$out['description']  = $this->description;
		$out['location']  = $this->location;
		return $out;
	}
	/**
	 * Getters
	 * 
	 * A collection of getters for the private variables
	 * 
	 * 
	 * @codeCoverageIgnore
	 */
	function getStartTimestamp() {
		return strtotime($this->date . " " . $this->startTime);
	}
	/**
	 * @codeCoverageIgnore
	 */
	function getEndTimestamp() {
		return strtotime($this->date . " " . $this->endTime);
	}
	/**
	 * @codeCoverageIgnore
	 */
	function getStartTime()	{
		return $this->startTime;
	}
	/**
	 * @codeCoverageIgnore
	 */
	function getEndTime()	{
		return $this->endTime;
	}
	/**
	 * @codeCoverageIgnore
	 */
	function getDate()	{
		return $this->date;
	}
	/**
	 * @codeCoverageIgnore
	 */
	function getTitle()	{
		return $this->title;
	}
	/**
	 * @codeCoverageIgnore
	 */
	function getDescription(){
		return $this->description;
	}
	/**
	 * @codeCoverageIgnore
	 */
	function getLocation()	{
		return $this->location;
	}
}
?>