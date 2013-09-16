<?php

namespace CowsAPI\Models\DomainObjects;

class FieldParser extends HTMLParser  {

	protected $doc;
	
	public function parse($doc)	{
		$this->setupDoc($doc);
	}
	
}
?>