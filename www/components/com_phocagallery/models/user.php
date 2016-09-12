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
jimport('joomla.application.component.model');

class PhocagalleryModelUser extends JModel
{

	/*function getUserCategory($userId) {
		if ($userId > 0) {	
	
			$query = 'SELECT uc.id AS id'
				    .' FROM #__phocagallery_user_category AS uc'
				    .' WHERE uc.userid = '. (int)$userId;
			$this->_db->setQuery($query, 0, 1);
			$userCatId = $this->_db->loadObject();
				
			return $userCatId;
			
		}
	}*/
	
	function getUserCategory($userId) {
		if ($userId > 0) {	
	
			$query = 'SELECT cc.id as categoryid, cc.title as categorytitle, cc.description as categorydescription, cc.published as categorypublished, uc.id AS id'
					.' FROM #__phocagallery_categories AS cc'
					.' LEFT JOIN #__phocagallery_user_category AS uc ON uc.catid = cc.id'
				    .' WHERE uc.userid = '. (int)$userId
					//.' AND cc.published = 1'
					.' GROUP BY cc.id';

			$this->_db->setQuery($query);
			$userCategory = $this->_db->loadObject();
				
			return $userCategory;
			
		}
	}
	
	function store($data) {
		
		$row =& $this->getTable('phocagalleryc');
		
		// Bind the form fields to the Phoca gallery table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Create the timestamp for the date
		$row->checked_out_time = gmdate('Y-m-d H:i:s');

		// if new item, order last in appropriate group
		if (!$row->id) {
			$where = 'parent_id = 0';
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
		
		return $row->id;
		
	}
	
	function storeUserCategory($data) {
		
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
	

	function getCategoryParams($id) {
		
		// current category info, id is catid
		$query = 'SELECT cc.params,cc.access,' .
			' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(\':\', cc.id, cc.alias) ELSE cc.id END as slug '.
			' FROM #__phocagallery_categories AS cc' .
			' WHERE cc.id = '. (int) $id;
		$this->_db->setQuery($query, 0, 1);
		$categoryParams = $this->_db->loadObject();
		
		return $categoryParams;
	}
}
?>