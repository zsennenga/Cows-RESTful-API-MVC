<?php

namespace CowsAPI\Models;

use CowsAPI\Utility\URLBuilder;
use Symfony\Component\Process\Exception\InvalidArgumentException;
use CowsAPI\Exceptions\ParameterException;

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
	 * @param DomainObjectFactory $do
	 * @param DataMapperFactory $dm
	 * @param array $rp
	 * @param URLBuilder $ub
	 * @param Site ID $siteId
	 */
	public function __construct(DomainObjectFactory $do, DataMapperFactory $dm, Array $rp, URLBuilder $ub, $siteId)	{
		$this->requestParams = $rp;
		$this->dataMapperFactory = $dm;
		$this->domainObjectFactory = $do;
		$this->urlBuilder = $ub;
		
		$this->validateSiteId($siteId);
		$this->siteId = $siteId;
	}
	/**
	 * Auth curl such that any requests use whatever auth the user has
	 * already establised with POST /session
	 * 
	 * 
	 */
	public function authCowsSession()	{
		$sessionManager = $this->dataMapperFactory->get('SessionManager');
		$sessionManager->authCurl($this->siteId);
	}
	
	/**
	 * Essentially a quick macro to get a document and return whatever the parser has on it.
	 * 
	 * @param ClassName of a DomainObject Parser $parserClass
	 * @param String $url
	 * 
	 * 
	 */
	public function grabAndParse($parserClass, $url)	{
		$document = $this->grabDocument($url);
		$parser = $this->domainObjectFactory->get($parserClass);
		return $parser->parse($document);
	}
	/**
	 * Gets a document and returns it
	 * 
	 * @param URL $url
	 * 
	 */
	public function grabDocument($url)	{
		$documentGrabber = $this->dataMapperFactory->get('DocumentGrabber');
		
		$documentGrabber->setUrl($url);
		
		return $documentGrabber->getDocument($this->siteId);
	}
	
	/**
	 * Check if cows threw any errors
	 * 
	 * @param unknown $doc
	 * 
	 */
	public function parseForErrors($doc)	{
		$cowsErrorParser = $this->domainObjectFactory->get('CowsErrorParser');
		
		$cowsErrorParser->parse($doc);
		
		return;
	}
	
	/**
	 * Takes in a Ticket Granting Cookie and uses it to generate a ticket with the CAS Proxy Service
	 * @param Ticket Granting Cookie $tgc
	 */
	public function getServiceTicket()	{
		
		if (!isset($this->requestParams['tgc'])) throw new ParameterException(ERROR_PARAMETERS, "TGC must be provided to create a ticket", 400);
		
		$url = $this->urlBuilder->getCasProxyURL("http://cows.ucdavis.edu/its/Account/LogOn?returnUrl=http://cows.ucdavis.edu/its", $this->requestParams['tgc']);
		return $this->grabAndParse('CasParser', $url);
	}
	
	/**
	 * Checks that a given siteId points to a valid COWS site
	 * 
	 * @param SiteId $siteId
	 */
	private function validateSiteId($siteId)	{
		$siteValidator = $this->dataMapperFactory->get('SiteValidator');
		
		if ($siteId == null || !$siteValidator->validSite($siteId))
			throw new ParameterException(ERROR_PARAMETERS,"Invalid Site Id", 400);
	}
	
	/**
	 * Takes in an event id and gets all the parameters describing it.
	 * 
	 * @param Event ID $eventId
	 * 
	 */
	public function getEventById($eventId)	{
		
		return $this->grabAndParse('SingleEventParser', $this->urlBuilder->getCowsEventIdUrl($this->siteId,$eventId));
	}
	
	/**
	 * Parses the RSS feed described by the passed GET parameters and parses it to an array.
	 * 
	 */
	public function getEvents()	{
		$parser = $this->domainObjectFactory->get("RssParser");
		
		if (isset($this->requestParams['timeStart']) && isset($this->requestParams['timeEnd']))	{
			$parser->setTimeBound($this->requestParams['timeStart'],$this->requestParams['timeEnd']);
			unset($this->requestParams['timeEnd']);
			unset($this->requestParams['timeStart']);
		}
		
		$doc = $this->grabDocument($this->urlBuilder->getCowsRssUrl($this->siteId, $this->requestParams));
		
		return $parser->parse($doc);
	}
	
	/**
	 * Applies some special transformations necessary to handle multiple categories and display locations.
	 * 
	 * Then it goes and scrapes the cows event page for all the parameters cows gets from LDAP.
	 * 
	 * @return Parameters as a request body
	 */
	public function buildEventParams()	{
		if (!isset($this->requestParams['Categories'])) throw new ParameterException(ERROR_PARAMETERS,"Categories is a required field.",400);
		
		$appendString = $this->getCatLoc();
		
		$paramArray= array_merge($this->requestParams,$this->getCowsFields());
		unset($paramArray['Categories']);
		unset($paramArray['Locations']);
		//$paramArray= array_merge($this->requestParams,array());
		$paramArray['siteId'] = $this->siteId;
		
		return http_build_query($paramArray) . $appendString;
	}
	
	/**
	 * Gets the category and location parameters strings
	 * @return string
	 */
	private function getCatLoc()	{
		$retVal = "";
		$retVal .= $this->explodeParameter("Categories", $this->requestParams['Categories']);
		if (isset($this->requestParams['Locations']))
			$retVal .= $this->explodeParameter("DisplayLocations", $this->requestParams['Locations']);
		return $retVal;
	}
	/**
	 * Explodes an ampersand delimited list into a list of HTTP post parameters
	 * @param POST Field Name $fieldName
	 * @param Values $data
	 * @return string
	 */
	private function explodeParameter($fieldName, $data)	{
		if (strlen($data) == 0)  return "";
		$retString = "";
		$data = explode("&",$data);
		foreach($data as $str)	{
			$retString .= '&' . $fieldName . '=' . urlencode($str);
		}
		return $retString;
	}
	
	/**
	 * Scrapes the cows event page and gets all the fields necessary to create an event.
	 * 
	 * @return multitype:NULL string
	 * 
	 */
	public function getCowsFields()	{
		
		$url = $this->urlBuilder->getCowsEventUrl($this->siteId);
		$cowsDocParser = $this->grabAndParse('FieldParser', $url);
		
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
	 * 
	 */
	public function checkSession()	{
		$this->authCowsSession();
		$url = $this->urlBuilder->getCowsEventUrl($this->siteId);
		$cowsDocParser = $this->dataMapperFactory->get("SiteValidator");
		
		return $cowsDocParser->checkNoRedirect($url);
	}
	
	/**
	 * Deletes the event with a given ID.
	 * 
	 * @param $eventId $eventId
	 * 
	 */
	public function deleteEvent($eventId)	{		
		
		$url = $this->urlBuilder->getEventDeleteUrl($this->siteId, $eventId);
		$cowsDocParser = $this->grabAndParse('FieldParser', $url);
		
		$params = array(
				"SiteId" => $this->siteId,
				"EventId" => $eventId,
				"__RequestVerificationToken" => $cowsDocParser->getNodeValue("__RequestVerificationToken"),
				"timestamp" => "AAAAAAAOH6s="
		);
		

		$doc = $this->dataMapperFactory->get('basicPost')->execute($url,$params);
		
		$this->parseForErrors($doc);
		return true;
	}
	
	/**
	 * Creates an event with the given Parameters.
	 * 
	 * @param String $params
	 * 
	 */
	public function createEvent($params)	{
		
		$url = $this->urlBuilder->getCowsEventUrl($this->siteId);
		$doc = $this->dataMapperFactory->get('basicPost')->execute($url,$params);
		
		$this->parseForErrors($doc);
		
		return $this->findEventId();
	}
	
	public function findEventId()	{
		$baseUrl = $this->urlBuilder->getCowsEventJson($this->siteId);
		
		$idParams = array();
		$idParams['startDate'] = $this->requestParams['StartDate'];
		$idParams['endDate'] = $this->requestParams['EndDate'];
		$idParams = http_build_query($idParams);
		$idParams .= $this->explodeParameter("Categories", $this->requestParams['Categories']);
		
		$url = $baseUrl . "?" . $idParams;
		
		$doc = $this->grabDocument($url);
		
		$parser = $this->domainObjectFactory->get('EventJsonParser');
		
		$parser->setEventTitle($this->requestParams['EventTitle']);
		$parser->setBuildingRoom($this->requestParams['BuildingAndRoom']);
		
		return $parser->parse($doc);
		
	}
	
	/**
	 * Logout from cows.
	 * 
	 */
	public function destroySession()	{
		
		$url = $this->urlBuilder->getCowsLogoutUrl($this->siteId);
		
		$cowsSessionManager = $this->dataMapperFactory->get('SessionManager');
		$cowsSessionManager->destroy($this->siteId, $url);
		$doc = $this->dataMapperFactory->get('basicPost')->execute($url,$this->requestParams	);
		
		$this->parseForErrors($doc);
		
	}
	/**
	 * Login from Cows
	 * 
	 * @param CAS Ticket $ticket
	 * 
	 */
	public function createSession($ticket)	{
		
		$url = $this->urlBuilder->getCowsLoginUrl($this->siteId, $ticket);
		$cowsSessionTable = $this->dataMapperFactory->get('SessionManager');
		
		$doc = $cowsSessionTable->create($this->siteId, $url);
		
		$this->parseForErrors($doc);
	}
	
	/**
	 * Sets the request parameters
	 * @param string $p
	 * 
	 */
	public function setParams($p)	{
		$this->requestParams = $p;
	}
	/**
	 * Wrapper for object creation
	 * 
	 * 
	 */
	public function getPrivateKey()	{
		$keyDB = $this->dataMapperFactory->get("KeyTable");
		return $keyDB->getPrivateKey();
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
		$privateKey = $this->getPrivateKey();
		if ($privateKey === false) return false;
		
		$authChecker = $this->domainObjectFactory->get('AuthChecker');
		
		return $authChecker->checkSignature($signature, 
											$privateKey,
											$timeStamp,
											$method,
											$uri,
											http_build_query($this->requestParams));
		
	}
}

?>