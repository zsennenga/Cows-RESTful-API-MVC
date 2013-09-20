<?php

namespace CowsAPI\Models\DataMappers;
use CowsAPI\Exceptions\ParameterException;
use CowsAPI\Models\DataMappers\SessionObjects\SessionDB;
/**
 * Manages the connection between a Cows session and the DB/Curl parameters that maintain it
 * 
 * @author its-zach
 * 
 */
class SessionManager extends GenericDataMapper  {
	/**
	 * Login to cows and store the cookies in the DB
	 * @param siteid $siteId
	 * @param login url $url
	 */
	public function create($siteId, $url)	{
		$cookieFile = $this->createDB($siteId);
		return $this->createCurl($url, $cookieFile);
	}
	/**
	 * Creates a session on COWS end with CURL
	 * @param login url $url
	 * @param cookie storage location $cookieFile
	 */
	public function createCurl($url,$cookieFile)	{
		$this->curl->setOption(CURLOPT_CUSTOMREQUEST, "GET");
		$this->curl->setOption(CURLOPT_URL, $url);
		$this->curl->setOption(CURLOPT_COOKIEJAR, $cookieFile);
		$this->curl->setOption(CURLOPT_COOKIEFILE, $cookieFile);
	
		return $this->curl->execute();
	}
	/**
	 * Creates a session on the API end with the DB
	 * @param login url $url
	 * @param cookie storage location $cookieFile
	 */
	public function createDB($siteId)	{
		if (!$this->sessionExists($siteId))	{
			$cookieFile = $this->genFilename();
	
			if ($this->publicKey == null )
				throw new ParameterException(ERROR_GENERIC, "Public key should not be null", 500);
	
			$this->db->addParam(":publicKey", $this->publicKey);
			$this->db->addParam(":siteId", $siteId);
			$this->db->addParam(":cookieFile", $cookieFile);
	
			$this->db->query("INSERT INTO " . DB_TABLE_SESSION  . " VALUES (:publicKey, :siteId, :cookieFile)");
		}
		else {
			$cookieFile = $this->getCookieFile($siteId);
			$cookieFile = $cookieFile['cookieFile'];
		}
		return $cookieFile;
	}
	
	/**
	 * Logout from cows, delete any cookies, and update the DB
	 * 
	 * @param SiteID $siteId
	 * @param URL $url
	 */
	public function destroy($siteId, $url)	{
		if ($url != null) $this->destroyCurl($url);
		$this->destroyDB($siteId);
	}
	/**
	 * Destroys the session on the COWS end with curl
	 * @param unknown $url
	 */
	public function destroyCurl($url)	{
		$this->curl->setOption(CURLOPT_CUSTOMREQUEST, "GET");
		$this->curl->setOption(CURLOPT_URL, $url);
		$this->curl->execute();
	}
	/**
	 * Destroys the session on the API end with the DB
	 * @param SiteID $siteId
	 */
	public function destroyDB($siteId)	{
		$c = $this->getCookieFile($siteId);
		if ($c === false) return;
		@unlink($c['cookieFile']);
		
		$this->db->addParam(":siteId", $siteId);
		$this->db->addParam(":publicKey", $this->publicKey);
		
		$out = $this->db->query("DELETE FROM " . DB_TABLE_SESSION  . " WHERE publicKey = :publicKey AND siteId = :siteId");
		return $out;
	}
	/**
	 * Tell curl where to find the cookies for any upcoming request
	 * 
	 * @param unknown $siteId
	 * 
	 */
	
	public function authCurl($siteId)	{
		
		$cookieFile = $this->getCookieFile($siteId);
		
		if ($cookieFile === false) 
			return false;
		
		$cookieFile = $cookieFile['cookieFile'];
		
		$this->curl->setOption(CURLOPT_COOKIEJAR, $cookieFile);
		$this->curl->setOption(CURLOPT_COOKIEFILE, $cookieFile);
		
		return true;
	}
	/**
	 * Get Cookie File from the DB
	 * @param unknown $siteId
	 */
	public function getCookieFile($siteId)	{	
		$this->db->addParam(":siteId", $siteId);
		$this->db->addParam(":publicKey", $this->publicKey);
	
		return $this->db->query("SELECT cookieFile FROM " . DB_TABLE_SESSION . " WHERE publicKey = :publicKey AND siteId = :siteId");
	}
	/**
	 * Generate a random filename for the cookie file
	 *
	 * @return string
	 * @codeCoverageIgnore
	 */
	
	private function genFilename()	{
		$charset = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$randString = '';
		for ($i = 0; $i < 15; $i++) {
			$randString .= $charset[rand(0, strlen($charset)-1)];
		}
		$path = DIRECTORY_SEPARATOR . "Data" . DIRECTORY_SEPARATOR . "cookies" . DIRECTORY_SEPARATOR . "cookieFile" . $randString;
		return realpath(dirname(dirname(dirname(__FILE__)))) . $path;
	}	
	/**
	 * Check if the user has an active session in the DB
	 * @param unknown $siteId
	 * @return boolean
	 */
	private function sessionExists($siteId)	{
		$this->db->addParam(":publicKey", $this->publicKey);
		$this->db->addParam(":siteId", $siteId);
		$out = $this->db->query("SELECT * FROM " . DB_TABLE_SESSION  . " WHERE publicKey = :publicKey AND siteId = :siteId");
		return ($out !== false);
	}
}
?>