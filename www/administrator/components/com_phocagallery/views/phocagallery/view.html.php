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
jimport( 'joomla.application.component.view' );

class PhocaGalleryCpViewPhocaGallery extends JView
{
	function display($tpl = null) {
		global $mainframe;
		
		if($this->getLayout() == 'form') {
			$this->_displayForm($tpl);
			return;
		}
		parent::display($tpl);
	}

	function _displayForm($tpl) {
		global $mainframe, $option;

		$db		=& JFactory::getDBO();
		$uri 	=& JFactory::getURI();
		$user 	=& JFactory::getUser();
		$model	=& $this->getModel();
		$editor =& JFactory::getEditor();
		$tmpl	= array();
		
		$params = JComponentHelper::getParams('com_phocagallery');
		
		$tmpl['enablethumbcreation']		= $params->get('enable_thumb_creation', 1 );
		$tmpl['enablethumbcreationstatus'] 	= PhocaGalleryAdminRender::renderThumbnailCreationStatus((int)$tmpl['enablethumbcreation']);

		JHTML::_('behavior.calendar');
		
		//Data from model
		$phocagallery	=& $this->get('Data');
		JHTML::stylesheet( 'phocagallery.css', 'administrator/components/com_phocagallery/assets/' );
		
		//Image button
		$link = 'index.php?option=com_phocagallery&amp;view=phocagalleryi&amp;tmpl=component';
		JHTML::_('behavior.modal', 'a.modal-button');
		$button = new JObject();
		$button->set('modal', true);
		$button->set('link', $link);
		$button->set('text', JText::_( 'Image' ));
		$button->set('name', 'image');
		$button->set('modalname', 'modal-button');
		$button->set('options', "{handler: 'iframe', size: {x: 760, y: 520}}");
		
		
		$lists 	= array();		
		$isNew	= ($phocagallery->id < 1);

		// fail if checked out not by 'me'
		if ($model->isCheckedOut( $user->get('id') )) {
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'Phoca gallery' ), $phocagallery->title );
			$mainframe->redirect( 'index.php?option='. $option, $msg );
		}

		// Set toolbar items for the page
		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Phoca Gallery Image' ).': <small><small>[ ' . $text.' ]</small></small>', 'gallery' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		JToolBarHelper::help( 'screen.phocagallery', true );

		// Edit or Create?
		if (!$isNew) {
			$model->checkout( $user->get('id') );
		} else {
			// initialise new record
			$phocagallery->published 	= 1;
			$phocagallery->order 		= 0;
			$phocagallery->catid 		= JRequest::getVar( 'catid', 0, 'post', 'int' );
		}

		// build the html select list for ordering
		$query = 'SELECT ordering AS value, title AS text'
			. ' FROM #__phocagallery'
			. ' WHERE catid = ' . (int) $phocagallery->catid
			. ' ORDER BY ordering';
		$lists['ordering'] 			= JHTML::_('list.specificordering',  $phocagallery, $phocagallery->id, $query, false );

		// - - - - - - - - - - - - - - -
		// Build the list of categories
		$query = 'SELECT a.title AS text, a.id AS value, a.parent_id as parentid'
		. ' FROM #__phocagallery_categories AS a'
	//	. ' WHERE a.published = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery( $query );
		$phocagallerys = $db->loadObjectList();

		$tree = array();
		$text = '';
		$tree = PhocaGalleryHelper::CategoryTreeOption($phocagallerys, $tree, 0, $text, -1);
		array_unshift($tree, JHTML::_('select.option', '0', '- '.JText::_('Select Category').' -', 'value', 'text'));
		
		//list categories
		$lists['catid'] = JHTML::_( 'select.genericlist', $tree, 'catid',  '', 'value', 'text', $phocagallery->catid);
		// - - - - - - - - - - - - - - -
		
		// Build the html select list
		$lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $phocagallery->published );

		

		// Params
		$videoCode 		= PhocaGalleryHelper::getParamsArray($phocagallery->params, 'videocode');
		$vmProductId 	= PhocaGalleryHelper::getParamsArray($phocagallery->params, 'vmproductid');
		$longitude		= PhocaGalleryHelper::getParamsArray($phocagallery->params, 'longitude');
		$latitude		= PhocaGalleryHelper::getParamsArray($phocagallery->params, 'latitude');
		$zoom			= PhocaGalleryHelper::getParamsArray($phocagallery->params, 'zoom');
		$geotitle		= PhocaGalleryHelper::getParamsArray($phocagallery->params, 'geotitle');
		
		//Longitude Latitude
		if (!isset($longitude[0]) || (isset($longitude[0]) && ($longitude[0] == '' || $longitude[0] == 0))) {
			$longitude[0] = '';
			$longitudeLink = '14.429919719696045';
		} else {
			$longitudeLink = $longitude[0];
		}
		
		if (!isset($latitude[0]) || (isset($latitude[0]) && ($latitude[0] == '' || $latitude[0] == 0))) {
			$latitude[0] = '';
			$latitudeLink = '50.079623358200884';
		} else {
			$latitudeLink = $latitude[0];
		}
		
		if (!isset($zoom[0]) || (isset($zoom[0]) && ($zoom[0] == '' || $zoom[0] == 0))) {
			$zoom[0] = 2;
		}
		if (!isset($geotitle[0]) || (isset($geotitle[0]) && $geotitle[0] == '')) {
			$geotitle[0] = '';
		}
		
		if (!isset($videoCode[0])) {
			$tmpl['videocode'] = '';
		} else {
			$tmpl['videocode'] = $videoCode[0];
		}
		
		if (!isset($vmProductId[0])) {
			$tmpl['vmproductid'] = '';
		} else {
			$tmpl['vmproductid'] = $vmProductId[0];
		}

		$tmpl['longitude']	= $longitude[0];
		$tmpl['latitude']	= $latitude[0];
		$tmpl['zoom']		= $zoom[0];
		$tmpl['geotitle']	= $geotitle[0];
		
		//Get button
		$linkg = 'index.php?option=com_phocagallery&amp;view=phocagalleryg&amp;tmpl=component&amp;lat='.$latitudeLink.'&amp;lng='.$longitudeLink.'&amp;zoom='.$zoom[0];
		JHTML::_('behavior.modal', 'a.modal-button');
		$buttong = new JObject();
		$buttong->set('modal', true);
		$buttong->set('link', $linkg);
		$buttong->set('text', JText::_( 'coordinates' ));
		$buttong->set('name', 'image');
		$buttong->set('modalname', 'modal-button');
		$buttong->set('options', "{handler: 'iframe', size: {x: 640, y: 560}}");
		
		
		if (isset($phocagallery->extlink1)) {
			$tmpl['extlink1']	= explode("|", $phocagallery->extlink1, 4);
		}
		if (!isset($tmpl['extlink1'][0])) {$tmpl['extlink1'][0] = '';}
		if (!isset($tmpl['extlink1'][1])) {$tmpl['extlink1'][1] = '';}
		if (!isset($tmpl['extlink1'][2])) {$tmpl['extlink1'][2] = '_self';}
		if (!isset($tmpl['extlink1'][3])) {$tmpl['extlink1'][3] = 1;}
		
		if (isset($phocagallery->extlink2)) {
			$tmpl['extlink2']	= explode("|", $phocagallery->extlink2, 4);
		}
		if (!isset($tmpl['extlink2'][0])) {$tmpl['extlink2'][0] = '';}
		if (!isset($tmpl['extlink2'][1])) {$tmpl['extlink2'][1] = '';}
		if (!isset($tmpl['extlink2'][2])) {$tmpl['extlink2'][2] = '_self';}
		if (!isset($tmpl['extlink2'][3])) {$tmpl['extlink2'][3] = 1;}
		
		//clean gallery data
		jimport('joomla.filter.output');
		JFilterOutput::objectHTMLSafe( $phocagallery, ENT_QUOTES, 'description' );
		
		$this->assignRef('editor', $editor);
		$this->assignRef('tmpl', $tmpl);
		$this->assignRef('lists', $lists);
		$this->assignRef('phocagallery', $phocagallery);
		$this->assignRef('button', $button);
		$this->assignRef('buttong', $buttong);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
}
?>
