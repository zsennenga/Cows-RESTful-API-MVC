<?php

use CowsAPI\Models\DomainObjects\RssParser;
class RssParserTest extends PHPUnit_Framework_TestCase {

	protected $object;
	
	protected function setUp()	{
		$this->object = new RssParser();
	}

	public function testTimeBoundException1()	{
		$this->setExpectedException("\CowsAPI\Exceptions\ParameterException");
		$this->object->setTimeBound("asdasdasd", "blah");
	}
	
	public function testTimeBoundException2()	{
		$this->setExpectedException("\CowsAPI\Exceptions\ParameterException");
		$this->object->setTimeBound("+2 hours", "+1 hour");
	}
	
	public function testTimeBound()	{
		$this->object->setTimeBound("+1 hour", "+2 hours");
	}
	
	public function testRssParse()	{
		$doc = file_get_contents(__DIR__ . "\..\..\data\RSSTest.xml");
		$out = $this->object->parse($doc);
		$this->assertSame("Financial Mtg", $out[0]['title']);
	}
	public function testRssParse2()	{
		$doc = file_get_contents(__DIR__ . "\..\..\data\RSSTest2.xml");
		$out = $this->object->parse($doc);
		$this->assertSame("test", $out[0]['description']);
	}
	
	public function testRssParseTime()	{
		$doc = file_get_contents(__DIR__ . "\..\..\data\RSSTest.xml");
		$this->object->setTimeBound("9/1/2013", "9/15/2013");
		$out = $this->object->parse($doc);
		$this->assertSame(array(), $out);
	}
	
	public function testRssParseTime2()	{
		$doc = file_get_contents(__DIR__ . "\..\..\data\RSSTest.xml");
		$this->object->setTimeBound("6/1/2013", "9/15/2013");
		$out = $this->object->parse($doc);
		$this->assertSame("Financial Mtg", $out[0]['title']);
	}
	
}
?>