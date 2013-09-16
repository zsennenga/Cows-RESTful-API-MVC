<?php

use CowsAPI\Models\DomainObjects\CasParser;
class CasParserTest extends \PHPUnit_Framework_TestCase {

	protected $object;
	
	protected function setUp()	{
		$this->object = new CasParser();
	}
	
	public function testSuccessParse()	{
		$doc = file_get_contents(__DIR__ . "\..\..\data\serviceTicketTest1.xml");
		$out = $this->object->parse($doc);
		
		$this->assertSame($out, "ticket");
	}
	
	public function testFailParse()	{
		$doc = file_get_contents(__DIR__ . "/../../data/serviceTicketTest2.xml");
		$this->setExpectedException('\CowsAPI\Exceptions\CasException');
		$this->object->parse($doc);
	}
}
?>