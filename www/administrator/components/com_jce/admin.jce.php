<?php
/**
* @version		$Id: admin.jce.php 7320 2007-05-03 19:02:52Z jinx $
* @package		Joomla
* @subpackage	Plugins
* @copyright	Copyright (C) 2005 - 2007 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$task = JRequest::getCmd( 'task' );

/*
 * Editor or plugin request.
 */
if( $task == 'plugin' || $task == 'help' ){
	require_once( dirname( __FILE__ ) .DS. 'editor.php' );
	exit();
}

// Authorize
$user 	= & JFactory::getUser();
$acl	= & JFactory::getACL();

// Fudge ACL for Administrators
$acl->addACL( 'com_jce', $task, 'users', 'super administrator' );
$acl->addACL( 'com_jce', $task, 'users', 'administrator' );
// Uncomment to allow Manager access
//$acl->addACL( 'com_jce', $task, 'users', 'manager' );

if (!$user->authorize( 'com_jce', $task )) {
	$mainframe->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}

require_once( dirname( __FILE__ ) .DS. 'helper.php' );
if( JRequest::getVar( 'redirected' ) ){
	require_once( dirname( __FILE__ ) .DS. 'cpanel.php' );
	return false;
}
$option = JRequest::getCmd( 'option' );
$type	= JRequest::getCmd( 'type' );
/* 
 * Installer error fallback functions
 */
if( $type == 'fixinstall-editor' ){
	JCEHelper::fixEditor();
}
if( $type == 'fixinstall-plugins' ){
	JCEHelper::fixPlugins();
}
if( $type == 'fixinstall-groups' ){
	JCEHelper::fixGroups();
}
/* 
 * Check for installations
 */
if( !JCEHelper::checkEditor() ){
	$mainframe->redirect( 'index.php?option=com_jce&redirected=1', JText::_('Editor is not enabled. Please enable the editor in the Joomla! Plugin Manager.') );
}
if( !JCEHelper::checkEditorPath() ){
	$mainframe->redirect( 'index.php?option=com_jce&redirected=1', JText::_('Editor files missing. Please install Editor Plugin or follow manual install instructions.') );
}
if( !JCEHelper::checkPlugins() ){
	$mainframe->redirect( 'index.php?option=com_jce&redirected=1', JText::_('The plugins database was not created during install. Click the Fix Plugins button below to complete the installation.') );
}
if( !JCEHelper::checkGroups() ){
	$mainframe->redirect( 'index.php?option=com_jce&redirected=1', JText::_('The groups database was not created during install. Click the Fix Groups button below to complete the installation.') );
}

$client = JRequest::getWord( 'client', 'site' );
$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

JArrayHelper::toInteger($cid, array(0));

switch( $type ){
	case 'plugin':	
		switch( $task ){
			case 'install':
			case 'remove':
			case 'manage':
				require_once( dirname( __FILE__ ) .DS. 'installer.php' );
				break;
			case 'view':
			default:
				require_once( dirname( __FILE__ ) .DS. 'plugin.php' );
				break;
		}
		break;
	case 'group':	
		switch( $task ){
			case 'view':
			default:
				require_once( dirname( __FILE__ ) .DS. 'groups.php' );
				break;
		}
		break;
	case 'language':
		switch( $task ){
			case 'install':
			case 'remove':
			case 'manage':
				require_once( dirname( __FILE__ ) .DS. 'installer.php' );
				break;
			default:
				require_once( dirname( __FILE__ ) .DS. 'cpanel.php' );
				break;
		}
		break;
	case 'extension':
		switch( $task ){
			case 'install':
			case 'remove':
			case 'manage':
				require_once( dirname( __FILE__ ) .DS. 'installer.php' );
				break;
			default:
				require_once( dirname( __FILE__ ) .DS. 'plugin.php' );
				break;
		}
		break;
	case 'config':
		require_once( dirname( __FILE__ ) .DS. 'config.php' );
		break;
	case 'install':
		require_once( dirname( __FILE__ ) .DS. 'installer.php' );
		break;
	default:
		switch( $task ){
			case 'install':
				require_once( dirname( __FILE__ ) .DS. 'installer.php' );
				break;
			default:
			require_once( dirname( __FILE__ ) .DS. 'cpanel.php' );
				break;	
		}
		break;
}
?>
