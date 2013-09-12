<?php

namespace CowsAPI\Models;

use CowsAPI\Utility\URLBuilder;
use Symfony\Component\Process\Exception\InvalidArgumentException;
/**
 * 
 * ServiceFactory
 * 
 * Class to connect the DataMapperFactory and the DomainObjectFactory
 * 
 * Manages the passing of Http Documents to their respective parsers and for 
 * telling the Data mappers to go GET and POST things.
 * 
 * @author its-zach
 *
 */
class ServiceFactory	{
	
	private $requestParams;
	private $dataMapperFactory;
	private $domainObjectFactory;
	
	private $siteId;
	
	private $urlBuilder;

	/**
	 * Builds the service factory
	 * 
	 * Throws InvalidArgumentException if $siteId is invalid.
	 * 
	 * @param array $do
	 * @param DataMapperFactory $dm
	 * @param DomainObjectFactory $rp
	 * @param URLBuilder $ub
	 * @param Site ID $siteId
	 */
	public function __construct(Array $do, DataMapperFactory $dm, DomainObjectFactory $rp, URLBuilder $ub, $siteId)	{
		$this->requestParams = $rp;
		$this->dataMapperFactory = $dm;
		$this->domainObjectFactory = $do;
		$this->urlBuilder = $ub;
		
		$this->validateSiteId($siteId);
		$this->siteId = $siteId;
	}
	
	public function authCowsSession()	{
		$sessionManager = $this->dataMapperFactory->get('SessionManager');
		$sessionManager->authCurl($this->siteId);
	}
	
	/**
	 * Essentially a quick macro to get a document and return whatever the parser has on it.
	 * 
	 * @param ClassName of a DomainObject Parser $parserClass
	 * @param String $url
	 */
	private function grabAndParse($parserClass, $url)	{
		$documentGrabber = $this->dataMapperFactory->get('DocumentGrabber');
		$parser = $this->domainObjectFactory->get($parserClass);
		
		$documentGrabber->setUrl($url);
		
		$document = $documentGrabber->getDocument($this->siteId);
		return $parser->parse($document);
	}
	
	/**
	 * Takes in a Ticket Granting Cookie and uses it to generate a ticket with the CAS Proxy Service
	 * @param Ticket Granting Cookie $tgc
	 */
	public function getServiceTicket($tgc)	{
		
		$url = $this->urlBuilder->getCasProxyURL("http://cows.ucdavis.edu/its/Account/LogOn?returnUrl=http://cows.ucdavis.edu/its", $tgc);
		
		return $this->grabAndParse('CasParser', $url);
	}
	/**
	 * Checks that a given siteId points to a valid COWS site
	 * 
	 * @param SiteId $siteId
	 */
	private function validateSiteId($siteId)	{
		$siteValidator = $this->dataMapperFactory->get('SiteValidator');
		
		if (!$siteValidator->validSite($siteId))
			throw new InvalidArgumentException("Invalid Site Id");
	}
	
	/**
	 * Takes in an event id and gets all the parameters describing it.
	 * 
	 * @param Event ID $eventId
	 */
	public function getEventById($eventId)	{
		
		return $this->grabAndParse('SingleEventParser', $this->urlBuilder->getCowsEventIdUrl($siteId,$eventId));
	}
	
	/**
	 * Parses the RSS feed described by the passed GET parameters and parses it to an array.
	 * 
	 */
	public function getEvents()	{
		return $this->grabAndParse('RSSParser', $this->urlBuilder->getCowsRssUrl($this->siteId, $this->requestParams));
	}
	
	/**
	 * Applies some special transformations necessary to handle multiple categories and display locations.
	 * 
	 * Then it goes and scrapes the cows event page for all the parameters cows gets from LDAP.
	 * 
	 * @return Parameters as a request body
	 */
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
	
	/**
	 * Scrapes the cows event page and gets all the fields necessary to create an event.
	 * 
	 * @return multitype:NULL string
	 */
	private function getCowsFields()	{
		$cowsDocParser = $this->domainObjectFactory->get('FieldParser');
		$cowsEventDoc = $this->dataMapperFactory->get('DocumentGrabber');
		
		$cowsEventDoc->setUrl($this->urlBuilder->getCowsEventUrl($this->siteId));
		
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
	
	/**
	 * Checks that you're logged into cows.
	 * @return boolean
	 */
	public function checkSession()	{
		$cowsDocParser = $this->domainObjectFactory->get('FieldParser');
		$cowsEventDoc = $this->dataMapperFactory->get('DocumentGrabber');
		
		$cowsEventDoc->setUrl($this->urlBuilder->getCowsBaseUrl($this->siteId));
		
		$cowsDocParser->setDocument($cowsEventDoc->getDocument());
		
		return strpos($cowsDocParser->getNodeValue('login'), "Log Off") === false;
	}
	
	/**
	 * Deletes the event with a given ID.
	 * 
	 * @param $eventId $eventId
	 */
	public function deleteEvent($eventId)	{		
		$cowsDocParser = $this->domainObjectFactory->get('FieldParser');
		$cowsEventDoc = $this->dataMapperFactory->get('DocumentGrabber');

		$url = $this->urlBuilder->getEventDeleteUrl($eventId);
		$cowsEventDoc->setUrl($url);
		
		$cowsDocParser->setDocument($cowsEventDoc->getDocument());
		
		$params = array(
				"SiteId" => $this->siteId,
				"EventId" => $eventId,
				"__RequestVerificationToken" => $cowsDocParser->getNodeValue("__RequestVerificationToken"),
				"timestamp" => "AAAAAAAOH6s="
		);
		
		$cowsErrorParser = $this->domainObjectFactory->get('CowsErrorParser');
		
		$doc = $this->dataMapperFactory->get('basicPost')->execute($url,$params);
		$cowsErrorParser->setDocument($doc);
		$cowsErrorParser->parse();
	}
	
	/**
	 * Creates an event with the given Parameters.
	 * 
	 * @param String $params
	 */
	public function createEvent($params)	{
		
		$url = $this->urlBuilder->getCowsEventUrl($this->siteId);
		
		$cowsErrorParser = $this->domainObjectFactory->get('CowsErrorParser');
		
		$doc = $this->dataMapperFactory->get('basicPost')->execute($url,$params);
		$cowsErrorParser->setDocument($doc);
		$cowsErrorParser->parse();
		
		$this->findEventById($params);
	}
	
	/**
	 * Logout from cows.
	 */
	public function destroySession()	{
		
		$url = $this->urlBuilder->getCowsLogoutUrl($this->siteId);
		
		$cowsSessionManager = $this->dataMapperFactory->get('SessionManager');
		
		$cowsSessionManager->destroy($this->siteId, $url);
		
		$cowsErrorParser = $this->domainObjectFactory->get('CowsErrorParser');
		
		$doc = $this->dataMapperFactory->get('basicPost')->execute($url,$params);
		$cowsErrorParser->setDocument($doc);
		$cowsErrorParser->parse();
		
	}
	/**
	 * Login from Cows
	 * 
	 * @param CAS Ticket $ticket
	 */
	public function createSession($ticket)	{
		
		$url = $this->urlBuilder->getCowsLoginUrl($this->siteId, $ticket);
		$cowsSessionTable = $this->dataMapperFactory->get('SessionManager');
		
		$cowsSessionTable->create($this->siteId, $url);
		
		$cowsErrorParser = $this->domainObjectFactory->get('CowsErrorParser');
		
		$cowsErrorParser->setDocument($doc);
		$cowsErrorParser->parse();
	}
	
	/**
	 * Sets the request parameters
	 * @param string $p
	 */
	public function setParams($p)	{
		$this->requestParams = $p;
	}
	
	/**
	 * Checks that a given signature is valid
	 * 
	 * @param Timestamp of Request from Headers $timeStamp
	 * @param Signature $signature
	 * @param HTTP Request Method $method
	 * @param Request URI $uri
	 * @return boolean
	 */
	public function checkSignature($timeStamp, $signature, $method, $uri)	{
		$keyDB = $this->dataMapperFactory->get("KeyTable");
		
		if (($privateKey = $keyDB->getPrivateKey()) === false) return false;
		
		$authChecker = $this->domainObjectFactory->get('AuthChecker');
		
		return $authChecker->verifySignature($signature, 
											$privateKey,
											$timeStamp,
											$method,
											$uri,
											http_build_query($this->requestParams));
		
	}
}

?>