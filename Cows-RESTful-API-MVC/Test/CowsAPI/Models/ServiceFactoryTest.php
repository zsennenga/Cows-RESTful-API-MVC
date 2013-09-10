<?php

class ServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \CowsAPI\Models\ServiceFactory
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
    	$this->object = new \CowsAPI\Models\ServiceFactory(
    			new \CowsAPI\Models\DomainObjectFactory(), 
    			new \CowsAPI\Models\DataMapperFactory(new \CowsAPI\Models\CurlWrapper(), new \CowsAPI\Models\DBWrapper()),
    			array(), 
    			new \CowsAPI\Utility\URLBuilder());
    }
 	
 	public function testServiceTicket()	{

    	$curl = $this->getMock('CurlWrapper');
    	$curl->expects($this->any())
    	->method('execute')
    	->will($this->returnValue(file_get_contents(__DIR__ . '../data/serviceTicketTest1.xml')));
    	$serviceFactory = new \CowsAPI\Models\ServiceFactory(new \CowsAPI\Models\DomainObjectFactory(), new \CowsAPI\Models\DataMapperFactory(null,$curl),array("tgc" => 1), \CowsAPI\Utility\URLBuilder());
		$this->assertSame("ticket",$serviceFactory->getServiceTicket());
    	
    	$this->setExpectedException("UnexpectedValueException");
    	$curl = $this->getMock('CurlWrapper');
    	$curl->expects($this->any())
    	->method('execute')
    	->will($this->returnValue(file_get_contents(__DIR__ . '../data/serviceTicketTest2.xml')));
    	$serviceFactory = new \CowsAPI\Models\ServiceFactory(new \CowsAPI\Models\DomainObjectFactory(), new \CowsAPI\Models\DataMapperFactory(null,$curl),array("tgc" => 1), \CowsAPI\Utility\URLBuilder());
    	$serviceFactory->getServiceTicket();
    }
    
    public function testServiceTicketNoTGC()	{
    	//Invalid param case
    	$curl = $this->getMock('CurlWrapper');
    	$curl->expects($this->any())
    	->method('execute')
    	->will($this->returnValue(false));
    	$serviceFactory = new \CowsAPI\Models\ServiceFactory(new \CowsAPI\Models\DomainObjectFactory(), new \CowsAPI\Models\DataMapperFactory(null,$curl),array());
    	$this->setExpectedException('InvalidArgumentException');
    	$serviceFactory->getServiceTicket();
    }
    
    public function testSiteId()	{
    	$this->assertTrue($object->validateSiteId("its"));
    	$this->assertTrue($object->validateSiteId("cbs"));
    	$this->assertTrue($object->validateSiteId("engr"));
    	$this->assertTrue($object->validateSiteId("law"));
    	$this->assertTrue($object->validateSiteId("rmi"));
    	$this->assertTrue($object->validateSiteId("taag"));
    	$this->assertFalse($object->validateSiteId("fake"));
    }
    
    public function testGetEventById()	{
    	$curl = $this->getMock('CurlWrapper');
    	$curl->expects($this->any())
    	->method('execute')
    	->will($this->returnValue(file_get_contents(__DIR__ . '../data/eventIdTest1.json')));
    	$serviceFactory = new \CowsAPI\Models\ServiceFactory(new \CowsAPI\Models\DomainObjectFactory(), new \CowsAPI\Models\DataMapperFactory(null,$curl),array("tgc" => 1), \CowsAPI\Utility\URLBuilder());
    	$out = $serviceFactory->getEventById(1);
    	$this->assertSame("Development Team Meeting", $out['title']);
    }

    public function testCheckSession()	{
    	$curl = $this->getMock('CurlWrapper');
    	$curl->expects($this->any())
    	->method('execute')
    	->will($this->returnValue(file_get_contents(__DIR__ . '../data/checkSessionTest1.html')));
    	$serviceFactory = new \CowsAPI\Models\ServiceFactory(new \CowsAPI\Models\DomainObjectFactory(), new \CowsAPI\Models\DataMapperFactory(null,$curl),array("tgc" => 1), \CowsAPI\Utility\URLBuilder());
		$this->assertFalse($serviceFactory->checkSession());
    	
    	$curl = $this->getMock('CurlWrapper');
    	$curl->expects($this->any())
    	->method('execute')
    	->will($this->returnValue(file_get_contents(__DIR__ . '../data/checkSessionTest2.html')));
    	$serviceFactory = new \CowsAPI\Models\ServiceFactory(new \CowsAPI\Models\DomainObjectFactory(), new \CowsAPI\Models\DataMapperFactory(null,$curl),array("tgc" => 1), \CowsAPI\Utility\URLBuilder());
    	$this->assertTrue($serviceFactory->checkSession());
    }

    public function testBuildEventParams()	{
    	$this->object->setParms(array('Categories' => 'Other'));
    	$this->assertSame("Categories=Other", $this->object->buildEventParams());
    	$this->object->setParms(array('Categories' => 'Other&m'));
    	$this->assertSame("Categories=Other&Categories=m", $this->object->buildEventParams());
    	$this->object->setParms(null);
    	$this->setExpectedException('InvalidArgumentException');
    	$this->assertSame($this->object->buildEventParams());
    }
    
    public function testCreateEvent($a)	{
    	$curl = $this->getMock('CurlWrapper');
    	$curl->expects($this->any())
    	->method('execute')
    	->will($this->returnValue($a[0]));
    	$serviceFactory = new \CowsAPI\Models\ServiceFactory(new \CowsAPI\Models\DomainObjectFactory(), new \CowsAPI\Models\DataMapperFactory(null,$curl),array(), \CowsAPI\Utility\URLBuilder());
    	$this->assertTrue($serviceFactory->createEvent());
    	 
    	$this->setExpectedException("RuntimeException");
    	$curl = $this->getMock('CurlWrapper');
    	$curl->expects($this->any())
    	->method('execute')
    	->will($this->returnValue($a[1]));
    	$serviceFactory = new \CowsAPI\Models\ServiceFactory(new \CowsAPI\Models\DomainObjectFactory(), new \CowsAPI\Models\DataMapperFactory(null,$curl),array(), \CowsAPI\Utility\URLBuilder());
    	$serviceFactory->createEvent();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
}
?>
