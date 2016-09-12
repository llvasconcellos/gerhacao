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
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

class PhocaGalleryCpModelPhocaGalleryM extends JModel
{
	var $imageCount;
	var $categoryCount;
	
	/*form*/
	function __construct()
	{
		$this->imageCount 		= 0;
		$this->categoryCount 	= 0;
		parent::__construct();
	}

	function &getData()
	{
		$this->_initData();
		return $this->_data;
	}

	function setImageCount($count)
	{
		$this->imageCount = $this->imageCount + $count	;
	}
	
	function setCategoryCount($count)
	{
		$this->categoryCount = $this->categoryCount + $count	;
	}
	
	/*	
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
	}*/

	function store($data)
	{		
		global $mainframe;

		// retrieve folders
		
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
		
		
		//Get folder variables from Helper
		$path = PhocaGalleryHelper::getPathSet();
		$orig_path = $path['orig_abs_ds'];
		$orig_path_server = str_replace(DS, '/', $path['orig_abs'] .'/');
		
		// Cache all existing categories	
		$query = 'SELECT id, title, parent_id'
	    . ' FROM #__phocagallery_categories'
        ;
		$this->_db->setQuery( $query );
	    $existing_categories = $this->_db->loadObjectList() ;
		
		// Cache all existing images
		$query = 'SELECT catid, filename'
	    . ' FROM #__phocagallery'
	    ;	    
		$this->_db->setQuery( $query );
	    $existing_images = $this->_db->loadObjectList() ;
		
		$result->category_count = 0;
		$result->image_count = 0;
		
		if (isset($data['foldercid']))
		{
			foreach ($data['foldercid'] as $foldername)
			{
				if (strlen($foldername) > 0) {
					$full_path = $path['orig_abs_ds'].$foldername;
					$result = $this->_createCategoriesRecursive( $orig_path_server, $full_path, $existing_categories, $existing_images, $data['catid'], true );					
				}		
			}
		}
		
		
		if (isset($data['cid']))
		{
			foreach ($data['cid'] as $filename)
			{				
				if ($filename)
				{
					$ext = strtolower(JFile::getExt($filename));
					// Don't create thumbnails from defined files (don't save them into a database)...			
					$dontCreateThumb	= PhocaGalleryHelper::dontCreateThumb ($filename);
					if ($dontCreateThumb == 1) {
						$ext = '';// WE USE $ext FOR NOT CREATE A THUMBNAIL CLAUSE
					}
					if ($ext == 'jpg' || $ext == 'png' || $ext == 'gif' || $ext == 'jpeg') {	
			
						$row =& $this->getTable('phocagallery');
						
						$datam = array();
						$datam['published']	= $data['published'];
						$datam['catid']		= $data['catid'];
						$datam['filename']	= $filename;
						$datam['title']		= PhocaGalleryHelper::getTitleFromFilenameWithoutExt($filename);
						$datam['alias'] 	= PhocaGalleryHelper::getTitleFromFilenameWithoutExt($filename);
						$datam['alias'] 	= PhocaGalleryHelper::getAliasName($datam['alias']);
					
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
						$result->image_count++;
					}
				}
			}
			$this->setImageCount($result->image_count);
			
		}
		$msg = $this->categoryCount. ' ' .JText::_('Categories added') .', '.$this->imageCount. ' ' . JText::_('Images added');
		$mainframe->redirect('index.php?option=com_phocagallery&view=phocagallerys&countimg='.$this->imageCount, JText::_( $msg ));		
		
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
		natcasesort($file_list);
		// Iterate over the files if they exist
		//file - abc.img, file_no - folder/abc.img
		if ($file_list !== false)
		{
			foreach ($file_list as $filename)
			{
			    $storedfilename	= str_replace(DS, '/', JPath::clean($rel_path . DS . $filename ));
				
				$ext = strtolower(JFile::getExt($filename));
				// Don't create thumbnails from defined files (don't save them into a database)...			
				$dontCreateThumb	= PhocaGalleryHelper::dontCreateThumb ($filename);
				if ($dontCreateThumb == 1) {
					$ext = '';// WE USE $ext FOR NOT CREATE A THUMBNAIL CLAUSE
				}
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
		
	//	$this->setImageCount($count);
		return $count;
	}
	
	function _createCategoriesRecursive(&$orig_path_server, $path, &$existing_categories, &$existing_images, $parent_id = 0) {
		$totalresult->image_count = 0 ;
		$totalresult->category_count = 0 ;
				
		$category_name = basename($path);
		
		$id = $this->_getCategoryId( $existing_categories, $category_name, $parent_id ) ;
		$category = null;
		
		
		// Category doesn't exist
		if ( $id == -1 ) {
		  $row =& $this->getTable('phocagalleryc');
		  $row->published = 1;
		  $row->parent_id = $parent_id;
		  $row->title = $category_name;
		  
		  // Create the timestamp for the date
		  $row->date = gmdate('Y-m-d H:i:s');
		  
		  $row->alias = PhocaGalleryHelper::getAliasName($category_name);;
		  $row->ordering = $row->getNextOrder( "parent_id = " . $this->_db->Quote($row->parent_id) );				
		
		  if (!$row->check()) {
			JError::raiseError(500, $row->getError('Check Problem') );
		  }

		  if (!$row->store()) {
			JError::raiseError(500, $row->getError('Store Problem') );
		  }
		  
		  $category = new JObject();
		  $category->title = $category_name ;
		  $category->parent_id = $parent_id;
		  $category->id = $row->id;
		  $totalresult->category_count++;
		  $id = $category->id;
		  $existing_categories[] = &$category ;
		  $this->setCategoryCount(1);//This subcategory was added
		}
		
		// Full path: eg. "/home/www/joomla/images/categ/subcat/"
		$full_path	   = str_replace(DS, '/', JPath::clean(DS . $path));
		// Relative path eg "categ/subcat"
		$relative_path = str_replace($orig_path_server, '', $full_path);	

		//
		// Add all images from this folder
		//
		$totalresult->image_count += $this->_addAllImagesFromFolder( $existing_images, $id, $path, $relative_path );
		$this->setImageCount($totalresult->image_count);
		
		
		//
		// Do sub folders
		//
		$parent_id = $id;		
		$folder_list = JFolder::folders( 
			$path, $filter = '.', $recurse = false, $fullpath = true, $exclude = array('thumbs') );		
		// Iterate over the folders if they exist
		if ($folder_list !== false)
		{
			foreach ($folder_list as $folder)
			{
				//$this->setCategoryCount(1);//This subcategory was added
				$result = $this->_createCategoriesRecursive( $orig_path_server, $folder, $existing_categories, $existing_images, $id );
				$totalresult->image_count += $result->image_count ;
				$totalresult->category_count += $result->category_count ;
				
				
			//	$this->setCategoryCount($totalresult->category_count);
				
			}
		}
		
		return $totalresult ;
		
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
			$phocagallery->category			= null;
			$this->_data					= $phocagallery;
			return (boolean) $this->_data;
		}
		return true;
	}
	
	//---------------------------------------------------------------------------------------------------------
	/*images*/
	function getState($property = null)
	{
		static $set;

		if (!$set) {
			$folder = JRequest::getVar( 'folder', '', '', 'path' );
			$this->setState('folder', $folder);

			$parent = str_replace("\\", "/", dirname($folder));
			$parent = ($parent == '.') ? null : $parent;
			$this->setState('parent', $parent);
			$set = true;
		}
		return parent::getState($property);
	}

	function getImages()
	{
		$list = $this->getList();
		return $list['images'];
	}

	function getFolders()
	{
		$list = $this->getList();
		return $list['folders'];
	}

	function getList()
	{
		static $list;

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
		
		// Only process the list once per request
		if (is_array($list)) {
			return $list;
		}

		// Get current path from request
		$current = $this->getState('folder');

		// If undefined, set to empty
		if ($current == 'undefined') {
			$current = '';
		}

		//Get folder variables from Helper
		$path = PhocaGalleryHelper::getPathSet();
		
		// Initialize variables
		if (strlen($current) > 0) {
			$orig_path = $path['orig_abs_ds'].$current;
		} else {
			$orig_path = $path['orig_abs_ds'];
		}
		$orig_path_server 	= str_replace(DS, '/', $path['orig_abs'] .'/');
		

		$images 	= array ();
		$folders 	= array ();

		// Get the list of files and folders from the given folder
		$file_list 		= JFolder::files($orig_path);
		$folder_list 	= JFolder::folders($orig_path, '', false, false, array(0 => 'thumbs'));
		
		natcasesort($file_list);
		
		// Iterate over the files if they exist
		//file - abc.img, file_no - folder/abc.img
		if ($file_list !== false)
		{
			foreach ($file_list as $file)
			{

				$ext = strtolower(JFile::getExt($file));
				// Don't display thumbnails from defined files (don't save them into a database)...			
				$dontCreateThumb	= PhocaGalleryHelper::dontCreateThumb ($file);
				if ($dontCreateThumb == 1) {
					$ext = '';// WE USE $ext FOR NOT CREATE A THUMBNAIL CLAUSE
				}
				if ($ext == 'jpg' || $ext == 'png' || $ext == 'gif' || $ext == 'jpeg') {

					if (is_file($orig_path.DS.$file) && substr($file, 0, 1) != '.' && strtolower($file) !== 'index.html')
					{
						//Create thumbnails small, medium, large
						$refresh_url 	= 'index.php?option=com_phocagallery&view=phocagallerym&layout=form&hidemainmenu=1&folder='.$current;
						$file_no		= $current . "/" . $file;
						$file_thumb 	= PhocaGalleryHelper::getOrCreateThumbnail($path['orig_abs_ds'], $file_no, $refresh_url, 0, 0, 0);
						
						$tmp 								= new JObject();			
						$tmp->name 							= $file_thumb['name'];
						$tmp->path_with_name_relative_no	= $file_thumb['path_with_name_relative_no']	;			
						$tmp->linkthumbnailpath				= $file_thumb['thumb_name_m_no_rel'];					
						$images[] = $tmp;
					}
				}
			}
		}

		//Clean Thumbs Folder if there are thumbnail files but not original file
		if ($clean_thumbnails == 1)
		{
			PhocaGalleryHelper::cleanThumbsFolder();
		}	
		//---------------------------------------------------------------------------------------------------------------
		
		// Iterate over the folders if they exist
		if ($folder_list !== false)
		{
			foreach ($folder_list as $folder)
			{
				$tmp 							= new JObject();
				$tmp->name 						= basename($folder);
				$tmp->path_with_name 			= str_replace(DS, '/', JPath::clean($orig_path . DS . $folder));
				$tmp->path_without_name_relative= $path['orig_rel_ds'] . str_replace($orig_path_server, '', $tmp->path_with_name);
				$tmp->path_with_name_relative_no= str_replace($orig_path_server, '', $tmp->path_with_name);	

				$folders[] = $tmp;
			}
		}

		$list = array('folders' => $folders, 'images' => $images);
		return $list;
	}
}
?>