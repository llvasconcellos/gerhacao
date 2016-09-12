<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die();
jimport( 'joomla.client.helper' );
jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pane' );
require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_phocagallery'.DS.'helpers'.DS.'phocagalleryupload.php' );
require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_phocagallery'.DS.'helpers'.DS.'phocagalleryrender.php' );

class PhocaGalleryViewUser extends JView
{
	function display($tpl = null) {
		
		global $mainframe;
		$document	= &JFactory::getDocument();
		$uri 		= &JFactory::getURI();
		$menus		= &JSite::getMenu();
		$menu		= $menus->getActive();
		$params		= &$mainframe->getParams();
		$user 		= &JFactory::getUser();
		
		// LIBRARY
		$library 							= &PhocaLibrary::getLibrary();
		$libraries['pg-css-ie'] 			= $library->getLibrary('pg-css-ie');
		
		// Only registered users
		if ($user->aid == 0) {
			$mainframe->redirect(JRoute::_('index.php?option=com_user&view=login', false), JText::_("ALERTNOTAUTH"));
			exit;
		}
		
		// CSS, JS
		JHTML::stylesheet( 'phocagallery.css', 'components/com_phocagallery/assets/' );
		if ( $libraries['pg-css-ie']->value == 0 ) {
			$document->addCustomTag("<!--[if lt IE 8]>\n<link rel=\"stylesheet\" href=\""
			.JURI::base(true)
			."/components/com_phocagallery/assets/phocagalleryieall.css\" type=\"text/css\" />\n<![endif]-->");
			$library->setLibrary('pg-css-ie', 1);
		}
		$document->addScript(JURI::base(true).'/components/com_phocagallery/assets/js/comments.js');
		
		//$id 						= JRequest::getVar('id', 0, '', 'int');
		$tmpl['tab'] 				= JRequest::getVar('tab', 0, '', 'string');
		$tmpl['formaticon'] 		= PhocaGalleryHelperFront::getFormatIcon();
		$tmpl['displaytitleupload']	= $params->get( 'display_title_upload', 0 );
		$tmpl['displaydescupload'] 	= $params->get( 'display_description_upload', 0 );
		$tmpl['maxuploadchar']		= $params->get( 'max_upload_char', 1000 );
		$tmpl['maxcreatecatchar']	= $params->get( 'max_create_cat_char', 1000 );
		$tmpl['phocagalleryic']		= $params->get( 'display_phoca_info', 1 );
		$tmpl['phocagalleryic'] 	= PhocaGalleryHelper::getPhocaIc((int)$tmpl['phocagalleryic']);
		$tmpl['showpagetitle'] 		= $params->get( 'show_page_title', 1 );
		$tmpl['enablejava']			= $params->get( 'enable_java', 0 );
		$tmpl['javaresizewidth'] 	= $params->get( 'java_resize_width', -1 );
		$tmpl['javaresizeheight'] 	= $params->get( 'java_resize_height', -1 );
		$tmpl['javaboxwidth'] 		= $params->get( 'java_box_width', 480 );
		$tmpl['javaboxheight'] 		= $params->get( 'java_box_height', 480 );
		
		// UCP is disabled (security reasons)
		$enable_user_cp		 		= $params->get( 'enable_user_cp', 0 );
		if ($enable_user_cp == 0) {
			$mainframe->redirect(JURI::base(true), JText::_("User Control Panel is disabled"));
			exit;
		}
		
		// PANE - CATEGORY EDIT | CREATE
		// MODEL - Get user's category		
		$model 		= $this->getModel('user');
		$userCategory 	= $model->getUserCategory($user->id);
		
		if (!empty($userCategory->categoryid)) {
			
			if ((int)$userCategory->categorypublished == 1) {
				$tmpl['createoredithead']		= JText::_('Edit Category');
				$tmpl['createoredit']			= JText::_('Phoca Gallery Edit');		
				$tmpl['categorytitle']			= $userCategory->categorytitle;
				$tmpl['categorydescription']	= $userCategory->categorydescription;
				$tmpl['categorypublished']		= 1;
			} else {
				$tmpl['categorypublished']		= 0;
			}
			
		} else {
			
			$tmpl['createoredithead']		= JText::_('Create Category');
			$tmpl['createoredit']			= JText::_('Phoca Gallery Create');
			$tmpl['categorytitle']			= '';
			$tmpl['categorydescription']	= '';
			$tmpl['categorypublished']		= -1;
			
		}
		
		
		$document->addCustomTag(PhocaGalleryHelperRender::renderDescriptionCreateCatJS((int)$tmpl['maxcreatecatchar']));
		
		// PANE - UPLOAD
		// Category Params
		if (!empty($userCategory->categoryid)) {
			$catParams		= $model->getCategoryParams((int)$userCategory->categoryid);
			
			// ===========================================================
			// Upload
			$tmpl['displayupload']	= 0;
			// USER RIGHT - UPLOAD =======================================
			// 2, 2 means that user access will be ignored in function getUserRight for display Delete button
			$rightDisplayUpload = 0;// default is to null (all users cannot upload)
			if (isset($catParams->params)) {
				$rightDisplayUpload = PhocaGalleryHelper::getUserRight($catParams->params, 'uploaduserid', 1, $user->get('aid', 0), $user->get('id', 0), 0);
			}

			if ($rightDisplayUpload == 1) {
				$tmpl['displayupload']	= 1;
				$document->addCustomTag(PhocaGalleryHelperRender::renderDescriptionUploadJS((int)$tmpl['maxuploadchar']));
			}
			// ===========================================================
			
			// USER RIGHT - ACCESS =======================================
			$rightDisplay = 1;//default is set to 1 (all users can see the category)
			if (isset($catParams->params)) {
				$rightDisplay = PhocaGalleryHelper::getUserRight ($catParams->params, 'accessuserid', 0, $user->get('aid', 0), $user->get('id', 0), 1);
			}
			
			if ($rightDisplay == 0) {
				$mainframe->redirect(JRoute::_('index.php?option=com_user&view=login', false), JText::_("ALERTNOTAUTH"));
				exit;
			}		
			// ===========================================================
		
			// Upload Form -----------------------------------------------
			// Set FTP form
			$ftp = !JClientHelper::hasCredentials('ftp');
			// PARAMS - Upload size
			$tmpl['uploadmaxsize'] = $params->get( 'upload_maxsize', 3000000 );
			$this->assignRef('session', JFactory::getSession());
			// END Upload Form -------------------------------------------
			
		} else {
			$tmpl['displayupload'] = 0;
		}

		$tmpl['createcategory'] = 1;
		// Tabs
		$displayTabs	= 0;
		if ((int)$tmpl['createcategory'] == 0) {
			$currentTab['createcategory'] = -1;
		} else {
			$currentTab['createcategory'] = $displayTabs;
			$displayTabs++;	
		}
		
		
		if ((int)$tmpl['displayupload'] == 0) {
			$currentTab['upload'] = -1;
		}else {
			$currentTab['upload'] = $displayTabs;
			$displayTabs++;	
		}
	
		$tmpl['displaytabs']	= $displayTabs;
		$tmpl['currenttab']		= $currentTab;
		
		// ACTION
		$tmpl['action']	= $uri->toString();
		
		// ASIGN
		$this->assignRef( 'tmpl',				$tmpl);
		$this->assignRef( 'params' ,			$params);
		$this->assignRef( 'session', JFactory::getSession());
		parent::display($tpl);
	}
}
?>
