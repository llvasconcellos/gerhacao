<?php
/**
* @version $Id: help.php 2005-12-27 09:23:43Z Ryan Demmer $
* @package JCE
* @copyright Copyright (C) 2005 Ryan Demmer. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* JCE is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
*/
defined( '_VALID_MOS' ) or die( 'Restricted Access.' );

$version = "1.1.0";

require_once( $mainframe->getCfg('absolute_path') . '/mambots/editors/jce/jscripts/tiny_mce/libraries/classes/jce.class.php' );
require_once( $mainframe->getCfg('absolute_path') . '/mambots/editors/jce/jscripts/tiny_mce/libraries/classes/jce.utils.class.php' );
$jce = new JCE();

$params = $jce->getParams();
$helpurl = strval( $params->get( 'help_url', 'http://www.cellardoor.za.net' ) );
	
$lang = $jce->getLanguage();	
include_once( $jce->getLibPath() . '/langs/' . $lang . '.php' );
	
$plugin = mosGetParam( $_REQUEST, 'plugin', '' );
$jce->setPlugin( $plugin );
include_once( $jce->getPluginPath() . '/langs/' .  $jce->getPluginLanguage() . '.php' );

$fullhelpurl = $helpurl . '/index2.php?option=com_content&amp;task=findkey&amp;pop=1&amp;lang=' . $lang . '&amp;keyref=';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php $cl['iso'];?>" />
<title><?php echo $pl['title'].'&nbsp;'.$cl['help'];?></title>
</head>
<frameset cols="260,*" frameborder="yes" border="3" framespacing="0">
	<frame src="index2.php?option=com_jce&amp;no_html=1&amp;task=help&amp;plugin=<?php echo $plugin;?>&amp;file=menu.help.php" name="menuFrame" id="menuFrame" />
	<frame src="<?php echo $fullhelpurl . urlencode( $plugin . '.about' );?>" name="helpFrame" id="helpFrame" scrolling="yes"/>
</frameset>
<noframes><body></body>
</noframes></html>