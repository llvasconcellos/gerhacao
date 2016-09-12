<?php
/****************************************************************************
Component: Expose flash gallery component for Joomla 1.0.x and 1.5.x.
Version  : 4.6.3 (03/02/2008)
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

require_once( $mainframe->getPath( 'toolbar_html' ) );
//require_once( $mainframe->getPath( 'toolbar_default' ) );

switch ($task) {
	case "config":
		ExposeMenu::FORMATS_MENU();
		break;

	case "save":
		ExposeMenu::FORMATS_MENU();
		break;

	case "formats":
		ExposeMenu::FORMATS_MENU();
		break;

	case "about":
		ExposeMenu::ABOUT_MENU();
		break;

	case "settings":
		ExposeMenu::FORMATS_MENU();
		break;

	default:
		ExposeMenu::MAIN_MENU();
		break;
}
?>