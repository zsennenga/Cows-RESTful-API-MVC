<?php

namespace CowsAPI\Views;
/**
 * View when the SiteId is invalid
 * 
 * Has no related controller because there's nothing to route/control
 * 
 * @author its-zach
 * 
 * @codeCoverageIgnore
 */
class InvalidSiteId extends BaseView {

	public function render()	{
		\http_response_code(400);
		$out = $this->template->parse(ERROR_PARAMETERS,"Invalid Site ID");
		$this->logger->setResp($out);
		echo $out;
	}

}
?>