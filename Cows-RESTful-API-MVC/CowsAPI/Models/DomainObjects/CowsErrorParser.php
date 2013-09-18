<?php
namespace CowsAPI\Models\DomainObjects;

use CowsAPI\Exceptions\CowsException;
class CowsErrorParser extends HTMLParser {
	public function parse($doc)	{
		$this->setupDoc($doc);
		
		$div = $this->doc->query('//div[@class="validation-summary-errors"]');
		
		//Any results means cows threw an error
		if ($div->length > 0)	{
			$div = $div->item(0);
			$error = str_replace('may not be null or empty', '', $div->nodeValue);
			throw new CowsException(ERROR_COWS,"COWS Error: " . strip_tags(htmlspecialchars_decode($error)),400);
		}
		
		//Cows likes to throw generic errors sometimes for no reason
		//Well okay there is usually a reason
		if (strstr($this->rawData,"Error") !== false)	{
			throw new CowsException(ERROR_COWS,"COWS Error: Unknown Problem occurred.",400);
		}
	}
}
?>