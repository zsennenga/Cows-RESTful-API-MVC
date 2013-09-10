<?php

require 'vendor/autoload.php';

//Handle headers

$headerManager = new \CowsAPI\Utility\HeaderManager();
$class = "\\CowsAPI\\Templates\\".$headerManager->getResponseClass();
$template = new $class();

$db = new \CowsAPI\Models\DBWrapper();
$log = new \CowsAPI\Utility\Log($db, $table);

$route = new \CowsAPI\Utility\Router($log, file_get_contents("CowsApi/Data/Routes.json"));
$route->setRoute($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

//Instantiate
$curl = new \CowsAPI\Models\CurlWrapper();

if ($_SERVER['REQUEST_METHOD'] == "GET") 
	$requestParams = $_GET;
else if ($_SERVER['REQUEST_METHOD'] == "POST") 
	$requestParams = $_POST;
else 
	$requestParams = array();
	
$log->setParams($requestParams);
	
$serviceFactory = new \CowsAPI\Models\ServiceFactory(new \CowsAPI\Models\DomainObjectFactory(), new \CowsAPI\Models\DataMapperFactory($db,$curl),$requestParams);

if ($serviceFactory->checkSignature($headerManager->getPublicKey(), $headerManager->getTimestamp(), $headerManager->getSignature(), $route))	{
	$baseClass = $route->getClass();
	$controllerType = "\\CowsAPI\\Controller\\".$baseClass;
	$viewType = "\\CowsAPI\\View\\".$baseClass;
		
	$view = new $viewType($log,$template);
	$controller = new $controllerType($view, $route->eventId, $serviceFactory);
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