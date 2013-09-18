<?php
namespace CowsAPI\Models;

use CowsAPI\Models\DB\DBWrapper;
use CowsAPI\Models\HTTP\CurlWrapper;
use CowsAPI\Exceptions\InvalidClassException;

class DataMapperFactory	{
	
	private $db;
	private $curl;
	private $publicKey;
	
	public function __construct(DBWrapper $db, CurlWrapper $curl, $publicKey)	{
		$this->db = $db;
		$this->curl = $curl;
		$this->publicKey = $publicKey;
	}
	
	/**
	 * Creates the data mapper object with the given name
	 * 
	 * @param unknown $className
	 * @return unknown
	 */
	public function get($className)	{
		$className = "\\CowsAPI\\Models\\DataMappers\\" . $className;
		
		if (!class_exists($className)) throw new InvalidClassException(ERROR_GENERIC,$className . " not found", 500);
	
		return new $className($this->curl, $this->db, $this->publicKey);
	}

}

?>