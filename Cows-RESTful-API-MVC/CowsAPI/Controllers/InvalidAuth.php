<?php

namespace CowsAPI\Controllers;
/**
 * 
 * Controller when a signature/public key is invalid
 * 
 * @author its-zach
 * @codeCoverageIgnore
 *
 */
class InvalidAuth extends BaseController {
	
	public function invoke($message)	{
		$this->updateView($message,ERROR_PARAMETERS, 403);
	}
}