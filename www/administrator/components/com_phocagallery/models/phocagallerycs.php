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

class PhocaGalleryCpModelPhocaGalleryCs extends JModel
{

	var $_data 					= null;
	var $_data_categories 		= null;
	var $_data_outcome_array	= null;
	var $_total 				= null;
	var $_pagination 			= null;

	function __construct()
	{
		parent::__construct();
		
		global $mainframe, $option;		
		$context			= 'com_phocagallery.phocagalleryc.list.';
		// Get the pagination request variables
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
	//	$limitstart	= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
		$limitstart	= $mainframe->getUserStateFromRequest( $context.'limitstart',	'limitstart',	0, 'int' );

		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	function getData()
	{
		if (empty($this->_data) && empty ($this->_data_categories) )
		{
			$query 		 = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
			
			$query_sub 	 = $this->_buildQuerySubcategories();
			// We need all data (id, parentid,text) without limitation
			// because of creating correct tree, e.g: user hase pagination 10
			// and he hase one category and 15 subcategories, on the second
			// site he must get information about the parent category
			// not limitation required (but there are only 3 items)
		//	$this->_data_categories = $this->_getList($query_sub, $this->getState('limitstart'), $this->getState('limit'));
			$this->_data_categories = $this->_getList($query_sub);
			
	
			$user = &JFactory::getUser();
			
			$tree = array();
			$text = '';
			//$tree = PhocaGalleryHelper::CategoryTreeOption($this->_data_categories, $tree, 0, $text);
			//$this->_data_categories = PhocaGalleryHelper::CategoryTreeCreating($this->_data_categories, $tree, 0);
			$this->_data_categories = PhocaGalleryHelper::CategoryTreeOption($this->_data_categories, $tree, 0, $text, -1);

			foreach ($this->_data_categories as $key => $value)
			{
				foreach ($this->_data as $key2 => $value2)
				{
					if ($value->value == $value2->id)
					{
						
						$this->_data_outcome 					= new JObject();
						$this->_data_outcome->id				= $value2->id;
						$this->_data_outcome->parent_id			= $value2->parent_id;
						$this->_data_outcome->title				= $value->text;//$value2->title;
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
						$this->_data_outcome->hits				= $value2->hits;
						$this->_data_outcome->params			= $value2->params;
						$this->_data_outcome->checked_out		= $value2->checked_out;
						$this->_data_outcome->groupname			= $value2->groupname;
						$this->_data_outcome->parentname		= $value2->parentname;
						$this->_data_outcome->ratingavg			= $value2->ratingavg;
						$this->_data_outcome->usercatname		= $value2->usercatname;
				
						$this->_data_outcome_array[] 	= $this->_data_outcome;
					}	
				}
			}
			
			$this->_data = $this->_data_outcome_array;
			
		}
			
		
		
		return $this->_data;
	}

	function getTotal()
	{
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}

	function getPagination()
	{
		if (empty($this->_pagination))
		{

			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}

	function _buildQuery()
	{	
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy('cc.parent_id');
		// a. - category
		// cc. - parent category
		$query = ' SELECT a.*, cc.title AS parentname, u.name AS editor, g.name AS groupname, v.average AS ratingavg, ua.username AS usercatname '
			. ' FROM #__phocagallery_categories AS a '
			. ' LEFT JOIN #__users AS u ON u.id = a.checked_out '
			. ' LEFT JOIN #__groups AS g ON g.id = a.access '
			. ' LEFT JOIN #__phocagallery_categories AS cc ON cc.id = a.parent_id'
			. ' LEFT JOIN #__phocagallery_votes_statistics AS v ON v.catid = a.id'
			. ' LEFT JOIN #__phocagallery_user_category AS uc ON uc.catid = a.id'
			. ' LEFT JOIN #__users AS ua ON ua.id = uc.userid'
			. $where
			. $orderby
		;
		return $query;
		
	}
	
	
	function _buildQuerySubcategories() {
		$orderby	= $this->_buildContentOrderBy('a.parent_id');
		//build the list of categories
		$query = 'SELECT a.title AS text, a.id AS value, a.parent_id as parentid, v.average AS ratingavg'
		. ' FROM #__phocagallery_categories AS a'
		. ' LEFT JOIN #__phocagallery_votes_statistics AS v ON v.catid = a.id'
		//. ' WHERE cc.published = 1'
		. $orderby;
		
		return $query;
	}
	
	function _buildContentOrderBy($cc_or_a)
	{		
		global $mainframe;
		$context			= 'com_phocagallery.phocagalleryc.list.';
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'a.ordering',	'cmd' );// Category tree works with id not with ordering
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );

		
		
		if ($filter_order == 'a.ordering'){
			$orderby 	= ' ORDER BY  a.ordering '.$filter_order_Dir;
		} else if ($filter_order == 'category'){
			$orderby 	= ' ORDER BY ' .$cc_or_a . ', a.ordering ' .$filter_order_Dir;
		} else if ($filter_order == 'groupname'){
			$orderby 	= ' ORDER BY a.groupname , a.ordering ' .$filter_order_Dir;
		} else {
			$orderby 	= ' ORDER BY '.$filter_order . ' ' . $filter_order_Dir .  ' ';
		}

		return $orderby;
	}
	
	function _buildContentOrderByCategories()
	{
		global $mainframe;
		$context			= 'com_phocagallery.phocagalleryc.list.';
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'cc.ordering',	'cmd' );// Category tree works with id not with ordering
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );

		
	
		if ($filter_order == 'cc.ordering'){
			$orderby 	= ' ORDER BY  cc.ordering '.$filter_order_Dir;
		} else if ($filter_order == 'category'){
			$orderby 	= ' ORDER BY  cc.ordering'.$filter_order_Dir;
		} else {
			$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.' , cc.ordering ';
		}

		return $orderby;
	}

	function _buildContentWhere()
	{
		global $mainframe;
		$context			= 'com_phocagallery.phocagalleryc.list.';
		$filter_state		= $mainframe->getUserStateFromRequest( $context.'filter_state',		'filter_state',		'',				'word' );
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'a.ordering',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		$search				= $mainframe->getUserStateFromRequest( $context.'search',			'search',			'',				'string' );
		$search				= JString::strtolower( $search );
		$where = array();

		if ($search) {
			$where[] = 'LOWER(a.title) LIKE '.$this->_db->Quote('%'.$search.'%');
		}
		if ( $filter_state ) {
			if ( $filter_state == 'P' ) {
				$where[] = 'a.published = 1';
			} else if ($filter_state == 'U' ) {
				$where[] = 'a.published = 0';
			}
		}
		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );
		return $where;
	}
}
?>