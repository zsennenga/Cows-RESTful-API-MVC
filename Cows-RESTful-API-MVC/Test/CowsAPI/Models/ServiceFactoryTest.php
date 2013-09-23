<?php
require_once __DIR__ . "../../../../CowsAPI/Data/Config.php";
use CowsAPI\Models\ServiceFactory;
use CowsAPI\Models\DomainObjectFactory;
use CowsAPI\Models\DataMapperFactory;
use CowsAPI\Models\DB\DBWrapper;
use CowsAPI\Utility\URLBuilder;
use CowsAPI\Exceptions\CurlException;
use CowsAPI\Models\HTTP\CurlWrapper;
class ServiceFactoryTest extends \PHPUnit_Framework_TestCase {

	protected function setUp()	{
		
	}
	
	private function getMockForGrab($ret, $params = array())	{
		$stub = $this->getMock('\CowsAPI\Models\ServiceFactory', array('grabAndParse'), array(new DomainObjectFactory(),$this->getDataMapper(), $params, new URLBuilder(), 'its'));
		$stub->expects($this->any())
					->method('grabAndParse')
					->will($this->returnValue($ret));
		return $stub;
	}
	private function getMockForGrabBase($ret, $params = array())	{
		$stub = $this->getMock('\CowsAPI\Models\ServiceFactory', array('grabDocument'), array(new DomainObjectFactory(),$this->getDataMapper(), $params, new URLBuilder(), 'its'));
		$stub->expects($this->any())
		->method('grabDocument')
		->will($this->returnValue($ret));
		return $stub;
	}
	
	private function getMockForBuild($ret, $params = array())	{
		$stub = $this->getMock('\CowsAPI\Models\ServiceFactory', array('getCowsFields'), array(new DomainObjectFactory(),$this->getDataMapper(), $params, new URLBuilder(), 'its'));
		$stub->expects($this->any())
		->method('getCowsFields')
		->will($this->returnValue($ret));
		return $stub;
	}
	
	private function getMockPrivateKey()	{
		$stub = $this->getMock('\CowsAPI\Models\ServiceFactory', array('getPrivateKey'),array(new DomainObjectFactory(),$this->getDataMapper(), array("test"=>"t"), new URLBuilder(), 'its'));
		$stub->expects($this->any())
		->method('getPrivateKey')
		->will($this->returnValue('test'));
		return $stub;
	}

	public function getDataMapper()	{
		return new DataMapperFactory(new DBWrapper(), new CurlWrapper(), null);
	}
	
	public function testParameterBuilding()	{
		$params = array(
				'EventTitle' => 'test',
				'StartDate' => '9/1/2013',
				'EndDate' => '9/1/2013',	
				'StartTime' => "8:00 AM",
				'EndTime' => "9:00 AM",
				'DisplayStartTime' => '8:00 AM',
				'DisplayEndTime' => '9:00 AM',
				'BuildingAndRoom' => '1590_Tilia!1142',
				'Categories' => 'Other',
				'EventTypeName' => 'Maintenance_Other'
		);
		$ret = array("__RequestVerificationToken" => 'blah',
				"ContactName" => 				'blah',
				"ContactPhone" => 				'blah',
				"ContactEmail" => 				'blah',
				"EventStatusName" => 			'blah');
		$obj = $this->getMockForBuild($ret, $params);
		
		$this->assertSame($obj->buildEventParams(), "EventTitle=test&StartDate=9%2F1%2F2013&EndDate=9%2F1%2F2013&StartTime=8%3A00+AM&EndTime=9%3A00+AM&DisplayStartTime=8%3A00+AM&DisplayEndTime=9%3A00+AM&BuildingAndRoom=1590_Tilia%211142&EventTypeName=Maintenance_Other&__RequestVerificationToken=blah&ContactName=blah&ContactPhone=blah&ContactEmail=blah&EventStatusName=blah&siteId=its&Categories=Other");
	
		$params['Categories'] = "Other&test";
		$obj = $this->getMockForBuild($ret, $params);
		$this->assertSame($obj->buildEventParams(), "EventTitle=test&StartDate=9%2F1%2F2013&EndDate=9%2F1%2F2013&StartTime=8%3A00+AM&EndTime=9%3A00+AM&DisplayStartTime=8%3A00+AM&DisplayEndTime=9%3A00+AM&BuildingAndRoom=1590_Tilia%211142&EventTypeName=Maintenance_Other&__RequestVerificationToken=blah&ContactName=blah&ContactPhone=blah&ContactEmail=blah&EventStatusName=blah&siteId=its&Categories=Other&Categories=test");
		
		$this->setExpectedException('CowsAPI\Exceptions\ParameterException');
		unset($params['Categories']);
		$obj = $this->getMockForBuild($ret, $params);
		$obj->buildEventParams();
		
	}
	
	public function testServiceTicket()	{
		$obj = $this->getMockForGrab("blah", array('tgc' => 'blah'));
		
		$this->assertEquals('blah', $obj->getServiceTicket());
		
		$obj = $this->getMockForGrab("blah");
		$this->setExpectedException('CowsAPI\Exceptions\ParameterException');
		$obj->getServiceTicket();
	}
	
	public function testCheckSignature()	{
		$obj = $this->getMockPrivateKey('test');
		
		$this->assertTrue($obj->checkSignature("0123456", "6e8389389567e2068750f6d4195c08900dfd58adb84647a1ebce3b9755b958e8", "GET", "/"));
	   $this->assertFalse($obj->checkSignature("0123456", "6e8389389567e2068750f6d4195c08900dfd58adb84647a1ebce3b9sds755b958e8", "GET", "/"));
	}
	
	public function testGetEventId()	{
		$doc = file_get_contents(__DIR__ . "/../data/eventIdTest1.json");
		$obj = $this->getMockForGrabBase($doc, array(
				'StartDate' => 'blah',
				'EndDate' => 'blah',
				'Categories' => 'blah',
				'EventTitle' => 'Development Team Meeting',
				'BuildingAndRoom' => "1605_Tilia!1162"));
		$this->assertSame(1, $obj->findEventId());
	}
	
	public function getServiceFactory($params = array())	{
		return new ServiceFactory(new DomainObjectFactory(), new DataMapperFactory(new DBWrapper(), new CurlWrapper(), 'test'), $params, new URLBuilder(), 'its');
	}
	
	public function testPrivateKey()	{
		$sf = $this->getServiceFactory();
		$this->assertSame('test', $sf->getPrivateKey());
	}
	
	public function testSetParams()	{
		$sf = $this->getServiceFactory();
		$sf->setParams(array());
	}
	
	public function testDestroySession()	{
		$sf = $this->getServiceFactory();
		$sf->destroySession();
	}
	
	public function testGetEventByID()	{
		$sf = $this->getServiceFactory();
		$out = $sf->getEventById('123894');
		
		$this->assertSame('MTLC Core Meeting', $out['title']);
	}
	
	public function testParseForErrorsNone()	{
		$doc = file_get_contents(__DIR__ . "/../data/noerrors.html");
		$sf = $this->getServiceFactory();
		$sf->parseForErrors($doc);
	}
	
	public function testEvents()	{
		$sf = $this->getServiceFactory(array('timeStart' => 'today', 'timeEnd' => 'tomorrow'));
		$sf->getEvents();  
	}
	
	public function testFullMonty()	{
		$sf = $this->getServiceFactory(array("tgc" => "ENTERAVALIDTGC"));
		$sf->destroySession();
		$ticket = $sf->getServiceTicket();
		$sf->createSession($ticket);
		$sf->setParams(array(
	'EventTitle' => 'test',
	'StartDate' => '10/6/2013',
	'EndDate' => '10/6/2013',	
	'StartTime' => "8:00 AM",
	'EndTime' => "9:00 AM",
	'DisplayStartTime' => '8:00 AM',
	'DisplayEndTime' => '9:00 AM',
	'BuildingAndRoom' => '1590_Tilia!1142',
	'Categories' => 'Other',
	'EventTypeName' => 'Maintenance_Other'
	));
		$params = $sf->buildEventParams();
		$id = $sf->createEvent($params);
		$sf->deleteEvent($id);
		$sf->destroySession();
	}
	
	public function testCheckSession()	{
		$sf = $this->getServiceFactory(array("tgc" => "ENTERAVALIDTGC"));
		$sf->destroySession();
		$this->assertFalse($sf->checkSession());
		$sf->createSession($sf->getServiceTicket());
		$this->assertTrue($sf->checkSession());
		$sf->destroySession();
	}
}
?>