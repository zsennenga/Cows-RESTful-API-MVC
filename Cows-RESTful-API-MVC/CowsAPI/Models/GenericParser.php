<?php
namespace CowsAPI\Models;

abstract class GenericParser	{
	
	public final function __construct()	{
	
	}
	
	abstract public function parse($doc);
}

?>