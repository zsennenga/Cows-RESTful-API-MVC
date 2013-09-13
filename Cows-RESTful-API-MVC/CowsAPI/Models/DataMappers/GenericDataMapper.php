<?php

namespace CowsAPI\Models\DataMappers;

use CowsAPI\Models\DB\DBWrapper;
use CowsAPI\Models\HTTP\CurlWrapper;

class GenericDataMapper	{

	protected $curl;
	protected $db;
	protected $publicKey;
	
	public final function __construct(CurlWrapper $curl, DBWrapper $db, $pk)	{
		
		$this->publicKey = $pk;
		$this->curl = $curl;
		$this->db = $db;
		
	}

}
?>