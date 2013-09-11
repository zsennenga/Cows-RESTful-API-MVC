<?php

namespace CowsAPI\Models ;

class BasicPost extends GenericDataMapper {

	public function execute($url, $params)	{
		$this->curl->setOption(CURLOPT_URL, $url);
		$this->curl->setOption(CURLOPT_POSTFIELDS, $params);
		$this->curl->setOption(CURLOPT_CUSTOMREQUEST, "POST");
	
		$out = $this->curl->execute();
	
		$this->curl->setOption(CURLOPT_POSTFIELDS, null);
	
		return $out;
	}
}
?>