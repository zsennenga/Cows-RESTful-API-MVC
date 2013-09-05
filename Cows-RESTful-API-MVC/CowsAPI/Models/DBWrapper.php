<?php

namespace CowsAPIModels;

class DBWrapper implements \DBInterface	{
	
	private $dbHandle;
	private $params;
	
	public function DBWrapper()	{
		$this->dbHandle =  new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
		$this->dbHandle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->dbHandle->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$this->params = array();
	}
	
	public function addParam($key, $value)	{
		$this->params[$key] = $value;
	}
	
	public function query($stmt)	{
		$stmt = $this->dbHandle->prepare($stmt);
		foreach($this->params as $key => $value)	{
			$stmt->bindParam($key,$value);
		}
		if ($stmt->execute() == false) return array();
		$this->params = array();
		return $stmt->fetch();
	}
	
	public function close()	{
		$this->dbHandle->close();
	}
}

?>