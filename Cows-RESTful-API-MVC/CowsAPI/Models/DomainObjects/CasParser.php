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
		$out = trim($out);
		if (strpos($doc,"proxyFailure") === false)	{
			return $out;
		}
		else	{
			throw new CasException(ERROR_CAS,$out,500);
		}
	}
}
?>