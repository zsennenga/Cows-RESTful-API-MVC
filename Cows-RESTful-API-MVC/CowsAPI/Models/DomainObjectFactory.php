<?php
namespace CowsAPI\Models;

class DomainObjectFactory	{
	
	public function __construct()	{
		
	}
	
	public function get($className)	{
		$className = "\\CowsAPI\\Models\\DomainObjects\\" . $className;
		
		if (!class_exists($className)) throw new \Exception($className . " not found");
		
		return new $className();
	}

}
?>