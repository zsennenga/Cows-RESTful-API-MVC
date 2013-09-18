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
/**
 * cowsRss
 * 
 * RSS Feed parsing class. Grabs the feed url (defaulting to front-tv) and parses it.
 * 
 * @throws SimplePie_Exception
 *
 */
class cowsRss	{
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
		$this->feed = new SimplePie();
		$this->feed->strip_htmltags(false);
	}
	/**
	 * 
	 */
	function setFeedData($data)	{
		$this->feed->set_raw_data($data);
		$ec = $this->feed->init();
		if (!$ec)	{
			new ParameterException(ERROR_RSS,$this->feed->error(), 400);
		}
		$this->feed->handle_content_type();
	}
	/**
	 * getRaw
	 * 
	 * Getter for the underlying simplepie feed.
	 * 
	 * @return SimplePie Feed
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
	function getData()	{

		$items = $this->feed->get_items();
		//$i is -1 because we increment it and the begining of every foreach iteration. We want it to start at 0
		$i = -1;
		$out = array();
		foreach($items as $item)	{
			$tok = strtok($item->get_content(), "\n");
			$i++;
			$out[$i] = array();
			//title and description are done first because these values are outside the normal parts of $item->get_content()
			$out[$i]['Title'] = array();
			$out[$i]['Description'] = array();
			array_push($out[$i]['Title'],$item->get_title());
			array_push($out[$i]['Description'],$item->get_description(true));
			while($tok !== false)	{
				$tokenArray = explode(": ",$tok);
				//This handles the case where we get a descriptor with null data. Replaces null with an empty string to avoid weirdness later
				if (!isset($tokenArray[1]))	{
					$tokenArray[0] = str_replace(':','',$tokenArray[0]);
					$tokenArray[1] = '';
				}
				//arrayify the key if not already
				if(!isset($out[$i][$tokenArray[0]])) {
					$out[$i][$tokenArray[0]] = array();
				}
				array_push($out[$i][$tokenArray[0]], $tokenArray[1]);
				$tok = strtok("\n");
			}
		}
		//$this->cache($out);
		return $out;
	}
}
?>