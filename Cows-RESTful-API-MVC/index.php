<?php

require 'vendor/autoload.php';

//Handle headers

$headerManager = new HeaderManager();
$class = "\\CowsAPITemplates\\".$headerManager->getResponseClass();
$template = new $class();

//Auth
$db = new DBWrapper();
$log = new Log($db, $table);

$route = new Router($log, file_get_contents("CowsApi/Data/Routes.json"));
$route->setRoute($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

$authHandler = new Authenticator($db, $log, $headerManager->getAuthArray());

if ($authHandler->signatureIsValid())	{
	//Instantiate
	$curl = new CurlWrapper();
	
	if ($_SERVER['REQUEST_METHOD'] == "GET") 
		$requestParams = $_GET;
	else if ($_SERVER['REQUEST_METHOD'] == "POST") 
		$requestParams = $_POST;
	else 
		$requestParams = array();
	
	$serviceFactory = new ServiceFactory(new DomainObjectFactory(), new DataMapperFactory($route->params(),$requestParams,$db,$curl));
	
	$baseClass = $route->getClass();
	$controllerType = "\\CowsAPIController\\".$baseClass;
	$viewType = "\\CowsAPIView\\".$baseClass;
	
	$view = new $viewType($log,$template);
	$controller = new $controllerType($view, $serviceFactory);
}
else {
	$view = new \CowsAPIView\InvalidAuth($log, $template);
	$controller = new \CowsApiController\InvalidAuth($view,null);
}

//Execute
$controller->{$route->getMethod()}();
$view->render();
$log->execute();
?>