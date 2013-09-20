<?php

use CowsAPI\Utility\Log;
use CowsAPI\Models\DB\DBWrapper;
class LogTest extends PHPUnit_Framework_TestCase {

	protected $object;
	
	public function setUp()	{
		$this->object = new Log(new DBWrapper(), DB_TABLE_LOG);
	}
	
	public function testLog()	{
		$_SERVER['REMOTE_ADDR'] = "asdf";
		$this->object->setKey('test');
		$this->object->setRoute('arr', 'blah');
		$this->object->setResp('res');
		$this->object->setParams(array('tgc'));
		$this->object->execute();
		
		$db = new DBWrapper();
		$out = $db->query('SELECT * FROM cows_log WHERE publicKey = \'test\' AND ip = \'asdf\' ');
		$this->assertTrue($out != false);
		$out = $db->query('DELETE FROM cows_log WHERE publicKey = \'test\' AND ip = \'asdf\' ');
	}

}
?>