<?php

namespace CowsAPI\Views;
/**
 * View when the SiteId is invalid
 * 
 * Has no related controller because there's nothing to route/control
 * 
 * @author its-zach
 * @codeCoverageIgnore
 *
 */
class InvalidSiteId {

	public function render()	{
		http_response_code(400);
		echo $this->template->parse(ERROR_PARAMETERS,"Invalid Site ID");
		$this->log->setResponse($out);
		echo $out;
	}

}
?>