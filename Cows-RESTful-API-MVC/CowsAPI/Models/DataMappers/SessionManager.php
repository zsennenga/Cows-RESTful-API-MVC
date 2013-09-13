<?php

namespace CowsAPI\Models\DataMappers;

class SessionManager extends GenericDataMapper  {

private function genFilename()	{
		$charset = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$randString = '';
		for ($i = 0; $i < 15; $i++) {
			$randString .= $charset[rand(0, strlen($charset)-1)];
		}
		$path = DIRECTORY_SEPARATOR . "Data" . DIRECTORY_SEPARATOR . "cookies" . DIRECTORY_SEPARATOR . "cookieFile" . $randString;
		return realpath(dirname(dirname(__FILE__))) . $path;
	}
	
	public function create($siteId, $url)	{
		$this->curl->setOption(CURLOPT_CUSTOMREQUEST, "GET");
		$this->curl->setOption(CURLOPT_URL, $url);
		
		$this->db->addParam(":siteId", $siteId);
		$this->db->addParam(":publicKey", $this->publicKey);
		$this->db->addParam(":cookieFile",  $this->genFilename());
		
		$this->db->query("INSERT INTO " . DB_TABLE_SESSION  . " VALUES (:publicKey, :siteId, :cookieFile)");
		
		curl_setopt($this->curlHandle, CURLOPT_COOKIEJAR, $this->cookieFile);
		curl_setopt($this->curlHandle, CURLOPT_COOKIEFILE, $this->cookieFile);
		
		return $this->curl->execute();
	}
	
	private function getCookieFile($siteId)	{
		
		$this->db->addParam(":siteId", $siteId);
		$this->db->addParam(":publicKey", $this->publicKey);
		
		return $this->db->query("SELECT cookieFile FROM " . DB_TABLE_SESSION . " WHERE publicKey = :publicKey AND siteId = :siteId");
	}
	
	public function destroy($siteId, $url)	{
		$this->curl->setOption(CURLOPT_CUSTOMREQUEST, "GET");
		$this->curl->setOption(CURLOPT_URL, $url);
		
		$c = $this->getCookieFile($siteId);
		if ($c === false) return;
		unlink($c['cookieFile']);
		
		$this->db->addParam(":siteId", $siteId);
		$this->db->addParam(":publicKey", $this->publicKey);
		
		$this->db->query("DELETE FROM " . DB_TABLE_SESSION  . " WHERE publicKey = :publicKey AND siteId = :siteId");
		
	}
	
	public function authCurl($siteId)	{
		
		$cookieFile = $this->getCookieFile($siteId);
		
		if ($cookieFile === false) return;
		
		curl_setopt($this->curlHandle, CURLOPT_COOKIEJAR, $cookieFile);
		curl_setopt($this->curlHandle, CURLOPT_COOKIEFILE, $cookieFile);
		
	}

}
?>