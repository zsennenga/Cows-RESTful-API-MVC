<?php
namespace CowsAPI\Models;

use CowsAPI\Models\DB\DBWrapper;
use CowsAPI\Models\HTTP\CurlWrapper;

class DataMapperFactory	{
	
	private $db;
	private $curl;
	private $publicKey;
	
	public function __construct(DbWrapper $db, CurlWrapper $curl, $publicKey)	{
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
		
		if (!class_exists($className)) throw new \Exception($className . " not found");
	
		return new $className($this->curl, $this->db, $this->publicKey);
	}

}

?>