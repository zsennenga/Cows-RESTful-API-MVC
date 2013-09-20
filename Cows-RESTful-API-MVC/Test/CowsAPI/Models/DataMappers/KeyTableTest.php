<?php

use CowsAPI\Models\DataMappers\KeyTable;
use CowsAPI\Models\HTTP\CurlWrapper;
use CowsAPI\Models\DB\DBWrapper;
class KeyTableTest extends PHPUnit_Framework_TestCase {

	protected $object;
	
	protected function setUp()	{
		$this->object = new KeyTable(new CurlWrapper(), new DBWrapper(), 'test');
	}
	
	public function testGetPrivateKey()	{
		$this->assertSame('test', $this->object->getPrivateKey());
		$this->object = new KeyTable(new CurlWrapper(), new DBWrapper(), 'testa');
		$this->assertFalse($this->object->getPrivateKey());
	}

}
?>