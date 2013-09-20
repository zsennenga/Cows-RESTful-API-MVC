<?php

namespace CowsAPI\Models\DataMappers;
/**
 * Checks if a site is valid
 * @author its-zach
 *
 */
class SiteValidator extends GenericDataMapper{

	public function validSite($siteId)	{
		if ($siteId === null) return false;
		$url = COWS_BASE_PATH . $siteId;
		$this->curl->setOption(CURLOPT_URL, $url);
		$this->curl->execute();
		$last = $this->curl->getInfo(CURLINFO_EFFECTIVE_URL);
		if (strpos($last,"aspxerrorpath") !== false) return false;
		return true;
	}
	
	public function checkNoRedirect($url)	{
		$this->curl->setOption(CURLOPT_URL, $url);
		$this->curl->execute();
		$last = $this->curl->getInfo(CURLINFO_EFFECTIVE_URL);
		if ($url != $last) return false;
		return true;
	}

}
?>