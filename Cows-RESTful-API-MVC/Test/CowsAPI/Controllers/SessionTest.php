<?php

require_once __DIR__ . "../../../../CowsAPI/Data/Config.php";

class SessionTest extends \PHPUnit_Framework_TestCase {

	protected $log;
	
	protected function setUp()
	{
		$log = $this->getMockBuilder('\CowsAPI\Utility\Log')
		->disableOriginalConstructor()
		->getMock();
		$this->view = $this->getMock("\\CowsAPI\\Views\\Event", null, array($log, null));
	}
	
	public function testPostTicketException()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('getServiceTicket', 'createSession'))
		->getMock();
		$stub->expects($this->any())
		->method('getServiceTicket')
		->will($this->throwException(new \Exception("Test")));
		
		$controller = new \CowsAPI\Controllers\Session($this->view, 1, $stub);
		$this->assertSame("Test", $controller->POST());
	}
	
	public function testPostTicketException2()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('getServiceTicket', 'createSession'))
		->getMock();
		$stub->expects($this->any())
		->method('getServiceTicket')
		->will($this->throwException(new \CowsAPI\Exceptions\BaseException(1,"Test",1)));
	
		$controller = new \CowsAPI\Controllers\Session($this->view, 1, $stub);
		$this->assertSame("Test", $controller->POST());
	}
	
	public function testPostTicketExceptionFail()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('getServiceTicket', 'createSession'))
		->getMock();
		$stub->expects($this->any())
		->method('getServiceTicket')
		->will($this->throwException(new \Exception("Test")));
		
		$controller = new \CowsAPI\Controllers\Session($this->view, 1, $stub);
		$this->assertFalse("Tes t" == $controller->POST());
	}
	
	public function testPostSessionException()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('getServiceTicket', 'createSession'))
		->getMock();
		$stub->expects($this->any())
		->method('createSession')
		->will($this->throwException(new \Exception("Test")));
		
		$controller = new \CowsAPI\Controllers\Session($this->view, 1, $stub);
		$this->assertSame("Test", $controller->POST());
	}
	
	public function testPostSessionExceptionFail()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('getServiceTicket', 'createSession'))
		->getMock();
		$stub->expects($this->any())
		->method('createSession')
		->will($this->throwException(new \Exception("Test")));
		
		$controller = new \CowsAPI\Controllers\Session($this->view, 1, $stub);
		$this->assertFalse("Test " == $controller->POST());
	}
	
	public function testPostSessionExceptionFail2()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('getServiceTicket', 'createSession'))
		->getMock();
		$stub->expects($this->any())
		->method('createSession')
		->will($this->throwException(new \CowsAPI\Exceptions\BaseException(1,"Test",1)));
	
		$controller = new \CowsAPI\Controllers\Session($this->view, 1, $stub);
		$this->assertFalse("Test " == $controller->POST());
	}
	
	public function testPostSuccess()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('getServiceTicket', 'createSession'))
		->getMock();
		
		$controller = new \CowsAPI\Controllers\Session($this->view, 1, $stub);
		$this->assertTrue("" == $controller->POST());
	}
	
	public function testPostSuccessTestFail()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('getServiceTicket', 'createSession'))
		->getMock();
		
		$controller = new \CowsAPI\Controllers\Session($this->view, 1, $stub);
		$this->assertFalse(" " == $controller->POST());
	}

	public function testDeleteCheckSession()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('destroySession', 'checkSession'))
		->getMock();
		$stub->expects($this->any())
		->method('checkSession')
		->will($this->returnValue(false));
		
		$controller = new \CowsAPI\Controllers\Session($this->view, 1, $stub);
		$this->assertTrue("" == $controller->DELETE());
	}
	
	public function testDeleteCheckSessionFail()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('destroySession', 'checkSession'))
		->getMock();
		$stub->expects($this->any())
		->method('checkSession')
		->will($this->returnValue(false));
		
		$controller = new \CowsAPI\Controllers\Session($this->view, 1, $stub);
		$this->assertFalse(" " == $controller->DELETE());
	}
	
	public function testDeleteDestroySessionException()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('destroySession', 'checkSession'))
		->getMock();
		$stub->expects($this->any())
		->method('checkSession')
		->will($this->returnValue(true));
		$stub->expects($this->any())
		->method('destroySession')
		->will($this->throwException(new \Exception("Test")));
	
		$controller = new \CowsAPI\Controllers\Session($this->view, 1, $stub);
		$this->assertTrue("Test" == $controller->DELETE());
	}
	
	public function testDeleteDestroySessionException2()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('destroySession', 'checkSession'))
		->getMock();
		$stub->expects($this->any())
		->method('checkSession')
		->will($this->returnValue(true));
		$stub->expects($this->any())
		->method('destroySession')
		->will($this->throwException(new \CowsAPI\Exceptions\BaseException(1,"Test",1)));
	
		$controller = new \CowsAPI\Controllers\Session($this->view, 1, $stub);
		$this->assertTrue("Test" == $controller->DELETE());
	}
	
	public function testDeleteDestroySessionExceptionFail()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('destroySession', 'checkSession'))
		->getMock();
		$stub->expects($this->any())
		->method('checkSession')
		->will($this->returnValue(true));
		$stub->expects($this->any())
		->method('destroySession')
		->will($this->throwException(new \Exception("Test")));
	
		$controller = new \CowsAPI\Controllers\Session($this->view, 1, $stub);
		$this->assertFalse("" == $controller->DELETE());
	}
	
	public function testDeleteSuccess()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('destroySession', 'checkSession'))
		->getMock();
		$stub->expects($this->any())
		->method('checkSession')
		->will($this->returnValue(true));
	
		$controller = new \CowsAPI\Controllers\Session($this->view, 1, $stub);
		$this->assertTrue("" == $controller->DELETE());
	}
	
	public function testDeleteSuccessFail()	{
		$stub = $this->getMockBuilder("\\CowsAPI\\Models\\ServiceFactory")
		->disableOriginalConstructor()
		->setMethods(array('destroySession', 'checkSession'))
		->getMock();
		$stub->expects($this->any())
		->method('checkSession')
		->will($this->returnValue(true));
	
		$controller = new \CowsAPI\Controllers\Session($this->view, 1, $stub);
		$this->assertFalse(" " == $controller->DELETE());
	}
	
	
	
}
?>