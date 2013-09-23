<?php

use CowsAPI\Models\HTTP\CurlWrapper;
use CowsAPI\Utility\URLBuilder;
require_once __DIR__ . "../../../../CowsAPI/Data/Config.php";
class URLBuilderTest extends PHPUnit_Framework_TestCase {

	protected $object;
	
	protected function setUp()	{
		
	}
	
	public function testdataProv()	{
		$u = new URLBuilder();
		return array(
			array($u->getCasLogoutUrl(), ''),
			array($u->getCasProxyURL('a', 'b'), ''),
			array($u->getCowsBaseUrl('its'), ''),
			array($u->getCowsEventIdUrl('its', '1'), ''),
			array($u->getCowsEventJson('its'), ''),
			array($u->getCowsEventUrl('its'), 'https://cas.ucdavis.edu:8443/cas/login?service=http%3a%2f%2fcows.ucdavis.edu%2fAccount%2fLogOn%3fReturnUrl%3d%2fits%2fEvent%2fcreate'),
			array($u->getCowsLoginUrl('its', 'a'), 'https://cas.ucdavis.edu:8443/cas/login?service=http%3a%2f%2fcows.ucdavis.edu%2fits%2fAccount%2fLogOn%3freturnUrl%3dhttp%3a%2f%2fcows.ucdavis.edu%2fits'),
			array($u->getCowsLogoutUrl('its'), 'http://cows.ucdavis.edu/its'),
			array($u->getCowsRssUrl('its', array('1' => '1')), ''),
			array($u->getEventDeleteUrl('its', '1'), 'https://cas.ucdavis.edu:8443/cas/login?service=http%3a%2f%2fcows.ucdavis.edu%2fAccount%2fLogOn%3fReturnUrl%3d%2fits%2fevent%2fDelete%2f1')
		);
	}
	/**
	 * 
	 * @dataProvider testdataProv
	 */
	public function testURLs($a,$b)	{
		$this->object = new CurlWrapper();
		$this->object->setOption(CURLOPT_URL, $a);
		$this->object->setOption(CURLOPT_CUSTOMREQUEST, 'GET');
		$this->object->execute();
		$last = $this->object->getInfo(CURLINFO_EFFECTIVE_URL);
		if ($b === '') $b = $a;
		$this->assertSame($last, $b);
	}

}
?>