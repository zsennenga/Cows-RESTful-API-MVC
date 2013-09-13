<?php

namespace CowsAPI\Models\DataMappers;

class KeyTable extends GenericDataMapper  {
	
	
	public function getPrivateKey()	{
		$this->db->addParam(":publicKey", $this->publicKey);
		
		$out = $this->db->query("SELECT privateKey FROM " . DB_TABLE_KEY . " WHERE publicKey = :publicKey");
		
		if ($out === false || $out === array() || $out === null) return false;
		
		return $out['privateKey'];
	}
	
}
?>