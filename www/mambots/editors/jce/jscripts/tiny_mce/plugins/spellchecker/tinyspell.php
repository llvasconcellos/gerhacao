<?php
/**
 * $RCSfile: tinyspell.php,v $
 * $Revision: 1.1 $
 * $Date: 2006/03/14 17:33:47 $
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2006, Moxiecode Systems AB, All rights reserved.
 */
	defined( '_VALID_MOS' ) or die( 'Restricted Access.' );
	
	require_once( $mainframe->getCfg('absolute_path') . '/mambots/editors/jce/jscripts/tiny_mce/libraries/classes/jce.class.php' );
	
	$jce = new JCE();
	$jce->setPlugin('spellchecker');
	
	//Load Plugin Parameters
	$params = $jce->getPluginParams();
	
	// Ignore the Notice errors for now.
	error_reporting(E_ALL ^ E_NOTICE);

	//require_once("config.php");
	$class = $params->get('class', 'TinyGoogleSpell');
	require_once( $jce->getPluginPath() . "/classes/" . $class . ".class.php" );

	$id = sanitize($_POST['id'], "loose");

	// Basic config
	$languages = $params->get('languages', 'English=en');
	$languages = explode( ',', $languages );
	$languages = explode( '=', $languages[0] );
	$defaultLanguage = $languages[1];
		
	$defaultMode = $params->get('default_mode', 'PSPELL_FAST');

	// Normaly not required to configure
	$defaultSpelling = $params->get('default_spelling', '');
	$defaultJargon = $params->get('default_jargon', '');
	$defaultEncoding = $params->get('default_encoding', '');
	$outputType = "xml"; // Do not change

	// Get input parameters.

	$check = urldecode(getRequestParam('check'));
	$cmd = sanitize(getRequestParam('cmd'));
	$lang = sanitize(getRequestParam('lang'), "strict");
	$mode = sanitize(getRequestParam('mode'), "strict");
	$spelling = sanitize(getRequestParam('spelling'), "strict");
	$jargon = sanitize(getRequestParam('jargon'), "strict");
	$encoding = sanitize(getRequestParam('encoding'), "strict");
	$sg = sanitize(getRequestParam('sg'), "bool");
	$words = array();

	$validRequest = true;

	if (empty($check))
		$validRequest = false;

	if (empty($lang))
		$lang = $defaultLanguage;

	if (empty($mode))
		$mode = $defaultMode;

	if (empty($spelling))
		$spelling = $defaultSpelling;

	if (empty($jargon))
		$jargon = $defaultJargon;

	if (empty($encoding))
		$encoding = $defaultEncoding;

	function sanitize($str, $type="strict") {
		switch ($type) {
			case "strict":
				$str = preg_replace("/[^a-zA-Z0-9_\-]/i", "", $str);
			break;

			case "loose":
				$str = preg_replace("/</i", "&gt;", $str);
				$str = preg_replace("/>/i", "&lt;", $str);
			break;

			case "bool":
				if ($str == "true" || $str == true)
					$str = true;
				else
					$str = false;
			break;
		}

		return $str;
	}

	function getRequestParam($name, $default_value = false) {
		if (!isset($_REQUEST[$name]))
			return $default_value;

		if (!isset($_GLOBALS['magic_quotes_gpc']))
			$_GLOBALS['magic_quotes_gpc'] = ini_get("magic_quotes_gpc");

		if (isset($_GLOBALS['magic_quotes_gpc'])) {
			if (is_array($_REQUEST[$name])) {
				$newarray = array();

				foreach($_REQUEST[$name] as $name => $value)
					$newarray[stripslashes($name)] = stripslashes($value);

				return $newarray;
			}
			return stripslashes($_REQUEST[$name]);
		}

		return $_REQUEST[$name];
	}

	$result = array();
	$tinyspell = new $class($params, $lang, $mode, $spelling, $jargon, $encoding);

	if (count($tinyspell->errorMsg) == 0) {
		switch($cmd) {
			case "spell":
				// Space for non-exec version and \n for the exec version.
				$words = preg_split("/ |\n/", $check, -1, PREG_SPLIT_NO_EMPTY);
				$result = $tinyspell->checkWords($words);
			break;
	
			case "suggest":
				$result = $tinyspell->getSuggestion($check);
			break;

			default:
				// Just use this for now.
				$tinyspell->errorMsg[] = "No command.";
				$outputType = $outputType . "error";
			break;
		}
	} else
		$outputType = $outputType . "error";

	if (!$result)
		$result = array();

	// Output data
	switch($outputType) {
		case "xml":
			header('Content-type: text/xml; charset=utf-8');
			$body  = '<?xml version="1.0" encoding="utf-8" ?>';
			$body .= "\n";
			
			if (count($result) == 0)
				$body .= '<res id="' . $id . '" cmd="'. $cmd .'" />';
			else
				$body .= '<res id="' . $id . '" cmd="'. $cmd .'">'. urlencode(implode(" ", $result)) .'</res>';

			echo $body;
		break;

		case "xmlerror";
			header('Content-type: text/xml; charset=utf-8');
			$body  = '<?xml version="1.0" encoding="utf-8" ?>';
			$body .= "\n";
			$body .= '<res id="' . $id . '" cmd="'. $cmd .'" error="true" msg="'. implode(" ", $tinyspell->errorMsg) .'" />';
			echo $body;
		break;

		case "html":
			var_dump($result);
		break;

		case "htmlerror":
			echo "Error";
		break;
	}

?>
