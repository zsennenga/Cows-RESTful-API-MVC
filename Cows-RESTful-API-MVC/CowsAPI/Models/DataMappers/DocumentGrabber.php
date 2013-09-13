<?php

namespace CowsAPI\Models\DataMappers;
/**
 * Gets the HTTP document at the specified URL
 * @author its-zach
 *
 */
class DocumentGrabber extends GenericDataMapper {
	
	public function setUrl($url)	{
		$this->curl->setOption(CURLOPT_URL, $url);
	}
	
	/**
	 * 
	 * Gets the document at the preset URL.
	 * 
	 * If
	 * 
	 * @param Site ID $siteId
	 */
	public function getDocument($siteId)	{
		$this->curl->setOption(CURLOPT_CUSTOMREQUEST, "GET");
		return $this->curl->execute();
	}
	
}

?>