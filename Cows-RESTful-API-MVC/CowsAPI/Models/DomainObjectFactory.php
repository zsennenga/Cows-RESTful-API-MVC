<?php
namespace CowsAPI\Models;

use CowsAPI\Exceptions\InvalidClassException;
class DomainObjectFactory	{
	
	public function __construct()	{
		
	}
	/**
	 * Returns an instance of the DomainObject class with the given name 
	 * 
	 * @param Class Name $className
	 * @throws InvalidClassException
	 * @return Instance of $className
	 */
	public function get($className)	{
		$className = "\\CowsAPI\\Models\\DomainObjects\\" . $className;
		
		if (!class_exists($className)) throw new InvalidClassException(ERROR_GENERIC, $className . " not found", 500);
		
		return new $className();
	}

}
?>