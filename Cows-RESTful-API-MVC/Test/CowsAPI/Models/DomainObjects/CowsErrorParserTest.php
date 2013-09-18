<?php

use CowsAPI\Models\DomainObjects\CowsErrorParser;
class CowsErrorParserTest extends PHPUnit_Framework_TestCase {

	protected $object;
	
	protected function setUp()	{
		$this->object = new CowsErrorParser();
	}

	public function testFindErrorEvent()	{
		$doc = file_get_contents(__DIR__ . "\..\..\data\cowsErrorTest1.html");
		
		$this->setExpectedException("\CowsAPI\Exceptions\CowsException");
		$this->object->parse($doc);
	}
	
	public function testFindErrorUnexpected()	{
		$doc = file_get_contents(__DIR__ . "\..\..\data\cowsErrorTest2.html");
	
		$this->setExpectedException("\CowsAPI\Exceptions\CowsException");
		$this->object->parse($doc);
	}
	
	public function testNoFindError()	{
		$doc = file_get_contents(__DIR__ . "\..\..\data\cowsErrorTest3.html");
		
		$this->assertTrue($this->object->parse($doc));
	}
}
?>