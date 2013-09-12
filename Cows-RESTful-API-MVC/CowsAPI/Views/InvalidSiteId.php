<?php

namespace CowsAPI\Views;

class InvalidSiteId {

	public function render()	{
		http_response_code(400);
		echo $this->template->parse(ERROR_PARAMETERS,"Invalid Site ID");
		$this->log->setResponse($out);
		echo $out;
	}

}
?>