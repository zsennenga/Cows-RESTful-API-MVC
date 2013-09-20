<?php

use CowsAPI\Models\DataMappers\SessionManager;
use CowsAPI\Models\HTTP\CurlWrapper;
use CowsAPI\Models\DB\DBWrapper;
use CowsAPI\Utility\URLBuilder;
class SessionManagerTest extends PHPUnit_Framework_TestCase {

	protected $object;
	
	protected function setUp()	{
		$this->object = new SessionManager(new CurlWrapper(), new DBWrapper(), 'test');
	}
	
	public function testABunch()	{
		$this->create();
		$this->destroy();
		$this->create();
		$this->create();
	}
	
	private function create()	{
		$ub = new URLBuilder();
		$url = $ub->getCowsLoginUrl('its', 'localhost');
		$this->assertTrue($this->object->create('its', $url) !== false);
		$cookieFile = $this->object->getCookieFile('its');
		$db = new DBWrapper();
		$db->addParam(':publicKey', 'test');
		$db->addParam(':siteId', 'its');
		$out = $db->query('SELECT cookieFile FROM cows_session WHERE publicKey = :publicKey AND siteId = :siteId');
		$this->assertTrue($out !== false);
		$this->assertSame($out['cookieFile'], $cookieFile['cookieFile']);
	}
	private function destroy()	{
		$this->object->destroy('its', 'localhost');
		$db = new DBWrapper();
		$db->addParam(':publicKey', 'test');
		$db->addParam(':siteId', 'its');
		$out = $db->query('SELECT cookieFile FROM cows_session WHERE publicKey = :publicKey AND siteId = :siteId');
		$this->assertFalse($out);
	}

}
?>