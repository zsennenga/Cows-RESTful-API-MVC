<?php

namespace CowsAPIModels;

class POSTModel extends HttpModel	{
	
	public function __construct($curl)	{
		$this->method = "DELETE";
		$this->curl = $curl;
	}
	
	public function setParams() {
		return;
	}

}

?>
