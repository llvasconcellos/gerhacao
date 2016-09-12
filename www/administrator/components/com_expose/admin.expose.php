<?php
/****************************************************************************
Component: Expose flash gallery component for Joomla 1.0.x and 1.5.x.
Version  : 4.6.3 (23/03/2008)
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

	if(function_exists('jimport')) {
		$acl =& JFactory::getACL();
		$acl->addACL( 'com_expose', 'manage', 'users', 'super administrator' );
		/* Additional access groups */
//		$acl->addACL( 'com_expose', 'manage', 'users', 'administrator' );
//		$acl->addACL( 'com_expose', 'manage', 'users', 'manager' );
		$user = & JFactory::getUser();
		if (!$user->authorize( 'com_expose', 'manage' )) {
			$mainframe->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
		}
	} else {
		// ensure user has access to this function
		if (!($acl->acl_check( 'administration', 'edit', 'users', $my -> usertype, 'components', 'all' )
		| $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_expose' ))) {
			mosRedirect( 'index2.php', _NOT_AUTH );
		}
	}

	// Check for php version for domxml
	if (version_compare(PHP_VERSION,'5','>='))
		include_once("../components/com_expose/expose/manager/misc/domxml-php4-to-php5.php");

	include_once("../components/com_expose/expose/admin/config.expose.php");
	include_once("../components/com_expose/expose/manager/misc/common.inc.php");
	include_once("../components/com_expose/expose/manager/misc/xmlfunc.inc.php");
	include_once("../configuration.php" );
	include_once("../includes/joomla.php" );
	include_once($mainframe->getPath( 'admin_html' ) );

	HTML_content::ShowHeader();

/*	// Check if the system has the SYSTEM REQUIREMENTS
	$checksys = expose_M::syscheck();
	If($checksys != "") {
		echo HTML_content::lbl('BAD_SERVERCONFIG');
		$task = "check";
		//echo $checksys;
	} */

	//defign security id
	$userid = uniqid(mt_rand());

	// Main switch
	switch ($task) {
		case "apply":
			$msg = expose_M::SaveConfig();
			$msg = $msg . expose_M::SaveFonts();
			$msg = $msg . expose_M::SaveSettings();
			HTML_content::ShowRedirect('index2.php?option=com_expose&task=config', $msg);
			break;

		case "save":
			$msg = expose_M::SaveConfig();
			$msg = $msg . expose_M::SaveFonts();
			$msg = $msg . expose_M::SaveSettings();
			HTML_content::ShowRedirect('index2.php', $msg);
			break;

		case "config":
			$xmlF = expose_M::rpath();
			HTML_content::ShowConfig($userid);
			break; 

		case "wiki":
			HTML_content::ShowRedirect('http://joomlacode.org/gf/project/expose/wiki/', HTML_content::lbl('REDIRECT_WIKI'));
			break;

		case "manual":
			HTML_content::ShowRedirect('http://joomlacode.org/gf/project/expose/frs/', HTML_content::lbl('REDIRECT_FILES'));
			break;

		case "check":
			HTML_content::ShowCheck();
			break;

		case 'cancel':
			HTML_content::ShowRedirect('index2.php', HTML_content::lbl( 'REDIRECT_CANCELED' ));
			break;

		default: // "manage":
			HTML_content::ShowManager();
			break;

	}
?>
