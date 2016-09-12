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


class PhocagalleryModelCategories extends JModel
{
	var $_data 					= null;
	var $_data_filename 		= null;
	var $_data_categories 		= null;
	var $_data_outcome			= null;
	var $_data_outcome_array	= array();
	var $_total 				= null;
	var $_category_ordering		= null;

	function __construct()
	{
		parent::__construct();
	}

	function getData( $show_subcategories = 1, $show_empty_categories = 0, $hide_categories = '' )
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = $this->_buildQuery($show_subcategories, $show_empty_categories, $hide_categories);
			$this->_data = $this->_getList($query);
			
			$query_categories = $this->_buildQuerySubcategories();
			$this->_data_categories = $this->_getList($query_categories);
			
			
			$tree = array();
			$text = '';
			$this->_data_categories = PhocaGalleryHelper::CategoryTreeOption($this->_data_categories, $tree, 0, $text, -1);
		
			//$this->_data_categories = PhocaGalleryHelperFront::CategoryTreeCreating($this->_data_categories, $tree, 0);
			

			foreach ($this->_data_categories as $key => $value)
			{
				foreach ($this->_data as $key2 => $value2)
				{
					if ($value->value == $value2->id)
					{
						
						$this->_data_outcome 					= new JObject();
						$this->_data_outcome->id				= $value2->id;
						$this->_data_outcome->parent_id			= $value2->parent_id;
						$this->_data_outcome->title				= $value->text;
						$this->_data_outcome->title_self		= $value2->title;
						$this->_data_outcome->name				= $value2->name;
						$this->_data_outcome->alias				= $value2->alias;
						$this->_data_outcome->image				= $value2->image;
						$this->_data_outcome->section			= $value2->section;
						$this->_data_outcome->image_position	= $value2->image_position;
						$this->_data_outcome->description		= $value2->description;
						$this->_data_outcome->published			= $value2->published;
						$this->_data_outcome->editor			= $value2->editor;
						$this->_data_outcome->ordering			= $value2->ordering;
						$this->_data_outcome->access			= $value2->access;
						$this->_data_outcome->count				= $value2->count;
						$this->_data_outcome->params			= $value2->params;
						$this->_data_outcome->catid				= $value2->catid;
						$this->_data_outcome->numlinks			= $value2->numlinks;
						$this->_data_outcome->slug				= $value2->slug;
						$this->_data_outcome->hits				= $value2->hits;
						$this->_data_outcome->username			= $value2->username;
						$this->_data_outcome->ratingaverage		= $value2->ratingaverage;
						$this->_data_outcome->ratingcount		= $value2->ratingcount;
						$this->_data_outcome->link				= "";
						$this->_data_outcome->filename			= "";
						$this->_data_outcome->linkthumbnailpath	= "";
						
					//	$query_filename 				= $this->_buildQueryFilename($value2->id);
					//	$this->_data_filename 			= $this->_getList($query_filename);
					
						$this->_data_filename	= $this->_getRandomImageRecursive($value2->id);
						if (!empty($this->_data_filename))
						{
							$this->_data_outcome->filename	= $this->_data_filename->filename;
						}
						else
						{
							$this->_data_outcome->filename	= '';
						}
						
						
						$this->_data_outcome_array[] 	= $this->_data_outcome;
					}	
				}
			}
			return $this->_data_outcome_array;
		}
	}


	function getTotal() {
		if (empty($this->_total)) {
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}

	function _buildQuery( $show_subcategories = 1, $show_empty_categories = 0, $hide_categories = '' ) {
		$user =& JFactory::getUser();
		$gid = $user->get('aid', 0);
		
		//Display or hide subcategories in categories vies
		if ($show_subcategories == 1) {
			$hideSubCatSql = '';
		} else {
			$hideSubCatSql = ' AND cc.parent_id = 0';
		}
		
		$hideCat		= trim( $hide_categories );
		$hideCatArray	= explode( ';', $hide_categories );
		$hideCatSql		= '';
		if (is_array($hideCatArray)) {
			foreach ($hideCatArray as $value) {
				$hideCatSql .= ' AND cc.id != '. (int) trim($value) .' ';
			}
		}
		
		//Display or hide empty categories
		if ($show_empty_categories == 1) {
			$emptyCat = '';
		} else {
			$emptyCat = ' AND a.published = 1';
		}
		
		$categoryOrdering = $this->_getCategoryOrdering();
				
		$query = 'SELECT cc.*, a.catid, COUNT(a.id) AS numlinks, u.username AS username, r.count AS ratingcount, r.average AS ratingaverage,'
		. ' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(\':\', cc.id, cc.alias) ELSE cc.id END as slug'
		. ' FROM #__phocagallery_categories AS cc'
		//. ' LEFT JOIN #__phocagallery AS a ON a.catid = cc.id'
		. ' LEFT JOIN #__phocagallery AS a ON a.catid = cc.id and a.published = 1'
		. ' LEFT JOIN #__phocagallery_user_category AS uc ON uc.catid = cc.id'
		. ' LEFT JOIN #__users AS u ON u.id = uc.userid'
		. ' LEFT JOIN #__phocagallery_votes_statistics AS r ON r.catid = cc.id'
		. ' WHERE cc.published = 1'
		//. ' AND (a.published = 1 OR a.id is null)'
		. $emptyCat
		. $hideSubCatSql
		. $hideCatSql
		. ' GROUP BY cc.id'
		. ' ORDER BY cc.'.$categoryOrdering;
		
		return $query;
	}
	
	function _getCategoryOrdering() {
		if (empty($this->_category_ordering)) {
			global $mainframe;
			$params						= &$mainframe->getParams();
			$ordering					= $params->get( 'category_ordering', 1 );
			$this->_category_ordering 	= $this->_getOrderingText($ordering);
		}
		return $this->_category_ordering;
	}
	
	function _getOrderingText ($ordering) {
		switch ((int)$ordering) {
			case 2:
				$orderingOutput	= 'ordering DESC';
			break;
			
			case 3:
				$orderingOutput	= 'title ASC';
			break;
			
			case 4:
				$orderingOutput	= 'title DESC';
			break;
			
			case 5:
				$orderingOutput	= 'date ASC';
			break;
			
			case 6:
				$orderingOutput	= 'date DESC';
			break;
			
			case 7:
				$orderingOutput	= 'id ASC';
			break;
			
			case 8:
				$orderingOutput	= 'id DESC';
			break;
		
			case 1:
			default:
				$orderingOutput = 'ordering ASC';
			break;
		}
		return $orderingOutput;
	}
	
	function _getRandomImageRecursive($categoryid)
    {
        // We need to get a list of all phocagallery in the given category
        $query = 'SELECT id, filename' .
            ' FROM #__phocagallery' .
            ' WHERE catid = '.(int) $categoryid.
            ' AND published = 1'.
            ' ORDER BY RAND()';     
        $images = $this->_getList($query, 0, 1);
       
        if (count($images) == 0)
        {
            $image->filename = "";
            $subCategories = $this->_getRandomCategory($categoryid);
			
            foreach ($subCategories as $subCategory)
            {
                $image = $this->_getRandomImageRecursive($subCategory->id);
                if ($image->filename != "")
                {
                    break;
                }
            }
        }
        else
        {
            $image = $images[0] ;
        }

        return $image;
    }
	
	  function _getRandomCategory($parentid)
    {
        $query = 'SELECT id' .
            ' FROM #__phocagallery_categories AS c' .
            ' WHERE c.parent_id = '.(int) $parentid.
            ' AND c.published = 1' .
            ' ORDER BY RAND()';

        return $this->_getList($query);
    }

	function _buildQuerySubcategories(){
		$categoryOrdering = $this->_getCategoryOrdering();

		$query = 'SELECT cc.title AS text, cc.id AS value, cc.parent_id as parentid'
		. ' FROM #__phocagallery_categories AS cc'
		. ' WHERE cc.published = 1'
		. ' ORDER BY cc.'.$categoryOrdering;
		return $query;
	}
	
	
}
?>