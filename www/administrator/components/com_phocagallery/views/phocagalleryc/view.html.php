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

class PhocaGalleryCpViewPhocaGalleryC extends JView
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
		
		JHTML::_('behavior.calendar');
		
		//Data from model
		$phocagallery	=& $this->get('Data');
		JHTML::stylesheet( 'phocagallery.css', 'administrator/components/com_phocagallery/assets/' );
		
		//Image button
		$link = 'index.php?option=com_phocagallery&amp;view=phocagalleryf&amp;tmpl=component';
		JHTML::_('behavior.modal', 'a.modal-button');
		$button = new JObject();
		$button->set('modal', true);
		$button->set('link', $link);
		$button->set('text', JText::_( 'Folder' ));
		$button->set('name', 'image');
		$button->set('modalname', 'modal-button');
		$button->set('options', "{handler: 'iframe', size: {x: 620, y: 400}}");
		
		$lists 	= array();		
		$isNew	= ((int)$phocagallery->id < 1);

		// fail if checked out not by 'me'
		if ($model->isCheckedOut( $user->get('id') )) {
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'Phoca Gallery Categories' ), $phocagallery->title );
			$mainframe->redirect( 'index.php?option='. $option, $msg );
		}

		// Set toolbar items for the page
		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Phoca Gallery Category' ).': <small><small>[ ' . $text.' ]</small></small>' , 'category');
		JToolBarHelper::save();
		JToolBarHelper::apply();
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
			$phocagallery->published 		= 1;
			$phocagallery->order 			= 0;
			$phocagallery->access			= 0;
		}

		// build the html select list for ordering
		$query = 'SELECT ordering AS value, title AS text'
			. ' FROM #__phocagallery_categories'
			. ' ORDER BY ordering';
		$lists['ordering'] 			= JHTML::_('list.specificordering',  $phocagallery, $phocagallery->id, $query, false );
		// build the html select list
		$lists['published'] 		= JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $phocagallery->published );
		
		$active =  ( $phocagallery->image_position ? $phocagallery->image_position : 'left' );
		$lists['image_position'] 	= JHTML::_('list.positions',  'image_position', $active, NULL, 0, 0 );
		// Imagelist
		$lists['image'] 			= JHTML::_('list.images',  'image', $phocagallery->image );
		// build the html select list for the group access
		$lists['access'] 			= JHTML::_('list.accesslevel',  $phocagallery );
		
		// All selected users
		// Get all users id from params string
		$accessActive 		= PhocaGalleryHelper::getParamsArray($phocagallery->params, 'accessuserid');
		$uploadActive 		= PhocaGalleryHelper::getParamsArray($phocagallery->params, 'uploaduserid');
		$deleteActive 		= PhocaGalleryHelper::getParamsArray($phocagallery->params, 'deleteuserid');
		$userFolder		 	= PhocaGalleryHelper::getParamsArray($phocagallery->params, 'userfolder');
		$longitude		 	= PhocaGalleryHelper::getParamsArray($phocagallery->params, 'longitude');
		$latitude		 	= PhocaGalleryHelper::getParamsArray($phocagallery->params, 'latitude');
		$zoom			 	= PhocaGalleryHelper::getParamsArray($phocagallery->params, 'zoom');
		$geotitle			= PhocaGalleryHelper::getParamsArray($phocagallery->params, 'geotitle');
		
		// Create a multiple selectbox
		$lists['accessusers'] = PhocaGalleryHelper::usersList('accessuserid[]',$accessActive,1, NULL,'name',0 );
		$lists['uploadusers'] = PhocaGalleryHelper::usersList('uploaduserid[]',$uploadActive,1, NULL,'name',0 );
		$lists['deleteusers'] = PhocaGalleryHelper::usersList('deleteuserid[]',$deleteActive,1, NULL,'name',0 );
		$lists['author'] = PhocaGalleryHelper::usersListAuthor('authorid',$phocagallery->userid,1, NULL,'name',0 );
		
		// - - - - - - - - - - - - - - - 
		// Build the list of categories
		$query = 'SELECT a.title AS text, a.id AS value, a.parent_id as parentid'
		. ' FROM #__phocagallery_categories AS a'
	//	. ' WHERE a.published = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery( $query );
		$phocagallerys = $db->loadObjectList();
		
		// New or Edit
		if (!$isNew) {
			$phocaGalleryId = $phocagallery->id;
		} else {
			$phocaGalleryId = -1;
		}
		$tree = array();
		$text = '';
		$tree = PhocaGalleryHelper::CategoryTreeOption($phocagallerys, $tree, 0, $text, $phocaGalleryId);
		array_unshift($tree, JHTML::_('select.option', '0', '- '.JText::_('Select Parent Category').' -', 'value', 'text'));
		
		//list categories
		$lists['parentid'] = JHTML::_( 'select.genericlist', $tree, 'parentid',  '', 'value', 'text', $phocagallery->parent_id);
		
		// - - - - - - - - - - - - - - - 
		
		// Clean gallery data
		jimport('joomla.filter.output');
		JFilterOutput::objectHTMLSafe( $phocagallery, ENT_QUOTES, 'description' );

		//Params
		#$file 	= JPATH_COMPONENT.DS.'models'.DS.'phocagallery.xml';
		#$params = new JParameter( $phocagallery->params, $file );
		
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
		
		$tmpl['longitude']	= $longitude[0];
		$tmpl['latitude']	= $latitude[0];
		$tmpl['zoom']		= $zoom[0];
		$tmpl['geotitle']	= $geotitle[0];
			
		$this->assignRef('userfolder', $userFolder[0]);
		$this->assignRef('editor', $editor);
		$this->assignRef('lists', $lists);
		$this->assignRef('phocagallery', $phocagallery);
		$this->assignRef('button', $button);
		$this->assignRef('buttong', $buttong);		
		$this->assignRef('tmpl', $tmpl);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
}
?>
