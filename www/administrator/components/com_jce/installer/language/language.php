<?php
/**
* @version $Id: language.php 328 2005-10-02 15:39:51Z Jinx $
* @package Joomla
* @subpackage Installer
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

// ensure user has access to this function
//if (!($acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' )
//                | $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_jce' ))) {
//        mosRedirect( 'index2.php', _NOT_AUTH );
//}

global $mainframe, $database;
$database->setQuery( "SELECT lang FROM #__jce_langs WHERE published= '1'" );
$lang = $database->loadResult();

$database->setQuery( "SELECT id as id, plugin as plugin FROM #__jce_plugins WHERE type = 'plugin'" );
$plugins = $database->loadObjectList();

require_once( $mainframe->getCfg('absolute_path') . '/administrator/components/com_jce/language/' . $lang . '.php' );

$backlink = '<a href="index2.php?option=com_jce&task=lang">'._JCE_LANG_BACK.'</a>';
HTML_installer::showInstallForm( _JCE_LANG_HEADING_INSTALL, $option, 'language', '', dirname(__FILE__), $backlink );
?>
<table class="content">
<?php
writableCell( 'administrator/components/com_jce/language' );
writableCell( 'mambots/editors/jce/jscripts/tiny_mce/langs' );
writableCell( 'mambots/editors/jce/jscripts/tiny_mce/themes/advanced/langs' );
foreach( $plugins as $plugin ){
	if( file_exists( $mainframe->getCfg('absolute_path') . '/mambots/editors/jce/jscripts/tiny_mce/plugins/' . $plugin->plugin . '/langs' ) ){
		writableCell( 'mambots/editors/jce/jscripts/tiny_mce/plugins/' . $plugin->plugin . '/langs' );
	}
}
?>
</table>
