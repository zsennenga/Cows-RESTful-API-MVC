<?php

namespace CowsAPITemplates;

abstract class BaseTemplate	{
	abstract function parse($statusCode, $message);
}

?>