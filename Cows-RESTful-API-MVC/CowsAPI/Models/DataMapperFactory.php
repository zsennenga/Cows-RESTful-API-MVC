<?php
namespace CowsAPI\Models;

class DataMapperFactory	{
	
	private $db;
	private $curl;
	
	public function __construct(DbWrapper $db, CurlWrapper $curl)	{
		$this->db = $db;
		$this->curl = $curl;
	}
	
	public function get($className)	{
		$className = "\\CowsApi\\Models\\" . $className;
		return new $className($this->curl, $this->db);
	}
	
	public function basicPost($url, $params)	{
		$this->curl->setOption(CURLOPT_URL, $url);
		$this->curl->setOption(CURLOPT_POSTFIELDS, $params);
		$this->curl->setOption(CURLOPT_CUSTOMREQUEST, "POST");
		
		$out = $this->curl->execute(); 
		
		$this->curl->setOption(CURLOPT_POSTFIELDS, null);
		
		return $out;
	}
}

?>