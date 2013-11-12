<?php

/**
 * BrowserSniffer.php
 * User Agent Detection
 */

class BrowserSniffer {
	private $userAgentString;
	private $browserList;
	private $browserToken;
	private $platformList;

	/**
	 * Initialize properties
	 */
	function __construct() {
		$this->userAgentString = strtolower($_SERVER['HTTP_USER_AGENT']);
		$this->browserList = $this->getBrowserList();
		$this->platformList = array_change_key_case($this->getPlatformList(), CASE_LOWER);
	}

	/**
	 * Retrieve browser name according to browser token
	 */
	private function getBrowserList() {
		// Browser token with browser name, browser vendor & rendering engine
		return array(
			'opr' => 'Opera|Opera Software ASA|WebKit',
			'chrome' => 'Chrome|Google Inc.|WebKit',
			'firefox' => 'Firefox|Mozilla Foundation|Gecko',
			'msie' => 'Internet Explorer|Microsoft Corporation|Trident',
			'opera' => 'Opera|Opera Software ASA|Presto',
			'safari' => 'Safari|Apple Inc.|WebKit'
		);
	}

	/**
	 * Retrieve OS name according to platform token
	 */
	public function getPlatformList() {
		// PLatform token with OS name
		return array(
			'Windows NT 6.3' => 'Windows 8.1',
			'Windows NT 6.2' => 'Windows 8',
			'Windows NT 6.1' => 'Windows 7',
			'Windows NT 6.0' => 'Windows Vista',
			'Windows NT 5.2' => 'Windows Server 2003; Windows XP x64 Edition',
			'Windows NT 5.1' => 'Windows XP',
			'Windows NT 5.01' => 'Windows 2000, Service Pack 1 (SP1)',
			'Windows NT 5.0' => 'Windows 2000',
			'Windows NT 4.0' => 'Microsoft Windows NT 4.0',
			// 'Windows 98; Win 9x 4.90' => 'Windows Millennium Edition (Windows Me)',
			'Windows 98' => 'Windows 98',
			'Windows 95' => 'Windows 95',
			'Windows CE' => 'Windows CE',
			'Mac OS X 10.2' => 'Mac OS X v10.2 Jaguar',
			'Mac OS X 10.3' => 'Mac OS X v10.3 Panther',
			'Mac OS X 10.4' => 'Mac OS X v10.4 Tiger',
			'Mac OS X 10.5' => 'Mac OS X v10.5 Leopard',
			'Mac OS X 10.6' => 'Mac OS X v10.6 Snow Leopard',
			'Mac OS X 10.7' => 'Mac OS X v10.7 Lion',
			'Mac OS X 10.8' => 'Mac OS X v10.8 Mountain Lion',
			'Mac OS X 10.9' => 'Mac OS X v10.9 Mavericks'
		);
	}

	/**
	 * Return browser detail
	 * 0: Browser Name
	 * 1: Browser Vendor
	 * 2: Rendering Engine
	 */
	public function getBrowserDetail($detail_target) {
		foreach ($this->browserList as $browserToken => $browserDetail) {
			if (strpos($this->userAgentString, $browserToken) !== false) {
				$this->browserToken = $browserToken;
				return explode('|', $browserDetail)[$detail_target];
			}
		}
	}

	/**
	 * Return browser version
	 */
	public function getBrowserVersion() {
		// Browser version regular expression
		$reg_exp = null;
		switch ($this->browserToken) {
			case 'opr':
			case 'chrome':
			case 'firefox':
				$reg_exp = "/{$this->browserToken}\/([0-9\.]+)/";
				break;
			case 'msie':
				$reg_exp = "/{$this->browserToken} ([0-9\.]+)/";
				break;

			default:
				$reg_exp = "/version\/([0-9\.]+)/";
				break;
		}
		// Match
		preg_match($reg_exp, $this->userAgentString, $browserVersion);
		return $browserVersion[1];
	}

	/**
	 * Return browser rendering engine version
	 */
	public function getBrowserRenderEngineVersion() {
		$render_engine = strtolower(explode('|', $this->browserList[$this->browserToken])[2]);
		preg_match("/{$render_engine}\/([0-9\.]+)/", $this->userAgentString, $render_engine_version);
		return $render_engine_version[1];
	}

	/**
	 * Return operating system name
	 */
	public function getOperatingSystemName() {
		// Method - 1
		/*foreach ($this->platformList as $platformToken => $platformName)
			if (strpos($this->userAgentString, $platformToken) !== false) return $platformName;*/
		// Method - 2
		preg_match("/windows([a-z0-9\. ]+)/", $this->userAgentString, $os_name);
		return $this->platformList[$os_name[0]];
	}
}

// Instance of BrowserSniffer class
$browser_sniffer = new BrowserSniffer();

// Get browser name
var_dump($browser_sniffer->getBrowserDetail(0));
// Get browser version
var_dump($browser_sniffer->getBrowserVersion());
// Get browser vendor
var_dump($browser_sniffer->getBrowserDetail(1));
// Get browser rendering engine
var_dump($browser_sniffer->getBrowserDetail(2));
// Get browser rendering engine
var_dump($browser_sniffer->getBrowserRenderEngineVersion());
// Get OS name
var_dump($browser_sniffer->getOperatingSystemName());

?>