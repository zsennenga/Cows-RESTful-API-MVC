<?php

namespace CowsAPI\Templates;

/**
 * Json view for the API
 * 
 * @author its-zach
 *
 */
class JSON extends BaseTemplate {
	
	public function __construct()	{
		if (isset($_GET['callback'])) {
			$this->callback = $_GET['callback'];
			unset($_GET['callback']);
		}
	}
	
	public function parse($statusCode, $message)	{
		$outArray = array(
				"code" => $statusCode,
				"message" => $message
		);

		$out = json_encode($outArray);
		
		if (isset($this->callback))	{
			return $this->callback . "(" . $out . ")";
		}
		else	{
			return $out;
		}
	}
}