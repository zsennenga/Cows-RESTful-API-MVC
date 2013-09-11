<?php

namespace CowsAPI\Controllers;

class InvalidAuth extends BaseController {
	
	public function invoke()	{
		$this->updateView(null,403,null);
	}
}
?>