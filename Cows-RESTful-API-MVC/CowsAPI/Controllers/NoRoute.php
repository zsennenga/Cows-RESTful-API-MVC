<?php

namespace CowsAPIControllers;

class NoRoute extends BaseController	{
		
	public function invoke() {
		$this->message = "Route not Found";
		//TODO setup status codes
		$this->statusCode = 1;
		$this->responseCode = 404;
	}

}
?>