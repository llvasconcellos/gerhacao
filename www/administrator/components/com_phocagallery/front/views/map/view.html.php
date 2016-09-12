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
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view');
class PhocaGalleryViewMap extends JView
{
	function display($tpl = null) {
		global $mainframe;
		$tmpl = array();
		
		// PLUGIN WINDOW - we get information from plugin
		$get		= '';
		$get['map']	= JRequest::getVar( 'map', '', 'get', 'string' );
		
		$document	= & JFactory::getDocument();		
		$params		= &$mainframe->getParams();
		
		// START CSS
		$document->addStyleSheet(JURI::base(true).'/components/com_phocagallery/assets/phocagallery.css');
		
		// PARAMS - Open window parameters - modal popup box or standard popup window
		$detail_window = $params->get( 'detail_window', 0 );
		
		// Plugin information
		if (isset($get['map']) && $get['map'] != '') {
			$detail_window = $get['map'];
		}
		
		
		// Standard popup window
		if ($detail_window == 1) {
			$detail_window_close 	= 'window.close();';
			$detail_window_reload	= 'window.location.reload(true);';
		} else {//modal popup window
			$detail_window_close 	= 'window.parent.document.getElementById(\'sbox-window\').close();';
			$detail_window_reload	= 'window.location.reload(true);';
		}
		
		// PARAMS - Display Description in Detail window - set the font color
		$tmpl['detailwindowbackgroundcolor']= $params->get( 'detail_window_background_color', '#ffffff' );
		$description_lightbox_font_color 	= $params->get( 'description_lightbox_font_color', '#ffffff' );
		$description_lightbox_bg_color 		= $params->get( 'description_lightbox_bg_color', '#000000' );
		$description_lightbox_font_size 	= $params->get( 'description_lightbox_font_size', 12 );

		// NO SCROLLBAR IN DETAIL WINDOW
		$document->addCustomTag( "<style type=\"text/css\"> \n" 
			." html,body, .contentpane{overflow:hidden;background:".$tmpl['detailwindowbackgroundcolor'].";} \n" 
			." center, table {background:".$tmpl['detailwindowbackgroundcolor'].";} \n" 
			." #sbox-window {background-color:#fff100;padding:5px} \n" 
			." </style> \n");
		
		
		// PARAMS - Get image height and width
		$tmpl['boxlargewidth']		= $params->get( 'front_modal_box_width', 680 );
		$tmpl['boxlargeheight'] 	= $params->get( 'front_modal_box_height', 560 );
		$front_popup_window_width 	= $tmpl['boxlargewidth'];//since version 2.2
		$front_popup_window_height 	= $tmpl['boxlargeheight'];//since version 2.2
		
		if ($detail_window == 1) {
			$tmpl['windowwidth']	= $front_popup_window_width;
			$tmpl['windowheight']	= $front_popup_window_height;
		} else {//modal popup window
			$tmpl['windowwidth']	= $tmpl['boxlargewidth'];
			$tmpl['windowheight']	= $tmpl['boxlargeheight'];
		}
		
		$tmpl['largemapwidth']		= (int)$tmpl['windowwidth'] - 20;
		$tmpl['largemapheight']		= (int)$tmpl['windowheight'] - 20;
		$tmpl['googlemapsapikey']	= $params->get( 'google_maps_api_key', '' );
			
		// MODEL
		$model	= &$this->getModel();
		$map	= $model->getData();
		
		// ASIGN
		$this->assignRef( 'tmpl', $tmpl );
		$this->assignRef( 'map', $map );
		parent::display($tpl);
	}
}
