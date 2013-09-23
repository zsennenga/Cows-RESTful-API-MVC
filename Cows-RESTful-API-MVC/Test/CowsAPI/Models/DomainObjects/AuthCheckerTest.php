<?php

class AuthCheckerTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var Router
	 */
	protected $object;
	
	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->object = new \CowsAPI\Models\DomainObjects\AuthChecker();
	}
	
	public function testSignatureGeneration()	{
		$t = time();
		$this->assertFalse($this->object->checkSignature("6e8389389567e2068750f6d4195c08900dfd58adb84647a1ebce3b9755b958e8", 
											"test",
											$t,
											"GET",
											"/",
											"test=t"));
		$this->assertTrue($this->object->checkSignature(hash_hmac('sha256','GET/test=t'.$t,'test'), 
											"test",
											$t,
											"GET",
											"/",
											"test=t"));
	}
	
}
?>