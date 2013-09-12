<?php

namespace CowsApi\Models;

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
		$this->object = new \CowsAPI\Models\AuthChecker();
	}
	
	public function testSignatureGeneration()	{
		$this->assertTrue($this->object->checkSignature("6e8389389567e2068750f6d4195c08900dfd58adb84647a1ebce3b9755b958e8", 
											"test",
											"0123456",
											"GET",
											"/",
											"test=t"));
		$this->assertFalse($this->object->checkSignature("6e8389389567e2068750f6d4195c08900dfd58adb84647a1ebce3b9755b958e8", 
											"test",
											"0123456",
											"GET",
											"/different",
											"test=t"));
	}
	
}
?>