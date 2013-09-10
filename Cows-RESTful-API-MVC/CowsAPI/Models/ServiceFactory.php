<?php

namespace CowsAPI\Models;

class ServiceFactory	{
	
	private $requestParams;
	private $dataMapperFactory;
	private $domainObjectFactory;
	
	private $siteId;
	
	private $urlBuilder;
	
	public function __construct($do, $dm, $rp, $ub)	{
		$this->requestParams = $rp;
		$this->dataMapperFactory = $dm;
		$this->domainObjectFactory = $do;
		$this->urlBuilder = $ub;
	}
	
	public function grabAndParse($parserClass, $url)	{
		$documentGrabber = $this->dataMapperFactory->get('DocumentGrabber');
		$parser = $this->domainObjectFactory->get($parserClass);
		
		$documentGrabber->setUrl($url);
		
		$document = $documentGrabber->getDocument();
		return $parser->parse($document);
	}
	
	public function getServiceTicket($tgc)	{
		//TODO build URL
		//"http://cows.ucdavis.edu/its/Account/LogOn?returnUrl=http://cows.ucdavis.edu/its"
		
		return $this->grabAndParse('CasParser', $url);
	}
	
	public function validateSiteId()	{
		$document = $this->dataMapperFactory->get('DocumentGrabber');
		
		return $document->validSite();
	}
	
	public function getEventById($eventId)	{
		//TODO Build URL
		return $this->grabAndParse('SingleEventParser', $url);
	}
	
	public function getEvents()	{
		//TODO Build URL
		return $this->grabAndParse('RSSParser', URL);
	}
	
	public function buildEventParams()	{
		if (!isset($this->requestParams['Categories'])) throw new InvalidArgumentException("Categories is a required field.");
		$cat = urldecode($this->requestParams['Categories']);
		unset($this->requestParams['Categories']);
		
		$catString = "";
		if (strlen($cat) > 0) {
			$cat = explode("&",$cat);
			foreach($cat as $str)	{
				$catString .= "&Categories=" . urlencode($str);
			}
		}
		
		$locString = "";
		if (isset($this->requestParams['Locations']))	{
			$loc = urldecode($this->requestParams['Locations']);
			unset($this->requestParams['Locations']);
			if (strlen($loc) > 0) {
				$loc = explode("&",$loc);
				foreach($loc as $str)	{
					$locString .= "&DisplayLocations=" . urlencode($str);
				}
			}
				
		}
		
		$appendString = "";
		
		if ($catString != "")	{
			$appendString .= $catString;
		}
		
		if ($locString != "")	{
			$appendString .= $locString;
		}
		
		return http_build_query(array_merge($this->requestParams,$this->getCowsFields())) . $appendString . "&siteId=" . $this->siteId;
	}
	
	public function getCowsFields()	{
		$cowsDocParser = $this->domainObjectFactory->get('FieldParser');
		$cowsEventDoc = $this->dataMapperFactory->get('DocumentGrabber');
		
		//TODO calculate URL;
		$cowsEventDoc->setUrl();
		
		$cowsDocParser->setDocument($cowsEventDoc->getDocument());
		
		$cowsDocParser->getNodeValue("__RequestVerificationToken");
		$phone = $cowsDocParser->getNodeValue("ContactPhone");
		if ($phone == "" || $phone == null) $phone = "No Phone";
		return array(
				"__RequestVerificationToken" => $cowsDocParser->getNodeValue("__RequestVerificationToken"),
				"ContactName" => $cowsDocParser->getNodeValue("ContactName"),
				"ContactPhone" => $phone,
				"ContactEmail" => $cowsDocParser->getNodeValue("ContactEmail"),
				"EventStatusName" => $cowsDocParser->getNodeValue("EventStatusName")
		);
	}
	
	public function checkSession()	{
		$cowsDocParser = $this->domainObjectFactory->get('FieldParser');
		$cowsEventDoc = $this->dataMapperFactory->get('DocumentGrabber');
		
		//TODO calculate URL;
		$cowsEventDoc->setUrl();
		
		$cowsDocParser->setDocument($cowsEventDoc->getDocument());
		
		return strpos($cowsDocParser->getNodeValue('login'), "Log Off") === false;
	}
	
	public function deleteEvent($eventId)	{		
		$cowsDocParser = $this->domainObjectFactory->get('FieldParser');
		$cowsEventDoc = $this->dataMapperFactory->get('DocumentGrabber');

		//TODO build url
		$url = "";
		$cowsEventDoc->setUrl($url);
		
		$cowsDocParser->setDocument($cowsEventDoc->getDocument());
		
		$params = array(
				"SiteId" => $this->siteId,
				"EventId" => $eventId,
				"__RequestVerificationToken" => $cowsDocParser->getNodeValue("__RequestVerificationToken"),
				"timestamp" => "AAAAAAAOH6s="
		);
		
		$cowsErrorParser = $this->domainObjectFactory->get('CowsErrorParser');
		
		$doc = $this->dataMapperFactory->basicPOST($url,$params);
		$cowsErrorParser->setDocument($doc);
		$cowsErrorParser->parse();
	}
	
	public function createEvent($params)	{
		//TODO build URL;
		$url = '';
		
		$cowsErrorParser = $this->domainObjectFactory->get('CowsErrorParser');
		
		$doc = $this->dataMapperFactory->basicPOST($url,$params);
		$cowsErrorParser->setDocument($doc);
		$cowsErrorParser->parse();
	}
	
	public function destroySession()	{
		//TODO build URL;
		$url = '';
		
		$cowsErrorParser = $this->domainObjectFactory->get('CowsErrorParser');
		
		$doc = $this->dataMapperFactory->basicPOST($url,$params);
		$cowsErrorParser->setDocument($doc);
		$cowsErrorParser->parse();
	}
	
	public function createSession($ticket)	{
		//TODO build URL;
		$url = '';
		
		$cowsErrorParser = $this->domainObjectFactory->get('CowsErrorParser');
		
		$doc = $this->dataMapperFactory->basicGET($url,$params);
		$cowsErrorParser->setDocument($doc);
		$cowsErrorParser->parse();
	}
	
	public function setSiteId($siteId)	{
		$this->siteId = $siteId;
	}
	
	public function setParams($p)	{
		$this->requestParams = $p;
	}
}

?>