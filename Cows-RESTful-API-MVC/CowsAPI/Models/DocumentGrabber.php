<?php

namespace CowsAPI\Models;

class DocumentGrabber extends GenericDataMapper {
	
	public function setUrl($url)	{
		$this->curl->setOption(CURLOPT_URL, $url);
	}
	
	public function getDocument($siteId)	{
		$this->sessionManager->authCurl($siteId);
		$this->curl->setOption(CURLOPT_CUSTOMREQUEST, "GET");
		return $this->curl->execute();
	}
	
}

?>