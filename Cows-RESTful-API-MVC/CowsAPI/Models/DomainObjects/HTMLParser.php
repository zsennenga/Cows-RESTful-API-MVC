<?php

namespace CowsAPI\Models\DomainObjects;

use CowsAPI\Exceptions\InvalidDocumentException;
abstract class HTMLParser extends GenericParser {

	protected $domDoc;
	
	protected function getField($query, $attr = null)	{
		$q = $this->domDoc->query($query);
		if (!is_object($q->item(0)))	{
			throw new InvalidDocumentException(ERROR_GENERIC,"Unable to parse Document", 500);
		}
		if (!isset($attr)) return $q->item(0)->nodeValue;
		else return $q->item(0)->getAttribute($attr);
	}
	
	protected function setupDoc($doc)	{
		if ($doc == null || trim($doc) == "")	{
			throw new InvalidDocumentException(ERROR_GENERIC, "Blank Document Given", 500);
		}
		$this->domDoc = new \DOMDocument();
		libxml_use_internal_errors(true);
		$this->domDoc->loadHTML($doc);
		libxml_use_internal_errors(false);
		$this->domDoc = new \DOMXPath($this->domDoc);
	}
	
}
?>