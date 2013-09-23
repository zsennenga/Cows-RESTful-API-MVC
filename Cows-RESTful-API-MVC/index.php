<?php

use CowsAPI\Utility\HeaderManager;
use CowsAPI\Exceptions\ParameterException;
use CowsAPI\Models\DB\DBWrapper;
use CowsAPI\Utility\Log;
use CowsAPI\Utility\Router;
use CowsAPI\Models\HTTP\CurlWrapper;
use CowsAPI\Models\ServiceFactory;
use CowsAPI\Models\DomainObjectFactory;
use CowsAPI\Models\DataMapperFactory;
use CowsAPI\Utility\URLBuilder;
use CowsAPI\Views\InvalidAuth;
use CowsAPI\Views\InvalidSiteId;

require 'vendor/autoload.php';
require_once 'CowsApi/Data/Config.php';


/*$_SERVER['REQUEST_URI'] = "/session/its";
$_SERVER['REQUEST_METHOD'] = "POST";
$_SERVER['REMOTE_ADDR'] = "1";
$_POST['tgc'] = "1";*/

//Handle headers
$headerManager = new HeaderManager();

//Instantiate all necessary objecst
$class = "\\CowsAPI\\Templates\\".$headerManager->getResponseClass();
$template = new $class();

$db = new DBWrapper();
$log = new Log($db, DB_TABLE_LOG);

$route = new Router($log, file_get_contents("CowsApi/Data/Routes.json"));
$route->setRoute($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
unset($_GET['r']);

$curl = new CurlWrapper();

//Set Parameters
if ($_SERVER['REQUEST_METHOD'] == "GET") 
	$requestParams = $_GET;
else if ($_SERVER['REQUEST_METHOD'] == "POST") 
	$requestParams = $_POST;
else 
	$requestParams = array();
$log->setParams($requestParams);

$parsedAuth = $headerManager->parseAuth();
$publicKey = $headerManager->getPublicKey();

try	{
	$serviceFactory = new ServiceFactory(
			 		  	new DomainObjectFactory(), 
					  	new DataMapperFactory($db,$curl,$publicKey),
				   	  	$requestParams,
					  	new URLBuilder(),
					  	$route->getParams('siteId'));
}
catch (ParameterException $e)	{
	$view = new InvalidSiteId($log, $template);
	$view->render();
	$log->execute();
	exit(0);
}

if ($parsedAuth && $serviceFactory->checkSignature($headerManager->getTimestamp(), $headerManager->getSignature(), $route->getMethod(), $route->getURI()))	{
	$baseClass = $route->getClass();
	$controllerType = "\\CowsAPI\\Controllers\\".$baseClass;
	$viewType = "\\CowsAPI\\Views\\".$baseClass;
		
	$view = new $viewType($log,$template);
	$controller = new $controllerType($view, $route->getParams('eventId'), $serviceFactory);
	$controller->authCows();
	$controller->{$route->getMethod()}();
}
else {
	$view = new InvalidAuth($log, $template);
	$controller = new \CowsAPI\Controllers\InvalidAuth($view,1,null);
	if (!$parsedAuth)	{
		$message = "Invalid or no Auth Header Found";
	}
	else	{
		$message = "Invalid Signature Route: " . $route->getURI() . " Method: " . $route->getMethod();
	}
	$controller->invoke($message);
}

$view->render();
$log->execute();
?>