<?php

namespace CowsAPI\Models;

class GenericDataMapper	{

	protected $curl;
	protected $db;
	protected $publicKey;
	protected $sessionManager;
	
	public final function __construct(CurlWrapper $curl, DBWrapper $db, $pk, SessionManager $sm = null)	{
		
		$this->publicKey = $pk;
		$this->curl = $curl;
		$this->db = $db;
		$this->sessionManager = $sm;
		
	}

}
?>