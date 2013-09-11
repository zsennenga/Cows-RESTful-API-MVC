<?php

namespace CowsAPI\Controllers;

class NoRoute extends BaseController	{
		
	public function invoke() {
		$this->updateView("Route not Found", 404, ERROR_PARAMETERS);
	}

}
?>