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

class PhocaGalleryCpModelPhocaGalleryD extends JModel
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
		$this->_id		= $id;
		$this->_data	= null;
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
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			// Lets load the content if it doesn't already exist
			if (empty($this->_data))
			{
				$query = 'SELECT p.filename as filename' .
						' FROM #__phocagallery AS p' .
						' WHERE p.id = '.(int) $this->_id;
				$this->_db->setQuery($query);
				$filename_object = $this->_db->loadObject();
				
				//Get Folder settings and File resize settings
				$path = PhocaGalleryHelper::getPathSet();
				$file 	= new JObject();
		
				//Create thumbnail if it doesn't exists but originalfile must exist
				$orig_path = $path['orig_abs_ds'];
				$refresh_url = 'index.php?option=com_phocagallery&view=phocagalleryd&tmpl=component&cid[]='.$this->_id;
				
				//Creata thumbnails if not exist
				PhocaGalleryHelper::getOrCreateThumbnail($orig_path, $filename_object->filename, $refresh_url, 1, 1, 1);
				
				jimport( 'joomla.filesystem.file' );
				if (!isset($filename_object->filename))
				{					
					$file->set('linkthumbnailpath', '');			
				}
				else
				{
					$thumbnail_file = PhocaGalleryHelper::getThumbnailName ($filename_object->filename, 'large');
					$file->set('linkthumbnailpath', $thumbnail_file['rel']);
				}
			}
				
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
	
}
?>
