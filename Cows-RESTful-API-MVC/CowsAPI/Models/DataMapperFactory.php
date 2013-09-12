<?php
namespace CowsAPI\Models;

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
		$className = "\\CowsApi\\Models\\" . $className;
		
		$sm = null;
		if ($className != "SessionManager") 
			$sm = new SessionManager($this->curl, $this->db, $this->publicKey, null);
	
		return new $className($this->curl, $this->db, $this->publicKey, $sm);
	}

}

?>