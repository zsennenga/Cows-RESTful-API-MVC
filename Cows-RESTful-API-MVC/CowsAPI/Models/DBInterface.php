<?php

namespace CowsAPI\Models;

/**
 * Interface to abstract DB Requests
 * @author its-zach
 *
 */
interface DBInterface	{
	public function close();
	public function query($stmt);
	public function addParam($key,$value);
}
?>