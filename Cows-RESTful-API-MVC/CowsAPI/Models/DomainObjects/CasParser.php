<?php

namespace CowsAPI\Models\DomainObjects;

use CowsAPI\Exceptions\CasException;
class CasParser extends GenericParser {

	public function parse($doc)	{
		$out = strip_tags($doc);
		$out = str_replace(' ', '', $out);
		$out = str_replace('\n','', $out);
		$out = str_replace('\t','', $out);
		$out = str_replace('\r', '', $out);
		if (strpos($doc,"proxyFailure") === false)	{
			return trim($out);
		}
		else	{
			throw new CasException($out);
		}
	}
}
?>