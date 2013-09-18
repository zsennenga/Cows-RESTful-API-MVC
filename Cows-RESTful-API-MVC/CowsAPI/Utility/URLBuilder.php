<?php
namespace CowsAPI\Utility;
/**
 * Creates URLs from config defines for use by the models/controllers
 * 
 * @author its-zach
 * @codeCoverageIgnore
 */
class URLBuilder	{
	
	public function __construct()	{
		
	}
	
	public function getCasProxyURL($serviceName, $tgc)	{
		
		return CAS_PROXY_PATH . "?pgt=" . urlencode($tgc) . "&service=" . urlencode($serviceName);
	}
	
	public function getCasLogoutUrl()	{
		return CAS_PROXY_PATH;
	}
	
	public function getCowsEventUrl($siteId)	{
		return COWS_BASE_PATH . $siteId . COWS_BASE_EVENT_PATH;
	}
	
	public function getCowsLoginUrl($siteId, $ticket)	{
		return COWS_BASE_PATH . $siteId . COWS_LOGIN_PATH . "?returnUrl=http://cows.ucdavis.edu/". $siteId .  "&ticket=" . urlencode($ticket);
	}
	
	public function getCowsLogoutUrl($siteId)	{
		return COWS_BASE_PATH . $siteId . COWS_LOGOUT_PATH;
	}
	
	public function getCowsRssUrl($siteId, $params)	{
		return COWS_BASE_PATH . $siteId . COWS_RSS_PATH . "?" . http_build_query($params);
	}
	
	public function getCowsEventIdUrl($siteId, $eventId)	{
		return COWS_BASE_PATH . $siteId . COWS_BASE_EVENT_PATH . "/details/" . $eventId;
	}
	
	public function getEventDeleteUrl($siteId)	{
		return COWS_BASE_PATH . $siteId . COWS_DELETE_PATH;
	}
	
	public function getCowsBaseUrl($siteId)	{
		return COWS_BASE_PATH . $siteId;
	}
	
	public function getCowsEventJson($siteId)	{
		COWS_BASE_PATH . $siteId . COWS_BASE_EVENT_PATH . "/jsonbyday";
	}
}
?>