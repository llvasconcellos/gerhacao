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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
jimport('joomla.application.component.model');

class PhocaGalleryCpModelPhocaGalleryC extends JModel
{
	var $_XMLFile;
	var $_id;
	var $_data;
	
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	function &getData()
	{
		if ($this->_loadData())
		{
			$user = &JFactory::getUser();
			
	/*		// Check to see if the category is published
			if (!$this->_data->cat_pub) {
				JError::raiseError( 404, JText::_("Resource Not Found") );
				return;
			}
			
			// Check whether category access level allows access
			if ($this->_data->cat_access > $user->get('aid', 0)) {
				JError::raiseError( 403, JText::_('ALERTNOTAUTH') );
				return;
			}*/
		}
		else
		{
			$this->_initData();
		}
		return $this->_data;
	}
	
	function isCheckedOut( $uid=0 )
	{
		if ($this->_loadData())
		{
			if ($uid) {
				return ($this->_data->checked_out && $this->_data->checked_out != $uid);
			} else {
				return $this->_data->checked_out;
			}
		}
	}

	function checkin()
	{
		if ($this->_id)
		{
			$phocagallery = & $this->getTable();
			if(! $phocagallery->checkin($this->_id)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return false;
	}

	function checkout($uid = null)
	{
		if ($this->_id)
		{
			// Make sure we have a user id to checkout the article with
			if (is_null($uid)) {
				$user	=& JFactory::getUser();
				$uid	= $user->get('id');
			}
			// Lets get to it and checkout the thing...
			$phocagallery = & $this->getTable();
			if(!$phocagallery->checkout($uid, $this->_id)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}
		return false;
	}
	
	function store($data)
	{
		
		//clean alias name (no bad characters)
		if ($data['alias'] == '') {
			$data['alias'] = $data['title'];
		}
		$data['alias'] = PhocaGalleryHelper::getAliasName($data['alias']);
		
		$row =& $this->getTable();
		
		// Bind the form fields to the table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->date) {
			$row->date = gmdate('Y-m-d H:i:s');
		}
		
		// if new item, order last in appropriate group
		if (!$row->id) {
			$where = 'parent_id = ' . (int) $row->parent_id ;
			//$where = '';
			$row->ordering = $row->getNextOrder( $where );
		}

		// Make sure the table is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the table to the database
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return $row->id;
	}
	
	/*
	 * AUTHOR
	 * Store information about author (if administrator add a category to some author
	 */
	function storeUserCategory($data) {
		
		// DELETE this category from other users
		// If this category will be added to USER 1, it must be removed from USER 2
		$db =& JFactory::getDBO();
		$query = 'DELETE FROM #__phocagallery_user_category'
			. ' WHERE catid = '.(int)$data['catid']
			. ' AND userid <> '.(int)$data['userid'];
			
		$db->setQuery( $query );
		if (!$db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		//
		
		$row =& $this->getTable('phocagalleryusercategory');
		
		// Bind the form fields to the Phoca gallery table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Make sure the Phoca gallery table is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the Phoca gallery table to the database
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		return $row->id;
		
	}
	
	/*
	 * AUTHOR
	 * Get information about author's category
	 */
	function getUserCategoryId($userId) {
		
		$db =& JFactory::getDBO();

		$query = 'SELECT uc.id'
			. ' FROM #__phocagallery_user_category AS uc'
			. ' WHERE uc.userid = '.(int)$userId;
		
		$db->setQuery( $query );
		$userCategoryId = $db->loadObject();
		if (!isset($userCategoryId->id)) {
			return false;
		}
		return $userCategoryId->id;
	}
	
	function accessmenu($id, $access)
	{
		global $mainframe;
		$row =& $this->getTable();
		$row->id = $id;
		$row->access = $access;

		if ( !$row->check() ) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if ( !$row->store() ) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
	}

	function delete($cid = array())
	{
		global $mainframe;
		$db =& JFactory::getDBO();
		
		$result = false;
		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
			
			// FIRST - if there are subcategories ---------------------------------------------------	
			$query = 'SELECT c.id, c.name, c.title, COUNT( s.parent_id ) AS numcat'
			. ' FROM #__phocagallery_categories AS c'
			. ' LEFT JOIN #__phocagallery_categories AS s ON s.parent_id = c.id'
			. ' WHERE c.id IN ( '.$cids.' )'
			. ' GROUP BY c.id'
			;
			$db->setQuery( $query );
				
			
			if (!($rows2 = $db->loadObjectList())) {
				JError::raiseError( 500, $db->stderr('Load Data Problem') );
				return false;
			}

			// Add new CID without categories which have subcategories (we don't delete categories with subcat)
			$err_cat = array();
			$cid 	 = array();
			foreach ($rows2 as $row) {
				if ($row->numcat == 0) {
					$cid[] = (int) $row->id;
				} else {
					$err_cat[] = $row->title;
				}
			}
			
			
			// End subcategories ----------------------------------------------------------------------
			
			// Images with new cid
			if (count( $cid ))
			{
				JArrayHelper::toInteger($cid);
				$cids = implode( ',', $cid );
			

				// Select id's from phocagallery tables. If the category has some images, don't delete it
				$query = 'SELECT c.id, c.name, c.title, COUNT( s.catid ) AS numcat'
				. ' FROM #__phocagallery_categories AS c'
				. ' LEFT JOIN #__phocagallery AS s ON s.catid = c.id'
				. ' WHERE c.id IN ( '.$cids.' )'
				. ' GROUP BY c.id'
				;
			
				$db->setQuery( $query );

				if (!($rows = $db->loadObjectList())) {
					JError::raiseError( 500, $db->stderr('Load Data Problem') );
					return false;
				}
				
				$err_img = array();
				$cid 	 = array();
				foreach ($rows as $row) {
					if ($row->numcat == 0) {
						$cid[] = (int) $row->id;
					} else {
						$err_img[] = $row->title;
					}
				}
				
				if (count( $cid )) {
					$cids = implode( ',', $cid );
					$query = 'DELETE FROM #__phocagallery_categories'
					. ' WHERE id IN ( '.$cids.' )'
					;
					$db->setQuery( $query );
					if (!$db->query()) {
						$this->setError($this->_db->getErrorMsg());
						return false;
					}
					
					// Delete items in phocagallery_user_category
					$query = 'DELETE FROM #__phocagallery_user_category'
					. ' WHERE catid IN ( '.$cids.' )'
					;
					$db->setQuery( $query );
					if (!$db->query()) {
						$this->setError($this->_db->getErrorMsg());
						return false;
					}
				}
			}
			
			// There are some images in the category - don't delete it
			$msg = '';
			if (count( $err_cat ) || count( $err_img ))
			{
				if (count( $err_cat ))
				{
					$cids_cat = implode( ", ", $err_cat );
					$msg .= JText::sprintf( 'WARNNOTREMOVEDRECORDS PHOCA GALLERY CAT', $cids_cat );
				}
				
				if (count( $err_img ))
				{
					$cids_img = implode( ", ", $err_img );
					$msg .= JText::sprintf( 'WARNNOTREMOVEDRECORDS PHOCA GALLERY', $cids_img );
				}
					
					
					$link = 'index.php?option=com_phocagallery&view=phocagallerycs';
					$mainframe->redirect($link, $msg);
			}
		}
		return true;
	}

	function publish($cid = array(), $publish = 1)
	{
		$user 	=& JFactory::getUser();

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__phocagallery_categories'
				. ' SET published = '.(int) $publish
				. ' WHERE id IN ( '.$cids.' )'
				. ' AND ( checked_out = 0 OR ( checked_out = '.(int) $user->get('id').' ) )'
			;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}

	function move($direction)
	{
		$row =& $this->getTable();
		if (!$row->load($this->_id)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->move( $direction, ' parent_id = '.(int) $row->parent_id.' AND published >= 0 ' )) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}
	
	


	function saveorder($cid = array(), $order)
	{
		$row =& $this->getTable();
		$groupings = array();

		//$catid is null
		// update ordering values
		for( $i=0; $i < count($cid); $i++ )
		{
			$row->load( (int) $cid[$i] );
			// track categories
			$groupings[] = $row->parent_id;

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}

		// execute updateOrder for each parent group
		$groupings = array_unique( $groupings );
		foreach ($groupings as $group){
			$row->reorder('parent_id = '.(int) $group);
		}
		return true;
	}
	
	function _loadData()
	{
		if (empty($this->_data))
		{		
			$query = 'SELECT p.*, uc.userid AS userid '	
					.' FROM #__phocagallery_categories AS p'
					.' LEFT JOIN #__phocagallery_user_category AS uc ON uc.catid = p.id'
					.' WHERE p.id = '.(int) $this->_id;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}
	
	
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$phocagallery = new stdClass();
			$phocagallery->id				= 0;
			$phocagallery->parent_id		= 0;
			$phocagallery->title			= null;
			$phocagallery->name				= null;
			$phocagallery->alias			= null;
			$phocagallery->image			= null;
			$phocagallery->section        	= null;
			$phocagallery->image_position	= null;
			$phocagallery->description		= null;
			$phocagallery->date				= null;
			$phocagallery->published		= 0;
			$phocagallery->checked_out		= 0;
			$phocagallery->checked_out_time	= 0;
			$phocagallery->editor			= null;
			$phocagallery->ordering			= 0;
			$phocagallery->access			= 0;
			$phocagallery->hits				= 0;
			$phocagallery->count			= 0;
			$phocagallery->params			= null;
			$phocagallery->userid			= 0;
			$this->_data					= $phocagallery;
			return (boolean) $this->_data;
		}
		return true;
	}
	
	
	
	function piclens($cids)
	{
		$db 	=& JFactory::getDBO();
		$path 	= PhocaGalleryHelper:: getPathSet();
		$paramsC= JComponentHelper::getParams('com_phocagallery') ;
		
		// PARAMS
		$piclens_image 	= $paramsC->get( 'piclens_image', 1);
		
		if (JFolder::exists($path['orig_abs_ds'])) {  
			
			foreach ($cids as $kcid =>$vcid)
			{
				$this->setXMLFile();
				
				if (!$this->_XMLFile) {
					$this->setError( 'Could not create XML builder' );
					return false;
				}
						
				if (!$node = $this->_XMLFile->createElement( 'rss' )) {
					$this->setError( 'Could not create node!' );
					return false;
				}
				
				$node->setAttribute( 'xmlns:media', 'http://search.yahoo.com/mrss' );
				$node->setAttribute( 'xmlns:atom', 'http://www.w3.org/2005/Atom' );
				$node->setAttribute( 'version', '2.0' );
				
				$this->_XMLFile->setDocumentElement( $node );
				
				
				if (!$root =& $this->_XMLFile->documentElement) {
					$this->setError( 'Could not obtain root element!' );
					return false;
				}
			
				$channel =& $this->_XMLFile->createElement( 'channel' );
				$atomIcon=& $this->_XMLFile->createElement( 'atom:icon' );
				$atomIcon->setText( 'http://www.phoca.cz/images/phoca-piclens.png' );
				$channel->appendChild( $atomIcon );	
				
				$query = 'SELECT a.id AS id, a.title AS title, a.filename AS filename FROM #__phocagallery AS a'
				. ' WHERE a.catid = '.(int)$vcid
				. ' AND a.published = 1'
				. ' ORDER BY a.catid, a.ordering';
				
				$db->setQuery($query);
				$rows = $db->loadObjectList();
				
				foreach ($rows as $krow => $vrow)
				{
					$file = PhocaGalleryHelper::getOrCreateThumbnail('', $vrow->filename, '');	
					
					
					
					$orig_file			 	= str_replace( "../", "", $file['path_with_name_relative'] );
					$orig_file			 	= str_replace( "//", "/", $orig_file );
					$thumb_name_l_no_rel 	= str_replace( "../", "", $file['thumb_name_l_no_rel'] );
					$thumb_name_l_no_rel	= str_replace( "//", "/", $thumb_name_l_no_rel );
					$juri_base			 	= str_replace( "administrator", "", JURI::base(true));
					$thumb_image_path		= $juri_base . $thumb_name_l_no_rel;
					$orig_file_path			= $juri_base . $orig_file;					
					
					$item=& $this->_XMLFile->createElement( 'item' );
					$item->appendChild( $this->_buildXMLElement( 'title', $vrow->title ) );
					$item->appendChild( $this->_buildXMLElement( 'link', $thumb_image_path ) );
					
					$thumbnail=& $this->_XMLFile->createElement( 'media:thumbnail' );
					$thumbnail->setAttribute( 'url', $thumb_image_path );
					
					$content=& $this->_XMLFile->createElement( 'media:content' );
					if ($piclens_image == 1) {
						$content->setAttribute( 'url', $thumb_image_path );
					} else {
						$content->setAttribute( 'url', $orig_file_path );
					}
					$item->appendChild( $thumbnail );
					$item->appendChild( $content );
					
					$guid=& $this->_XMLFile->createElement( 'guid' );
					$guid->setText( $vcid .'-phocagallerypiclenscode-'.$vrow->filename );
					$guid->setAttribute( 'isPermaLink', "false" );
					$item->appendChild( $guid );
					
					$channel->appendChild( $item );
				}

				$root->appendChild( $channel );	 
			
				$this->_XMLFile->setXMLDeclaration( '<?xml version="1.0" encoding="utf-8" standalone="yes"?>' );
				
				//echo $this->_XMLFile->toNormalizedString( true );exit;
				// saveXML_utf8 doesn't save setXMLDeclaration
				if (!$this->_XMLFile->saveXML( $path['orig_abs_ds'] . DS . $vcid.'.rss', true )) {
					$this->setError( 'Could not save XML file!' );
					return false;
				}
			}
			
			return true;
		} else {
			$this->setError( 'Phoca Gallery image folder not exists' );
		}
	}
	
	function setXMLFile()
	{
		$this->_XMLFile =& JFactory::getXMLParser();
	}
	
	function &_buildXMLElement( $elementName, $text )
	{
		$node = $this->_XMLFile->createElement( $elementName );
		$node->setText( $text );
		return $node;
	}
}
?>