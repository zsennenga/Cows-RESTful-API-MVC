<?php

class BasicPostTest extends \PHPUnit_Framework_TestCase {
	
	private $object;
	
	protected function setUp()	{
		$this->object = new \CowsAPI\Models\DataMappers\BasicPost(new \CowsAPI\Models\HTTP\CurlWrapper(), new \CowsAPI\Models\DB\DBWrapper(), null); 
	}

	public function testPost()	{
		$this->assertContains("The request method <code>POST</code> is inappropriate", $this->object->execute("http://google.com", array()));
	}

}
?>