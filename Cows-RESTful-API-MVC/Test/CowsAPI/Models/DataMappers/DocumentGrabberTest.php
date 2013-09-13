<?php

use CowsAPI\Models\DataMappers\DocumentGrabber;
use CowsAPI\Models\HTTP\CurlWrapper;
use CowsAPI\Models\DB\DBWrapper;
class DocumentGrabberTest extends \PHPUnit_Framework_TestCase {

	
	protected $object;
	
	protected function setUp()	{
		$this->object = new DocumentGrabber(new CurlWrapper(), new DBWrapper(), null);
		$this->object->setUrl("www.its.ucdavis.edu");
	}

	public function testCorrectDocument()	{
		$this->assertContains("http://www.its.ucdavis.edu/wp-content/themes/ucdavis/js/custom.js", $this->object->getDocument());
	}
	
	public function testWrongDocument()	{
		$this->assertFalse("adfasdfasldfaslk;gaksjdf;lasdfkasdf;asldfasdf" == $this->object->getDocument());
	}
}
?>