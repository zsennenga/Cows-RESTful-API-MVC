<?php

use CowsAPI\Models\DB\DBWrapper;
use CowsAPI\Models\HTTP\CurlWrapper;
class DomainObjectFactoryTest extends \PHPUnit_Framework_TestCase {

	protected function setUp()	{
		$this->object = new \CowsAPI\Models\DomainObjectFactory();
	}
	
	public function testClassCreationReal()	{
		$this->assertInstanceOf('\CowsAPI\Models\DomainObjects\CasParser',$this->object->get('CasParser'));
		$this->assertInstanceOf('\CowsAPI\Models\DomainObjects\AuthChecker',$this->object->get('AuthChecker'));
		$this->assertInstanceOf('\CowsAPI\Models\DomainObjects\FieldParser',$this->object->get('FieldParser'));
	}
	
	public function testClassCreationBad()	{
		$this->setExpectedException('\CowsAPI\Exceptions\InvalidClassException');
		$this->object->get('Fake');
	}

}
?>