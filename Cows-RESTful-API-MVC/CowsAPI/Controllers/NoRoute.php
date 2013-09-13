<?php

namespace CowsAPI\Controllers;
/**
 * 
 * Controller when no route is found
 * 
 * @author its-zach
 * @codeCoverageIgnore
 */
class NoRoute extends BaseController	{
		
	public function invoke() {
		$this->updateView("Route not Found", 404, ERROR_PARAMETERS);
	}

}
?>