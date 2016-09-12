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

class PhocaGalleryCpModelPhocaGallery extends JModel
{
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	function setId($id)
	{
		// Set id and wipe data
		$this->_id				= $id;
		$this->_data			= null;
	//	$this->_data_cat_pub	= null;// CHANGE - IF user wants to edit image, he can edit images which are in unpublished category too
	}

	function &getData()
	{
		// Load the Phoca gallery data
		if ($this->_loadData())
		{
			// Initialize some variables
			$user = &JFactory::getUser();

			// Check to see if the category is published
			// CHANGE - IF user wants to edit image, he can edit images which are in unpublished category too
			/*if (!$this->_data->cat_pub) {
				JError::raiseError( 404, JText::_("Resource Not Found") );
				return;
			}*/

			// Check whether category access level allows access
			if ($this->_data->cat_access > $user->get('aid', 0)) {
				JError::raiseError( 403, JText::_('ALERTNOTAUTH') );
				return;
			}
		}
		else  $this->_initData();

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
	
	function _getCategoryId( &$existing_categories, &$title, $parent_id )
	{
	    $id = -1 ;
		$i = 0;
		$count = count($existing_categories);
		
		while ( $id == -1 && $i < $count )
		{
			if ( $existing_categories[$i]->title == $title &&
			     $existing_categories[$i]->parent_id == $parent_id )
			{
				$id = $existing_categories[$i]->id ;
			}
			$i++;
		}
		
		return $id ;
	}
	
	function _ImageExist( &$existing_image, &$filename, $catid )
	{
	    $result = false ;
		$i = 0;
		$count = count($existing_image);
		
		while ( $result == false && $i < $count )
		{
			if ( $existing_image[$i]->filename == $filename &&
			     $existing_image[$i]->catid == $catid )
			{
				$result = true;
			}
			$i++;
		}
		
		return $result ;
	}
	
	function _addAllImagesFromFolder(&$existing_images, $category_id, $full_path, $rel_path)
	{
		$count = 0;
		$file_list = JFolder::files( $full_path );
		
		// Iterate over the files if they exist
		//file - abc.img, file_no - folder/abc.img
		if ($file_list !== false)
		{
			foreach ($file_list as $filename)
			{
			    $storedfilename	= str_replace(DS, '/', JPath::clean($rel_path . DS . $filename ));
				
				$ext = strtolower(JFile::getExt($filename));
				if ($ext == 'jpg' || $ext == 'png' || $ext == 'gif' || $ext == 'jpeg') {
				
					if (is_file($full_path.DS.$filename) && 
					    substr($filename, 0, 1) != '.' && 
						strtolower($filename) !== 'index.html' &&
						!$this->_ImageExist($existing_images, $storedfilename, $category_id) )
					{
						$row =& $this->getTable('phocagallery');
						
						$datam = array();
						$datam['published']	= 1;
						$datam['catid']		= $category_id;
						$datam['filename']	= $storedfilename;
						$datam['title']		= PhocaGalleryHelper::getTitleFromFilenameWithoutExt($filename);
						$datam['alias'] 	= PhocaGalleryHelper::getAliasName($datam['title']);
					
						// Save
						// Bind the form fields to the Phoca gallery table
						if (!$row->bind($datam)) {
							$this->setError($this->_db->getErrorMsg());
							return false;
						}

						// Create the timestamp for the date
						$row->date = gmdate('Y-m-d H:i:s');

						// if new item, order last in appropriate group
						if (!$row->id) {
							$where = 'catid = ' . (int) $row->catid ;
							$row->ordering = $row->getNextOrder( $where );
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
						
						$image = new JObject();
					    $image->filename = $storedfilename ;
					    $image->catid = $category_id;
					    $existing_images[] = &$image ;
						$count++ ;
					}
				}
			}
		}
		return $count;
	}
	
	function _createCategoriesRecursive(&$orig_path_server, $path, &$existing_categories, &$existing_images, $parent_id = 0)
	{
		
		$totalresult->image_count = 0 ;
		$totalresult->category_count = 0 ;
		
		$folder_list = JFolder::folders( 
			$path, $filter = '.', $recurse = false, $fullpath = true, $exclude = array('thumbs') );
			
		// Iterate over the folders if they exist
		if ($folder_list !== false)
		{
			foreach ($folder_list as $folder)
			{
				$category_name = basename($folder);
		
			    $path_with_name 			= str_replace(DS, '/', JPath::clean(DS . $folder));
				$path_with_name_relative_no = str_replace($orig_path_server, '', $path_with_name);	

				$id = $this->_getCategoryId( $existing_categories, $category_name, $parent_id ) ;
				
				if ( $id == -1 )
				{
				  $row =& JTable::getInstance('phocagallery_categories');
				  $row->published = 1;
				  $row->parent_id = $parent_id;
				  $row->title = $category_name;
				  $row->alias = PhocaGalleryHelper::getAliasName($category_name);;
				  $row->ordering = $row->getNextOrder( "parent_id = " . $this->_db->Quote($row->parent_id) );				
				
				  if (!$row->check()) {
				  	JError::raiseError(500, $row->getError('Check Error') );
				  }
		
				  if (!$row->store()) {
				  	JError::raiseError(500, $row->getError('Store Error') );
				  }
  				  $id = $row->id; 

				  $category = new JObject();
				  $category->title = $category_name ;
				  $category->parent_id = $parent_id;
				  $category->id = $id;
				  $existing_categories[] = &$category ;
				  $totalresult->category_count++;
				}

				$totalresult->image_count += $this->_addAllImagesFromFolder( $existing_images, $id, $folder, $path_with_name_relative_no );
				$result = $this->_createCategoriesRecursive( $orig_path_server, $folder, $existing_categories, $existing_images, $id );
				$totalresult->image_count += $result->image_count ;
				$totalresult->category_count += $result->category_count ;
			}
		}
		
		return $totalresult ;
	}
	
	function store($data)
	{
		//Params
		$params	= &JComponentHelper::getParams( 'com_phocagallery' );
		//Clean Thumbnails or not
		if ($params->get( 'clean_thumbnails' ) != '') {
			$clean_thumbnails = $params->get( 'clean_thumbnails' );
		} else {
			$clean_thumbnails = 0;
		}
		
		//If this file doesn't exists don't save it
		if (!PhocaGalleryHelper::existsFileOriginal($data['filename'])) {
			$this->setError();
			return false;
		}
		
		
		//If there is no title and no alias, use filename as title and alias
		if ($data['title'] == '') {
			$data['title'] = PhocaGalleryHelper::getTitleFromFilenameWithoutExt($data['filename']);
		}
		if ($data['alias'] == '') {
			$data['alias'] = PhocaGalleryHelper::getTitleFromFilenameWithoutExt($data['filename']);
		}
		
		//clean alias name (no bad characters)
		$data['alias'] = PhocaGalleryHelper::getAliasName($data['alias']);
		
		$row =& $this->getTable();

		// Bind the form fields to the Phoca gallery table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Create the timestamp for the date
		if (!$row->date) {
			$row->date = gmdate('Y-m-d H:i:s');
		}

		// if new item, order last in appropriate group
		if (!$row->id) {
			$where = 'catid = ' . (int) $row->catid ;
			$row->ordering = $row->getNextOrder( $where );
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
		
		
		//------------------------------------------------------------------------------------------------------
		//Create thumbnail small, medium, large		
		//------------------------------------------------------------------------------------------------------
		//file - abc.img, file_no - folder/abc.img
		//Get folder variables from Helper
		$path 		= PhocaGalleryHelper::getPathSet();
		$orig_path 	= $path['orig_abs_ds'];
		
		//Create thumbnails small, medium, large
		$refresh_url = 'index.php?option=com_phocagallery&controller=phocagallery&task=thumbs';
		$file_thumb = PhocaGalleryHelper::getOrCreateThumbnail($orig_path, $row->filename, $refresh_url, 1, 1, 1);

		//Clean Thumbs Folder if there are thumbnail files but not original file
		if ($clean_thumbnails == 1)
		{
			PhocaGalleryHelper::cleanThumbsFolder();
		}
		//---------------------------------------------------------------------------------------------------------------
		
		return true;
	}

	function delete($cid = array())
	{
		//Params
		$params	= &JComponentHelper::getParams( 'com_phocagallery' );
		//Clean Thumbnails or not
		if ($params->get( 'clean_thumbnails' ) != '')
		{
			$clean_thumbnails = $params->get( 'clean_thumbnails' );
		}
		else
		{
			$clean_thumbnails = 0;
		}
		
		
		$result = false;

		
		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
			
			//-------------------------------------------------------------------------------------------------------
			// Get all filenames we want to delete from database, we delete all thumbnails from server of this file
			$queryd = 'SELECT filename as filename FROM #__phocagallery WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery($queryd);
			$file_object = $this->_db->loadObjectList();
			//-------------------------------------------------------------------------------------------------------

			//Delete it from DB
			$query = 'DELETE FROM #__phocagallery'
				. ' WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

			//------------------------------------------------------------------------------------------------------
			# Delete thumbnails - medium and large, small from server
			//All id we want to delete - gel all filenames
			foreach ($file_object as $key => $value)
			{
				//The file can be stored in other category - don't delete it from server because other category use it
				$querys = "SELECT id as id FROM #__phocagallery WHERE filename='".$value->filename."' ";
				$this->_db->setQuery($queryd);
				$same_file_object = $this->_db->loadObject();
				
				//same file in other category doesn't exist - we can delete it
				if (!$same_file_object)
				{
					//Delete all thumbnail files but not original (Only user can delete original file e.g. in media manager)
					//if (JFile::exists($file_original))		{JFile::delete($file_thumbnail);}
					PhocaGalleryHelper::deleteFileThumbnail($value->filename, 1, 1, 1);
				}
			}
			
			//Clean Thumbs Folder if there are thumbnail files but not original file
			if ($clean_thumbnails == 1)
			{
				PhocaGalleryHelper::cleanThumbsFolder();
			}
			//------------------------------------------------------------------------------------------------------
		}
		
		return true;
	}

	function publish($cid = array(), $publish = 1) {
		$user 	=& JFactory::getUser();

		if (count( $cid )) {
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__phocagallery'
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

		if (!$row->move( $direction, ' catid = '.(int) $row->catid.' AND published >= 0 ' )) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}


	function saveorder($cid = array(), $order)
	{
		$row =& $this->getTable();
		$groupings = array();

		// update ordering values
		for( $i=0; $i < count($cid); $i++ )
		{
			$row->load( (int) $cid[$i] );
			// track categories
			$groupings[] = $row->catid;

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
			$row->reorder('catid = '.(int) $group);
		}

		return true;
	}
	
	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = 'SELECT p.*, cc.title AS category,'.
					' cc.published AS cat_pub, cc.access AS cat_access'.
					' FROM #__phocagallery AS p' .
					' LEFT JOIN #__phocagallery_categories AS cc ON cc.id = p.catid' .
					' WHERE p.id = '.(int) $this->_id;
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
			$phocagallery->catid			= 0;
			$phocagallery->sid				= 0;
			$phocagallery->title			= null;
			$phocagallery->alias			= null;
			$phocagallery->filename         = null;
			$phocagallery->description		= null;
			$phocagallery->date				= null;
			$phocagallery->hits				= 0;
			$phocagallery->published		= 0;
			$phocagallery->checked_out		= 0;
			$phocagallery->checked_out_time	= 0;
			$phocagallery->ordering			= 0;
			$phocagallery->params			= null;
			$phocagallery->extlink1			= null;
			$phocagallery->extlink2			= null;
			$phocagallery->category			= null;
			$this->_data					= $phocagallery;
			return (boolean) $this->_data;
		}
		return true;
	}
	
	
	function disableThumbs()
	{
		
		$paramsComponentArray 	= '';
		$paramsD 				= array();
		$paramsD[0] 			= array('name' => 'enable_thumb_creation',
										'value'=> 0);
		// Params - Get component params
		$paramsComponent		= JComponentHelper::getParams('com_phocagallery') ;
		$paramsComponentArray 	= $paramsComponent->_registry['_default']['data'];
		
		// if empty object, php doesn't say it...
		$isArray = 0;
		foreach ($paramsComponentArray as $isKey => $isValue) {
			$isArray = 1;
		}

		// If no params are saved, we add only one params
		if ($isArray == 1) {
			// We get the params values from database and add new values ( no lose the other params)
			$newParamsComponent = '';
			foreach ($paramsComponentArray as $keyC => $valueC)
			{
				$newParamsComponent['params'][$keyC] = $valueC;
				foreach ($paramsD as $keyD => $valueD)
				{
					if ($valueD['name'] == $keyC)
					{
						$newParamsComponent['params'][$keyC] = $valueD['value'];
					}
					
				}
			}
		} else {
			$newParamsComponent = '';
			foreach ($paramsD as $keyD => $valueD)
			{
				$newParamsComponent['params'][$valueD['name']] = $valueD['value'];
			}
		}
		
		$table =& JTable::getInstance( 'component' );
		$table->loadByOption( 'com_phocagallery' );

		if (!$table->bind($newParamsComponent)) {
			JError::raiseWarning( 500, 'Not a valid component' );
			return false;
		}
			
		// pre-save checks
		if (!$table->check()) {
			JError::raiseWarning( 500, $table->getError('Check Problem') );
			return false;
		}

		// save the changes
		if (!$table->store()) {
			JError::raiseWarning( 500, $table->getError('Store Problem') );
			return false;
		}
		return true;
	}
	
	function rotate($id, $angle) {
		
		if ($id > 0 && $angle !='') {
			$query = 'SELECT a.filename as filename'.
					' FROM #__phocagallery AS a' .
					' WHERE a.id = '.(int) $id;
			$this->_db->setQuery($query);
			$file = $this->_db->loadObject();
			
			
			if (isset($file->filename) && $file->filename != '') {
				
				$thumbNameL	= PhocaGalleryHelper::getThumbnailName ($file->filename, 'large');
				$thumbNameM	= PhocaGalleryHelper::getThumbnailName ($file->filename, 'medium');
				$thumbNameS	= PhocaGalleryHelper::getThumbnailName ($file->filename, 'small');
				
				$rotateErrorL	= false;
				$rotateErrorM	= false;
				$rotateErrorS	= false;				
				$rotatingL 		= PhocaGalleryHelper::rotateImage($thumbNameL, 'large', $angle);
				$rotateErrorL 	= preg_match("/Error/i", $rotatingL);
				if ($rotateErrorL) {
					return $rotatingL;
				}
				$rotatingM 		= PhocaGalleryHelper::rotateImage($thumbNameM, 'medium', $angle);
				$rotateErrorM 	= preg_match("/Error/i", $rotatingM);
				if ($rotateErrorM) {
					return $rotatingM;
				} 
				$rotatingS 		= PhocaGalleryHelper::rotateImage($thumbNameS, 'small', $angle);
				$rotateErrorS 	= preg_match("/Error/i", $rotatingS);
				if ($rotateErrorS) {
					return $rotatingS;
				} 

				if ($rotatingL == 'Success' && $rotatingM == 'Success' && $rotatingS == 'Success' ) {
					return 'Success';
				} else {
					return 'ErrorModel1';
				}
			} return 'ErrorModel2';
		} return 'ErrorModel3';
	}
	
	function deletethumbs($id) {
		
		if ($id > 0) {
			$query = 'SELECT a.filename as filename'.
					' FROM #__phocagallery AS a' .
					' WHERE a.id = '.(int) $id;
			$this->_db->setQuery($query);
			$file = $this->_db->loadObject();
			if (isset($file->filename) && $file->filename != '') {
				
				$deleteThubms = PhocaGalleryHelper::deleteFileThumbnail($file->filename, 1, 1, 1);
				
				if ($deleteThubms) {
					return true;
				} else {
					return false;
				}
			} return false;
		} return false;
	}
}
?>