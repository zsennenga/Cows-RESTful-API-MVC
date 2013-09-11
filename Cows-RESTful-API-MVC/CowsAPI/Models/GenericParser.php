<?php
namespace CowsAPI\Models;

abstract class GenericParser	{
	protected $doc;
	
	public function __construct($doc)	{
		$this->doc = $doc;
	}
	
	abstract public function parse();
}

?>