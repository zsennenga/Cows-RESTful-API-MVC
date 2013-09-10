<?php

namespace CowsAPI\Templates;

abstract class BaseTemplate	{
	abstract function parse($statusCode, $message);
}

?>