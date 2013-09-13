<?php

namespace CowsAPI\Models\DomainObjects;
/**
 * Used to check if a user's request has a valid signature
 * @author its-zach
 *
 */
class AuthChecker {

	public function __construct()	{
		
	}
	
	/**
	 * Uses Hash HMAC to compare a calculated signature with the correct one
	 * @param Signature $sig
	 * @param Private Key $key
	 * @param Timestamp $timeStamp
	 * @param HTTP Method $method
	 * @param Request URI $uri
	 * @param Request Parameter $params
	 * @return boolean
	 */
	public function checkSignature($sig, $key, $timeStamp, $method, $uri, $params)	{
		return strtolower($sig) == strtolower(hash_hmac("sha256", $method.$uri.$params.$timeStamp, $key));
	}

}
?>