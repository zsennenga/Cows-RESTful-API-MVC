<?php
namespace CowsAPI\Models;

class DomainObjectFactory	{
	
	public function __construct()	{
		
	}
	
	public function get($className)	{
		$className = "\\CowsAPI\\Models\\" . $className;
		return new $className();
	}

}
?>