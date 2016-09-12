<?php
/**
* @version 1.0.1
* @package RSform!Pro 1.0.1
* @copyright (C) 2007-2008 www.rsjoomla.com
* @license Commercial License, http://www.rsjoomla.com/license/rsformpro.html
*/
require_once( $mainframe->getPath( 'toolbar_html' ) );

switch ( $task ) {
		case "forms.manage":
		menuRsform::FORMSMANAGE_MENU();
		break;
		case "forms.edit":
		menuRsform::FORMSEDIT_MENU();
		break;
		case "components.copy.screen":
		menuRsform::COMPONENTS_COPY_MENU();
		break;
		case "submissions.manage":
		menuRsform::SUBMISSIONSMANAGE_MENU();
		break;


		case "configuration.edit":
			menuRsform::SETTINGS_MENU();
		break;
		default:
		menuRsform::_DEFAULT();
		break;

	}

?>