<?php

/**
 * BrowserSniffer.php
 * User Agent Detection
 */

class BrowserSniffer {
	private $userAgentString;
	private $browserName;
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
		// Browser token with browser name
		return array(
			'firefox' => 'Firefox',
			'msie' => 'Internet Explorer',
			'opera' => 'Opera',
			'opr' => 'Opera',
			'chrome' => 'Chrome',
			'safari' => 'Safari'
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
			'Windows 98; Win 9x 4.90' => 'Windows Millennium Edition (Windows Me)',
			'Windows 98' => 'Windows 98',
			'Windows 95' => 'Windows 95',
			'Windows CE' => 'Windows CE'
		);
	}

	/**
	 * Return browser name
	 */
	public function getBrowserName() {
		foreach ($this->browserList as $browserToken => $browserName) {
			if (strpos($this->userAgentString, $browserToken) !== false) {
				$this->browserToken = $browserToken;
				return $browserName;
			}
		}
	}

	/**
	 * Return operating system name
	 */
	public function getOperatingSystemName() {
		foreach ($this->platformList as $platformToken => $platformName)
			if (strpos($this->userAgentString, $platformToken) !== false) return $platformName;
	}
}

// Instance of BrowserSniffer class
$browser_sniffer = new BrowserSniffer();

// Get browser name
var_dump($browser_sniffer->getBrowserName());
// Get OS name
var_dump($browser_sniffer->getOperatingSystemName());

?>