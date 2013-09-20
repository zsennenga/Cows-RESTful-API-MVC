<?php

use CowsAPI\Models\DataMappers\SiteValidator;
use CowsAPI\Models\HTTP\CurlWrapper;
use CowsAPI\Models\DB\DBWrapper;
class SiteValidatorTest extends \PHPUnit_Framework_TestCase {

	protected $object;
	
	protected function setUp()	{
		$this->object = new SiteValidator(new CurlWrapper(), new DBWrapper(), null);
	}
	
	public function siteValidProvider()	{
		return array(
			array("its"),
			array("cbs"),
			array("engr"),
			array("rmi"),
			array("taag")
		);
	}
	/**
	 * @dataProvider siteValidProvider
	 */
	public function testSiteValid($a)	{
		$this->assertTrue($this->object->validSite($a));
	}
	
	public function testSiteInvalid()	{
		$this->assertFalse($this->object->validSite("FakeSite"));
	}
	
	public function testRedir()	{
		$this->assertTrue($this->object->checkNoRedirect("http://cows.ucdavis.edu/"));
		$this->assertFalse($this->object->checkNoRedirect("http://cows.ucdavis.edu/its/Event"));
	}

}
?>