<?php

namespace CowsAPI\Models\DomainObjects;

class CasParser extends GenericParser {

	public function parse($doc)	{
		$xml = simplexml_load_string($doc);
		var_dump($xml);
	}
}
?>