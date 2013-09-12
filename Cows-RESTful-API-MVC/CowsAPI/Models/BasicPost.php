<?php

namespace CowsAPI\Models ;
/**
 * Class to perform a POST request with the given parameters to a given url
 * @author its-zach
 *
 */
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