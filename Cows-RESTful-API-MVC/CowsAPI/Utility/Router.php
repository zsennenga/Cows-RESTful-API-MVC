<?php
namespace CowsAPI\Utility;

/**
 *
 * The router takes a given URI and parses out URI parameters, and decides which
 * class to instantiate.
 *
 * Takes in a Log object and a string containing a json encoded route listing.
 *
 * @param Log $logger
 * @param String $routeSource
 */
class Router {
	
	private $routeArray;
	private $log;
	private $class;
	private $params;
	private $prefix;
	
	/**
	 * Creates an object which uses the Json routes file to calculate
	 * which controller needs to be created and which method on it to invoke
	 * 
	 * @param Log $logger
	 * @param Json $routeSource
	 * @throws \InvalidArgumentException
	 */
	public function __construct(Log $logger, $routeSource)	{
		$this->routeArray = json_decode($routeSource, true);
		if ($this->routeArray == false) throw new \InvalidArgumentException("Invalid Json");
		$this->log = $logger;
		$this->prefix = null;
		$this->class = "NoRoute";
		$this->params = array();
	}
	
	/**
	 * If your URLs have a prefix, set this
	 * @param unknown $p
	 */
	public function setPrefix($p)	{
		$this->prefix = $p;
	}
	
	/**
	 * 
	 * Takes a URI and an HTTPMethod and figures out where to shunt you to.
	 * 
	 * @param HTTP Method $method
	 * @param URI $route
	 */
	public function setRoute($method, $route)	{
		//Reset internal state
		$this->class = "NoRoute";
		$this->params = array();
		$this->method = $method;
		
		if ($this->prefix != null) $route = preg_replace("|". preg_quote($this->prefix) . "|iA", "", $route);
		$this->log->setRoute($method, $route);
		
		$routeParts = array_filter(explode("/", $route), 'strlen');
		$uri = strtolower(array_shift($routeParts));
		
		if (isset($this->routeArray[$uri]))	{
			foreach($this->routeArray[$uri] as $route)	{
				if ($route['method'] == $method && count($route['params']) == count($routeParts))	{
					$this->class = $route['class'];
					foreach ($route['params'] as $param)	{
						$this->params[$param] = array_shift($routeParts);
					}
					return;
				}
			}
		}
	}
	/**
	 * Gets a specific parameter from the url (siteId, etc)
	 * 
	 * @param string $key
	 * @return Ambigous <multitype:, mixed>|NULL
	 */
	public function getParams($key = null)	{
		if ($key == null) 
			return $this->params;
		else if (!isset($this->params[$key])) 
			return null;
		else 
			return $this->params[$key];
	}
	
	/**
	 * Get the controller class associated with a route
	 * 
	 * @return string
	 */
	public function getClass()	{
		return $this->class;
	}
	
	/**
	 * Gets the HTTP Method to invoke on the controller
	 * @return string
	 */
	public function getMethod()	{
		if ($this->class != "NoRoute")	{
			return $this->method;
		}
		return "invoke";
	}
}