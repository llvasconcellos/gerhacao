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

class PhocaGalleryModelDetail extends JModel
{

	function __construct()
	{
		parent::__construct();
		
		$id = JRequest::getVar('id', 0, '', 'int');
		$this->setId((int)$id);
	
		$slideshow = JRequest::getVar('phocaslideshow', 0, '', 'int');
		$this->setSlideshow((int)$slideshow);
		
		$download = JRequest::getVar('phocadownload', 0, '', 'int');
		$this->setDownload((int)$download);
		
	}
	
	function setId($id)
	{
		// Set id and wipe data
		$this->_id			= $id;
		$this->_data		= null;
		//$this->_category	= null;
	}
	
	function setSlideshow($slideshow)
	{
		// Set id and wipe data
		$this->_slideshow	= $slideshow;
		$this->_data		= null;
	}
	
	function setDownload($download)
	{
		// Set id and wipe data
		$this->_download	= $download;
		$this->_data		= null;
	}

	
	function &getData()
	{
		// Load the Phoca gallery data
		if (!$this->_loadData())
		{
			$this->_initData();
		}
		return $this->_data;
	}
	
	function _loadData()
	{
		global $mainframe;
		$user 		=& JFactory::getUser();
		
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = 'SELECT p.*' .
					' FROM #__phocagallery AS p' .
					' WHERE p.id = '.(int) $this->_id;
			$this->_db->setQuery($query);
			$items = $this->_db->loadObject();
		
		
			// Access check - don't display the image if you have no access to this image (if user add own url)
			if (isset($items->catid)) {
				$query = 'SELECT cc.access as access, cc.params as params' .
					' FROM #__phocagallery_categories AS cc' .
					' WHERE cc.id = '.(int) $items->catid;
				$this->_db->setQuery($query);
				$catid = $this->_db->loadObject();
				// USER RIGHT - ACCESS =======================================
				$rightDisplay	= 0;
				if (isset($catid->params)) {
					$rightDisplay = PhocaGalleryHelper::getUserRight ($catid->params, 'accessuserid', $catid->access, $user->get('aid', 0), $user->get('id', 0), 0);
				}
				
				if ($rightDisplay == 0) {
					$mainframe->redirect('index.php?option=com_user&view=login', JText::_("ALERTNOTAUTH"));
					exit;
				}
				// ============================================================
			}
			
			//Select category
			if (!$this->_loadCategory())
			{
				$this->_loadCategory();
			}
			
			//Slugs - possible
			//$items->slugid 		= (int) $items->id . "-" . $items->alias;
			//$items->slugcatid	= $this->_category->slug;
			
			// SLUG CATID
		/*	$query = 'SELECT c.alias'.
				' FROM #__phocagallery_categories AS c' .
				' WHERE c.id = '. (int) $this->_category->id;
			$this->_db->setQuery($query);
			$catid_alias = $this->_db->loadObject();
		*/	
			
			if (isset($this->_category->slug) && $this->_category->slug != '') {
				$catid_slug = $this->_category->slug;
			} else {
				
				$catid_slug = (int) $this->_category->id;
			}
			// ----------
			// SLUG ID
		/*	$query = 'SELECT a.alias'.
				' FROM #__phocagallery AS a' .
				' WHERE a.id = '. (int) $this->_id;
			$this->_db->setQuery($query);
			$id_alias = $this->_db->loadObject();
		*/	
			
			if (isset($items->alias) && $items->alias != '') {
				$id_slug = (int) $this->_id . ':'.$items->alias;
			} else {
				
				$id_slug = (int) $this->_id . ':';// Because of possible SEF problem
			}
			// ----------
			
			
			//Javascript Slideshow buttons
			$reload_button		= PhocaGalleryHelperFront::getGalleryReload((int) $this->_category->id, (int) $this->_id, $id_slug, $catid_slug);
			$close_button		= PhocaGalleryHelperFront::getGalleryClose((int) $this->_category->id, (int) $this->_id, $id_slug, $catid_slug);
			$close_text			= PhocaGalleryHelperFront::getGalleryCloseText((int) $this->_category->id, (int) $this->_id, $id_slug, $catid_slug);
			$next_button		= PhocaGalleryHelperFront::getGalleryNext((int) $this->_category->id, (int) $this->_id);
			$prev_button		= PhocaGalleryHelperFront::getGalleryPrevious((int) $this->_category->id, (int) $this->_id);
			$js_slideshow_data	= PhocaGalleryHelperFront::getGalleryJsSlideshow((int) $this->_category->id, (int) $this->_id, (int) $this->_slideshow, $id_slug, $catid_slug);
			
			// Get file thumbnail or No Image
			$file_name_no			= $items->filename;
			$file_name				= PhocaGalleryHelperFront::getTitleFromFilenameWithExt($items->filename);
			$image_size				= PhocaGalleryHelperFront::getImageSizePhoca($items->filename);
			$file_size				= PhocaGalleryHelperFront::getFileSizePhoca($items->filename);
			
			$file_thumbnail 		= PhocaGalleryHelperFront::displayFileOrNoImage($items->filename, 'large');
			$link_thumbnail_path	= $file_thumbnail['rel'];
			
			
			
			
			
			$file = new JObject();
			//slideshow
			$file->set('closebutton', $close_button);
			$file->set('reloadbutton', $reload_button);
			$file->set('nextbutton', $next_button);
			$file->set('prevbutton', $prev_button);
			$file->set('slideshowbutton', $js_slideshow_data['icons']);
			$file->set('slideshowfiles', $js_slideshow_data['files']);
			$file->set('slideshow', $this->_slideshow);
			//download
			$file->set('closetext', $close_text);
			$file->set('filenameno', $file_name_no);
			$file->set('filename', $file_name);
			$file->set('download', $this->_download);
			$file->set('filesize', $file_size);
			$file->set('imagesize', $image_size[0] . ' x '.$image_size[1]);
			//all
			$file->set('linkthumbnailpath', $link_thumbnail_path);
			//description
			$file->set('description', $items->description);
			$file->set('params', $items->params);
			$file->set('title', $items->title);
		
			if (isset($file))
			{
				$this->_data = $file;
			}
			else
			{
				$this->_data = '';
			}
			return (boolean) $this->_data;	
		}
		return true;
	}

	function _loadCategory()
	{
		if (empty($this->_category))
		{
			$query = 'SELECT c.*, '
			.' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as slug'
			.' FROM #__phocagallery_categories AS c'
			.' LEFT JOIN #__phocagallery AS a ON a.catid = c.id'
			.' WHERE a.id = '. (int) $this->_id;
			$this->_db->setQuery($query);
			$this->_category = $this->_db->loadObject();
		}
		return true;
	}
	
	
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$this->_data	= '';
			return (boolean) $this->_data;
		}
		return true;
	}
	
	function hit($id) {
	
		global $mainframe;
		$table = & JTable::getInstance('phocagallery', 'Table');
		$table->hit($id);
		return true;
	}
	
}
?>
