<?php

namespace CowsAPI\Views;

class InvalidAuth extends BaseView	{

	public function render()	{
		http_response_code($this->responseCode);
		echo $this->template->parse($this->statusCode,$this->message);
	}
}


?>