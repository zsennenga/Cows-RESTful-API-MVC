<?php
/**
 * cowsRss.php
 * 
 * File containing cowsRss class and associated functions
 * 
 * @author Zachary Ennenga
 */
namespace CowsAPI\Models\DomainObjects\RSS;

use CowsAPI\Exceptions\ParameterException;
//require_once __DIR__ .  '/../../../../vendor/simplepie/simplepie/autoloader.php';
/**
 * cowsRss
 * 
 * RSS Feed parsing class. Grabs the feed url (defaulting to front-tv) and parses it.
 * 
 * @throws SimplePie_Exception
 *
 */
class CowsRss	{
	/**
	 * 
	 * Raw SimplePie Feed
	 * 
	 * @var Simplepie Feed
	 */
	var $feed;
	/**
	 * __construct
	 * 
	 * Constructor for the cowsRSS class. Interfaces with the SimplePie library to do most of the
	 * heavy lifting with regards to rss parsing.
	 * 
	 * Either the /atom or /rss links from cows work. Do not use /ics links.
	 * 
	 * @param string $feedUrl
	 * @throws SimplePie_Exception
	 */
	function __construct()	{
		$this->feed = new \SimplePie();
		$this->feed->strip_htmltags(false);
	}
	/**
	 * 
	 */
	function setFeedData($data)	{
		$this->feed->set_raw_data($data);
		$ec = $this->feed->init();
		$this->feed->handle_content_type();
	}
	/**
	 * getRaw
	 * 
	 * Getter for the underlying simplepie feed.
	 * 
	 * @return SimplePie Feed
	 * @codeCoverageIgnore
	 */
	function getRaw()	{
		return $this->feed;
	}
	/**
	 * 
	 * getData
	 * 
	 * Returns an array of events parsed from the feed the object was constructed with.
	 * 
	 * The array keys are the field descriptors (Title, Description, etc). Each array key references another array, 
	 * whos values are the actual values the descriptor references. This is done because some descriptors (Description, primarily) can have multiple values
	 * 
	 * 
	 * @param int $cacheTime
	 * @return array
	 */
	public function getData()	{

		$items = $this->feed->get_items();
		$i = 0;
		$out = array();
		foreach($items as $item)	{
			$out[$i++] = $this->getSingleItem($item);
		}
		return $out;
	}
	
	public function getSingleItem($item)	{
		$out = array();
		//title and description are done first because these values are outside the normal parts of $item->get_content()
		$out['Title'] = array($item->get_title());
		$out['Description'] = array($item->get_description(true));
		
		$tokenArray = explode("\n",$item->get_content());
		
		foreach ($tokenArray as $token)	{
			$values = explode(": ",$token);
			$key = trim(str_replace(':','',$values[0]));
			$out[$key] = isset($values[1]) ? array($values[1]) : array("");
		}
		return $out;
	}
	
}
?>