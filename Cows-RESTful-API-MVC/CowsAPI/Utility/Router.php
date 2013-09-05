<?php
namespace CowsApiUtility;

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
	
	public function __construct($logger, $routeSource)	{
		$this->routeArray = json_decode($routeSource, true);
		if ($this->routeArray == false) throw new \InvalidArgumentException("Invalid Json");
		$this->log = $logger;
		$this->prefix = null;
		$this->class = "NoRoute";
		$this->params = array();
	}
	
	public function setPrefix($p)	{
		$this->prefix = $p;
	}
	
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
	
	public function getParams($key = null)	{
		if ($key == null) 
			return $this->params;
		else if (!isset($this->params[$key])) 
			return null;
		else 
			return $this->params[$key];
	}
	
	public function getClass()	{
		return $this->class;
	}
	
	public function getMethod()	{
		if ($this->class != "NoRoute")	{
			return $this->method;
		}
		return "invoke";
	}
}