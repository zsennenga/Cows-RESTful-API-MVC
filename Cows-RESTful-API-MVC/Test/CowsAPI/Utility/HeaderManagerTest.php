<?php

use CowsAPI\Utility\HeaderManager;
class HeaderManagerTest extends PHPUnit_Framework_TestCase {

	protected $object;
	
	protected function setUp()	{
		$this->object = new HeaderManager();
	}
	
	public function testGetResponseClass()	{
		$this->assertSame("JSON", $this->object->getResponseClass());
	}
	
	public function testParseAuth()	{
		$this->assertFalse($this->object->parseAuth());
		$this->object->setHeaders(array('Authorization' => "a|b|c"));
		$this->assertTrue($this->object->parseAuth());
		$this->assertSame("a", $this->object->getPublicKey());
		$this->assertSame("b", $this->object->getTimeStamp());
		$this->assertSame("c", $this->object->getSignature());
	}

}
?>