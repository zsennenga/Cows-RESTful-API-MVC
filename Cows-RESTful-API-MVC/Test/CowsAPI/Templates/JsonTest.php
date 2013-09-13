<?php

class JsonTest extends \PHPUnit_Framework_TestCase {
	
	protected $object;
	
	protected function setUp()
	{
		$this->object = new \CowsAPI\Templates\Json();
	}
	
	private function buildResponse($s, $m)	{
		return array(
				"code" => $s,
				"message" => $m
		);
	}
	
	public function testStringParse()	{
		$this->assertSame(json_encode($this->buildResponse(0, "Test")), $this->object->parse(0, "Test"));
	}
	
	public function testStringParseFail()	{
		$this->assertFalse(json_encode($this->buildResponse(1, "Test")) == $this->object->parse(0, "Test"));
	}
	
	public function testArrayParse()	{
		$this->assertSame(json_encode($this->buildResponse(1, array("test" => "test"))), $this->object->parse(1, array("test" => "test")));
	}
	
	public function testArrayParseFail()	{
		$this->assertFalse(json_encode($this->buildResponse(1, array("test" => "testF"))) == $this->object->parse(1, array("test" => "test")));
	}
	
	public function testCallback()	{
		$_GET['callback'] = '1';

		$this->object = new \CowsAPI\Templates\Json();
		
		$this->assertSame("1(" . json_encode($this->buildResponse(1, "Test")).")", $this->object->parse(1, "Test"));
		
		$this->object = new \CowsAPI\Templates\Json();
		
	}
	
	public function testCallbackFail()	{
		$_GET['callback'] = '1';
		
		$this->object = new \CowsAPI\Templates\Json();
		
		$this->assertFalse(json_encode($this->buildResponse(1, "Test")) == $this->object->parse(1, "Test"));
		
		$this->object = new \CowsAPI\Templates\Json();
	}

}
?>