<?php
/****************************************************************************
Component: Expose flash gallery component for Joomla 1.0.x and 1.5.x.
Version  : 4.6.3 (29/03/2008)
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

// Activate error_reporting(E_ALL);
error_reporting( E_ALL & E_NOTICE); 

// **** GENERAL FUNCTIONS ****

// Utility function to read the files in a directory (code in /includes/joomla.php:3030)
//function mosReadDirectory( $path, $filter='.', $recurse=false, $fullpath=false  ) {

// Function to strip additional / or \ in a path name (code in \includes\joomla.php:3165)
//function mosPathName($p_path,$p_addtrailingslash = true) {

function is__writable($path) {
// Will work in despite of Windows ACLs bug
// NOTE: use a trailing slash for paths!!!
// See http://bugs.php.net/bug.php?id=27609 and http://bugs.php.net/bug.php?id=30931

	// If path to check, create dummy file to check permissions
	if ($path{strlen($path)-1}=='/') {
		$path = $path . uniqid(mt_rand()) . '.tmp';
		$retval = is__writable($path);
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

//Read php.ini setting (return: true/false)
function get_php_setting($val) {
	return (ini_get($val) == '1' ? 1 : 0);
}

//Get a part ($value) of text ($text) 
function setastext($value,$text) {
	$words = split('%', $text);
	return $words[$value];
}

//Read the installation xml file information tags
function install_info($tagname) {
	// add XML library functions
	require_once( '../includes/domit/xml_domit_lite_include.php' );

	$xmlDoc = new DOMIT_Lite_Document();
	$xmlDoc->resolveErrors( true );

// Don't need to check for J! version, since both install.xml's should contain the same version information!
//	if(function_exists('jimport')) {
//		if (!$xmlDoc->loadXML( '../administrator/components/com_expose/1.5.expose.xml', false, true )) {
//			continue;
//		}
//	} else {
		if (!$xmlDoc->loadXML( '../administrator/components/com_expose/expose.xml', false, true )) {
			continue;
		}
//	}

	$root = &$xmlDoc->documentElement;

// Same here: both install xml's should have the same version
//	if(function_exists('jimport')) {
//		if ($root->getTagName() != 'install') {
//			continue;
//		}
//	} else {
		if ($root->getTagName() != 'mosinstall') {
			continue;
		}
//	}

	if ($root->getAttribute( "type" ) != "component") {
		continue;
	}

	$element = &$root->getElementsByPath($tagname, 1);
	return $element ? $element->getText() : HTML_content::lbl('ERR_UNKNOWN');

}

//Get the free drivespace
function my_drivespace(){
	  return intval(disk_free_space($_SERVER['DOCUMENT_ROOT'])/1048576);
}

// **** MD5 CHECK FUNCTIONS ****
// Modified snippet from Joomla! Tools Suite (@author Adam van dongen)
// Generate new file with MD5 hash results
function generateMd5Hash($opath = null, $MD5_File, $skipfiles) {
	if (is_null($opath)) return;

	if(function_exists('jimport')) {
		$files = JFolder::files($opath, '.', true, true);
	} else {
		$files = mosReadDirectory($opath, '.', true, true);
	}

	$filecontent = '';

	echo "<table border='0' width='100%' cellspadding='1' cellspacing='0' align='left' class='content'>";

	for($i=0,$n=count($files);$i<$n;$i++){
		$file = str_replace($opath . '/', '', str_replace("\\", "/", $files[$i]));
		if(!in_array($file, $skipfiles)){ //skip files that have been defined...
			if(is_file($opath . '/' . $file)){
				echo "<tr><td>". $file ."</td></tr>\n";
				$filecontent .= $file . "\t" . getFileHash($opath . '/' . $file) . "\n";
			}
		}
	}

	$fp = fopen($MD5_File, "w");
	if (fwrite($fp, $filecontent) === FALSE) {
		echo "<tr><td class='warn-message'>" . str_replace('%HashFile%', $MD5_File, HTML_content::lbl('ERR_HASH_UNWRITABLE')) . "</td></tr></table>";
		exit;
	}
	echo "<tr><td class='good-message'>" . str_replace('%HashFile%', $MD5_File, HTML_content::lbl('ERR_HASH_WRITABLE')) . "</td></tr></table>";
	fclose($fp);
}

function getFileHash($file){
  $content = file_get_contents($file);
  $content = str_replace(array("\n", "\r"), "", $content);
  return md5($content);
}

function compair($opath = null, &$errarr, $MD5_File, $skipfiles) {
	if (is_null($opath)) return;

	ob_flush();
	flush();

	$orig = array();
	$orig_c = file($MD5_File);

	for($i=0,$n=count($orig_c);$i<$n;$i++){
		$line = explode("\t", $orig_c[$i]);
		$orig[$opath . '/'. $line[0]] = trim($line[1]);
	}
	ob_flush();
	flush();

	if(function_exists('jimport')) {
		$files = JFolder::files($opath, '.', true, true); //false, true);
	} else {
		$files = mosReadDirectory($opath, '.', true, true); //false, true);
	}

	echo '<table cellpadding="4" cellspacing="1" border="1" width="100%">';  
	for($i=0,$n=count($files);$i<$n;$i++) {
		$file = str_replace('\\', '/', $files[$i]);
		if($content = @file_get_contents($file)) { //check if file is file or directory
			if((!empty($orig[$file])) && (getFileHash($file) != $orig[$file])){ //when a hash exists
				echo '<tr><td>' . HTML_content::lbl('ERR_WARNING') . '</td><td>' .$file . '</td><td colspan="2">' . HTML_content::lbl('ERR_ALTEREDFILE') . '</td></tr>';
				$errarr[4] ++;
			}
//			elseif(empty($orig[$file])){ //when a hash doesn't exists
//				if(!in_array($file, $skipfiles)){ //skip files that have been defined...
//					echo '<tr><td>' . $ERR_WARNING . '</td><td>' . $file . '</td><td colspan="2">' . $ERR_UNKNOWNFILE . '</td></tr>';
//					$errarr[4] ++;
//				}
//			}
			ob_flush();
			flush();
		}
		unset($orig[$file]);
	}

	if(sizeof($orig) > 0){ 
		$keys = array_keys($orig);
		for($i=0,$n=count($keys);$i<$n;$i++){
			$file = $keys[$i];
			echo '<tr><td>' . HTML_content::lbl('ERR_MISSING') . '</td><td>' . $file . '</td><td colspan="2">' . HTML_content::lbl('ERR_MISSINGFILE') . '</td></tr>';
			$errarr[4] ++;
			ob_flush();
			flush();
		}
	}
	if ($errarr[4] == 0) {
		echo '<tr><td>' . HTML_content::lbl('ERR_SUCCESS') . '</td><td>' . HTML_content::lbl('ERR_SUCCESFILE') . '</td><td colspan="2">' . HTML_content::lbl('ERR_SUCCESS') . '</td></tr>';
	}
	echo '</table>';
}

// **** HTML PRINTOUT FUNCTIONS ****
//Create html tabel code from $testarray information + keep track of errorious values
function print_table($testarr, &$errarr){
	echo "<table cellpadding='5' border='1'><tr><td>" . HTML_content::lbl('SETNG_SETTING') . "</td><td>" . HTML_content::lbl('SETNG_VALUE') . "</td><td>" . HTML_content::lbl('SETNG_FRONTEND') . "</td><td>" . HTML_content::lbl('SETNG_BACKEND') . "</td></tr>\n";
	foreach ($testarr as $arr) {
		$showmsg = false;
		echo '<tr><td><b>'.$arr['desc'].'</b></td><td><b>'.setastext($arr['test'],$arr['setng']).'</b></td>';
		echo '<td>';

	//use pics for pass and fail
	//$img = ((ini_get('register_globals')) ? 'publish_x.png' : 'tick.png');
	//echo "<img src='../images/$img'";
	
		//if test is true, then setting is ok
		if ($arr['test'] && $arr['sevfront'] != 3) echo HTML_content::lbl('ERR_SUCCESS');
		// if test is false, but warning
		elseif ($arr['sevfront'] == 1) {
			echo HTML_content::lbl('ERR_WARNING');
			//$warningfront ++;
			$errarr[0] ++;
			$showmsg = true;
		}
		// if test is false and fatal
		elseif ($arr['sevfront'] == 2) {
			echo HTML_content::lbl('ERR_FATAL');
			//$fatalfront ++;
			$errarr[1] ++;
			$showmsg = true;
		}
		//elseif ($arr['sevfront'] == 3) // don't show irrelevant result

		echo	'</td><td>';

		if ($arr['test'] && $arr['sevback'] != 3) {
			echo HTML_content::lbl('ERR_SUCCESS');
		}
		elseif ($arr['sevback'] == 1) {
			echo HTML_content::lbl('ERR_WARNING');
			//$warningback ++;
			$errarr[2] ++;
		$showmsg = true;
		}
		elseif ($arr['sevback'] == 2) {
			echo HTML_content::lbl('ERR_FATAL');
			//$fatalback ++;
			$errarr[3] ++;
			$showmsg = true;
		}
		//elseif ($arr['sevback'] == 3) // don't show irrelevant result
		
		if ($showmsg) echo "</td></tr>\n<tr><td colspan='4'>&nbsp;&nbsp;&nbsp;<font size='2' >&rArr;&nbsp;".$arr['failmsg']."</font>";
		echo "</td></tr>\n";
	}
	echo '</table><br/>';
}

// START OF SCRIPT **********

/*
desc - A description of the test
setng - Actual value of setting (test = false, test = true)
test - Boolean value of the result, true = good, false = bad
failmsg - Message to display on fail of test
sevfront - Severity of a fail in frontend, 0 = unused, 1 = warning, 2 = fatal
sevback - Severity of a fail in backend (Manager), 0 = unused, 1 = warning, 2 = fatal
*/
function doCheck () {

$skipfiles = array(
	'expose/manager/expose.md5');

	$path = dirname(__FILE__) .'/';
	$path = str_replace('\\', '/', $path);
	//goto the root of com_expose
	$path = substr( $path, 0, strrpos( $path, '/') );
	$path = substr( $path, 0, strrpos( $path, '/') );
	$path = substr( $path, 0, strrpos( $path, '/') );

/* Testarray structure:
=> array ("desc" => "Description of test",
		"setng" => "FailValueDescription" . '%' . "PassValueDescription",
		"test" => (testfunction),
		"failmsg" => "FailComment",
		"sevfront" => 1=warning, 2=fatal, 3=irrelevant,
		"sevback" => 1=warning, 2=fatal, 3=irrelevant),
*/

// Read all general System settings
$systests = array (
	0 => array ("desc" => HTML_content::lbl('SYS_BUILD'),
		"setng" => ('unknown%'.php_uname()),
		"test" => True,
		"failmsg" => "",
		"sevfront" => 3, "sevback" => 3),
	1 => array ("desc" => HTML_content::lbl('SYS_PHPVERSION'),
		"setng" => phpversion().'%'.phpversion(),
		"test" => version_compare(phpversion(),'4','>='),
		"failmsg" => HTML_content::lbl('ERR_PHPVERSION'),
		"sevfront" => 2, "sevback" => 2),
	2 => array ("desc" => HTML_content::lbl('SYS_INTERFACE'),
		"setng" => php_sapi_name().'%'.php_sapi_name(),
		"test" => (php_sapi_name() == 'apache2handler'),
		"failmsg" => HTML_content::lbl('ERR_INTERFACE'),
		"sevfront" => 1, "sevback" => 1),
	3 => array ("desc" => HTML_content::lbl('SYS_DISABLEDFUNCS'),
		"setng" => (ini_get('disable_functions')."%none"),
		"test" => (ini_get('disable_functions') == ''),
		"failmsg" => HTML_content::lbl('ERR_DISABLEDFUNCS'),
		"sevfront" => 3, "sevback" => 1)
);

//($df=ini_get('disable_functions'))?$df:'none')

// Read all general Joomla settings
$joomtests = array (
	0 => array ("desc" => HTML_content::lbl('SYS_RGEMULATION'),
		"setng" => HTML_content::lbl('SETNG_ENABLED') . '%' . HTML_content::lbl('SETNG_DISABLED'),
		"test" => (!get_php_setting('RG_EMULATION')),
		"failmsg" => HTML_content::lbl('ERR_RGEMULATION'),
		"sevfront" => 1, "sevback" => 1),
	1 => array ("desc" => HTML_content::lbl('SYS_REGGLOBALS'),
		"setng" => HTML_content::lbl('SETNG_ENABLED') . '%' . HTML_content::lbl('SETNG_DISABLED'),
		"test" => (!get_php_setting('register_globals')),
		"failmsg" => HTML_content::lbl('ERR_REGGLOBALS'),
		"sevfront" => 1, "sevback" => 1),
	2 => array ("desc" => HTML_content::lbl('SYS_MAGICQUOTES'),
		"setng" => HTML_content::lbl('SETNG_DISABLED') . '%' . HTML_content::lbl('SETNG_ENABLED'),
		"test" => (get_php_setting('magic_quotes_gpc')),
		"failmsg" => HTML_content::lbl('ERR_MAGICQUOTES'),
		"sevfront" => 1, "sevback" => 1),
	3 => array ("desc" => HTML_content::lbl('SYS_SAFEMODE'),
		"setng" => HTML_content::lbl('SETNG_ENABLED') . '%' . HTML_content::lbl('SETNG_DISABLED'),
		"test" => (!get_php_setting('safe_mode')),
		"failmsg" => HTML_content::lbl('ERR_SAFEMODE'),
		"sevfront" => 1, "sevback" => 2),
	4 => array ("desc" => HTML_content::lbl('SYS_FILEUPLOADS'),
		"setng" => HTML_content::lbl('SETNG_DISABLED') . '%' . HTML_content::lbl('SETNG_ENABLED'),
		"test" => (get_php_setting('file_uploads') != ''),
		"failmsg" => HTML_content::lbl('ERR_FILEUPLOADS'),
		"sevfront" => 3, "sevback" => 1),
	5 => array ("desc" => HTML_content::lbl('SYS_SESSIONSTART'),
		"setng" => HTML_content::lbl('SETNG_ENABLED') . '%' . HTML_content::lbl('SETNG_DISABLED'),
		"test" => !get_php_setting('session.auto_start'),
		"failmsg" => HTML_content::lbl('ERR_SESSIONSTART'),
		"sevfront" => 1, "sevback" => 1)
);

// Read all used Library settings
$libtests = array (
	0 => array ("desc" => HTML_content::lbl('SYS_DOMLIB') . " (A)",
		"setng" => HTML_content::lbl('SETNG_NOTINSTALLED') . '%' . HTML_content::lbl('SETNG_INSTALLED'),
		"test" => (extension_loaded('xml')),
		"failmsg" => HTML_content::lbl('ERR_DOMLIB'),
		"sevfront" => 2, "sevback" => 2),
	1 => array ("desc" => HTML_content::lbl('SYS_DOMLIB') . " (B)",
		"setng" => HTML_content::lbl('SETNG_NOTINSTALLED') . '%' . HTML_content::lbl('SETNG_INSTALLED'),
		"test" => (version_compare ('5.0.0', phpversion(), '<=') == 1 || function_exists ('domxml_open_file')),
		"failmsg" => HTML_content::lbl('ERR_DOMLIB'),
		"sevfront" => 2, "sevback" => 2),
	2 => array ("desc" => HTML_content::lbl('SYS_XMLLIB'),
		"setng" => HTML_content::lbl('SETNG_NOTINSTALLED') . '%' . HTML_content::lbl('SETNG_INSTALLED'),
		"test" => (extension_loaded('libxml')),
		"failmsg" => HTML_content::lbl('ERR_XMLLIB'),
		"sevfront" => 2, "sevback" => 2),
	3 => array ("desc" => HTML_content::lbl('SYS_GDLIB') . " (A)",
		"setng" => HTML_content::lbl('SETNG_NOTINSTALLED') . '%' . HTML_content::lbl('SETNG_INSTALLED'),
		"test" => (extension_loaded('gd')),
		"failmsg" => HTML_content::lbl('ERR_GDLIB'),
		"sevfront" => 3, "sevback" => 2),
	4 => array ("desc" => HTML_content::lbl('SYS_GDLIB') . " (B)",
		"setng" => HTML_content::lbl('SETNG_NOTINSTALLED') . '%' . HTML_content::lbl('SETNG_INSTALLED'),
		"test" => (function_exists ('imagecreatefromjpeg')),
		"failmsg" => HTML_content::lbl('ERR_GDLIB'),
		"sevfront" => 3, "sevback" => 2)
);

if (extension_loaded('gd')) { // first test if this lib is enabled, then check subsettings
	if (function_exists ('gd_info')) {
		$array = gd_info();
		$libtests[] = array ("desc" => HTML_content::lbl('SYS_JPGLIB'),
			"setng" => HTML_content::lbl('SETNG_DISABLED') . '%' . HTML_content::lbl('SETNG_ENABLED'),
			"test" => ($array['JPG Support'] == true),
			"failmsg" => HTML_content::lbl('ERR_JPGLIB'),
			"sevfront" => 3, "sevback" => 2);
		$libtests[] = array ("desc" => HTML_content::lbl('SYS_FREETYPELIB'),
			"setng" => HTML_content::lbl('SETNG_DISABLED') . '%' . HTML_content::lbl('SETNG_ENABLED'),
			"test" => ($array['FreeType Support'] == true),
			"failmsg" => HTML_content::lbl('ERR_FREETYPELIB'),
			"sevfront" => 3, "sevback" => 2);
	}
}

// Check all path and file permissions
$pathtests = array (
	0 => array ("desc" => HTML_content::lbl('SYS_XMLPATH') . "<br/>&nbsp;&nbsp;&lt;joomla_root&gt;/components/com_expose/expose/xml/",
		"setng" => HTML_content::lbl('SETNG_UNWRITABLE') . '%' . HTML_content::lbl('SETNG_WRITABLE'),
		"test" => (is__writable($path.'/expose/xml/')),
		"failmsg" => HTML_content::lbl('ERR_XMLPATH'),
		"sevfront" => 3, "sevback" => 1),
	1 => array ("desc" => HTML_content::lbl('SYS_XMLFILE') . "<br/>&nbsp;&nbsp;&lt;joomla_root&gt;/components/com_expose/expose/xml/albums.xml",
		"setng" => HTML_content::lbl('SETNG_UNWRITABLE') . '%' . HTML_content::lbl('SETNG_WRITABLE'),
		"test" => (is__writable($path.'/expose/xml/albums.xml')),
		"failmsg" => HTML_content::lbl('ERR_XMLFILE'),
		"sevfront" => 3, "sevback" => 1),
	2 => array ("desc" => HTML_content::lbl('SYS_IMGPATH') . "<br/>&nbsp;&nbsp;&lt;joomla_root&gt;/components/com_expose/expose/img/",
		"setng" => HTML_content::lbl('SETNG_UNWRITABLE') . '%' . HTML_content::lbl('SETNG_WRITABLE'),
		"test" => (is__writable($path.'/expose/img/')),
		"failmsg" => HTML_content::lbl('ERR_IMGPATH'),
		"sevfront" => 3, "sevback" => 1),
	3 => array ("desc" => HTML_content::lbl('SYS_SESSIONPATH') . "<br/>&nbsp;&nbsp;".ini_get('session.save_path'),
		"setng" => HTML_content::lbl('SETNG_UNWRITABLE') . '%' . HTML_content::lbl('SETNG_WRITABLE'),
		"test" => (is_writable(ini_get('session.save_path'))),
		"failmsg" => HTML_content::lbl('ERR_SESSIONPATH'),
		"sevfront" => 2, "sevback" => 2),
	4 => array ("desc" => HTML_content::lbl('SYS_PASSHASHFILE') . "<br/>&nbsp;&nbsp;&lt;joomla_root&gt;/components/com_expose/expose/manager/amfphp/extra/passhash.inc.php",
		"setng" => HTML_content::lbl('SETNG_UNWRITABLE') . '%' . HTML_content::lbl('SETNG_WRITABLE'),
		"test" => (is__writable($path.'/expose/manager/amfphp/extra/passhash.inc.php')),
		"failmsg" => HTML_content::lbl('ERR_PASSHASHFILE'),
		"sevfront" => 3, "sevback" => 1),
	5 => array ("desc" => HTML_content::lbl('SYS_CONFIGFILE') . "<br/>&nbsp;&nbsp;&lt;joomla_root&gt;/components/com_expose/expose/config/config.xml",
		"setng" => HTML_content::lbl('SETNG_UNWRITABLE') . '%' . HTML_content::lbl('SETNG_WRITABLE'),
		"test" => (is__writable($path.'/expose/config/config.xml')),
		"failmsg" => HTML_content::lbl('ERR_CONFIGFILE'),
		"sevfront" => 3, "sevback" => 1),
	6 => array ("desc" => HTML_content::lbl('SYS_SETTINGSFILE') . "<br/>&nbsp;&nbsp;&lt;joomla_root&gt;/components/com_expose/expose/manager/amfphp/extra/settings.xml",
		"setng" => HTML_content::lbl('SETNG_UNWRITABLE') . '%' . HTML_content::lbl('SETNG_WRITABLE'),
		"test" => (is__writable($path.'/expose/manager/amfphp/extra/settings.xml')),
		"failmsg" => HTML_content::lbl('ERR_SETTINGSFILE'),
		"sevfront" => 3, "sevback" => 1),
	7 => array ("desc" => HTML_content::lbl('SYS_DISKSPACE'),
		"setng" => (my_drivespace() . 'Mb%' . my_drivespace() . 'Mb'),
		"test" => (my_drivespace() > 2),
		"failmsg" => HTML_content::lbl('ERR_DISKSPACE'),
		"sevfront" => 3, "sevback" => 1)
);
/*	8 => array ("desc" => HTML_content::lbl('SYS_MD5FILE') . "<br/>&nbsp;&nbsp;&lt;joomla_root&gt;/components/com_expose/expose/manager/expose.md5",
		"setng" => "writable;NOT writable",
		"test" => (!is__writable($path.'/expose/manager/expose.md5')),
		"failmsg" => HTML_content::lbl('ERR_SETTINGSFILE'),
		"sevfront" => 3, "sevback" => 1),
*/
if (ini_get ('upload_tmp_dir')) { // it defaults to a default system location, only test if this is set
	$pathtests[] = array ("desc" => HTML_content::lbl('SYS_TEMPPATH') . "<br/>&nbsp;&nbsp;".ini_get('upload_tmp_dir'),
		"setng" => HTML_content::lbl('SETNG_UNWRITABLE') . '%' . HTML_content::lbl('SETNG_WRITABLE'),
		"test" => (is_dir(ini_get('upload_tmp_dir')) && is__writable (ini_get ('upload_tmp_dir')) ),
		"failmsg" => HTML_content::lbl('ERR_TEMPPATH'),
		"sevfront" => 3, "sevback" => 1);
	}

// Check some Expose specific settings
$exposetests = array (
	0 => array ("desc" => HTML_content::lbl('SYS_VERSION'),
		"setng" => (install_info('version').'%unknown'),
		"test" => (False),
		"failmsg" => HTML_content::lbl('ERR_VERSION'),
		"sevfront" => 1, "sevback" => 1),
	1 => array ("desc" => HTML_content::lbl('SYS_DATE'),
		"setng" => ("unknown%".install_info('creationDate')),
		"test" => (True),
		"failmsg" => HTML_content::lbl('ERR_DATE'),
		"sevfront" => 0, "sevback" => 0),
	2 => array ("desc" => HTML_content::lbl('SYS_EXEC'),
		"setng" => HTML_content::lbl('SETNG_DISABLED') . '%' . HTML_content::lbl('SETNG_ENABLED'),
		"test" => (!in_array ('exec', split (',\s*', ini_get ('disable_functions')))),
		"failmsg" => HTML_content::lbl('ERR_EXEC'),
		"sevfront" => 1, "sevback" => 1),
	3 => array ("desc" => HTML_content::lbl('SYS_SESSUSECOOKIES'),
		"setng" => HTML_content::lbl('SETNG_DISABLED') . '%' . HTML_content::lbl('SETNG_ENABLED'),
		"test" => (get_php_setting('session.use_cookies')),
		"failmsg" => HTML_content::lbl('ERR_SESSUSECOOKIES'),
		"sevfront" => 1, "sevback" => 1),
	4 => array ("desc" => HTML_content::lbl('SYS_ALLOWURLFOPEN'),
		"setng" => HTML_content::lbl('SETNG_DISABLED') . '%' . HTML_content::lbl('SETNG_ENABLED'),
		"test" => (get_php_setting('allow_url_fopen')),
		"failmsg" => HTML_content::lbl('ERR_ALLOWURLFOPEN'),
		"sevfront" => 3, "sevback" => 1),
	5 => array ("desc" => HTML_content::lbl('SYS_MAXPOSTSIZE'),
		"setng" => (ini_get( 'post_max_size' ) . 'b%' . ini_get( 'post_max_size' ) . 'b'),
		"test" => (ini_get( 'post_max_size' ) >= 20),
		"failmsg" => HTML_content::lbl('ERR_MAXPOSTSIZE'),
		"sevfront" => 3, "sevback" => 1),
	6 => array ("desc" => HTML_content::lbl('SYS_MAXINPUTTIME'),
		"setng" => (ini_get( 'max_input_time' ) . 'sec%' . ini_get( 'max_input_time' ) . 'sec'),
		"test" => (ini_get( 'max_input_time' ) >= 60),
		"failmsg" => HTML_content::lbl('ERR_MAXINPUTTIME'),
		"sevfront" => 3, "sevback" => 1),
	7 => array ("desc" => HTML_content::lbl('SYS_UPLOADMAXFILESIZE'),
		"setng" => (ini_get( 'upload_max_filesize' ) . 'b%' . ini_get( 'upload_max_filesize' ) . 'b'),
		"test" => (ini_get( 'upload_max_filesize' ) >= 20),
		"failmsg" => HTML_content::lbl('ERR_UPLOADMAXFILESIZE'),
		"sevfront" => 3, "sevback" => 1),
	8 => array ("desc" => HTML_content::lbl('SYS_MAXEXECUTIONTIME'),
		"setng" => (ini_get( 'max_execution_time' ) . 'sec%' . ini_get( 'max_execution_time' ) . 'sec'),
		"test" => (ini_get( 'max_execution_time' ) >= 60),
		"failmsg" => HTML_content::lbl('ERR_MAXEXECUTIONTIME'),
		"sevfront" => 3, "sevback" => 1),
	9 => array ("desc" => HTML_content::lbl('SYS_MEMORYLIMIT'),
		"setng" => (ini_get( 'memory_limit' ) . 'b%' . ini_get( 'memory_limit' ) . 'b'),
		"test" => (ini_get( 'memory_limit' ) >= 20),
		"failmsg" => HTML_content::lbl('ERR_MEMORYLIMIT'),
		"sevfront" => 3, "sevback" => 1)
);

//Create the html code
echo "<table class='adminheading'>
	<tr><th>
		<div><b><?php echo HTML_content::lbl('TITLE_CHECK');?></b></div>
	</th></tr>
</table>";

echo "<table class='adminlist'>
	<tr><th colspan='3'>
		<div align='center'><b><u>" . HTML_content::lbl('SYS_OVERVIEWTITLE') . "</u></b></div>
	</th></tr>
	<tr><td>";
		echo HTML_content::lbl('SYS_REPORT');
	echo "</td></tr>
</table>";

//echo $SYS_LIVEINFO
//		<iframe src='http://doctorjz.googlepages.com/expose_info.html' width='100%' frameborder='0'></iframe>";

	//Store tracked test-errors (warningfront, fatalfront, warningback, fatalback, warningfiles)
	$warnings = array(0,0,0,0,0);
	//  print_r ($joomtests);

//	echo HTML_content::lbl('SYS_SYSCHECK');
	echo HTML_content::lbl('SYS_SYSCHECK');
	print_table($systests, $warnings);
	echo HTML_content::lbl('SYS_JOOMCHECK');
	print_table($joomtests, $warnings);
	echo HTML_content::lbl('SYS_PATHCHECK');
	print_table($pathtests, $warnings);
	echo HTML_content::lbl('SYS_LIBCHECK');
	print_table($libtests, $warnings);
	echo HTML_content::lbl('SYS_EXPOSECHECK');
	print_table($exposetests, $warnings);
	echo HTML_content::lbl('SYS_HASHCHECK');

	if(function_exists('jimport')) {
		$MD5FilePath = JPath::clean( dirname(__FILE__) ).DS.'expose.md5';
	} else {
		$MD5FilePath = mosPathName( dirname(__FILE__) ).'expose.md5';
	}

	if (file_exists($MD5FilePath)) {
		compair($path, $warnings, $MD5FilePath, $skipfiles);
	} else {
		echo HTML_content::lbl('ERR_HASHMISSING');
		generateMd5Hash($path, $MD5FilePath, $skipfiles);
	}

	echo HTML_content::lbl('SYS_FINALREPORT');

	if ($warnings[4]) {
		echo HTML_content::lbl('SYS_HASHERRORS');
	}
	if ($warnings[1]) {
		echo str_replace('%warnings%',$warnings[1],HTML_content::lbl('SYS_FATALFRONT'));
	} elseif ($warnings[0]-1) {
		echo str_replace('%warnings%',($warnings[0]-1),HTML_content::lbl('SYS_WARNINGFRONT'));
	} else {
		echo HTML_content::lbl('$SYS_PASSFRONT');
	}
	echo '<br/>';
	if ($warnings[3]) {
		echo str_replace('%warnings%',$warnings[3],HTML_content::lbl('SYS_FATALBACK'));
	} elseif ($warnings[2]-1) {
		echo str_replace('%warnings%',($warnings[2]-1),HTML_content::lbl('SYS_WARNINGBACK'));
	} else {
		echo HTML_content::lbl('SYS_PASSBACK');
	}
	if (!$warnings[1]) echo HTML_content::lbl('SYS_PASSFINAL');
	else echo HTML_content::lbl('SYS_FATALFINAL');
}
?>
