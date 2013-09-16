<?php

use CowsAPI\Models\DomainObjects\SingleEventParser;

class SingleEventParserTest extends \PHPUnit_Framework_TestCase {

	protected $object;
	
	protected function setUp()	{
		$this->object = new SingleEventParser();
	}

	public function testDocumentParse()	{
		$doc = file_get_contents(__DIR__ . "/../../data/SingleEvent1.html");
		$out = $this->object->parse($doc);
		
		$this->assertSame("CWEE", $out['category']);
		$this->assertSame("8:00 AM", $out['startTime']);
		$this->assertSame("1103", $out['room']);
		$this->assertSame("1605_Tilia", $out['building']);
		$this->assertSame("9:00 AM", $out['endTime']);
		$this->assertSame("9/1/2013", $out['endDate']);
		$this->assertSame("9/1/2013", $out['startDate']);
		$this->assertSame("test2", $out['title']);
	}
	
	public function testFailParse()	{
		$this->setExpectedException('\CowsAPI\Exceptions\InvalidDocumentException');
		$this->object->parse("");
	}
}
?>