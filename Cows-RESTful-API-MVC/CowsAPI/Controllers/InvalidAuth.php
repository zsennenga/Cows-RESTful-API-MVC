<?php

namespace CowsAPI\Controllers;

class InvalidAuth extends BaseController {
	
	public function invoke()	{
		$this->updateView("Invalid Auth",403,null);
	}
}
?>