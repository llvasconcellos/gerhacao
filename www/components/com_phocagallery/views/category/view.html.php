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
jimport( 'joomla.html.pane' );
jimport( 'joomla.client.helper' );
jimport( 'joomla.application.component.view' );
require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_phocagallery'.DS.'helpers'.DS.'phocagalleryupload.php' );
require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_phocagallery'.DS.'helpers'.DS.'phocagalleryrender.php' );

class PhocaGalleryViewCategory extends JView
{
	function display($tpl = null) {
		
		global $mainframe;
		$document			= &JFactory::getDocument();
		$uri 				= &JFactory::getURI();
		$menus				= &JSite::getMenu();
		$menu				= $menus->getActive();
		$params				= &$mainframe->getParams();
		$user 				= &JFactory::getUser();
		$limitStart			= JRequest::getVar( 'limitstart', 0, '', 'int');
		$id 				= JRequest::getVar('id', 0, '', 'int');
		$tmpl['tab'] 		= JRequest::getVar('tab', 0, '', 'int');
		$tmpl['formaticon'] = PhocaGalleryHelperFront::getFormatIcon();
		
		// LIBRARY
		$library 							= &PhocaLibrary::getLibrary();
		$libraries['pg-css-ie'] 			= $library->getLibrary('pg-css-ie');
		$libraries['pg-css-ie-hover']		= $library->getLibrary('pg-css-ie-hover');
		$libraries['pg-group-shadowbox']	= $library->getLibrary('pg-group-shadowbox');
		$libraries['pg-group-highslide']	= $library->getLibrary('pg-group-highslide');
		
		// Limit start
		if ($limitStart > 0 ) {
			$tmpl['limitstarturl'] = '&limitstart='.$limitStart;
		} else {
			$tmpl['limitstarturl'] = '';
		}
		
		// PARAMS
		$tmpl['displaycatnametitle'] 	= $params->get( 'display_cat_name_title', 1 );
		$display_cat_name_breadcrumbs 	= $params->get( 'display_cat_name_breadcrumbs', 1 );
		$font_color 					= $params->get( 'font_color', '#b36b00' );
		$background_color 				= $params->get( 'background_color', '#fcfcfc' );
		$background_color_hover 		= $params->get( 'background_color_hover', '#f5f5f5' );
		$image_background_color 		= $params->get( 'image_background_color', '#f5f5f5' );
		$tmpl['displayimageshadow'] 	= $params->get( 'image_background_shadow', 'shadow1' );
		$border_color 					= $params->get( 'border_color', '#e8e8e8' );
		$border_color_hover 			= $params->get( 'border_color_hover', '#b36b00');
		$tmpl['imagewidth']				= $params->get( 'medium_image_width', 100 );
		$tmpl['imageheight'] 			= $params->get( 'medium_image_height', 100 );
		$front_modal_box_width 			= $params->get( 'front_modal_box_width', 680 );
		$front_modal_box_height 		= $params->get( 'front_modal_box_height', 560 );
		$front_popup_window_width 		= $front_modal_box_width; // since v2.2
		$front_popup_window_height 		= $front_modal_box_width; // since v2.2
		$tmpl['olbgcolor']				= $params->get( 'ol_bg_color', '#666666' );
		$tmpl['olfgcolor']				= $params->get( 'ol_fg_color', '#f6f6f6' );
		$tmpl['oltfcolor']				= $params->get( 'ol_tf_color', '#000000' );
		$tmpl['olcfcolor']				= $params->get( 'ol_cf_color', '#ffffff' );
		$tmpl['overliboverlayopacity']	= $params->get( 'overlib_overlay_opacity', 0.7 );
		$margin_box 					= $params->get( 'margin_box', 5 );
		$padding_box					= $params->get( 'padding_box', 5 );
		$tmpl['maxuploadchar']			= $params->get( 'max_upload_char', 1000 );
		$tmpl['maxcommentchar']			= $params->get( 'max_comment_char', 1000 );
		$tmpl['commentwidth']			= $params->get( 'comment_width', 500 );
		$tmpl['displayrating']			= $params->get( 'display_rating', 0 );
		$tmpl['displaycomment']			= $params->get( 'display_comment', 0 );
		$tmpl['displaysubcategory']			= $params->get( 'display_subcategory', 1 );
		$tmpl['displaycategorygeotagging']	= $params->get( 'display_category_geotagging', 0 );
		$tmpl['displaycategorystatistics']	= $params->get( 'display_category_statistics', 0 );
		// Used for Highslide JS (only image)
		$tmpl['displaydescriptiondetail']	= $params->get( 'display_description_detail', 0 );
		$tmpl['displaytitleindescription']	= $params->get( 'display_title_description', 0 );
	
		
		// - - - - - - - - - - - - - - -
		// Possible Categories View in Category View
		// - - - - - - - - - - - - - - -
		$tmpl['displaycategoriescv'] 		= $params->get( 'display_categories_cv', 0 );
		$display_subcat_page_cv				= $params->get( 'display_subcat_page_cv', 0 );
		$display_back_button_cv 			= $params->get( 'display_back_button_cv', 1 );
		$display_categories_back_button_cv 	= $params->get( 'display_categories_back_button_cv', 1 );
		$tmpl['displayimagecategoriescv'] 	= $params->get( 'display_image_categories_cv', 1 );
		$tmpl['categoriescolumnscv'] 		= $params->get( 'categories_columns_cv', 1 );
		$image_categories_size_cv 			= $params->get( 'image_categories_size_cv', 4 );
		$medium_image_width_cv 				= $params->get( 'medium_image_width', 100 );
		$medium_image_height_cv 			= $params->get( 'medium_image_height', 100 );
		$small_image_width_cv 				= $params->get( 'small_image_width', 50 );
		$small_image_height_cv 				= $params->get( 'small_image_height', 50 );
		$medium_image_height_cv				= $medium_image_height_cv + 18;
		$medium_image_width_cv 				= $medium_image_width_cv + 18;
		$small_image_width_cv				= $small_image_width_cv +18;
		$small_image_height_cv				= $small_image_height_cv +18;
		$tmpl['imagetypecv']				= $image_categories_size_cv;
		
		$catImg = PhocaGalleryHelperFront::getCategoriesImage($image_categories_size_cv, $small_image_width_cv, $small_image_height_cv,  $medium_image_height_cv, $medium_image_width_cv);
		$tmpl['imagebgcv'] 			= $catImg['imagebg'];
		$tmpl['imagewidthcv'] 		= $catImg['imagewidth'];
		// - - - - - - - - - - - - - - - 
		
		// PARAMS - Background shadow
		if ( $tmpl['displayimageshadow'] != 'none' ) {	
			$imageBgCSS = 'background: url(\''.JURI::base(true).'/components/com_phocagallery/assets/images/'.$tmpl['displayimageshadow'].'.'.$tmpl['formaticon'].'\') 0 0 no-repeat;';
		} else {
			$imageBgCSS = 'background: '.$image_background_color .';';
		}
		
		// CSS
		if ( $libraries['pg-css-ie']->value == 0 ) {
			$document->addCustomTag("<!--[if lt IE 8]>\n<link rel=\"stylesheet\" href=\""
			.JURI::base(true)
			."/components/com_phocagallery/assets/phocagalleryieall.css\" type=\"text/css\" />\n<![endif]-->");
			$library->setLibrary('pg-css-ie', 1);
		}
	
		$document->addCustomTag( PhocaGalleryHelperRender::renderCategoryCSS($font_color,
		$background_color, $border_color, $imageBgCSS, $border_color_hover, $background_color_hover,
		$tmpl['olfgcolor'], $tmpl['olbgcolor'], $tmpl['oltfcolor'], $tmpl['olcfcolor'],
		$margin_box, $padding_box, $tmpl['overliboverlayopacity']));
	
		
		if ( $libraries['pg-css-ie-hover']->value == 0 ) {
			$document->addCustomTag( PhocaGalleryHelperRender::renderIeHover());
			$library->setLibrary('pg-css-ie-hover', 1);
		}
		
		// PARAMS
		$tmpl['phocagallerywidth']		= $params->get( 'phocagallery_width', '');
		$display_description_detail 	= $params->get( 'display_description_detail', 0 );
		$description_detail_height 		= $params->get( 'description_detail_height', 16 );
		$tmpl['categoryboxspace'] 		= $params->get( 'category_box_space', 0 );
		$tmpl['detailwindow']			= $params->get( 'detail_window', 0 );
		
		// Description detail height
		if ($display_description_detail == 1) {
			$front_popup_window_height	= $front_popup_window_height + $description_detail_height;
		}
		// PARAMS - Display Buttons (height will be smaller)
		$detail_buttons = $params->get( 'detail_buttons', 1 );
		
		// Detail buttons in detail view
		if ($detail_buttons != 1) {
			$front_popup_window_height	= $front_popup_window_height - 45;
		}
		
		// PARAMS
		$modal_box_overlay_color 	= $params->get( 'modal_box_overlay_color', '#000000' );
		$modal_box_overlay_opacity 	= $params->get( 'modal_box_overlay_opacity', 0.3 );
		$modal_box_border_color 	= $params->get( 'modal_box_border_color', '#6b6b6b' );
		$modal_box_border_width 	= $params->get( 'modal_box_border_width', '2' );
		
		
		// =======================================================
		// DIFFERENT METHODS OF DISPLAYING THE DETAIL VIEW
		// =======================================================
		
		// MODAL - will be displayed in case e.g. highslide or shadowbox too, because in there are more links 
		JHTML::_('behavior.modal', 'a.modal-button');
		
		// CSS
		$document->addCustomTag( "<style type=\"text/css\"> \n"  
		." #sbox-window {background-color:".$modal_box_border_color.";padding:".$modal_box_border_width."px} \n"
		." #sbox-overlay {background-color:".$modal_box_overlay_color.";} \n"			
		." </style> \n");

		
		// BUTTON (IMAGE - standard, modal, shadowbox)
		$button = new JObject();
		$button->set('name', 'image');
		
		// BUTTON (ICON - standard, modal, shadowbox)
		$button2 = new JObject();
		$button2->set('name', 'icon');
		
		// BUTTON OTHER (geotagging, downloadlink, ...)
		$buttonOther = new JObject();
		$buttonOther->set('name', 'other');
		
		$tmpl ['highslideonclick']	= '';// for using with highslide
		
		// -------------------------------------------------------
		// STANDARD POPUP
		// -------------------------------------------------------
		
		if ($tmpl['detailwindow'] == 1) {
			
			$button->set('methodname', 'js-button');
			$button->set('options', "window.open(this.href,'win2','width=".$front_popup_window_width.",height=".$front_popup_window_height.",scrollbars=yes,menubar=no,resizable=yes'); return false;");
			
			$button2->methodname 		= &$button->methodname;
			$button2->options 			= &$button->options;
			$buttonOther->methodname  	= &$button->methodname;
			$buttonOther->options 		= &$button->options;
			
		}
		// -------------------------------------------------------
		// MODAL BOX
		// -------------------------------------------------------
		
		else if ($tmpl['detailwindow'] == 0 || $tmpl['detailwindow'] == 2) { 
			
			// Button
			$button->set('modal', true);
			$button->set('methodname', 'modal-button');
			
			$button2->modal 			= &$button->modal;
			$button2->methodname 		= &$button->methodname;
			$buttonOther->modal 		= &$button->modal;
			$buttonOther->methodname  	= &$button->methodname;
			
			// Modal - Image only
			if ($tmpl['detailwindow'] == 2) {
				
				$button->set('options', "{handler: 'image', size: {x: 200, y: 150}, overlayOpacity: ".$modal_box_overlay_opacity."}");
				$button2->options 		= &$button->options;
				$buttonOther->set('options', "{handler: 'iframe', size: {x: ".$front_modal_box_width.", y: ".$front_modal_box_height."}, overlayOpacity: ".$modal_box_overlay_opacity."}");
			
			// Modal - Iframe 			
			} else {
			
				$button->set('options', "{handler: 'iframe', size: {x: ".$front_modal_box_width.", y: ".$front_modal_box_height."}, overlayOpacity: ".$modal_box_overlay_opacity."}");
				
				$button2->options 		= &$button->options;
				$buttonOther->options  	= &$button->options;
			
			}
	
		} 
		
		// -------------------------------------------------------
		// SHADOW BOX
		// -------------------------------------------------------
		
		else if ($tmpl['detailwindow'] == 3) {
		
			$sb_slideshow_delay			= $params->get( 'sb_slideshow_delay', 5 );
			$sb_lang					= $params->get( 'sb_lang', 'en' );
			
			$button->set('methodname', 'shadowbox-button');
			$button->set('options', "shadowbox[PhocaGallery];options={slideshowDelay:".$sb_slideshow_delay."}");
			
			$button2->methodname 		= &$button->methodname;
			$button2->set('options', "shadowbox[PhocaGallery2];options={slideshowDelay:".$sb_slideshow_delay."}");
			
			$buttonOther->set('modal', true);
			$buttonOther->set('methodname', 'modal-button');
			$buttonOther->set('options', "{handler: 'iframe', size: {x: ".$front_modal_box_width.", y: ".$front_modal_box_height."}, overlayOpacity: ".$modal_box_overlay_opacity."}");
			
			
			
			//	$document->addScript(JURI::base(true).'/components/com_phocagallery/assets/js/shadowbox/adapter/shadowbox-mootools.js');
				$document->addScript(JURI::base(true).'/components/com_phocagallery/assets/js/shadowbox/shadowbox.js');	
			
			if ( $libraries['pg-group-shadowbox']->value == 0 ) {
				$document->addCustomTag('<script type="text/javascript">
Shadowbox.loadSkin("classic", "'.JURI::base(true).'/components/com_phocagallery/assets/js/shadowbox/src/skin");
Shadowbox.loadLanguage("'.$sb_lang.'", "'.JURI::base(true).'/components/com_phocagallery/assets/js/shadowbox/src/lang");
Shadowbox.loadPlayer(["img"], "'.JURI::base(true).'/components/com_phocagallery/assets/js/shadowbox/src/player");
window.addEvent(\'domready\', function(){
           Shadowbox.init();
});
</script>');
				// window.onload = function(){
				// Shadowbox.init();
				$library->setLibrary('pg-group-shadowbox', 1);
			}
		}
		
		// -------------------------------------------------------
		// HIGHSLIDE JS
		// -------------------------------------------------------
		
		else if ($tmpl['detailwindow'] == 4) {
			
			$button->set('methodname', 'highslide');
			$button2->methodname 		= &$button->methodname;
			$buttonOther->methodname 	= &$button->methodname;
			
			$document->addScript(JURI::base(true).'/components/com_phocagallery/assets/js/highslide/highslide-full.js');
			$document->addStyleSheet(JURI::base(true).'/components/com_phocagallery/assets/js/highslide/highslide.css');
			$document->addStyleSheet(JURI::base(true).'/components/com_phocagallery/assets/js/highslide/highslideimage.css');
			
			if ( $libraries['pg-group-highslide']->value == 0 ) {
				$document->addCustomTag( PhocaGalleryHelperRender::renderHighslideJSAll());
				$library->setLibrary('pg-group-highslide', 1);
			}
			
			$document->addCustomTag( PhocaGalleryHelperRender::renderHighslideJS ($front_modal_box_width, $front_modal_box_height));
			$tmpl['highslideonclick'] = 'return hs.htmlExpand(this, phocaZoom )';
		}
		
		// -------------------------------------------------------
		// HIGHSLIDE JS IMAGE ONLY
		// -------------------------------------------------------
		
		else if ($tmpl['detailwindow'] == 5) {
		
			$button->set('methodname', 'highslide');
			$button2->methodname 		= &$button->methodname;
			$buttonOther->methodname 	= &$button->methodname;
			
			$document->addScript(JURI::base(true).'/components/com_phocagallery/assets/js/highslide/highslide-full.js');
			$document->addStyleSheet(JURI::base(true).'/components/com_phocagallery/assets/js/highslide/highslide.css');
			$document->addStyleSheet(JURI::base(true).'/components/com_phocagallery/assets/js/highslide/highslideimage.css');
			
			if ( $libraries['pg-group-highslide']->value == 0 ) {		
				$document->addCustomTag( PhocaGalleryHelperRender::renderHighslideJSAll());
				$library->setLibrary('pg-group-highslide', 1);
			}
			
			$document->addCustomTag( PhocaGalleryHelperRender::renderHighslideJS ($front_modal_box_width, $front_modal_box_height));
			$tmpl['highslideonclick2']	= 'return hs.htmlExpand(this, phocaZoom )';
			$tmpl['highslideonclick']	= 'return hs.expand(this, phocaImage )';
		
		}
	
		$folderbutton = new JObject();
		$folderbutton->set('name', 'image');
		$folderbutton->set('options', "");					
		// End open window parameters
		
		// USER RIGHT - ACCESS =======================================
		// We get the category id, there is an USER ACCES BEHAVIOUR
		$category	= $this->get('category');// function getCategory in Model
		// ===========================================================
		$total				= $this->get('total');
		$tmpl['pagination']	= &$this->get('pagination');
		
		// CSS
		JHTML::stylesheet( 'phocagallery.css', 'components/com_phocagallery/assets/' );
		
		// PARAMS
		$tmpl['enablepiclens']	= $params->get( 'enable_piclens', 0 );
		$tmpl['startpiclens'] 	= 0;
		
		// PICLENS
		if ($tmpl['enablepiclens'] == 1) {
			$tmpl['startpiclens'] = $params->get( 'start_piclens', 0 );
			// CSS - PicLens START
			$document->addCustomTag(PhocaGalleryHelperRender::renderPicLens($category->id));
			
		}
		
		// PARAMS - Pagination and subcategories on other sites
		//Subcategories will be displayed only on first page if pagination will be used
		$display_subcat_page = $params->get( 'display_subcat_page', 0 );
		
		// On the first site subcategories will be displayed allways
		$get['start']	= JRequest::getVar( 'limitstart', '', 'get', 'string' );
		if ($display_subcat_page == 2) {
			$display_subcat_page = 0;// Nowhere
		} else if ($display_subcat_page == 0 && $get['start'] > 0) {
			$display_subcat_page = 0;//in case: second page and param=0
		} else {
			$display_subcat_page = 1;//in case:first page or param==1
		}
		// Categories View in Category View
		if ($display_subcat_page_cv == 2) {
			$display_subcat_page_cv = 0;// Nowhere
		} else if ($display_subcat_page_cv == 0 && $get['start'] > 0) {
			$display_subcat_page_cv = 0;//in case: second page and param=0
		} else {
			$display_subcat_page_cv = 1;//in case:first page or param==1
		}
	
		// PARAMS - Display Back Buttons
		$display_back_button 			= $params->get( 'display_back_button', 1 );
		$display_categories_back_button = $params->get( 'display_categories_back_button', 1 );
		
		// PARAMS - Access Category - display category (subcategory folder or backbutton  to not accessible cat
		$display_access_category 		= $params->get( 'display_access_category', 1 );	
		
		// Set page title per category
		if ($tmpl['displaycatnametitle'] == 1) {
			$document->setTitle($params->get( 'page_title') . ' - '. $category->title);
		} else {
			$document->setTitle( $params->get( 'page_title' ));
		}

		// Breadcrumb display:
		// 0 - only menu link
		// 1 - menu link - category name
		// 2 - only category name
		$this->_addBreadCrumbs($category, isset($menu->query['id']) ? $menu->query['id'] : 0, $display_cat_name_breadcrumbs);
		
		// PARAMS - the whole page title with category or without category
		$tmpl['showpagetitle'] = $params->get( 'show_page_title', 1 );
		
		// Define image tag attributes
		if (!empty ($category->image)) {
			$attribs['align'] = '"'.$category->image_position.'"';
			$attribs['hspace'] = '"6"';

			// Use the static HTML library to build the image tag
			$tmpl['image'] = JHTML::_('image', 'images/stories/'.$category->image, JText::_('Phoca gallery'), $attribs);
		}
		
		// PARAMS- Display or hide name, icon detail link
		$tmpl['displayname']			= $params->get( 'display_name', 1);
		$tmpl['displayicondetail'] 		= $params->get( 'display_icon_detail', 1 );
		$tmpl['displayicondownload'] 	= $params->get( 'display_icon_download', 0 );
		$tmpl['displayiconfolder'] 		= $params->get( 'display_icon_folder', 0 );
		$tmpl['displayiconvm']			= $params->get( 'display_icon_vm', 0 );
		$tmpl['fontsizename']			= $params->get( 'font_size_name', 12 );
		$tmpl['charlengthname'] 		= $params->get( 'char_length_name', 15);
		$tmpl['phocagalleryic']			= $params->get( 'display_phoca_info', 1 );
		$tmpl['displayicongeo']			= $params->get( 'display_icon_geotagging', 0 );// Check the category
		$tmpl['displayicongeoimage']	= $params->get( 'display_icon_geotagging', 0 );// Check the image
		$tmpl['displaycamerainfo']		= $params->get( 'display_camera_info', 0 );
		
		$tmpl['phocagalleryic'] 		= PhocaGalleryHelper::getPhocaIc((int)$tmpl['phocagalleryic']);
		
		
		// PARAMS - Switch Image
		$tmpl['switchimage']	= $params->get( 'switch_image', 0 );
		$tmpl['switchheight'] 	= $params->get( 'switch_height', 480 );
		$tmpl['switchwidth'] 	= $params->get( 'switch_width', 640);
		
		// Switch image JS
		$tmpl['basicimage']	= '';
		if ($tmpl['switchimage'] == 1) {
			$imagePathFront	= PhocaGalleryHelperFront::getPathSet();
			$waitImage 		= $imagePathFront['front_image'] . 'icon-switch.gif';
			$tmpl['basicimage']	= $imagePathFront['front_image'] . 'phoca_thumb_l_no_image.' . $tmpl['formaticon'];
			$document->addCustomTag(PhocaGalleryHelperRender::switchImage($waitImage));
			$basicImageSelected = 0; // we have not selected the basic image yet
		}
		
		// PARAMS - Upload
		$tmpl['displaytitleupload']	= $params->get( 'display_title_upload', 0 );
		$tmpl['displaydescupload'] 	= $params->get( 'display_description_upload', 0 );
		$tmpl['enablejava']			= $params->get( 'enable_java', 0 );
		$tmpl['javaresizewidth'] 	= $params->get( 'java_resize_width', -1 );
		$tmpl['javaresizeheight'] 	= $params->get( 'java_resize_height', -1 );
		$tmpl['javaboxwidth'] 		= $params->get( 'java_box_width', 480 );
		$tmpl['javaboxheight'] 		= $params->get( 'java_box_height', 480 );
		$tmpl['large_image_width']	= $params->get( 'large_image_width', 640 );
		$tmpl['large_image_height']	= $params->get( 'large_image_height', 640 );
		$tmpl['uploadmaxsize'] = $params->get( 'upload_maxsize', 3000000 );
		
		// PARAMS - Overlib
		$enable_overlib = $params->get( 'enable_overlib', 0 );
		
		// Overlib
		if ((int)$enable_overlib > 0) {
			$document->addScript(JURI::base(true).'/includes/js/overlib_mini.js');
		}
		
		// MODEL
		$model		= &$this->getModel();
		
		// Category Params
		$catParams	= $model->getCategoryParams((int)$id);
		
		// Trash
		$tmpl['trash']				= 0;
		$tmpl['publishunpublish']	= 0;
		// USER RIGHT - DELETE =======================================
		// 2, 2 means that user access will be ignored in function getUserRight for display Delete button
		$rightDisplayDelete = 0;// default is to null (all users cannot upload)
		if (isset($catParams->params)) {
			$rightDisplayDelete = PhocaGalleryHelper::getUserRight($catParams->params, 'deleteuserid', 1, $user->get('aid', 0), $user->get('id', 0), 0);
		}
		
		if ($rightDisplayDelete == 1) {
			$tmpl['trash']				= 1;
			$tmpl['publishunpublish']	= 1;
		}
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
			$rightDisplay = PhocaGalleryHelper::getUserRight ($catParams->params, 'accessuserid', 0, $user->get('aid', 0), $user->get('id', 0), $display_access_category);
		}
		
		if ($rightDisplay == 0) {
			$mainframe->redirect(JRoute::_('index.php?option=com_user&view=login', false), JText::_("ALERTNOTAUTH"));
			exit;
		}		
		// ===========================================================
		
		// 1. GEOTAGGING CATEGORY 
		if (isset($catParams->params)) {
			$longitude	= PhocaGalleryHelper::getParamsArray($catParams->params, 'longitude');
			$latitude	= PhocaGalleryHelper::getParamsArray($catParams->params, 'latitude');
			if (!isset($longitude[0]) || (isset($longitude[0]) && ($longitude[0] == '' || $longitude[0] == 0))) {
				$tmpl['displayicongeo'] = 0;
			} 
		
			if (!isset($latitude[0]) || (isset($latitude[0]) && ($latitude[0] == '' || $latitude[0] == 0))) {
				$tmpl['displayicongeo'] = 0;
			}
		} else {
			$tmpl['displayicongeo'] = 0;
		}
		

		
		// If user has rights to delete or publish or unpublish, unbublished items should be displayed
		if ($rightDisplayDelete == 1) {
			$items		= $model->getData($display_subcat_page, $display_subcat_page_cv, $display_back_button, $display_back_button_cv, $display_categories_back_button, $display_categories_back_button_cv, $display_access_category, 1, $tmpl['displaycategoriescv'] );
		} else {
			$items		= $model->getData($display_subcat_page, $display_subcat_page_cv, $display_back_button,  $display_back_button_cv, $display_categories_back_button,$display_categories_back_button_cv, $display_access_category, 0, $tmpl['displaycategoriescv']);
		}
		
		// Information because of height of box (if they are used not by all images)
		$tmpl['displayiconextlink1box'] = 0;
		$tmpl['displayiconextlink2box'] = 0;
		$tmpl['displayiconvmbox'] 		= 0;
		$tmpl['displayicongeobox'] 		= 0;
		
		// = = = = = = = = = = = = = = =
		// BOXES
		// = = = = = = = = = = = = = = =
		
        $k 		= 0;
		$unSet 	= 0;// If it will be unseted while access view, we must sort the keys from category array - ACCESS
		
	
		for($i = 0; $i <  count($items); $i++){
			$item =& $items[$i];
			// - - - - - - - - - - - - - - -
			// CATEGORY LIST - BACK BUTTON
			// - - - - - - - - - - - - - - -
			// Back to categories list if user is coming from cat list
			if (isset($item->item_type) && $item->item_type == "categorieslist") {
				$item->link 				= JRoute::_( $item->link );
				$item->button 				= &$folderbutton;
				$item->button->methodname 	= '';
				$item->displayicondetail 	= 0;				   
				$item->displayicondownload 	= 0;
				$item->displayiconfolder 	= 0;
				$item->displayname 			= 0;
				$item->displayiconvm 		= '';
				$item->startpiclens 		= 0;
				$item->trash				= 0;
				$item->publishunpublish		= 0;
				$item->enable_piclens		= 0;
				$item->overlib				= 0;
				$item->displayicongeo		= 0;
				$item->type					= 0;
				$item->camerainfo			= 0;
				$item->displayiconextlink1	= 0;
				$item->displayiconextlink2	= 0;
 			} 
			// - - - - - - - - - - - - - - -
			// CATEGORY LIST - BACK BUTTON - Categories View in Category View
			// - - - - - - - - - - - - - - -
			// Back to categories list if user is coming from cat list
			else if (isset($item->item_type) && $item->item_type == "categorieslistcv") {
				$item->link 				= JRoute::_( $item->link );
				$item->type					= 3;

 			}
			// - - - - - - - - - - - - - - -
			// PARENT FOLDER
			// - - - - - - - - - - - - - - -
			else if (isset($item->item_type) && $item->item_type == "parentfolder") {
				$item->link = JRoute::_('index.php?option=com_phocagallery&view=category&id='. $item->slug.'&Itemid='. JRequest::getVar('Itemid', 1, 'get', 'int')  );
				$item->button = &$folderbutton;
				$item->button->methodname 	= '';
				$item->displayicondetail 	= 0;				   
				$item->displayicondownload 	= 0;
				$item->displayiconfolder 	= 0;
				$item->displayname 			= 0;
				$item->displayiconvm 		= '';
				$item->startpiclens 		= 0;
				$item->trash				= 0;
				$item->publishunpublish		= 0;
				$item->enable_piclens		= 0;
				$item->overlib				= 0;
				$item->displayicongeo		= 0;
				$item->type					= 0;
				$item->camerainfo			= 0;
				$item->displayiconextlink1	= 0;
				$item->displayiconextlink2	= 0;				
 			}
			// - - - - - - - - - - - - - - -
			// PARENT FOLDER - Categories View in Category View
			// - - - - - - - - - - - - - - -
			else if (isset($item->item_type) && $item->item_type == "parentfoldercv") {
				$item->link = JRoute::_('index.php?option=com_phocagallery&view=category&id='. $item->slug.'&Itemid='. JRequest::getVar('Itemid', 1, 'get', 'int')  );
				$item->type		= 3;
					
 			} 
			// - - - - - - - - - - - - - - -
			// SUB FOLDER
			// - - - - - - - - - - - - - - -
			else if (isset($item->item_type) && $item->item_type == "subfolder") {			
				$item->link = JRoute::_('index.php?option=com_phocagallery&view=category&id='. $item->slug.'&Itemid='. JRequest::getVar('Itemid', 1, 'get', 'int')  );
				$item->button = &$folderbutton;
				$item->button->methodname 	= '';
				$item->displayicondetail 	= 0;				   
				$item->displayicondownload 	= 0;
				$item->displayiconfolder 	= $tmpl['displayiconfolder'];
				$item->displayname 			= $tmpl['displayname'];
				$item->displayiconvm 		= '';
				$item->startpiclens 		= 0;
				$item->trash				= 0;
				$item->publishunpublish		= 0;
				$item->enable_piclens		= 0;
				$item->overlib				= 0;
				$item->displayicongeo		= 0;
				$item->type					= 1;
				$item->camerainfo			= 0;
				$item->displayiconextlink1	= 0;
				$item->displayiconextlink2	= 0;
				//	$tmpl['displayname'] = 1;
			} 
			
			// - - - - - - - - - - - - - - -
			// SUB FOLDER - Categories View in Category View
			// - - - - - - - - - - - - - - -
			else if (isset($item->item_type) && $item->item_type == "subfoldercv") {			
				$item->link = JRoute::_('index.php?option=com_phocagallery&view=category&id='. $item->slug.'&Itemid='. JRequest::getVar('Itemid', 1, 'get', 'int')  );
				$item->type					= 4;

			} 
			// - - - - - - - - - - - - - - -
			// IMAGES
			// - - - - - - - - - - - - - - -
			else {                      
				// Add the first Image as basic image
				if ($tmpl['switchimage'] == 1) {
					if ($basicImageSelected == 0) {
						$tmpl['basicimage']	= $item->linkthumbnailpath;
						$basicImageSelected = 1;
					}
				}

				$thumbLink	= PhocaGalleryHelper::getThumbnailName($item->filename, 'large');
				$siteLink 	= JRoute::_('index.php?option=com_phocagallery&view=detail&catid='.$category->slug.'&id='. $item->slug.'&tmpl=component'.'&Itemid='. JRequest::getVar('Itemid', 1, 'get', 'int')  );
				$imgLink	= JURI::base(true) . str_replace( '..', '', $thumbLink['rel'] );
				
				if ($tmpl['detailwindow'] == 2 ) {
					$item->link 		= $imgLink;
					$item->link2		= $imgLink;
					$item->linkother	= $imgLink;
				
				} else if ( $tmpl['detailwindow'] == 3 ) {
				
					$item->link 		= $imgLink;
					$item->link2 		= $imgLink;
					$item->linkother	= $siteLink;
				
				} else if ( $tmpl['detailwindow'] == 5 ) {
					
					$item->link 		= $imgLink;
					$item->link2 		= $siteLink;
					$item->linkother	= $siteLink;
					
				} else {
				
					$item->link 		= $siteLink;
					$item->link2 		= $siteLink;
					$item->linkother	= $siteLink;
					
				}
				// Buttons, e.g. shadowbox:
				// button - click on image
				// button2 - click on zoom icon (cannot be the same as click on image because of duplicity of images)
				// buttonOther - other detail window like download, geotagging
				$item->button 				= &$button;
				$item->button2 				= &$button2;
				$item->buttonother 			= &$buttonOther;
				
				$item->displayicondetail 	= $tmpl['displayicondetail'];
				$item->displayicondownload 	= $tmpl['displayicondownload'];
				$item->displayiconfolder 	= 0;
				$item->displayname 			= $tmpl['displayname'];
				$item->displayiconvm 		= '';
				$item->startpiclens 		= $tmpl['startpiclens'] ;
				$item->type					= 2;
				
				// Trash icon
				if ($tmpl['trash'] == 1) {
					$item->trash	= 1;
				} else {
					$item->trash	= 0;
				}
				// Publish Unpublish icon
				if ($tmpl['publishunpublish'] == 1) {
					$item->publishunpublish	= 1;
				} else {
					$item->publishunpublish	= 0;
				}
				// PICLENS 
				if($tmpl['enablepiclens']) { 
					$item->enable_piclens	= 1; 
				} else { 
					$item->enable_piclens	= 0; 
				}
				// 2 GEOTAGGING IMAGE
				// We have checked the category so if geotagging is enabled
				// and there is no geotagging data for category, then $tmpl['displayicongeo'] = 0;
				// so we need to check it for the image too, we need to set the $tmpl['displayicongeoimage'] for image only
				// we are in loop now
				$tmpl['displayicongeoimagetmp'] = 0;
				if ($tmpl['displayicongeoimage'] == 1) {
					$tmpl['displayicongeoimagetmp'] = 1;
					if (isset($item->params)) {
						$longitude	= PhocaGalleryHelper::getParamsArray($item->params, 'longitude');
						$latitude	= PhocaGalleryHelper::getParamsArray($item->params, 'latitude');
					
						if (!isset($longitude[0]) || (isset($longitude[0]) && ($longitude[0] == '' || $longitude[0] == 0))) {
							$tmpl['displayicongeoimagetmp'] = 0;
						}
				
						if (!isset($latitude[0]) || (isset($latitude[0]) && ($latitude[0] == '' || $latitude[0] == 0))) {
							$tmpl['displayicongeoimagetmp'] = 0;
						}
					} else {
						$tmpl['displayicongeoimagetmp'] = 0;
					}
				}
				
				// GEOTAGGING
				if($tmpl['displayicongeo'] == 1 || $tmpl['displayicongeoimagetmp'] == 1) { 
					$item->displayicongeo		= 1;
					$tmpl['displayicongeobox']	= 1;// because of height of box			
				} else { 
					$item->displayicongeo	= 0; 
				}
				
				// Set it back because of loop
				$tmpl['displayicongeoimagetmp'] = 0;
				
				// CAMERA INFO 
				if($tmpl['displaycamerainfo'] == 1) { 
					$item->camerainfo			= 1;
				} else { 
					$item->camerainfo			= 0;	 
				}
				
				// EXT LINK
				$item->displayiconextlink1		= 0;
				if (isset($item->extlink1)) {
					$item->extlink1	= explode("|", $item->extlink1, 4);
					if (isset($item->extlink1[0]) && isset($item->extlink1[1])) {
						$item->displayiconextlink1		= 1;
						$tmpl['displayiconextlink1box'] = 1;// because of height of box
						if (!isset($item->extlink1[2])) {
							$item->extlink1[2] = '_self';
						}
						if (!isset($item->extlink1[3]) || $item->extlink1[3] == 1) {
							$item->extlink1[4] = JHTML::_('image', 'components/com_phocagallery/assets/images/icon-extlink1.'.$tmpl['formaticon'], JText::_($item->extlink1[1]));
							$item->extlink1[5] = '';
						} else {
							$item->extlink1[4] = $item->extlink1[1];
							$item->extlink1[5] = 'style="text-decoration:underline"';
						}
					} else {
						$item->displayiconextlink1		= 0;
					}
				}
				
				$item->displayiconextlink2		= 0;
				if (isset($item->extlink2)) {
					$item->extlink2	= explode("|", $item->extlink2, 4);
					if (isset($item->extlink2[0]) && isset($item->extlink2[1])) {
						$item->displayiconextlink2		= 1;
						$tmpl['displayiconextlink2box'] = 1;// because of height of box
						if (!isset($item->extlink2[2])) {
							$item->extlink2[2] = '_self';
						}
						if (!isset($item->extlink2[3]) || $item->extlink2[3] == 1) {
							$item->extlink2[4] = JHTML::_('image', 'components/com_phocagallery/assets/images/icon-extlink2.'.$tmpl['formaticon'], JText::_($item->extlink2[1]));
							$item->extlink2[5] = '';
						}else {
							$item->extlink2[4] = $item->extlink2[1];
							$item->extlink2[5] = 'style="text-decoration:underline"';
						}
					} else {
						$item->displayiconextlink2		= 0;
					}
				}
					
				
				// OVERLIB 
				if ($enable_overlib == 1) { 
					$item->overlib			= 1; 
				} else if ($enable_overlib == 2){ 
					$item->overlib			= 2; 
				} else if ($enable_overlib == 3){ 
					$item->overlib			= 3; 
				} else { 
					$item->overlib			= 0; 
				}
							
				// VirtueMart link 
				if ($tmpl['displayiconvm'] == 1) {
				
					$vmLinkError			= false;
					$vmProductId 			= 0;
					
					if (isset($item->params) && $item->params != '') {
						$paramsArray	= explode( ';', $item->params );
					
						if (is_array($paramsArray))
						{
							foreach ($paramsArray as $value)
							{
								$vmParam = preg_match( "/vmproductid=/i", $value );
								if ($vmParam) {
									$vmProductId = str_replace('vmproductid=', '', $value);
								}
							}
						}
					}
						
					$vmLink					= $model->getVmLink($vmProductId);
					$vmLinkError 			= preg_match( "/Error/i", $vmLink );
					if ($vmLinkError) {
						$item->displayiconvm = '';
					} else {
						$item->displayiconvm		= 1;
						$item->vmlink				= $vmLink;
						$tmpl['displayiconvmbox']	= 1;// because of height of box
					}
				} else {
					$item->displayiconvm = '';
				}
				// End VM Link
			}
		}            
		$item->odd		= $k;
		$item->count	= $i;
		$k 				= 1 - $k;

		
		// Upload Form ------------------------------------
	/*	JHTML::_('behavior.mootools');
		$document->addScript('administrator/components/com_phocagallery/assets/upload/mediamanager.js');
		$document->addStyleSheet('administrator/components/com_phocagallery/assets/upload/mediamanager.css');
	*/
		// Set FTP form
		$ftp = !JClientHelper::hasCredentials('ftp');
		// Set flash uploader if ftp password and login exists (will be not problems)
		//$stateFolder	= 'phocagallery';
		//$refreshSite 	= $uri->toString();
	/*	if (!$ftp) {
			if ($params->get('enable_flash', 1)) {
				PhocaGalleryHelperUpload::uploader('file-upload', array('onAllComplete' => 'function(){ window.location.href="'.$refreshSite.'"; }'));
			}
		}
	*/		
		// PARAMS - Upload size
		$tmpl['uploadmaxsize'] = $params->get( 'upload_maxsize', 3000000 );
		
		$this->assignRef('session', JFactory::getSession());
		//$this->assignRef('uploadmaxsize', $upload_maxsize);
		// END Upload Form ------------------------------------
			
			
		// Only registered (VOTES + COMMENTS)
		$tmpl['notregistered'] 	= true;
		$tmpl['username']		= '';
		if ($user->aid > 0) {
			$tmpl['notregistered'] 	= false;
			$tmpl['username']		= $user->name;
		}	
			
		// VOTES Statistics
		if ((int)$tmpl['displayrating'] == 1) {
			$tmpl['votescount']		= 0;
			$tmpl['votesaverage'] 	= 0;
			$tmpl['voteswidth']		= 0;
			$votesStatistics	= $model->getVotesStatistics((int)$id);
			if (!empty($votesStatistics->count)) {
				$tmpl['votescount'] = $votesStatistics->count;
			}
			if (!empty($votesStatistics->average)) {
				$tmpl['votesaverage'] = $votesStatistics->average;
				if ($tmpl['votesaverage'] > 0) {
					$tmpl['votesaverage'] 	= round(((float)$tmpl['votesaverage'] / 0.5)) * 0.5;
					$tmpl['voteswidth']		= 22 * $tmpl['votesaverage'];
				}
				
			}
			if ((int)$tmpl['votescount'] > 1) {
				$tmpl['votestext'] = 'votes';
			} else {
				$tmpl['votestext'] = 'vote';
			}
		
			// Already rated?
			$tmpl['alreadyrated']	= $model->checkUserVote( (int)$id, (int)$user->id );
		}

		// COMMENTS
		if ((int)$tmpl['displaycomment'] == 1) {
			$document->addScript(JURI::base(true).'/components/com_phocagallery/assets/js/comments.js');
			$document->addCustomTag(PhocaGalleryHelperRender::renderCommentJS((int)$tmpl['maxcommentchar']));
		
			$tmpl['alreadycommented'] 	= $model->checkUserComment( (int)$id, (int)$user->id );
			$commentItem				= $model->displayComment( (int)$id );
	
			$this->assignRef( 'commentitem',		$commentItem);
		}

		
		// GEOTAGGING
		
		$map 				= $model->getGeotagging($id);
		if ($map['geotitle'] == '') {
			$map['geotitle']	= $category->title;
		}
		
		// TABS
		$displayTabs	= 0;
		
		// R A T I N G
		if ((int)$tmpl['displayrating'] == 0) {
			$currentTab['rating'] = -1;	
		} else {
			$currentTab['rating'] = $displayTabs;
			$displayTabs++;
		}
		
		// C O M M E N T S
		if ((int)$tmpl['displaycomment'] == 0) {
			$currentTab['comment'] = -1;
		} else {
			$currentTab['comment'] = $displayTabs;
			$displayTabs++;	
		}
/*TODO		
		// S U B C A T E G O R Y
		if ((int)$tmpl['displaysubcategory'] == 0) {
			$currentTab['subcategory'] = -1;
		} else {
			$currentTab['subcategory'] = $displayTabs;
			$displayTabs++;	
		}			
		$tmpl['createoredit']			= JText::_('Phoca Gallery Create');
		$tmpl['categoryname']			= '';
		$tmpl['categorydescription']	= '';
		$tmpl['categorypublished']		= -1;
		$tmpl['maxcreatecatchar']	= $params->get( 'max_create_cat_char', 1000 );
		$document->addCustomTag(PhocaGalleryHelperRender::renderDescriptionCreateCatJS((int)$tmpl['maxcreatecatchar']));
*/		
		// S T A T I S T I C S
		if ((int)$tmpl['displaycategorystatistics'] == 0) {
			$currentTab['statistics'] = -1;
		} else {
			$currentTab['statistics'] = $displayTabs;
			$displayTabs++;

			
			$tmpl['displaymaincatstat']			= $params->get( 'display_main_cat_stat', 1 );
			$tmpl['displaylastaddedcatstat']	= $params->get( 'display_lastadded_cat_stat', 1 );
			$tmpl['displaymostviewedcatstat']	= $params->get( 'display_mostviewed_cat_stat', 1 );
			$tmpl['countlastaddedcatstat']		= $params->get( 'count_lastadded_cat_stat', 3 );
			$tmpl['countmostviewedcatstat']		= $params->get( 'count_mostviewed_cat_stat', 3 );
			
			
			if ($tmpl['displaymaincatstat'] == 1) {
				$numberImgP		= $model->getCountImages($id, 1);
				$tmpl['numberimgpub'] 	= $numberImgP->countimg;
				$numberImgU		= $model->getCountImages($id, 0);
				$tmpl['numberimgunpub'] = $numberImgU->countimg;
				$categoryViewed	= $model->getHits($id);
				$tmpl['categoryviewed'] = $categoryViewed->catviewed;
			}
			
			// MOST VIEWED IMAGES
			//$tmpl['mostviewedimg'] = array();
			if ($tmpl['displaymostviewedcatstat'] == 1) {
				$mostViewedImages	= $model->getStatisticsImages($id, 'hits', 'DESC', $tmpl['countmostviewedcatstat']);
				for($i = 0; $i <  count($mostViewedImages); $i++) {
					$itemMVI 		=& $mostViewedImages[$i];
					$itemMVI->button 				= &$button;
					$itemMVI->button2 				= &$button2;
					$itemMVI->buttonother 			= &$buttonOther;
					$itemMVI->displayicondetail 	= $tmpl['displayicondetail'];
					$itemMVI->displayname 			= $tmpl['displayname'];
					$itemMVI->type		 			= 2;
					
					$thumbLink	= PhocaGalleryHelper::getThumbnailName($itemMVI->filename, 'large');
					$siteLink 	= JRoute::_('index.php?option=com_phocagallery&view=detail&catid='.$category->slug.'&id='. $itemMVI->slug.'&tmpl=component'.'&Itemid='. JRequest::getVar('Itemid', 1, 'get', 'int')  );
					$imgLink	= JURI::base(true) . str_replace( '..', '', $thumbLink['rel'] );
					if ($tmpl['detailwindow'] == 2) {
						$itemMVI->link 		= $imgLink;
					} else {
						$itemMVI->link 		= $siteLink;
					}
					//$tmpl['mostviewedimg'][] = $itemMVI;
				}
				
				$tmpl['mostviewedimg'] = $mostViewedImages;
			}
			
			// LAST ADDED IMAGES
			//$tmpl['lastaddedimg'] = array();
			if ($tmpl['displaylastaddedcatstat'] == 1) {			
				$lastAddedImages	= $model->getStatisticsImages($id, 'date', 'DESC', $tmpl['countlastaddedcatstat']);
				for($i = 0; $i <  count($lastAddedImages); $i++) {
					$itemLAI 		=& $lastAddedImages[$i];
					$itemLAI->link 	= JRoute::_('index.php?option=com_phocagallery&view=detail&catid='.$category->slug.'&id='. $itemLAI->slug.'&tmpl=component'.'&Itemid='. JRequest::getVar('Itemid', 1, 'get', 'int')  );
					$itemLAI->button 				= &$button;
					$itemLAI->button2 				= &$button2;
					$itemLAI->buttonother 			= &$buttonOther;
					$itemLAI->displayicondetail 	= $tmpl['displayicondetail'];
					$itemLAI->displayname 			= $tmpl['displayname'];
					$itemLAI->type		 			= 2;
					
					$thumbLink	= PhocaGalleryHelper::getThumbnailName($itemLAI->filename, 'large');
					$siteLink 	= JRoute::_('index.php?option=com_phocagallery&view=detail&catid='.$category->slug.'&id='. $itemLAI->slug.'&tmpl=component'.'&Itemid='. JRequest::getVar('Itemid', 1, 'get', 'int')  );
					$imgLink	= JURI::base(true) . str_replace( '..', '', $thumbLink['rel'] );
					if ($tmpl['detailwindow'] == 2) {
						$itemLAI->link 		= $imgLink;
					} else {
						$itemLAI->link 		= $siteLink;
					}
					//$tmpl['lastaddedimg'][] = $itemLAI;
				}
				$tmpl['lastaddedimg'] = $lastAddedImages;
			}
		}
		
		// G E O T A G G I N G
		if ((int)$tmpl['displaycategorygeotagging'] == 0) {
			$currentTab['geotagging'] = -1;
		} else {
			$currentTab['geotagging'] = $displayTabs;
			$displayTabs++;	
			
			$tmpl['googlemapsapikey'] 			= $params->get( 'google_maps_api_key', '' );
			$tmpl['categorymapwidth'] 			= $params->get( 'category_map_width', 500 );
			$tmpl['categorymapheight'] 			= $params->get( 'category_map_height', 400 );
	
		}
		
		// U P L O A D
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
		
		
		// ADD STATISTICS
		$model->hit($id);
		
		
		// ASIGN
		$this->assignRef( 'tmpl',				$tmpl);
		$this->assignRef( 'params' ,			$params);
		$this->assignRef( 'map',				$map);		
		$this->assignRef( 'items' ,				$items);
		$this->assignRef( 'category' ,			$category);
		$this->assignRef( 'button',				$button );
		$this->assignRef( 'button2',			$button2 );
		$this->assignRef( 'buttonother',		$buttonOther );

		
		parent::display($tpl);
	}
	
	/**
	 * Method to add Breadcrubms in Phoca Gallery
	 * @param array $category Object array of Category
	 * @param int $rootId Id of Root Category
	 * @param int $displayStyle Displaying of Breadcrubm - Nothing, Category Name, Menu link with Name
	 * @return string Breadcrumbs
	 */
	function _addBreadCrumbs($category, $rootId, $displayStyle)
	{
	    global $mainframe;
		$i = 0;
	    while (isset($category->id))
	    {
			$crumbList[$i++] = $category;
			if ($category->id == $rootId)
			{
				break;
			}

	        $db =& JFactory::getDBO();
	        $query = 'SELECT *' .
	            ' FROM #__phocagallery_categories AS c' .
	            ' WHERE c.id = '.(int) $category->parent_id.
	            ' AND c.published = 1';
	        $db->setQuery($query);
	        $rows = $db->loadObjectList('id');
			if (!empty($rows))
			{
				$category = $rows[$category->parent_id];
			}
			else
			{
				$category = '';
			}
		//	$category = $rows[$category->parent_id];
	    }

	    $pathway 		=& $mainframe->getPathway();
		$pathWayItems 	= $pathway->getPathWay();
		$lastItemIndex 	= count($pathWayItems) - 1;

	    for ($i--; $i >= 0; $i--)
	    {
			// special handling of the root category
			if ($crumbList[$i]->id == $rootId) 
			{
				switch ($displayStyle) 
				{
					case 0:	// 0 - only menu link
						// do nothing
						break;
					case 1:	// 1 - menu link with category name
						// replace the last item in the breadcrumb (menu link title) with the current value plus the category title
						$pathway->setItemName($lastItemIndex, $pathWayItems[$lastItemIndex]->name . ' - ' . $crumbList[$i]->title);
						break;
					case 2:	// 2 - only category name
						// replace the last item in the breadcrumb (menu link title) with the category title
						$pathway->setItemName($lastItemIndex, $crumbList[$i]->title);
						break;
				}
			} 
			else 
			{
				$pathway->addItem($crumbList[$i]->title, JRoute::_('index.php?option=com_phocagallery&view=category&id='. $crumbList[$i]->id.':'.$crumbList[$i]->alias.'&Itemid='. JRequest::getVar('Itemid', 1, 'get', 'int') ));
			}
	    }
	}	
}
?>
