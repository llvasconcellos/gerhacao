<?php
/**
* @version		$Id: helper.php 2009-04-16 vinaora $
* @package		VINAORA VISITORS COUNTER
* @copyright	Copyright (C) 2007 - 2009 VINAORA.COM. All rights reserved.
* @license		GNU/GPL
* @website		http://vinaora.com
* @email		admin@vinaora.com
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<?php

class modVisitCounterBrowser {

	/*
	** Explode Item from Array
	*/
	function w($a = '')
	{
		if (empty($a)) return array();
	   
		return explode(' ', $a);
	}

	/* 
	** Return Browser Info
	** browser['name'] = 'msie firefox konqueror safari netscape navigator opera mosaic lynx amaya omniweb chrome avant camino flock seamonkey aol mozilla gecko'
	** browser['version']
	** browser['platform'] = linux,  mac / macintosh / mac platform x, windows / windows32
	** browser['useragent']
	*/ 
	public static function browser($a_browser = false, $a_version = false, $name = false)
	{
		$browser_list = 'msie firefox konqueror safari netscape navigator opera mosaic lynx amaya omniweb chrome avant camino flock seamonkey aol mozilla gecko';
		$user_browser = strtolower($_SERVER['HTTP_USER_AGENT']);
		$this_version = $this_browser = '';
	   
		$browser_limit = strlen($user_browser);
		foreach (modVisitCounterBrowser::w($browser_list) as $row)
		{
			$row = ($a_browser !== false) ? $a_browser : $row;
			$n = stristr($user_browser, $row);
			if (!$n || !empty($this_browser)) continue;
		   
			$this_browser = $row;
			$j = strpos($user_browser, $row) + strlen($row) + 1;
			for (; $j <= $browser_limit; $j++)
			{
				$s = trim(substr($user_browser, $j, 1));
				$this_version .= $s;
			   
				if ($s === '') break;
			}
		}
	   
		if ($a_browser !== false)
		{
			$ret = false;
			if (strtolower($a_browser) == $this_browser)
			{
				$ret = true;
			   
				if ($a_version !== false && !empty($this_version))
				{
					$a_sign = explode(' ', $a_version);
					if (version_compare($this_version, $a_sign[1], $a_sign[0]) === false)
					{
						$ret = false;
					}
				}
			}
		   
			return $ret;
		}
	   
		//
		$this_platform = '';
		if (strpos($user_browser, 'linux'))
		{
			$this_platform = 'linux';
		}
		elseif (strpos($user_browser, 'macintosh') || strpos($user_browser, 'mac platform x'))
		{
			$this_platform = 'mac';
		}
		else if (strpos($user_browser, 'windows') || strpos($user_browser, 'win32'))
		{
			$this_platform = 'windows';
		}
	   
		if ($name !== false)
		{
			return $this_browser . ' ' . $this_version;
		}
	   
		return array(
			"name"      => $this_browser,
			"version"      => $this_version,
			"platform"     => $this_platform,
			"useragent"    => $user_browser
		);
	}
	
	/* 
	** Simple Check Browser.
	** Return: FF (Firefox), CR (Chrome), MOZ (Mozilla, Netscape)
	** Return: IE6 (Internet Explorer 6), IE7 (Internet Explorer 7)
	** Return: OPE (Opera)
	** Return: NA (Unknown or N/A)
	*/
	function browserName () {
		$browser = "NA";
		if (!isset($_SERVER['HTTP_USER_AGENT'])) return $browser;
		
		$agent = strtolower(trim($_SERVER['HTTP_USER_AGENT']));
		
		if ( strpos($agent, 'gecko') )
		{
			if ( strpos($agent, 'firefox') ){
				$browser = 'FF';
			}
			else if ( strpos($agent, 'chrome') ){
				$browser = 'CR';
			}
			else{
				$browser = 'MOZ';
			}
		}
		else if ( strpos($agent, 'msie') && !preg_match('/opera/i',$agent) ){
			$msie='/msie\s(7\.[0-9]).*(win)/i';
			if (preg_match($msie,$agent)) $browser = 'IE7';
			else $browser = 'IE6';
		}
		else if ( preg_match('/opera/i',$agent) ){
			$browser = 'OPE';
		}
		return $browser;
	}

}
?>