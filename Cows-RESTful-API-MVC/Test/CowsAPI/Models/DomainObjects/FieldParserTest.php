<?php

use CowsAPI\Models\DomainObjects\FieldParser;
class FieldParserTest extends PHPUnit_Framework_TestCase{

	protected $object;
	
	protected function setUp()	{
		$this->object = new FieldParser();
		$doc = file_get_contents(__DIR__ . "/../../data/CowsFieldParserTest.html");
		$this->object->parse($doc);
	}
	
	public function testParse()	{
		
		$this->assertSame("zennenga@ucdavis.edu", $this->object->getNodeValue("ContactEmail"));
		$this->assertSame("Zachary Ennenga", $this->object->getNodeValue("ContactName"));
	}
	
	public function testException()	{
		$this->setExpectedException("\CowsAPI\Exceptions\InvalidDocumentException");
		$this->object->getNodeValue("notafield");
	}

}
?>