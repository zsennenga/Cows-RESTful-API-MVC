<?php
namespace CowsAPI\Utility;
/**
 * 
 * Logs certain parameters in the database after each request
 * 
 * @author its-zach
 * 
 */
class Log	{
	private $dbHandle;
	private $table;
	
	private $publicKey;
	private $route;
	private $response;
	private $params;
	private $method;
	
	public function __construct($dbHandle, $table)	{
		$this->dbHandle = $dbHandle;
		$this->table = $table;
		$this->publicKey = "";
		$this->route = "";
		$this->response = "";
		$this->params = "";
		$this->method = "";
	}
	
	public function setKey($pk)	{
		$this->publicKey = $pk;
	}
	
	public function setRoute($r,$m)	{
		$this->route = $r;
		$this->method = $m;
	}
	
	public function setResp($res)	{
		if (is_array($res)) $this->response = serialize($res);
		else $this->response = $res;
	}
	
	public function setParams($p)	{
		unset($p['tgc']);
		$p = http_build_query($p);
		$this->params = $p;
	}
	
	public function execute()	{
		$this->dbHandle->addParam(":ip", $_SERVER['REMOTE_ADDR']);
		$this->dbHandle->addParam(":pkey", $this->publicKey);
		$this->dbHandle->addParam(":route", $this->route);
		$this->dbHandle->addParam(":method", $this->method);
		$this->dbHandle->addParam(":params", $this->params);
		$this->dbHandle->addParam(":response", $this->response);
		$this->dbHandle->query("INSERT INTO ". DB_TABLE_LOG ." (ip,publicKey,route,method,params,response) VALUES (:ip,:pkey,:route,:method,:params,:response)");
	}
}
?>