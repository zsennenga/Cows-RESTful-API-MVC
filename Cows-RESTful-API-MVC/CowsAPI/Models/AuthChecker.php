<?php

namespace CowsAPI\Models;

class AuthChecker {

	public function __construct()	{
		
	}
	
	public function checkSignature($sig, $key, $timeStamp, $method, $uri, $params)	{
		return strtolower($sig) == strtolower(hash_hmac("sha256", $method.$uri.$params.$timeStamp, $key));
	}

}
?>