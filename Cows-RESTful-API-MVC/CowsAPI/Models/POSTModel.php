<?php

namespace CowsAPIModels;

class POSTModel extends HttpModel	{
	
	public function __construct($curl)	{
		$this->method = "POST";
		$this->curl = $curl;
	}
	
	public function setParams() {
		// TODO: Auto-generated method stub

	}

}

?>
