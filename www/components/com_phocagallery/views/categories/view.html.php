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

class PhocaGalleryViewCategories extends JView
{
	function display($tpl = null) {		
		global $mainframe;
		$db 		= &JFactory::getDBO();
		$model		= &$this->getModel();
		$document	= &JFactory::getDocument();
		$user 		= &JFactory::getUser();
		$params		= &$mainframe->getParams();
		$tmpl		= array();
		$tmpl2		= array();
		
		// Library
		$library 				= &PhocaLibrary::getLibrary();
		$libraries['pg-css-ie'] = $library->getLibrary('pg-css-ie');
		
	
		if ( $libraries['pg-css-ie']->value == 0 ) {
			$document->addCustomTag("<!--[if lt IE 8]>\n<link rel=\"stylesheet\" href=\""
			.JURI::base(true)
			."/components/com_phocagallery/assets/phocagalleryieall.css\" type=\"text/css\" />\n<![endif]-->");
			$library->setLibrary('pg-css-ie', 1);
		}
		JHTML::stylesheet( 'phocagallery.css', 'components/com_phocagallery/assets/' );
	
		
		// PARAMS
		$image_categories_size 	= $params->get( 'image_categories_size', 4 );
		$hide_categories 		= $params->get( 'hide_categories', '' );
		$medium_image_width 	= $params->get( 'medium_image_width', 100 );
		$medium_image_height 	= $params->get( 'medium_image_height', 100 );
		$small_image_width 		= $params->get( 'small_image_width', 50 );
		$small_image_height 	= $params->get( 'small_image_height', 50 );
		
		$medium_image_height	= $medium_image_height + 18;
		$medium_image_width 	= $medium_image_width + 18;
		$small_image_width		= $small_image_width +18;
		$small_image_height		= $small_image_height +18;
		
		$catImg = PhocaGalleryHelperFront::getCategoriesImage($image_categories_size, $small_image_width, $small_image_height,  $medium_image_height, $medium_image_width);
		
		$tmpl['imagebg'] 		= $catImg['imagebg'];
		$tmpl['imagewidth'] 	= $catImg['imagewidth'];
		
		// PARAMS
		$tmpl['phocagallerywidth'] 	= $params->get( 'phocagallery_width', '' );
		$display_subcategories 		= $params->get( 'display_subcategories', 1 );
		$display_empty_categories 	= $params->get( 'display_empty_categories', 0 );
		$tmpl['categoriescolumns'] 	= $params->get( 'categories_columns', 1 );
		$tmpl['phocagalleryic'] 	= $params->get( 'display_phoca_info', 1 );
		$tmpl['phocagalleryic'] 	= PhocaGalleryHelper::getPhocaIc((int)$tmpl['phocagalleryic']);
		$categories					= $model->getData($display_subcategories, $display_empty_categories, $hide_categories);
		
		// PARMAS - Access Category - display category in category list, which user cannot access
		$display_access_category = $params->get( 'display_access_category', 1 );
		// Add link and unset the categories which user cannot see (if it is enabled in params)
		$unSet = 0;// If it will be unseted while access view, we must sort the keys from category array - ACCESS
	
		foreach ($categories as $key => $category) { 
			$categories[$key]->link	= JRoute::_('index.php?option=com_phocagallery&view=category&id='. $category->slug.'&Itemid='. JRequest::getVar('Itemid', 1, 'get', 'int') ); 
			

			// USER RIGHT - ACCESS =======================================
			$rightDisplay	= 1;
			
			if (isset($categories[$key]->params)) {
				$rightDisplay = PhocaGalleryHelper::getUserRight ($categories[$key]->params, 'accessuserid', $category->access, $user->get('aid', 0), $user->get('id', 0), $display_access_category);
			}
						
			
			// Display Key Icon (in case we want to display unaccessable categories in list view)
			$rightDisplayKey  = 1;
			if ($display_access_category == 1) {
				// we simulate that we want not to display unaccessable categories
				// so we get rightDisplayKey = 0 then the key will be displayed
				if (isset($categories[$key]->params)) {
					$rightDisplayKey = PhocaGalleryHelper::getUserRight ($categories[$key]->params, 'accessuserid', $category->access, $user->get('aid', 0), $user->get('id', 0), 0);
				}
			}
			
			$file_thumbnail			= PhocaGalleryHelperFront::displayFileOrNoImageCategories($category->filename, $image_categories_size, $rightDisplayKey); 
			$categories[$key]->linkthumbnailpath	= $file_thumbnail['rel'];
		
			if ($rightDisplay == 0) {
				unset($categories[$key]);
				$unSet = 1;
			}
			// ============================================================	
			
		}
		
		
		// ACCESS - in case we unset some category from the list, we must sort the array new
		if ($unSet == 1) {
			$categories = array_values($categories);
		}

		// PARAMS - Define image tag attributes
		if ($params->get('image') != -1) {
			$attribs['align']	= $params->get('image_align');
			$attribs['hspace']	= 6;
			// Use the static HTML library to build the image tag
			$tmpl['image'] 		= JHTML::_('image', 'images/stories/'.$params->get('image'), JText::_('Phoca gallery'), $attribs);
		}
		
		// PARAMS - Display or hide image beside the category name
		$tmpl['displayimagecategories'] = $params->get( 'display_image_categories', 1 );

		// ASSIGN
		$this->assignRef('tmpl',	$tmpl);
		$this->assignRef('params',	$params);
		$this->assignRef('categories',	$categories);
		
		// PARAMS - GEO
		$display_categories_geotagging 		= $params->get( 'display_categories_geotagging', 0 );
		
		if ($display_categories_geotagging == 1) {
		
			$tmpl2['categorieslng'] 		= $params->get( 'categories_lng', '' );
			$tmpl2['categorieslat'] 		= $params->get( 'categories_lat', '' );
			$tmpl2['categorieszoom'] 		= $params->get( 'categories_zoom', 2 );
			$tmpl2['googlemapsapikey'] 		= $params->get( 'google_maps_api_key', '' );
			$tmpl2['categoriesmapwidth'] 	= $params->get( 'categories_map_width', 500 );
			$tmpl2['categoriesmapheight'] 	= $params->get( 'categorires_map_height', 500 );
			
			// if no lng and lat will be added, Phoca Gallery will try to find it in categories
			if ($tmpl2['categorieslat'] == '' || $tmpl2['categorieslng'] == '') {
				$latLng = PhocaGalleryHelper::findLatLngFromCategory($categories);
				$tmpl2['categorieslng'] = $latLng['lng'];
				$tmpl2['categorieslat'] = $latLng['lat'];
			}
		
			$this->assignRef('tmpl2',	$tmpl2);
			
			parent::display('map');
		} else {
			parent::display($tpl);
		}
	}
}
?>