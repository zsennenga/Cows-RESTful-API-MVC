<?php

namespace CowsAPIModels;

interface DBInterface	{
	public function close();
	public function query($stmt);
	public function addParam($key,$value);
}
?>