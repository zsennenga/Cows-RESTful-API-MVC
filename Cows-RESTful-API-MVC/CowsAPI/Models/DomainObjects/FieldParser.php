<?php

namespace CowsAPI\Models\DomainObjects;

use CowsAPI\Exceptions\InvalidDocumentException;
class FieldParser extends HTMLParser  {
	
	public function parse($doc)	{
		$this->setupDoc($doc);
		return $this;
	}
	
	public function getNodeValue($field)	{
		$nodes = $this->domDoc->query('//input[@name="'.$field.'"]');
		if ($nodes->length == 0)	{
			throw new InvalidDocumentException(ERROR_CAS, "Unable to obtain ". $field ,400);
		}
		$node = $nodes->item(0);
		
		$val = $node->getAttribute('value');
		return $val;
	}
}
?>