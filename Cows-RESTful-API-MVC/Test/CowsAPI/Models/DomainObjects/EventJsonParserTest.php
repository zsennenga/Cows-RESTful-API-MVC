<?php

use CowsAPI\Models\DomainObjects\EventJsonParser;
class EventJsonParserTest extends PHPUnit_Framework_TestCase {

	protected $object;
	
	protected function setUp()	{
		$this->object = new EventJsonParser();
	}
	
	 
	public function testFindEventId()	{
		$doc = file_get_contents(__DIR__ . "/../../data/eventIdTest1.json");
		$this->object->setBuildingRoom("1605_Tilia!1162");
		$this->object->setEventTitle("Development Team Meeting");
		$this->assertSame(1,$this->object->parse($doc));
		
		$this->object->setBuildingRoom("1605_Tilia!11d62");
		$this->object->setEventTitle("Development Team Meeting");
		$this->setExpectedException("\CowsAPI\Exceptions\CowsException");
		$this->object->parse($doc);
		
	}
	
	public function testInvalidDoc()	{
		$this->setExpectedException("\CowsAPI\Exceptions\InvalidDocumentException");
		$this->object->parse("[");
	}
}
?>