<?php

use CowsAPI\Models\ServiceFactory;
use CowsAPI\Utility\URLBuilder;
use CowsAPI\Models\DataMapperFactory;
use CowsAPI\Models\DB\DBWrapper;
use CowsAPI\Models\HTTP\CurlWrapper;
use CowsAPI\Models\DomainObjectFactory;
require_once __DIR__ . "../../../../CowsAPI/Data/Config.php";

class EventTest extends \PHPUnit_Framework_TestCase {
	
	protected $view;
	
	protected function setUp()
	{
		$log = $this->getMockBuilder('\CowsAPI\Utility\Log')
                     ->disableOriginalConstructor()
                     ->getMock();
		$this->view = $this->getMock("\\CowsAPI\\Views\\Event", null, array($log, null));
	}
	
	public function testGetEventIdNotFound()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
						->disableOriginalConstructor()
						->setMethods(array('getEvents', 'getEventById'))
						->getMock();
		$stub->expects($this->any())
				->method('getEventById')
				->will($this->returnValue(null));
		
		$controller = new \CowsAPI\Controllers\Event($this->view, 1, $stub);
		$this->assertSame("Event Not Found", $controller->GET());
	}
	
	public function testGetEventIdFound()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
						->disableOriginalConstructor()
						->setMethods(array('getEvents', 'getEventById'))
						->getMock();
		$stub->expects($this->any())
				->method('getEventById')
				->will($this->returnValue(array("test" => "test")));
		
		$controller = new \CowsAPI\Controllers\Event($this->view, 1, $stub);
		$this->assertSame(array("test" => "test"), $controller->GET());
	}
	
	public function testGetEventIdException()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
						->disableOriginalConstructor()
						->setMethods(array('getEvents', 'getEventById'))
						->getMock();
		$stub->expects($this->any())
				->method('getEventById')
				->will($this->throwException(new \Exception("Test")));
		
		$controller = new \CowsAPI\Controllers\Event($this->view, 1, $stub);
		$this->assertSame("Test", $controller->GET());
	}
	
	public function testGetEventIdException2()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('getEvents', 'getEventById'))
		->getMock();
		$stub->expects($this->any())
		->method('getEventById')
		->will($this->throwException(new \CowsAPI\Exceptions\BaseException(1,"Test",1)));
	
		$controller = new \CowsAPI\Controllers\Event($this->view, 1, $stub);
		$this->assertSame("Test", $controller->GET());
	}
	
	public function testGetEventsException()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('getEvents', 'getEventById'))
		->getMock();
		$stub->expects($this->any())
		->method('getEvents')
		->will($this->throwException(new \CowsAPI\Exceptions\BaseException(1,"Test",1)));
	
		$controller = new \CowsAPI\Controllers\Event($this->view, null, $stub);
		$this->assertSame("Test", $controller->GET());
	}
	
	public function testGetEventsException2()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
						->disableOriginalConstructor()
						->setMethods(array('getEvents', 'getEventById'))
						->getMock();
		$stub->expects($this->any())
				->method('getEvents')
				->will($this->throwException(new \Exception("Test")));
		
		$controller = new \CowsAPI\Controllers\Event($this->view, null, $stub);
		$this->assertSame("Test", $controller->GET());
	}
	
	public function testGetEventsFound()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
						->disableOriginalConstructor()
						->setMethods(array('getEvents', 'getEventById'))
						->getMock();
		$stub->expects($this->any())
				->method('getEvents')
				->will($this->returnValue(array("test" => "test")));
		
		$controller = new \CowsAPI\Controllers\Event($this->view, null, $stub);
		$this->assertSame(array("test" => "test"), $controller->GET());
	}
	
	public function testGetEventIdNotFoundFail()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('getEvents', 'getEventById'))
		->getMock();
		$stub->expects($this->any())
		->method('getEventById')
		->will($this->returnValue(null));
	
		$controller = new \CowsAPI\Controllers\Event($this->view, 1, $stub);
		$this->assertFalse("Event Not Founda" == $controller->GET());
	}
	
	public function testGetEventIdFoundFail()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('getEvents', 'getEventById'))
		->getMock();
		$stub->expects($this->any())
		->method('getEventById')
		->will($this->returnValue(array("test" => "test")));
	
		$controller = new \CowsAPI\Controllers\Event($this->view, 1, $stub);
		$this->assertFalse(array("tesat" => "test") == $controller->GET());
	}
	
	public function testGetEventIdExceptionFail()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('getEvents', 'getEventById'))
		->getMock();
		$stub->expects($this->any())
		->method('getEventById')
		->will($this->throwException(new \Exception("Test")));
	
		$controller = new \CowsAPI\Controllers\Event($this->view, 1, $stub);
		$this->assertFalse("Tesat" == $controller->GET());
	}
	
	public function testGetEventsExceptionFail()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('getEvents', 'getEventById'))
		->getMock();
		$stub->expects($this->any())
		->method('getEvents')
		->will($this->throwException(new \Exception("Test")));
	
		$controller = new \CowsAPI\Controllers\Event($this->view, null, $stub);
		$this->assertFalse("Testa" == $controller->GET());
	}
	
	public function testGetEventsFoundFail()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('getEvents', 'getEventById'))
		->getMock();
		$stub->expects($this->any())
		->method('getEvents')
		->will($this->returnValue(array("test" => "test")));
	
		$controller = new \CowsAPI\Controllers\Event($this->view, null, $stub);
		$this->assertFalse(array("test" => "atest") == $controller->GET());
	}

	public function testPostInvalidSession()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('checkSession', 'buildEventParams', 'createEvent'))
		->getMock();
		$stub->expects($this->any())
		->method('checkSession')
		->will($this->returnValue(false));
		
		$controller = new \CowsAPI\Controllers\Event($this->view, null, $stub);
		
		$this->assertTrue("Invalid session" == $controller->POST());
		
	}
	
	public function testPostInvalidSessionFail()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('checkSession', 'buildEventParams', 'createEvent'))
		->getMock();
		$stub->expects($this->any())
		->method('checkSession')
		->will($this->returnValue(false));
	
		$controller = new \CowsAPI\Controllers\Event($this->view, null, $stub);
	
		$this->assertFalse("Isnvalid session" == $controller->POST());
	
	}
	
	public function testPostBuildParamsException()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('checkSession', 'buildEventParams', 'createEvent'))
		->getMock();
		$stub->expects($this->any())
		->method('checkSession')
		->will($this->returnValue(true));
		$stub->expects($this->any())
		->method('buildEventParams')
		->will($this->throwException(new \Exception("Test")));
		
		$controller = new \CowsAPI\Controllers\Event($this->view, null, $stub);
		$this->assertSame("Test", $controller->POST());
	}
	
	public function testPostBuildParamsException2()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('checkSession', 'buildEventParams', 'createEvent'))
		->getMock();
		$stub->expects($this->any())
		->method('checkSession')
		->will($this->returnValue(true));
		$stub->expects($this->any())
		->method('buildEventParams')
		->will($this->throwException(new \CowsAPI\Exceptions\BaseException(1,"Test",1)));
	
		$controller = new \CowsAPI\Controllers\Event($this->view, null, $stub);
		$this->assertSame("Test", $controller->POST());
	}
	
	public function testPostBuildParamsExceptionFail()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('checkSession', 'buildEventParams', 'createEvent'))
		->getMock();
		$stub->expects($this->any())
		->method('checkSession')
		->will($this->returnValue(true));
		$stub->expects($this->any())
		->method('buildEventParams')
		->will($this->throwException(new \Exception("Test")));
	
		$controller = new \CowsAPI\Controllers\Event($this->view, null, $stub);
		$this->assertFalse("Testa" == $controller->POST());
	}
	
	public function testPostCreateEventException()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('checkSession', 'buildEventParams', 'createEvent'))
		->getMock();
		$stub->expects($this->any())
		->method('checkSession')
		->will($this->returnValue(true));
		$stub->expects($this->any())
		->method('createEvent')
		->will($this->throwException(new \Exception("Test")));
		
		$controller = new \CowsAPI\Controllers\Event($this->view, null, $stub);
		$this->assertSame("Test", $controller->POST());
	}
	public function testPostCreateEventException2()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('checkSession', 'buildEventParams', 'createEvent'))
		->getMock();
		$stub->expects($this->any())
		->method('checkSession')
		->will($this->returnValue(true));
		$stub->expects($this->any())
		->method('createEvent')
		->will($this->throwException(new \CowsAPI\Exceptions\BaseException(1,"Test",1)));
	
		$controller = new \CowsAPI\Controllers\Event($this->view, null, $stub);
		$this->assertSame("Test", $controller->POST());
	}
	
	public function testPostCreateEventExceptionFail()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('checkSession', 'buildEventParams', 'createEvent'))
		->getMock();
		$stub->expects($this->any())
		->method('checkSession')
		->will($this->returnValue(true));
		$stub->expects($this->any())
		->method('createEvent')
		->will($this->throwException(new \Exception("Test")));
	
		$controller = new \CowsAPI\Controllers\Event($this->view, null, $stub);
		$this->assertFalse("Testa" == $controller->POST());
	}
	
	public function testPostSuccess()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('checkSession', 'buildEventParams', 'createEvent'))
		->getMock();
		$stub->expects($this->any())
		->method('checkSession')
		->will($this->returnValue(true));
		$stub->expects($this->any())
		->method('createEvent')
		->will($this->returnValue("1"));
		
		$controller = new \CowsAPI\Controllers\Event($this->view, 1, $stub);
		$this->assertTrue("1" == $controller->POST());
	}
	
	public function testPostSuccessFail()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('checkSession', 'buildEventParams', 'createEvent'))
		->getMock();
		$stub->expects($this->any())
		->method('checkSession')
		->will($this->returnValue(true));
		$stub->expects($this->any())
		->method('createEvent')
		->will($this->returnValue("1"));
		
		$controller = new \CowsAPI\Controllers\Event($this->view, 1, $stub);
		$this->assertFalse("2" == $controller->POST());
	}
	
	public function testDeleteFail()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('deleteEvent'))
		->getMock();
		$stub->expects($this->any())
		->method('deleteEvent')
		->will($this->returnValue(false));
		
		$controller = new \CowsAPI\Controllers\Event($this->view, 1, $stub);
		$this->assertTrue("Unable to delete event" == $controller->DELETE());
	}
	public function testDeleteFailTestFail()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('deleteEvent'))
		->getMock();
		$stub->expects($this->any())
		->method('deleteEvent')
		->will($this->returnValue(false));
	
		$controller = new \CowsAPI\Controllers\Event($this->view, 1, $stub);
		$this->assertFalse("Unable to delete  event" == $controller->DELETE());
	}
	
	public function testDeleteSuccess()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('deleteEvent'))
		->getMock();
		$stub->expects($this->any())
		->method('deleteEvent')
		->will($this->returnValue(true));
		
		$controller = new \CowsAPI\Controllers\Event($this->view, 1, $stub);
		$this->assertTrue("" == $controller->DELETE());
	}
	
	public function testDeleteException()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('deleteEvent'))
		->getMock();
		$stub->expects($this->any())
		->method('deleteEvent')
		->will($this->throwException(new \Exception("Test")));
	
		$controller = new \CowsAPI\Controllers\Event($this->view, 1, $stub);
		$this->assertTrue("Test" == $controller->DELETE());
	}
	
	public function testDeleteExceptionFail()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('deleteEvent'))
		->getMock();
		$stub->expects($this->any())
		->method('deleteEvent')
		->will($this->throwException(new \Exception("Test")));
	
		$controller = new \CowsAPI\Controllers\Event($this->view, 1, $stub);
		$this->assertFalse("Unable to delete  event" == $controller->DELETE());
	}
	
	public function testDeleteExceptionFail2()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('deleteEvent'))
		->getMock();
		$stub->expects($this->any())
		->method('deleteEvent')
		->will($this->throwException(new \CowsAPI\Exceptions\BaseException(1,"Test",1)));
	
		$controller = new \CowsAPI\Controllers\Event($this->view, 1, $stub);
		$this->assertFalse("Unable to delete  event" == $controller->DELETE());
	}
	
	public function testDeleteSuccessTestFail()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('deleteEvent'))
		->getMock();
		$stub->expects($this->any())
		->method('deleteEvent')
		->will($this->returnValue(true));
	
		$controller = new \CowsAPI\Controllers\Event($this->view, 1, $stub);
		$this->assertFalse("Unable to delete  event" == $controller->DELETE());
	}
	
	public function testAuthCows()	{
		$stub = new ServiceFactory(new DomainObjectFactory(), new DataMapperFactory(new DBWrapper(), new CurlWrapper(), 'test'), array(), new URLBuilder(), 'its');
		
		$controller = new \CowsAPI\Controllers\Event($this->view, 1, $stub);
		$controller->authCows();
	}
}
?>