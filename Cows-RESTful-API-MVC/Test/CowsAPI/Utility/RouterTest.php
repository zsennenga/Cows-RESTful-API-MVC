<?php

include(__DIR__ . '\..\..\..\vendor\autoload.php');
require_once __DIR__ . '\..\..\..\vendor\phpunit\phpunit\PHPUnit\Autoload.php';
require_once __DIR__ . '\..\..\..\vendor\phpunit\phpunit\PHPUnit\Framework\TestSuite.php';


/**
 * Test class for Router.
 * Generated by PHPUnit on 2013-09-03 at 13:51:48.
 */
class RouterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Router
     */
    protected $object;
    protected $log;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
    	$this->log = $this->getMockBuilder('\CowsAPI\Utility\Log')
                     ->disableOriginalConstructor()
                     ->getMock();
    	$this->object = new CowsAPI\Utility\Router($this->log, file_get_contents(__DIR__ . "\..\..\..\CowsAPI\Data\Routes.json"));
    	
    }
    
    public function testGetMethod()	{
    	$this->object->setRoute("GET" , "/event/its/1");
    	$this->assertSame("GET", $this->object->getMethod());
    	
    	$this->object->setRoute("POST" , "/session/its");
    	$this->assertSame("POST", $this->object->getMethod());
    	
    	$this->object->setRoute("DELETE" , "/event/its/1/");
    	$this->assertSame("DELETE", $this->object->getMethod());
    	
    	$this->object->setRoute("POST" , "/event/its/1");
    	$this->assertSame("invoke", $this->object->getMethod());
    }
    
    public function testParams()	{
    	$this->object->setRoute("GET" , "/event/its/1");
    	$this->assertSame("its", $this->object->getParams('siteId'));
    	$this->assertSame("1", $this->object->getParams('eventId'));
    
    	$this->object->setRoute("GET" , "/event/its/1/");
    	$this->assertSame("its", $this->object->getParams('siteId'));
    	$this->assertSame("1", $this->object->getParams('eventId'));
    	
    	$this->object->setRoute("GET" , "/event/its/");
    	$this->assertSame("its", $this->object->getParams('siteId'));
    	$this->assertSame(null, $this->object->getParams('eventId'));
    	
    	$this->object->setRoute("GET" , "/event/its");
    	$this->assertSame("its", $this->object->getParams('siteId'));
    	$this->assertSame(null, $this->object->getParams('eventId'));
    	
    	$this->object->setRoute("GET" , "/event");
    	$this->assertSame(null, $this->object->getParams('siteId'));
    	$this->assertSame(null, $this->object->getParams('eventId'));
    	
    	$this->object->setRoute("GET" , "/event/");
    	$this->assertSame(null, $this->object->getParams('siteId'));
    	$this->assertSame(null, $this->object->getParams('eventId'));
    	
    	$this->object->setRoute("POST" , "/session/its");
    	$this->assertSame("its", $this->object->getParams('siteId'));
    	$this->assertSame(null, $this->object->getParams('eventId'));
    	
    	$this->object->setRoute("POST" , "/session/its/");
    	$this->assertSame("its", $this->object->getParams('siteId'));
    	$this->assertSame(null, $this->object->getParams('eventId'));
    	
    	$this->object->setRoute("DELETE" , "/session/its/");
    	$this->assertSame("its", $this->object->getParams('siteId'));
    	$this->assertSame(null, $this->object->getParams('eventId'));
    	
    	$this->object->setRoute("DELETE" , "/session/its");
    	$this->assertSame("its", $this->object->getParams('siteId'));
    	$this->assertSame(null, $this->object->getParams('eventId'));
    	
    	$this->object->setRoute("DELETE" , "/event/its/1");
    	$this->assertSame("its", $this->object->getParams('siteId'));
    	$this->assertSame("1", $this->object->getParams('eventId'));
    	 
    	$this->object->setRoute("DELETE" , "/event/its/1/");
    	$this->assertSame("its", $this->object->getParams('siteId'));
    	$this->assertSame("1", $this->object->getParams('eventId'));
    }
    
    public function testRouteIdentification()	{
    	$this->object->setRoute("DELETE" , "/event/its/1");
    	$this->assertSame("Event", $this->object->getClass());
    	$this->object->setRoute("DELETE" , "/session/its");
    	$this->assertSame("Session", $this->object->getClass());
    	$this->object->setRoute("GET" , "/event/its");
    	$this->assertSame("Event", $this->object->getClass());
    	$this->object->setRoute("GET" , "/event/its/1");
    	$this->assertSame("Event", $this->object->getClass());
    	$this->object->setRoute("POST" , "/session/its");
    	$this->assertSame("Session", $this->object->getClass());
    	$this->object->setRoute("POST" , "/event/its");
    	$this->assertSame("Event", $this->object->getClass());
    	$this->assertSame("/event/its", $this->object->getURI());
    	
    	$this->object->setRoute("DELETE" , "/event/its/1/");
    	$this->assertSame("Event", $this->object->getClass());
    	$this->object->setRoute("DELETE" , "/session/its/");
    	$this->assertSame("Session", $this->object->getClass());
    	$this->object->setRoute("GET" , "/event/its/");
    	$this->assertSame("Event", $this->object->getClass());
    	$this->object->setRoute("GET" , "/event/its/1/");
    	$this->assertSame("Event", $this->object->getClass());
    	$this->object->setRoute("POST" , "/session/its/");
    	$this->assertSame("Session", $this->object->getClass());
    	$this->object->setRoute("POST" , "/event/its/");
    	$this->assertSame("Event", $this->object->getClass());
    	
    	$this->object->setRoute("DELETE" , "/session/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/event/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/session/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/event/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	
    	$this->object->setRoute("DELETE" , "/session");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/event");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/session");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/event");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	
    	$this->object->setRoute("GET" , "/session/its");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("DELETE" , "/event/its");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/event/its/1");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	
    	$this->object->setRoute("DELETE" , "/fake/its/1");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("DELETE" , "/fake/its");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/fake/its");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/fake/its/1");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/fake/its");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/fake/its");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	 
    	$this->object->setRoute("DELETE" , "/fake/its/1/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("DELETE" , "/fake/its/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/fake/its/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/fake/its/1/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/fake/its/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/fake/its/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	
    	$this->object->setRoute("DELETE" , "/event/its/1/fake");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("DELETE" , "/session/its/fake");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/event/its/1/fake");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/session/its/fake");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/event/its/fake");
    	$this->assertSame("NoRoute", $this->object->getClass());
    }
    
    public function testPrefixRemoval()		{
    	$this->object->setPrefix("notAPrefix");
    	
    	$this->object->setRoute("DELETE" , "/event/its/1");
    	$this->assertSame("Event", $this->object->getClass());
    	$this->object->setRoute("DELETE" , "/session/its");
    	$this->assertSame("Session", $this->object->getClass());
    	$this->object->setRoute("GET" , "/event/its");
    	$this->assertSame("Event", $this->object->getClass());
    	$this->object->setRoute("GET" , "/event/its/1");
    	$this->assertSame("Event", $this->object->getClass());
    	$this->object->setRoute("POST" , "/session/its");
    	$this->assertSame("Session", $this->object->getClass());
    	$this->object->setRoute("POST" , "/event/its");
    	$this->assertSame("Event", $this->object->getClass());
    	 
    	$this->object->setRoute("DELETE" , "/event/its/1/");
    	$this->assertSame("Event", $this->object->getClass());
    	$this->object->setRoute("DELETE" , "/session/its/");
    	$this->assertSame("Session", $this->object->getClass());
    	$this->object->setRoute("GET" , "/event/its/");
    	$this->assertSame("Event", $this->object->getClass());
    	$this->object->setRoute("GET" , "/event/its/1/");
    	$this->assertSame("Event", $this->object->getClass());
    	$this->object->setRoute("POST" , "/session/its/");
    	$this->assertSame("Session", $this->object->getClass());
    	$this->object->setRoute("POST" , "/event/its/");
    	$this->assertSame("Event", $this->object->getClass());
    	 
    	$this->object->setRoute("DELETE" , "/session/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/event/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/session/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/event/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	 
    	$this->object->setRoute("DELETE" , "/session");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/event");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/session");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/event");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	 
    	$this->object->setRoute("GET" , "/session/its");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("DELETE" , "/event/its");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/event/its/1");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	 
    	$this->object->setRoute("DELETE" , "/fake/its/1");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("DELETE" , "/fake/its");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/fake/its");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/fake/its/1");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/fake/its");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/fake/its");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	
    	$this->object->setRoute("DELETE" , "/fake/its/1/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("DELETE" , "/fake/its/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/fake/its/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/fake/its/1/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/fake/its/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/fake/its/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	 
    	$this->object->setRoute("DELETE" , "/event/its/1/fake");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("DELETE" , "/session/its/fake");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/event/its/1/fake");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/session/its/fake");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/event/its/fake");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	
    	
    	$this->object->setPrefix("/test/");
    	$this->object->setRoute("DELETE" , "/test/event/its/1");
    	$this->assertSame("Event", $this->object->getClass());
    	$this->object->setRoute("DELETE" , "/test/session/its");
    	$this->assertSame("Session", $this->object->getClass());
    	$this->object->setRoute("GET" , "/test/event/its");
    	$this->assertSame("Event", $this->object->getClass());
    	$this->object->setRoute("GET" , "/test/event/its/1");
    	$this->assertSame("Event", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/session/its");
    	$this->assertSame("Session", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/event/its");
    	$this->assertSame("Event", $this->object->getClass());
    	 
    	$this->object->setRoute("DELETE" , "/test/event/its/1/");
    	$this->assertSame("Event", $this->object->getClass());
    	$this->object->setRoute("DELETE" , "/test/session/its/");
    	$this->assertSame("Session", $this->object->getClass());
    	$this->object->setRoute("GET" , "/test/event/its/");
    	$this->assertSame("Event", $this->object->getClass());
    	$this->object->setRoute("GET" , "/test/event/its/1/");
    	$this->assertSame("Event", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/session/its/");
    	$this->assertSame("Session", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/event/its/");
    	$this->assertSame("Event", $this->object->getClass());
    	 
    	$this->object->setRoute("DELETE" , "/test/session/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/test/event/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/session/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/event/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	 
    	$this->object->setRoute("DELETE" , "/test/session");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/test/event");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/session");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/event");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	 
    	$this->object->setRoute("GET" , "/test/session/its");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("DELETE" , "/test/event/its");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/event/its/1");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	 
    	$this->object->setRoute("DELETE" , "/test/fake/its/1");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("DELETE" , "/test/fake/its");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/test/fake/its");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/test/fake/its/1");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/fake/its");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/fake/its");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	
    	$this->object->setRoute("DELETE" , "/test/fake/its/1/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("DELETE" , "/test/fake/its/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/test/fake/its/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/test/fake/its/1/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/fake/its/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/fake/its/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	 
    	$this->object->setRoute("DELETE" , "/test/event/its/1/fake");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("DELETE" , "/test/session/its/fake");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/test/event/its/1/fake");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/session/its/fake");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/event/its/fake");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	
    	$this->object->setPrefix("/test");
    	$this->object->setRoute("DELETE" , "/test/event/its/1");
    	$this->assertSame("Event", $this->object->getClass());
    	$this->object->setRoute("DELETE" , "/test/session/its");
    	$this->assertSame("Session", $this->object->getClass());
    	$this->object->setRoute("GET" , "/test/event/its");
    	$this->assertSame("Event", $this->object->getClass());
    	$this->object->setRoute("GET" , "/test/event/its/1");
    	$this->assertSame("Event", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/session/its");
    	$this->assertSame("Session", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/event/its");
    	$this->assertSame("Event", $this->object->getClass());
    	
    	$this->object->setRoute("DELETE" , "/test/event/its/1/");
    	$this->assertSame("Event", $this->object->getClass());
    	$this->object->setRoute("DELETE" , "/test/session/its/");
    	$this->assertSame("Session", $this->object->getClass());
    	$this->object->setRoute("GET" , "/test/event/its/");
    	$this->assertSame("Event", $this->object->getClass());
    	$this->object->setRoute("GET" , "/test/event/its/1/");
    	$this->assertSame("Event", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/session/its/");
    	$this->assertSame("Session", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/event/its/");
    	$this->assertSame("Event", $this->object->getClass());
    	
    	$this->object->setRoute("DELETE" , "/test/session/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/test/event/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/session/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/event/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	
    	$this->object->setRoute("DELETE" , "/test/session");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/test/event");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/session");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/event");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	
    	$this->object->setRoute("GET" , "/test/session/its");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("DELETE" , "/test/event/its");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/event/its/1");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	
    	$this->object->setRoute("DELETE" , "/test/fake/its/1");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("DELETE" , "/test/fake/its");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/test/fake/its");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/test/fake/its/1");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/fake/its");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/fake/its");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	 
    	$this->object->setRoute("DELETE" , "/test/fake/its/1/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("DELETE" , "/test/fake/its/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/test/fake/its/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/test/fake/its/1/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/fake/its/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/fake/its/");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	
    	$this->object->setRoute("DELETE" , "/test/event/its/1/fake");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("DELETE" , "/test/session/its/fake");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("GET" , "/test/event/its/1/fake");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/session/its/fake");
    	$this->assertSame("NoRoute", $this->object->getClass());
    	$this->object->setRoute("POST" , "/test/event/its/fake");
    	$this->assertSame("NoRoute", $this->object->getClass());
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
static function main() {

  $suite = new \PHPUnit_Framework_TestSuite( __CLASS__);
  \PHPUnit_TextUI_TestRunner::run( $suite);
 }
}

if (!defined('PHPUnit_MAIN_METHOD')) {
    RouterTest::main();
}
?>
