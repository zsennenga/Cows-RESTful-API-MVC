<?php

require 'vendor/autoload.php';
require_once 'CowsApi/Data/Config.php';

//Handle headers
$headerManager = new \CowsAPI\Utility\HeaderManager(apache_request_headers());

//Instantiate all necessary objecst
$class = "\CowsAPI\\Templates\\".$headerManager->getResponseClass();
$template = new $class();

$db = new \CowsAPI\Models\DB\DBWrapper();
$log = new \CowsAPI\Utility\Log($db, $table);

$route = new \CowsAPI\Utility\Router($log, file_get_contents("CowsApi/Data/Routes.json"));
$route->setRoute($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

$curl = new \CowsAPI\Models\HTTP\CurlWrapper();

//Set Parameters
if ($_SERVER['REQUEST_METHOD'] == "GET") 
	$requestParams = $_GET;
else if ($_SERVER['REQUEST_METHOD'] == "POST") 
	$requestParams = $_POST;
else 
	$requestParams = array();
	
$log->setParams($requestParams);
try	{
	$serviceFactory = new \CowsAPI\Models\ServiceFactory(
			 		  	new \CowsAPI\Models\DomainObjectFactory(), 
					  	new \CowsAPI\Models\DataMapperFactory($db,$curl,$headerManager->getPublicKey()),
				   	  	$requestParams,
					  	new \CowsAPI\Utility\URLBuilder(),
					  	$route->getParam('siteId'));
}
catch (InvalidArgumentException $e)	{
	$view = new \CowsAPI\Views\InvalidSiteId($log,$template);
	$view->render();
	exit(0);
}

if ($serviceFactory->checkSignature($headerManager->getTimestamp(), $headerManager->getSignature(), $route->getMethod(), $route->getURI()))	{
	$baseClass = $route->getClass();
	$controllerType = "\\CowsAPI\\Controller\\".$baseClass;
	$viewType = "\\CowsAPI\\View\\".$baseClass;
		
	$view = new $viewType($log,$template);
	$controller = new $controllerType($view, $route->getParam('eventId'), $serviceFactory);
	$controller->authCows();
	$controller->{$route->getMethod()}();
}
else {
	$view = new \CowsAPI\View\InvalidAuth($log, $template);
	$controller = new \CowsApi\Controller\InvalidAuth($view,null);
	$controller->invoke();
}

$view->render();
$log->execute();
?>