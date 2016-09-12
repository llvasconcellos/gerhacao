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
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view');

class PhocaGalleryViewDetail extends JView
{
	function display($tpl = null) {
		global $mainframe;
		
		// PLUGIN WINDOW - we get information from plugin
		$get				= '';
		$get['detail']		= JRequest::getVar( 'detail', '', 'get', 'string');
		$get['buttons']		= JRequest::getVar( 'buttons', '', 'get', 'string' );
		
		$document			= &JFactory::getDocument();		
		$params				= &$mainframe->getParams();
		
		// START CSS
		$document->addStyleSheet(JURI::base(true).'/components/com_phocagallery/assets/phocagallery.css');
		
		// PARAMS - Open window parameters - modal popup box or standard popup window
		$detail_window 			= $params->get( 'detail_window', 0 );
		// Plugin information
		if (isset($get['detail']) && $get['detail'] != '') {
			$detail_window 		= $get['detail'];
		}
		
		$tmpl['detailbuttons']	= $params->get( 'detail_buttons', 1 );
		
		// Plugin information
		if (isset($get['buttons']) && $get['buttons'] != '') {
			$tmpl['detailbuttons'] = $get['buttons'];
		}
		
		// Standard popup window
		if ($detail_window == 1) {
			$tmpl['detailwindowclose']	= 'window.close();';
			$tmpl['detailwindowreload']	= 'window.location.reload(true);';
		} else if ($detail_window == 4 || $detail_window == 5) {// highslide
			$tmpl['detailwindowclose']	= 'return false;';
			$tmpl['detailwindowreload']	= 'window.location.reload(true);';
		} else {//modal popup window
			$tmpl['detailwindowclose']	= 'window.parent.document.getElementById(\'sbox-window\').close();';
			$tmpl['detailwindowreload']	= 'window.location.reload(true);';
		} 
		
		$tmpl['displaydescriptiondetail']		= $params->get( 'display_description_detail', 0 );
		$tmpl['displaytitleindescription']		= $params->get( 'display_title_description', 0 );
		$tmpl['descriptiondetailheight']		= $params->get( 'description_detail_height', 16 );
		$tmpl['fontsizedesc'] 					= $params->get( 'font_size_desc', 11 );
		$tmpl['fontcolordesc'] 					= $params->get( 'font_color_desc', '#333333' );
		$tmpl['detailwindowbackgroundcolor']	= $params->get( 'detail_window_background_color', '#ffffff' );
		$tmpl['descriptionlightboxfontcolor']	= $params->get( 'description_lightbox_font_color', '#ffffff' );
		$tmpl['descriptionlightboxbgcolor']		= $params->get( 'description_lightbox_bg_color', '#000000' );
		$tmpl['descriptionlightboxfontsize']	= $params->get( 'description_lightbox_font_size', 12 );
		
		$tmpl['detailwindow']					= $params->get( 'detail_window', 0 );
		
		// NO SCROLLBAR IN DETAIL WINDOW
		$document->addCustomTag( "<style type=\"text/css\"> \n" 
			." html,body, .contentpane{overflow:hidden;background:".$tmpl['detailwindowbackgroundcolor'].";} \n" 
			." center, table {background:".$tmpl['detailwindowbackgroundcolor'].";} \n" 
			." #sbox-window {background-color:#fff100;padding:5px} \n" 
			." </style> \n");
		
		
		// PARAMS - Get image height and width
		$tmpl['largewidth'] 		= $params->get( 'large_image_width', 640 );
		$tmpl['largeheight'] 		= $params->get( 'large_image_height', 480 );
		$tmpl['boxlargewidth'] 		= $params->get( 'front_modal_box_width', 680 );
		$tmpl['boxlargeheight'] 	= $params->get( 'front_modal_box_height', 560 );
		$front_popup_window_width 	= $tmpl['boxlargewidth'];//since version 2.2
		$front_popup_window_height 	= $tmpl['boxlargeheight'];//since version 2.2
		
		// YOUTUBE
		// Standard popup window
		if ($detail_window == 1) {
			$tmpl['windowwidth']	= $front_popup_window_width;
			$tmpl['windowheight']	= $front_popup_window_height;
		} else {//modal popup window
			$tmpl['windowwidth']	= $tmpl['boxlargewidth'];
			$tmpl['windowheight']	= $tmpl['boxlargeheight'];
		}
		
		// PARAMS - Slideshow
		$tmpl['slideshowdelay'] 	= $params->get( 'slideshow_delay', 3000 );
		$tmpl['slideshowpause'] 	= $params->get( 'slideshow_pause', 0 );
		$tmpl['slideshowrandom'] 	= $params->get( 'slideshow_random', 0 );
		
		// MODEL
		$model			= &$this->getModel();
		$file	= $model->getData();
		
		// YouTube
		$videoCode			= PhocaGalleryHelper::getParamsArray($file->params, 'videocode');
		$tmpl['videocode']	= '';
		if (!empty($videoCode[0])) {
			$tmpl['videocode']	= $videoCode[0];
		}
		
		
		$realImageSize	= '';
		$realImageSize 	= PhocaGalleryHelper::getRealImageSize ($file->filenameno);
		if (isset($realImageSize['w']) && isset($realImageSize['h'])) {
			$tmpl['realimagewidth']		= $realImageSize['w'];
			$tmpl['realimageheight']	= $realImageSize['h'];
		} else {
			$tmpl['realimagewidth'] 	= $tmpl['largewidth'];
			$tmpl['realimageheight']	= $tmpl['largeheight'];
		}
		
		// ADD STATISTICS
		$model->hit(JRequest::getVar( 'id', '', '', 'int' ));
		
		// ASIGN
		$this->assignRef( 'tmpl', $tmpl );
		$this->assignRef( 'file', $file );
		
		if ($tmpl['videocode'] != '') {
			parent::display('video');
		} else {
			parent::display('slideshowjs');
			if ($file->slideshow == 1) {
				parent::display('slideshow');
			} else if ($file->download == 1) {
				parent::display('download');
			} else {
				parent::display($tpl);
			}
		}
	}
}
