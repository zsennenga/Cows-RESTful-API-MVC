<?php

namespace CowsAPI\Models\DB;

/**
 * PDO Database implementation
 * 
 * @author its-zach
 * 
 *
 */
class DBWrapper implements DBInterface	{
	
	private $dbHandle;
	private $params;
	
	public function __construct()	{
		$this->dbHandle =  new \PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
		$this->dbHandle->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
		$this->params = array();
	}
	
	public function addParam($key, $value)	{
		$this->params[$key] = $value;
	}
	
	public function query($stmt)	{
		$stmt = $this->dbHandle->prepare($stmt);
		$params =& $this->params;
		foreach($params as $key => &$value)	{
			$stmt->bindParam($key,$value);
		}
		$this->params = array();
		if ($stmt->execute() == false) return array();
		return $stmt->fetch();
	}
}

?>