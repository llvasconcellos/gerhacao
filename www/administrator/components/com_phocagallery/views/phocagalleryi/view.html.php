<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die();
jimport( 'joomla.client.helper' );
jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pane' );

class PhocaGalleryCpViewPhocagalleryI extends JView
{
	function display($tpl = null) {
		global $mainframe;

		$document	= &JFactory::getDocument();
		$params 	= &JComponentHelper::getParams( 'com_phocagallery' );
		
		JHTML::stylesheet( 'phocagallery.css', 'administrator/components/com_phocagallery/assets/' );

		$document->addCustomTag("<!--[if IE]>\n<link rel=\"stylesheet\" href=\"../administrator/components/com_phocagallery/assets/phocagalleryieall.css\" type=\"text/css\" />\n<![endif]-->");
		
		$tmpl['large_image_width']	= $params->get( 'large_image_width', 640 );
		$tmpl['large_image_height']	= $params->get( 'large_image_height', 480 );
		$tmpl['uploadmaxsize'] 		= $params->get( 'upload_maxsize', 3000000 );
		$tmpl['javaresizewidth'] 	= $params->get( 'java_resize_width', -1 );
		$tmpl['javaresizeheight'] 	= $params->get( 'java_resize_height', -1 );
		
		$tmpl['tab'] 				= JRequest::getVar('tab', 0, '', 'int');
		
		// Do not allow cache
		JResponse::allowCache(false);

		$path 			= PhocaGalleryHelper::getPathSet();
		$path_orig_rel 	= $path['orig_rel_ds'];
		
		$this->assign('path_orig_rel', $path_orig_rel);
		$this->assignRef('images', $this->get('images'));
		$this->assignRef('folders', $this->get('folders'));
		$this->assignRef('state', $this->get('state'));
		
	
		// Upload Form ------------------------------------
		JHTML::_('behavior.mootools');
		//$document->addScript('components/com_phocagallery/assets/upload/mediamanager.js');
		$document->addStyleSheet('components/com_phocagallery/assets/upload/mediamanager.css');

		// Set FTP form
		$ftp = !JClientHelper::hasCredentials('ftp');
		
		// Set flash uploader if ftp password and login exists (will be not problems)
		$state			= $this->get('state');
		$refreshSite 	= 'index.php?option=com_phocagallery&view=phocagalleryi&tmpl=component&tab=2&folder='.$state->folder;
		if (!$ftp) {
		//	if ($params->get('enable_flash', 0)) {
				PhocaGalleryHelperUpload::uploader('file-upload', array('onAllComplete' => 'function(){ window.location.href="'.$refreshSite.'"; }'));
		//	}
		}
		// END Upload Form ------------------------------------
		//TABS
		$tmpl['displaytabs']	= 0;
		
		// UPLOAD
		$tmpl['currenttab']['upload'] = $tmpl['displaytabs'];
		$tmpl['displaytabs']++;
		
		// JAVA UPLOAD
		$tmpl['currenttab']['javaupload'] = $tmpl['displaytabs'];
		$tmpl['displaytabs']++;	

		// FLASH UPLOAD
		$tmpl['currenttab']['flashupload'] = $tmpl['displaytabs'];
		$tmpl['displaytabs']++;		
		
		$this->assignRef('session', JFactory::getSession());
		$this->assign('tmpl', $tmpl);
		$this->assign('require_ftp', $ftp);

		parent::display($tpl);
		echo JHTML::_('behavior.keepalive');
	}

	function setFolder($index = 0)
	{
		if (isset($this->folders[$index])) {
			$this->_tmp_folder = &$this->folders[$index];
		} else {
			$this->_tmp_folder = new JObject;
		}
	}

	function setImage($index = 0)
	{
		if (isset($this->images[$index])) {
			$this->_tmp_img = &$this->images[$index];
		} else {
			$this->_tmp_img = new JObject;
		}
	}
}
?>