<?php

use CowsAPI\Models\DB\DBWrapper;
use CowsAPI\Models\HTTP\CurlWrapper;
class DataMapperFactoryTest extends \PHPUnit_Framework_TestCase {

	protected function setUp()	{
		$this->object = new \CowsAPI\Models\DataMapperFactory(new DBWrapper(), new CurlWrapper(), null);
	}
	
	public function testClassCreationReal()	{
		$this->assertInstanceOf('\CowsAPI\Models\DataMappers\KeyTable',$this->object->get('KeyTable'));
		$this->assertInstanceOf('\CowsAPI\Models\DataMappers\SessionManager',$this->object->get('SessionManager'));
		$this->assertInstanceOf('\CowsAPI\Models\DataMappers\DocumentGrabber',$this->object->get('DocumentGrabber'));
	}
	
	public function testClassCreationBad()	{
		$this->setExpectedException('\CowsAPI\Exceptions\InvalidClassException');
		$this->object->get('Fake');
	}

}
?>