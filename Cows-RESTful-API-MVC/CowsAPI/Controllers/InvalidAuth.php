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
	
	public function invoke()	{
		$this->updateView("Invalid Auth",403,ERROR_PARAMETERS);
	}
}