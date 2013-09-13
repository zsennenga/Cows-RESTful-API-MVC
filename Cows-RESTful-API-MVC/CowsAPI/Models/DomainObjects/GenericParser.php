<?php
namespace CowsAPI\Models\DomainObjects;

abstract class GenericParser	{
	
	public final function __construct()	{
	
	}
	
	abstract public function parse($doc);
}

?>