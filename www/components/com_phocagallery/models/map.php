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

class PhocaGalleryModelMap extends JModel
{

	function __construct() {
		parent::__construct();
		$id 	= JRequest::getVar('id', 0, '', 'int');
		$this->setId((int)$id);
		$catid	= JRequest::getVar('catid', 0, '', 'int');
		$this->setCatid((int)$catid);
		$post	= JRequest::get('get');
	}
	
	function setId($id){
		$this->_id			= $id;
		$this->_data		= null;
	}
	
	function setCatid($catid) {
		if ($catid == 0) { //SEF
			$query = 'SELECT c.catid' .
				' FROM #__phocagallery AS c' .
				' WHERE c.id = '. (int) $this->_id;
			$this->_db->setQuery($query, 0, 1);
			$catid 			= $this->_db->loadObject();
			$this->_catid	= $catid->catid;
		} else {
			$this->_catid	= $catid;
		}
		$this->_data		= null;
	}

	
	function &getData() {
		// Load the Phoca gallery data
		if (!$this->_loadData()) {
			$this->_initData();
		}
		return $this->_data;
	}
	
	function _loadData() {
		global $mainframe;
		$user 		=& JFactory::getUser();
		// Lets load the content if it doesn't already exist
		if (empty($this->_data)) {
			// First try to get image data
			$query = 'SELECT a.title, a.filename, a.description, a.params' .
				' FROM #__phocagallery AS a' .
				' WHERE a.id = '. (int) $this->_id;
			$this->_db->setQuery($query, 0, 1);
			$image 	= $this->_db->loadObject();
			
			if (isset($image->filename) && $image->filename != '') {
				$file_thumbnail 	= PhocaGalleryHelperFront::displayFileOrNoImage($image->filename, 'small');
				$this->_data['thumbnail'] = $file_thumbnail['rel'];
			} else {
				$this->_data['thumbnail'] = '';
			}
			if (isset($image->description) && $image->description != '') {
				$this->_data['description'] = $image->description;
			} else {
				$this->_data['description'] = '';
			}
			
			if (isset($image->params) && $image->params !='') {
				$longitude	= PhocaGalleryHelper::getParamsArray($image->params, 'longitude');
				$latitude	= PhocaGalleryHelper::getParamsArray($image->params, 'latitude');
				$zoom		= PhocaGalleryHelper::getParamsArray($image->params, 'zoom');
				$geotitle	= PhocaGalleryHelper::getParamsArray($image->params, 'geotitle');
				
				if (!isset($longitude[0]) || (isset($longitude[0]) && ($longitude[0] == '' || $longitude[0] == 0))) {
					$this->_data['longitude'] = '';
				} else {
					$this->_data['longitude'] = $longitude[0];
				}
			
				if (!isset($latitude[0]) || (isset($latitude[0]) && ($latitude[0] == '' || $latitude[0] == 0))) {
					$this->_data['latitude'] = '';
				} else {
					$this->_data['latitude'] = $latitude[0];
				}
				
				if (!isset($zoom[0]) || (isset($zoom[0]) && ($zoom[0] == '' || $zoom[0] == 0))) {
					$this->_data['zoom'] = 2;
				} else {
					$this->_data['zoom'] = $zoom[0];
				}
				
				if (!isset($geotitle[0]) || (isset($geotitle[0]) && $geotitle[0] == '')) {
					$this->_data['geotitle'] = $image->title;
				} else {
					$this->_data['geotitle'] = $geotitle[0];
				}
			} else {
				$this->_data['longitude']	= '';
				$this->_data['latitude']	= '';
				$this->_data['zoom']		= 2;
				$this->_data['geotitle'] 	= '';
			}
			
			// Second try to get category data
			if ($this->_data['longitude'] == '' && $this->_data['latitude']	== '' && $this->_data['geotitle'] == '') {
				
				$query = 'SELECT c.params, c.title' .
			//	' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as slug '.
				' FROM #__phocagallery_categories AS c' .
				' WHERE c.id = '. (int) $this->_catid;
				$this->_db->setQuery($query, 0, 1);
				$category = $this->_db->loadObject();
				
				if (isset($category->params) && $category->params !='') {
					$longitude	= PhocaGalleryHelper::getParamsArray($category->params, 'longitude');
					$latitude	= PhocaGalleryHelper::getParamsArray($category->params, 'latitude');
					$zoom		= PhocaGalleryHelper::getParamsArray($category->params, 'zoom');
					$geotitle	= PhocaGalleryHelper::getParamsArray($category->params, 'geotitle');
					
					if (!isset($longitude[0]) || (isset($longitude[0]) && ($longitude[0] == '' || $longitude[0] == 0))) {
						$this->_data['longitude'] = '';
					} else {
						$this->_data['longitude'] = $longitude[0];
					}
				
					if (!isset($latitude[0]) || (isset($latitude[0]) && ($latitude[0] == '' || $latitude[0] == 0))) {
						$this->_data['latitude'] = '';
					} else {
						$this->_data['latitude'] = $latitude[0];
					}
					
					if (!isset($zoom[0]) || (isset($zoom[0]) && ($zoom[0] == '' || $zoom[0] == 0))) {
						$this->_data['zoom'] = 2;
					} else {
						$this->_data['zoom'] = $zoom[0];
					}
					
					if (!isset($geotitle[0]) || (isset($geotitle[0]) && $geotitle[0] == '')) {
						$this->_data['geotitle'] = $category->title;
					} else {
						$this->_data['geotitle'] = $geotitle[0];
					}
				} else {
					$this->_data['longitude']	= '';
					$this->_data['latitude']	= '';
					$this->_data['zoom']		= 2;
					$this->_data['geotitle'] 	= '';
				}
			}
			return (boolean) $this->_data;	
		}
		return true;
	}
	
	
	function _initData() {
		if (empty($this->_data)) {
			$this->_data	= '';
			return (boolean) $this->_data;
		}
		return true;
	}
}
?>
