<?php
/**
 * eventSequence.php
 * 
 * Contains the class eventSequence and associated functions
 */
namespace CowsAPI\Models\DomainObjects\RSS;

require_once("CowsRSS.php");
require_once("Event.php");
/**
 * eventSequence
 * 
 * Contains and manages a sequence of event objects
 * 
 * @author its-zach
 *
 */
class EventSequence	{
	/**
	 * eventList
	 * 
	 * array containing all the events referenced by this sequence
	 * 
	 * @var array
	 */
	private $eventList;
	/**
	 * displayPast
	 * 
	 * boolean, defaulting to false, which specifies whether or not events in the past are to be outputed from toString()
	 * 
	 * @var boolean
	 */
	private $displayPast;
	
	/**
	 * __construct
	 * 
	 * Builds a sequence of events in $eventList
	 * 
	 * @param array $eventArray
	 */
	function __construct($eventArray)	{
		$this->eventList = array();
		foreach ($eventArray as $event)	{
			array_push($this->eventList,new Event($event));
		}
		usort($this->eventList,'\CowsAPI\Models\DomainObjects\RSS\EventSequence::doSort');
		$this->displayPast = false;
	}
	/**
	 * createSequenceFromArrayTimeBounded
	 * 
	 * Creates an eventSequence with all events that are occuring between $startTime and $endTime.
	 * The strings should be in the format of a date, such as MM/DD/YY or MM/DD/YYYY or in the form of +X Hours or -X Hours
	 * 
	 * @param array $es
	 * @param string $startTime
	 * @param string $endTime
	 */
	public static function createSequenceFromArrayTimeBounded($eventArray, $startTime, $endTime)	{
		$eventSource = new EventSequence($eventArray);
		$eventOut = new EventSequence(array());
		foreach($eventSource->getList() as $event)	{
			if ($event->getStartTimestamp() >= $startTime
					&& $event->getEndTimestamp() <= $endTime)	{
				$eventOut->addEvent($event);
			}
		}
		$eventOut->setdisplayPast(false);
		return $eventOut;
	}
	/**
	 * createSequenceFromSequenceTimeBounded
	 *
	 * Creates an eventSequence with all events that are occuring between $startTime and $endTime.
	 * The strings should be in the format of a date, such as MM/DD/YY or MM/DD/YYYY or in the form of +X Hours or -X Hours
	 *
	 * @param eventSequence $es
	 * @param string $startTime
	 * @param string $endTime
	 * @codeCoverageIgnore
	 */
	public static function createSequenceFromSequenceTimeBounded($eventArray, $startTime, $endTime)	{
		$eventOut = new EventSequence(array());
		foreach($eventArray->getList() as $event)	{
			if ($event->getStartTimestamp() >= strtotime($startTime)
					&& $event->getEndTimestamp() <= strtotime($endTime))	{
					$eventOut->addEvent($event);
			}
			
		}
		$eventOut->setdisplayPast(false);
		return $eventOut;
	}
	/**
	 * 
	 * setDisplayPast
	 * 
	 * sets the variable DisplayPast
	 * 
	 * @param boolean $bool
	 */
	function setDisplayPast($bool)	{
		$this->displayPast = $bool;
	}
	/**
	 * toArray
	 * 
	 * Returns an array containing all of the events described by this eventSequence
	 */
	function toArray()	{
		$retArray = array();
		foreach ($this->eventList as $event)	{
			array_push($retArray,$event->toArray());
		}
		return $retArray;
	}
	/**
	 * getList
	 * 
	 * returns the internal eventList
	 * 
	 * @return array
	 */
	function getList()	{
		return $this->eventList;
	}
	/**
	 * 
	 * @param Event $event
	 */
	function addEvent($event)	{
		array_push($this->eventList,$event);
	}
	/**
	 *
	 * doSort
	 *
	 * Actual sort function used by uasort. Sorts by date and time.
	 *
	 * 
	 * @codeCoverageIgnore
	 * @param event $a
	 * @param event $b
	 * @return number
	 */
	public static function doSort($a,$b)	{
		if ($a->getStartTimestamp() == $b->getStartTimestamp()) return 0;
		if ($a->getStartTimestamp() > $b->getStartTimestamp())	{
			return 1;
		}
		else	{
			return -1;
		}
	}
}

?>