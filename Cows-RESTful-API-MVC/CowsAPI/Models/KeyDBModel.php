<?php

namespace CowsAPIModels;

class KeyDBModel	{
	private $db;
	private $publicKey;
	private $privateKey;
	
	public function __construct($db)	{
		$this->db = $db;
		$this->publicKey = null;
		$this->privateKey = null;
	}
	
	public function setActivePublicKey($publicKey)	{
		$this->publicKey = $publicKey;
	}
	
	public function getPublicKey()	{
		return $this->publicKey;
	}
	
	public function setCookieFile($cookie)	{
		
	}
	
	public function getCookieFile()	{
		if ($this->publicKey == null) return null;
		
	}
	
	public function getPrivateKey()	{
		if ($this->publicKey == null) return null;
		
		
	}
}