<?php
/****************************************************************************
Component: Expose flash gallery component for Joomla 1.0.x and 1.5.x.
Version  : 4.6.3 (04/05/2008)
Author   : Josh, Steinthor, Bruno, Anything Digital
E-mail   : josh@gotgtek.net, steinthor@setjan.com, bruno@gotgtek.net
Web Site : www.gotgtek.net  www.slooz.com
Copyright: Copyright 2005-2008 by GTEK Technologies
License  : Expose is free for personal use. In a commercial setting, you need to
			purchase a one-site license. Check http://www.slooz.com for more info.
			The implementation into the Joomla CMR is published as GPL
			General Public License. Check http://www.gotgtek.net for details.
*****************************************************************************/

if(!(defined('_VALID_MOS')||defined('_JEXEC'))) die( 'Direct Access to this location is not allowed.' );

class expose_M {

	// Get the path for our xml files, called from administrator backend page
	function rpath() {
			$xmlF = realpath2 ("../components/com_expose/expose/config")."/";
		return $xmlF;
	}

	// Get the path for our xml files, called from frontend page
	function rpath2() {
		$xmlF = realpath2 ("components/com_expose/expose/config")."/";
		return $xmlF;
	}

	//Loop trough config.xml and save changed $_POST values only
	function SaveConfig() {
		$changed = false;
		$path = expose_M::rpath();
		if(!$dom = domxml_open_file($path . 'config.xml'))
			return str_replace('%xml%','config.xml',HTML_content::lbl('ERR_OPEN_XMLFILE'));
		$root = $dom->document_element();
		$nodes = $root->child_nodes ();
		foreach($nodes as $node) { // Search the format tags
		//for ($i = 0; $i < count ($nodes); $i++) { // Loop trough XML file
			//$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "param") { // If setting found
					$newvalue = $_POST[str_replace(' ','_',$node->get_attribute('name'))];
					if ($newvalue <> $node->get_attribute('value')) { // If field value <> current setting value
						$node->set_attribute ("value", $newvalue);
						$changed = $node->get_attribute('group');
					}
				}
			}
		}
		if ($changed) {
			$dom->dump_file ($path . 'config.xml', false, true);
			$dom->free();
			return HTML_content::lbl('TITLE_TAB_'.$changed) . ' ' . HTML_content::lbl('SETNG_SAVED');
		}
		$dom->free();
		return '';
	}

	function SaveFonts() {
		$changed = false;
		$path = expose_M::rpath();
		if(!$dom = domxml_open_file($path . 'formats.xml'))
			return str_replace('%xml%','formats.xml',HTML_content::lbl('ERR_OPEN_XMLFILE'));
		$nodes = $dom->get_elements_by_tagname("format");
		foreach($nodes as $node) { // Search the format tags
			$formatid = $node->get_attribute('id');
			$childs = $node->child_nodes();
			foreach($childs as $child) { // Search the fontsetting tags
				if ($child->node_type() == XML_ELEMENT_NODE) {
					$fontsetting = $child->node_name();
					$configname = $fontsetting . '_' . $formatid;
					if (isset($_POST[$configname])) {
						$newvalue = $_POST[$configname];
						if ($newvalue <> $child->node_value()) { // If field value <> current setting value
							$newchild = $dom->create_text_node($newvalue);
							$child->replace_child($newchild,$child->first_child());
							$changed = true;
						}
					}
				}
			}
		}
		if ($changed) {
			$dom->dump_file ($path . 'formats.xml', false, true);
			$dom->free();
			return HTML_content::lbl('TITLE_TAB_FONTS') . ' ' . HTML_content::lbl('SETNG_SAVED');
		}
		$dom->free();
		return '';
	}

	function SaveSettings() {
		$changed = false;
		$path = realpath2 ("../components/com_expose/expose/manager/amfphp/extra/")."/";
		if (!$dom = domxml_open_file($path . "settings.xml"))
			return str_replace('%xml%','settings.xml',HTML_content::lbl('ERR_OPEN_XMLFILE'));
		$nodes = $dom->get_elements_by_tagname("setting");
		foreach($nodes as $node) { // Search the format tags
			$formatid = $node->get_attribute('name');
			if (isset($_POST[$formatid])) {
				$newvalue = $_POST[$formatid];
				if ($newvalue <> $node->node_value()) { // If field value <> current setting value
					$newchild = $dom->create_text_node($newvalue);
					$node->replace_child($newchild,$node->first_child());
					$changed = true;
				}
			}
		}
		if ($changed) {
			$dom->dump_file ($path . 'settings.xml', false, true);
			$dom->free();
			return HTML_content::lbl('TITLE_TAB_SETTINGS') . ' ' . HTML_content::lbl('SETNG_SAVED');
		}
		$dom->free();
		return '';
	}

	// Get the values from param in config.xml, called by expose.html.php and admin.expose.html.php
	function GetAttr($path,$attr) {
		$settings = array ();
		if (!$dom = domxml_open_file($path . "config.xml"))
			return ''; //str_replace('%xml%','config.xml',HTML_content::lbl('ERR_OPEN_XMLFILE'));
		$root = $dom->document_element();
		$nodes = $root->child_nodes ();
		foreach($nodes as $node) { // Search the format tags
		//for ($i = 0; $i < count ($nodes); $i++) {
		//	$node = $nodes[$i];
			//if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "param") {
					if($node->get_attribute ("name") == $attr)
						//$dom->free();
						return $node->get_attribute ("value");
				}
			//}
		}
	$dom->free();
	}

	function GetLanguage($xmlfile) {
	// Get languagestring
		if(function_exists('jimport')) { // There isn't any class who can do this conversion from 'en-GB' to 'english'
			if(!$dom = domxml_open_file($xmlfile))
				return ''; //str_replace('%xml%','strings.xml',HTML_content::lbl('ERR_OPEN_XMLFILE'));
			$conf =& JFactory::getConfig();
			$langcode = split('-',$conf->getValue('config.language'));
			$nodes = $dom->get_elements_by_tagname("string");
			foreach($nodes as $node) { // Search the string tags
				if ($node->get_attribute('id') == 'languagecode') {
					$childs = $node->child_nodes();
					foreach($childs as $child) { // Search the language tags
					//	if ($child->node_type() == XML_ELEMENT_NODE) {
							if ($child->node_value() == $langcode[0])
								//$dom->free();
								return $child->node_name();
					//	}
					}
				}
			}
			$dom->free();
		} else {
			global $mosConfig_lang;
			return $mosConfig_lang;
		}
	}

	function is__writable($path) {
	// Will work in despite of Windows ACLs bug
	// NOTE: use a trailing slash for paths!!!
	// See http://bugs.php.net/bug.php?id=27609 and http://bugs.php.net/bug.php?id=30931 for more information

		// If path to check, create dummy file to check permissions
		if ($path{strlen($path)-1}=='/') {
			$path = $path . uniqid(mt_rand()) . '.tmp';
			$retval = expose_M::is__writable($path);
			if ($retval)
				unlink($path); // delete dummy file
			return $retval;
		} else {
			if (!($f = @fopen($path, 'a+'))) // Check if file is readable and writable (open for append)
				return false;
			fclose($f);
			return true;
		}
	}

	// Check if the system has the SYSTEM REQUIREMENTS
	function syscheck() {
		$response = '';
		if (version_compare ('5.0.0', phpversion(), '<=') == 1 || function_exists ('domxml_open_file')) {
			$domerror = 0;
		} else {
			$response = '<font color=#ff3300>DOMXML extension unavailable</font><br />';
		}

		if (!function_exists ('imagecreatefromjpeg')) {
			$response = $response . '<font color=#ff3300>GD extension unavailable</font><br />';
		}

		if (!expose_M::is__writable(dirname(__FILE__).'/'.'../img/')) {
			$response = $response . '<font color=#ff3300>expose/img/ folder is NOT writable. please set the correct permissions.</font><br />';
		}

		if (!expose_M::is__writable(dirname(__FILE__).'/'.'../config/')) {
			$response = $response . '<font color=#ff3300>expose/config/ folder is NOT writable. please set the correct permissions.</font><br />';
		}

		if (!expose_M::is__writable(dirname(__FILE__).'/'.'../xml/')) {
			$response = $response . '<font color=#ff3300>expose/xml/ folder is NOT writable. please set the correct permissions.</font><br />';
		}

		if (!expose_M::is__writable(dirname(__FILE__).'/'.'../manager/amfphp/extra/passhash.inc.php')) {
			$response = $response . '<font color=#ff3300>Password file (expose/manager/amfphp/extra/passhash.inc.php) is NOT writable. please set the correct permissions.</font><br />';
		}

		return $response;
	}
}

?>
