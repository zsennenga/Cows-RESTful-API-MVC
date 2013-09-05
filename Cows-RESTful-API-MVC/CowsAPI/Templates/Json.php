<?php

namespace CowsAPITemplates;

/**
 * Json view for the API
 * 
 * @author its-zach
 *
 */
class Json extends BaseTemplate {
	
	public function parse($statusCode, $message, $callback = null)	{
		$outArray = array(
				"code" => $statusCode,
				"message" => $message
		);

		$out = json_encode($outArray);
		
		if (isset($callback))	{
			return $this->callback . "(" . $out . ")";
		}
		else	{
			return $out;
		}
	}
}