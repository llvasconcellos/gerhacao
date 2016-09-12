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
require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_phocagallery'.DS.'helpers'.DS.'phocagalleryrate.php' );

/**
 * Phoca Gallery Model Category
 */
class PhocagalleryModelCategory extends JModel
{

	var $_id 				= null;
	var $_data 				= null;
	var $_subcategories 	= null;
	var $_total 			= null;
	var $_category 			= null;
	var $_category_params 	= null;
	var $_category_id	 	= null;
	var $_category_alias	= null;
	var $_category_ordering	= null;
	var $_image_ordering	= null;

	/**
	 * Method construct
	 */
	function __construct()
	{
		global $mainframe;

		parent::__construct();

		$config = JFactory::getConfig();		
		
		$paramsC 			= JComponentHelper::getParams('com_phocagallery') ;
		$defaultPagination	= $paramsC->get( 'default_pagination', '20' );
		
		// Get the pagination request variables
		$this->setState('limit', $mainframe->getUserStateFromRequest('com_phocagallery.limit', 'limit', $defaultPagination, 'int'));
		$this->setState('limitstart', JRequest::getVar('limitstart', 0, '', 'int'));

		// In case limit has been changed, adjust limitstart accordingly
		$this->setState('limitstart', ($this->getState('limit') != 0 ? (floor($this->getState('limitstart') / $this->getState('limit')) * $this->getState('limit')) : 0));

		// Get the filter request variables
		$this->setState('filter_order', JRequest::getCmd('filter_order', 'ordering'));
		$this->setState('filter_order_dir', JRequest::getCmd('filter_order_Dir', 'ASC'));

		$id = JRequest::getVar('id', 0, '', 'int');
		$this->setId((int)$id);

	}

	/**
	 * Method to set id
	 * @param int $id Set id of an image
	 */
	function setId($id)
	{
		// Set category ID and wipe data
		$this->_id			= $id;
		$this->_category	= null;
	}
	
	/**
	 * Method to get data from database
	 * @param int $display_subcat_page
	 * @param int $display_back_button
	 * @return array Data
	 */
	function getData( $display_subcat_page = 0, $display_subcat_page_cv = 0, $display_back_button = 1, $display_back_button_cv = 1, $display_categories_back_button = 1, $display_categories_back_button_cv = 1, $display_access_category = 1, $display_unpublished = 0, $display_categories_cv = 0)
	{
		$menu 	= &JSite::getMenu();
		$user 	=& JFactory::getUser();
		
		// SET BACK BUTTON TO CATEGORIES VIEW
		$items	= $menu->getItems('link', 'index.php?option=com_phocagallery&view=categories');

		$itemId	= 0;
		if(isset($items[0])) {
			$itemId = $items[0]->id;
		}	
		
		$backLink = 'index.php?option=com_phocagallery&view=categories&Itemid='.$itemId;

			
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{ 
		   $count = 0 ;

		   // ----------------------------------------
		   // Super folders STANDARD
		   // ----------------------------------------
		   $parentcategory = $this->_getParentCategory();   

		   if ($display_back_button == 1) {
				if ( $parentcategory ) {
					$this->_data[$count] = $parentcategory ;
					$item =& $this->_data[$count];
		
					// USER RIGHT - ACCESS =======================================
					// Should be the link to parentcategory displayed
					$rightDisplay = PhocaGalleryHelper::getUserRight ($parentcategory->params, 'accessuserid', $item->access, $user->get('aid', 0), $user->get('id', 0), $display_access_category);
					
					// Display Key Icon (in case we want to display unaccessable categories in list view)
					$rightDisplayKey  = 1;
					if ($display_access_category == 1) {
						// we simulate that we want not to display unaccessable categories
						// so we get rightDisplayKey = 0 then the key will be displayed
						if (isset($parentcategory->params)) {
							$rightDisplayKey = PhocaGalleryHelper::getUserRight ($parentcategory->params, 'accessuserid', $item->access, $user->get('aid', 0), $user->get('id', 0), 0);
						}
					}
					

					// ===========================================================
				
					if ($rightDisplay > 0) {
						$item->slug = $item->id.':'.$item->alias;
						$item->item_type = "parentfolder";
						$file_thumbnail = PhocaGalleryHelperFront::displayBackFolder('medium', $rightDisplayKey);
						$item->linkthumbnailpath  = $file_thumbnail['rel'];
						$item->numlinks = 0;// We are in category view
						$count++;
					}
				} else { // Back button to categories list if it exists
					if ($itemId > 0 && $display_categories_back_button == 1) {
						$this->_data[$count] = new JObject();
						$item =& $this->_data[$count];
						$item->link = $backLink;
						$item->title= JTEXT::_('Category List');
						$item->item_type = "categorieslist";
						$file_thumbnail = PhocaGalleryHelperFront::displayBackFolder('medium', 1);
						$item->linkthumbnailpath  = $file_thumbnail['rel'];
						$item->numlinks = 0;// We are in category view
						$count++;
					}
				}
			}
			
			
		   // ----------------------------------------
		   // Super folders Categories View in Category View
		   // ----------------------------------------
		   $parentcategory = $this->_getParentCategory();   

		   if ($display_back_button_cv == 1 && $display_categories_cv == 1) {
				if ( $parentcategory ) {
					$this->_data[$count] = $parentcategory ;
					$item =& $this->_data[$count];
		
					// USER RIGHT - ACCESS =======================================
					// Should be the link to parentcategory displayed
					$rightDisplay = PhocaGalleryHelper::getUserRight ($parentcategory->params, 'accessuserid', $item->access, $user->get('aid', 0), $user->get('id', 0), $display_access_category);
					
					// Display Key Icon (in case we want to display unaccessable categories in list view)
					$rightDisplayKey  = 1;
					if ($display_access_category == 1) {
						// we simulate that we want not to display unaccessable categories
						// so we get rightDisplayKey = 0 then the key will be displayed
						if (isset($parentcategory->params)) {
							$rightDisplayKey = PhocaGalleryHelper::getUserRight ($parentcategory->params, 'accessuserid', $item->access, $user->get('aid', 0), $user->get('id', 0), 0);
						}
					}
					

					// ===========================================================
					
					if ($rightDisplay > 0) {
						$item->slug = $item->id.':'.$item->alias;
						$item->item_type = "parentfoldercv";
						$file_thumbnail = PhocaGalleryHelperFront::displayBackFolder('medium', $rightDisplayKey);
						$item->linkthumbnailpath  = $file_thumbnail['rel'];
						$item->numlinks = 0;// We are in category view
						$count++;
					}
				} else { // Back button to categories list if it exists
					if ($itemId > 0 && $display_categories_back_button_cv == 1) {
						$this->_data[$count] = new JObject();
						$item =& $this->_data[$count];
						$item->link = $backLink;
						$item->title= JTEXT::_('Category List');
						$item->item_type = "categorieslistcv";
						$file_thumbnail = PhocaGalleryHelperFront::displayBackFolder('medium', 1);
						$item->linkthumbnailpath  = $file_thumbnail['rel'];
						$item->numlinks = 0;// We are in category view
						$count++;
					}
				}
			}
		  
			// ----------------------------------------
			// Sub folders STANDARD
			// ----------------------------------------
			if ($display_subcat_page == 1)//display subcategories on every page
			{	
				$query = $this->_buildSubCategoriesQuery();
				$this->_subcategories = $this->_getList($query);
				$total = count($this->_subcategories);
			  
				for($i = 0; $i < $total; $i++) {
					// USER RIGHT - ACCESS =======================================
					$rightDisplay = PhocaGalleryHelper::getUserRight ($this->_subcategories[$i]->params, 'accessuserid', $this->_subcategories[$i]->access, $user->get('aid', 0), $user->get('id', 0), $display_access_category);
					
					// Display Key Icon (in case we want to display unaccessable categories in list view)
					$rightDisplayKey  = 1;
					if ($display_access_category == 1) {
						// we simulate that we want not to display unaccessable categories
						// so we get rightDisplayKey = 0 then the key will be displayed
						if (isset($this->_subcategories[$i]->params)) {
							$rightDisplayKey = PhocaGalleryHelper::getUserRight ($this->_subcategories[$i]->params, 'accessuserid', $this->_subcategories[$i]->access, $user->get('aid', 0), $user->get('id', 0), 0);
						}
					}

					
					// ===========================================================
				
					if ($rightDisplay > 0) {
					
						$this->_data[$count] = $this->_subcategories[$i];
						$item =& $this->_data[$count] ;
						
						$item->slug = $item->id.':'.$item->alias;
						$item->item_type = "subfolder";
						$random = $this->_getRandomImageRecursive($item->id);
						
						$numlinks = $this->countItem($item->id);
						if (isset($numlinks[0]) && $numlinks[0] > 0) {
							$item->numlinks = (int)$numlinks[0];
						} else {
							$item->numlinks = 0;
						}
						$file_thumbnail = PhocaGalleryHelperFront::displayThumbOrFolder($random->filename, 'medium', $rightDisplayKey, 'display_icon_random_image');
						$item->linkthumbnailpath  = $file_thumbnail['rel'];
						$count++;
						
					}
				}
				
			}
			
			// ----------------------------------------
			// Sub folders Categories View in Category View
			// ----------------------------------------
			if ($display_subcat_page_cv == 1 && $display_categories_cv == 1)//display subcategories on every page
			{	
				$query = $this->_buildSubCategoriesQuery();
				$this->_subcategories = $this->_getList($query);
				$total = count($this->_subcategories);
			  
				for($i = 0; $i < $total; $i++) {
					
					
					// USER RIGHT - ACCESS =======================================
					$rightDisplay = PhocaGalleryHelper::getUserRight ($this->_subcategories[$i]->params, 'accessuserid', $this->_subcategories[$i]->access, $user->get('aid', 0), $user->get('id', 0), $display_access_category);
					
					// Display Key Icon (in case we want to display unaccessable categories in list view)
					$rightDisplayKey  = 1;
					if ($display_access_category == 1) {
						// we simulate that we want not to display unaccessable categories
						// so we get rightDisplayKey = 0 then the key will be displayed
						if (isset($this->_subcategories[$i]->params)) {
							$rightDisplayKey = PhocaGalleryHelper::getUserRight ($this->_subcategories[$i]->params, 'accessuserid', $this->_subcategories[$i]->access, $user->get('aid', 0), $user->get('id', 0), 0);
						}
					}

					
					// ===========================================================
				 
					if ($rightDisplay > 0) {
						$this->_data[$count] = $this->_subcategories[$i];
						$item =& $this->_data[$count] ;
					
						$item->slug = $item->id.':'.$item->alias;
						$item->item_type = "subfoldercv";
						$random = $this->_getRandomImageRecursive($item->id);
						
						$numlinks = $this->countItem($item->id);
						if (isset($numlinks[0]) && $numlinks[0] > 0) {
							$item->numlinks = (int)$numlinks[0];
						} else {
							$item->numlinks = 0;
						}
						$file_thumbnail = PhocaGalleryHelperFront::displayThumbOrFolder($random->filename, 'medium', $rightDisplayKey, 'display_icon_random_image_cv');
						$item->linkthumbnailpath  = $file_thumbnail['rel'];
						$count++;
						
					}
				}
				
			}
	
			// ----------------------------------------
			// Images
			// ----------------------------------------
			$query = $this->_buildQuery($display_unpublished);
			$images = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));

			$parentcategory = $this->_getParentCategory(); 
			
			$total = count($images);
			
			for($i = 0; $i < $total; $i++) {
				
				$this->_data[$count] = $images[$i] ;
				$item =& $this->_data[$count];
				$item->slug = $item->id.':'.$item->alias;
				$item->item_type = "image";
				// Get file thumbnail or No Image
				$file_thumbnail    = PhocaGalleryHelperFront::displayFileOrNoImage($item->filename, 'medium');
				$item->linkthumbnailpath  = $file_thumbnail['rel'];
				
				if (isset($parentcategory->params)) {
					$item->parentcategoryparams = $parentcategory->params;
				}
				$count++;
			}
	   
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
			$this->_pagination = new PhocaGalleryPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}

	
	/**
	 * Method to display the category with images
	 * only in case the category is accessable for the user who wants to see it
	 * called from view.html.php
	 */
	function getCategory()
	{
		global $mainframe;
		// Load the Category data
		if ($this->_loadCategory())
		{
			// Initialize some variables
			$user = &JFactory::getUser();

			// Make sure the category is published
			if (!$this->_category->published) {
				JError::raiseError(404, JText::_("Resource Not Found"));
				return false;
			}
			
			// USER RIGHT - ACCESS =======================================
			$rightDisplay	= 1;//default is set to 1 (all users can see the category)
			if (isset($this->_category->params)) {
				$rightDisplay = PhocaGalleryHelper::getUserRight ($this->_category->params, 'accessuserid', $this->_category->access, $user->get('aid', 0), $user->get('id', 0), 0);
			}
			
			if ($rightDisplay == 0) {
				$mainframe->redirect(JRoute::_('index.php?option=com_user&view=login', false), JText::_("ALERTNOTAUTH"));
				exit;
			}
			// ============================================================
		}
		return $this->_category;
	}
	
	/**
	 * Method to get only Category Params (delete, publish, upload icons)
	 * called from view.html.php or from controller.php
	 */
	function getCategoryParams($id)
	{
		// Load the Category data
		$this->_loadCategoryParams($id);
		return $this->_category_params;
	}
	

	function _loadCategoryParams($id) // id is catid
	{
		if (empty($this->_category_params))
		{
			
			// current category info
			$query = 'SELECT c.params,c.access,' .
				' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as slug '.
				' FROM #__phocagallery_categories AS c' .
				' WHERE c.id = '. (int) $id;
			$this->_db->setQuery($query, 0, 1);
			$this->_category_params = $this->_db->loadObject();
		}
		return true;
	}
	
	/**
	 * Method to get only Category Id (if we know the id of an image but don't know the id of category)
	 * called from controller.php
	 */
	function getCategoryIdFromImageId($id) // id is id
	{
		// Load the Category data
		$this->_loadCategoryId($id);
		return $this->_category_id;
	}
	
	function _loadCategoryId($id)
	{
		if (empty($this->_category_id))
		{
			
			// current category info
			$query = 'SELECT a.catid' .
				' FROM #__phocagallery AS a' .
				' WHERE a.id = '. (int) $id;
			$this->_db->setQuery($query, 0, 1);
			$this->_category_id = $this->_db->loadObject();
		}
		return true;
	}
	
	/**
	 * Method to get only Category Alias
	 * called from controller.php
	 */
	function getCategoryAlias($id) // id is catid
	{
		// Load the Category data
		$this->_loadCategoryAlias($id);
		return $this->_category_alias;
	}
	
	function _loadCategoryAlias($id)
	{
		if (empty($this->_category_alias)) {
			
			
			// current category info
			$query = 'SELECT c.alias' .
				' FROM #__phocagallery_categories AS c' .
				' WHERE c.id = '. (int) $id;
			$this->_db->setQuery($query, 0, 1);
			$this->_category_alias = $this->_db->loadObject();
		}
		return true;
	}
	
	function _loadCategory() {
		if (empty($this->_category)){
			// current category info
			$query = 'SELECT c.*,' .
				' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as slug '.
				' FROM #__phocagallery_categories AS c' .
				' WHERE c.id = '. (int) $this->_id;
			$this->_db->setQuery($query, 0, 1);
			$this->_category = $this->_db->loadObject();
		}
		return true;
	}

	function _buildQuery($display_unpublished = 0) {
		$imageOrdering = $this->_getImageOrdering();
		
		if ($display_unpublished == 0 ) {
			$published  = ' AND published = 1';
		} else {
			$published  = '';
		}
		
		// We need to get a list of all phocagallery in the given category
		$query = 'SELECT a.*' .
			' FROM #__phocagallery AS a' .
			' WHERE a.catid = '.(int) $this->_id.
			$published .
			' ORDER BY a.'.$imageOrdering;
		return $query;
	}
	
	function _buildSubCategoriesQuery() {
	
		$categoryOrdering = $this->_getCategoryOrdering();
		// We need to get a list of all phocagallery in the given category
		$query = 'SELECT *' .
			' FROM #__phocagallery_categories AS c' .
			' WHERE c.parent_id = '.(int) $this->_id.
			' AND c.published = 1' .
			' AND c.id <> '.(int) $this->_id.
	/*		' AND (SELECT COUNT(*)' .
        ' FROM #__phocagallery as g' . 
        ' WHERE g.catid = c.id' .
        ' AND published = 1) > 0'.*/
		//	' GROUP BY c.id'.
			' ORDER BY c.'.$categoryOrdering;

		return $query;
	}
/*	
	function _getRandomImage($categoryid)
	{
		// We need to get a list of all phocagallery in the given category
		$query = 'SELECT *' .
			' FROM #__phocagallery' .
			' WHERE catid = '.(int) $categoryid.
			' AND published = 1'.
			' ORDER BY RAND()';      
        $images = $this->_getList($query, 0, 1);
		
		if (count($images) == 0)
		{
			$image->filename = "";
			return $image;
		}
		else
		{
			return $images[0] ;
		}
	}
*/

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
	
	function _getParentCategory() {
		
		$categoryOrdering = $this->_getCategoryOrdering();
		$query = 'SELECT *' .
			' FROM #__phocagallery_categories' .
			' WHERE id = '.(int) $this->_category->parent_id.
			' AND published = 1' .
			' AND id <> '.(int) $this->_category->id.
			' ORDER BY '.$categoryOrdering;
		$this->_db->setQuery($query, 0, 1);
		$parent_category = $this->_db->loadObject();
			
		return $parent_category ;
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
	
	function _getImageOrdering() {
		if (empty($this->_image_ordering)) {
	
			global $mainframe;
			$params						= &$mainframe->getParams();
			$ordering					= $params->get( 'image_ordering', 1 );
			$this->_image_ordering 		= $this->_getOrderingText($ordering);

		}
		return $this->_image_ordering;
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
	
	/**
	 * Method to display multiple select box
	 * @param int $id 
	 * @return array the link to some VirtueMart product
	 */
	
	function getVmLink($id)
	{

		if (is_file( JPATH_SITE.DS.'components'.DS.'com_virtuemart'.DS.'virtuemart_parser.php')) {
			require_once( JPATH_SITE.DS.'components'.DS.'com_virtuemart'.DS.'virtuemart_parser.php' );
		} else {
			return 'Error4';
		}
		if (is_file( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'virtuemart.cfg.php')) {
			require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'virtuemart.cfg.php' );
		} else {
			return 'Error5';
		}
		if (VM_TABLEPREFIX && FLYPAGE) {
			$vmPrefixC 		= VM_TABLEPREFIX;
			$flyPageC		= FLYPAGE;
			$checkStockC	= CHECK_STOCK;
			$showOutStockC	= PSHOP_SHOW_OUT_OF_STOCK_PRODUCTS;
		} else {
			return 'Error3';
		}
		
		
		if( $checkStockC && $showOutStockC != "1") {
		    $checkStockSql = " AND product_in_stock > 0 \n";
	    } else {
			$checkStockSql = '';
		}
		
		// We check publish adn if the product is on the stock (if the check is enabled in VM)
		$query = 'SELECT product_publish' .
			' FROM #__'.$vmPrefixC.'_product'.
			' WHERE product_id = '.(int) $id.
			$checkStockSql;
	
		$this->_db->setQuery($query);
		$publish = $this->_db->loadObject();
	
		if (isset($publish->product_publish) && $publish->product_publish == 'Y') {
			$query = 'SELECT category_id' .
			' FROM #__'.$vmPrefixC.'_product_category_xref'.
			' WHERE product_id = '.(int) $id;
	
			$this->_db->setQuery($query);
			$categoryId = $this->_db->loadObject();
		
			if (isset($categoryId->category_id)) {
			
				$flypage 	= $this->_getVmFlypage($vmPrefixC, $flyPageC, $categoryId->category_id);
				$itemId		= $this->_getVmItemid();
				$vmLink 	= 'index.php?option=com_virtuemart&page=shop.product_details&flypage='.$flypage.'&product_id='.$id.'&Itemid='.$itemId;
				
				return $vmLink;
			} else {
				return 'Error2';
			}
		
		} else {
			return 'Error1';
		}
	}
	
	function _getVmFlypage($vmPrefixC, $flyPageC, $category_id)
	{
		$query = 'SELECT category_flypage' .
			' FROM #__'.$vmPrefixC.'_category'.
			' WHERE category_id = '.(int) $category_id;
		$this->_db->setQuery($query);
		$flypage = $this->_db->loadObject();
		
		if (!isset($flypage->category_flypage) || (isset($flypage->category_flypage) && $flypage->category_flypage =='')) {
			// We don't have flypage, so we try the parent_id
			$query = 'SELECT category_parent_id' .
			' FROM #__'.$vmPrefixC.'_category_xref'.
			' WHERE category_child_id = '.(int) $category_id;
			$this->_db->setQuery($query);
			$parentId = $this->_db->loadObject();
			if (isset($parentId->category_parent_id) && $parentId->category_parent_id > 0) {
				//recursive function to find the last parent_id
				$flypageR = $this->_getVmFlypage($vmPrefixC, $flyPageC, $parentId->category_parent_id);
			} else {
				// we still don't have the 
				$flypageR = $flyPageC; // the constant from VM config
			}
			return $flypageR;
			
		} else {
			
			return $flypage->category_flypage;
		}
	}
	
	function _getVmItemid()
	{
		// Set Itemid id, exists this link in Menu?
		$menu 	= &JSite::getMenu();
		$itemVM	= $menu->getItems('link', 'index.php?option=com_virtuemart');
		

		if(isset($itemVM[0])) {
			$itemId = $itemVM[0]->id;
		} else {
			$itemId = 0;
		}
	
		return $itemId;
	}
	
	
	function delete($id = 0)
	{
		// Get all filenames we want to delete from database, we delete all thumbnails from server of this file
		$queryd = 'SELECT filename as filename FROM #__phocagallery WHERE id ='.(int)$id;
		$this->_db->setQuery($queryd);
		$file_object = $this->_db->loadObjectList();

		$query = 'DELETE FROM #__phocagallery'
			. ' WHERE id ='.(int)$id;
			
		$this->_db->setQuery( $query );
		if(!$this->_db->query()) {
			$this->setError('Database Error 2');
			return false;
		}
		
		// Delete thumbnails - medium and large, small from server
		// All id we want to delete - gel all filenames
		foreach ($file_object as $key => $value) {
			//The file can be stored in other category - don't delete it from server because other category use it
			$querys = "SELECT id as id FROM #__phocagallery WHERE filename='".$value->filename."' ";
			$this->_db->setQuery($queryd);
			$same_file_object = $this->_db->loadObject();
			
			//same file in other category doesn't exist - we can delete it
			if (!$same_file_object){
				//Delete all thumbnail files but not original
				PhocaGalleryHelper::deleteFileThumbnail($value->filename, 1, 1, 1);
				PhocaGalleryHelper::deleteFile($value->filename);
			}
		}
		return true;
	}

	function publish($id = 0, $publish = 1)
	{
		
		$user 	=& JFactory::getUser();
		$query = 'UPDATE #__phocagallery'
			. ' SET published = '.(int) $publish
			. ' WHERE id = '.$id
			. ' AND ( checked_out = 0 OR ( checked_out = '.(int) $user->get('id').' ) )'
		;
		
		$this->_db->setQuery( $query );
		if (!$this->_db->query()) {
			$this->setError('Database Error 2');
			return false;
		}
		return true;
	}
	
	function countItem($catid = 0)
	{
		$query = 'SELECT COUNT(id) FROM #__phocagallery'
			. ' WHERE published = 1'
			. ' AND catid = '.$catid;
		;
		$this->_db->setQuery( $query );
		if (!$this->_db->query()) {
			$this->setError('Database Error 3');
			return false;
		}
		return $this->_db->loadRow();
	}
	
	
	function store($data, $return)
	{
		//If this file doesn't exists don't save it
		if (!PhocaGalleryHelper::existsFileOriginal($data['filename'])) {
			$this->setError('File not exists');
			return false;
		}
		
		//If there is no title and no alias, use filename as title and alias
		if (!isset($data['title']) || (isset($data['title']) && $data['title'] == '')) {
			$data['title'] = PhocaGalleryHelper::getTitleFromFilenameWithoutExt($data['filename']);
		}

		if (!isset($data['alias']) || (isset($data['alias']) && $data['alias'] == '')) {
			$data['alias'] = PhocaGalleryHelper::getTitleFromFilenameWithoutExt($data['filename']);
		}
		
		//clean alias name (no bad characters)
		$data['alias'] = PhocaGalleryHelper::getAliasName($data['alias']);
		
		$row =& $this->getTable('phocagallery');
		
		// Bind the form fields to the Phoca gallery table
		if (!$row->bind($data)) {
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
		
		//Create thumbnail small, medium, large		
		$path 		= PhocaGalleryHelper::getPathSet();
		
		
		$returnFrontMessage = PhocaGalleryHelper::getOrCreateThumbnail($path['orig_abs_ds'], $row->filename, $return, 1, 1, 1, 1);
		
		if ($returnFrontMessage == 'Success') {
			
			return true;
		} else {
			return false;
		}
		
	}
	
	
	function getVotesStatistics($id) {
		$query = 'SELECT vs.count AS count, vs.average AS average'
				.' FROM #__phocagallery_votes_statistics AS vs'
			    .' WHERE vs.catid = '.(int) $id;
		$this->_db->setQuery($query, 0, 1);
		$votesStatistics = $this->_db->loadObject();
			
		return $votesStatistics;
	}
	
	function checkUserVote($catid, $userid) {
		$query = 'SELECT v.id AS id'
			    .' FROM #__phocagallery_votes AS v'
			    .' WHERE v.catid = '. (int)$catid 
				.' AND v.userid = '. (int)$userid;
		$this->_db->setQuery($query, 0, 1);
		$checkUserVote = $this->_db->loadObject();
			
		if ($checkUserVote) {
			return true;
		}
		return false;
	}
	
	function rate($data) {
		$row =& $this->getTable('phocagalleryvotes');
		
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		$row->date 		= gmdate('Y-m-d H:i:s');
		
		$row->published = 1;

		if (!$row->id) {
			$where = 'catid = ' . (int) $row->catid ;
			$row->ordering = $row->getNextOrder( $where );
		}

		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		// Update the Vote Statistics
		if (!PhocaGalleryHelperRate::updateVoteStatistics( $data['catid'])) {
			return false;
		}
		
		return true;
	}
	
	function checkUserComment($catid, $userid) {
		$query = 'SELECT co.id AS id'
			    .' FROM #__phocagallery_comments AS co'
			    .' WHERE co.catid = '. (int)$catid 
				.' AND co.userid = '. (int)$userid;
		$this->_db->setQuery($query, 0, 1);
		$checkUserComment = $this->_db->loadObject();
			
		if ($checkUserComment) {
			return true;
		}
		return false;
	}
	
	function comment($data) {
		$row =& $this->getTable('phocagallerycomments');
		
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		$row->date 		= gmdate('Y-m-d H:i:s');
		
		$row->published = 1;

		if (!$row->id) {
			$where = 'catid = ' . (int) $row->catid ;
			$row->ordering = $row->getNextOrder( $where );
		}

		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		return true;
	}
	
	
	function displayComment($catid) {
		$query = 'SELECT co.id AS id, co.title AS title, co.comment AS comment, co.date AS date, u.username AS username'
			    .' FROM #__phocagallery_comments AS co'
				.' LEFT JOIN #__users AS u ON u.id = co.userid '
			    .' WHERE co.catid = '. (int)$catid
				.' AND co.published = 1'
				.' ORDER by ordering';
		$this->_db->setQuery($query);
		$commentItem = $this->_db->loadObjectList();
			
		return $commentItem;
	}
	
	function getGeotagging($catId) {
		
		global $mainframe;
		
		$query = 'SELECT c.params,c.title,' .
			' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END as slug '.
			' FROM #__phocagallery_categories AS c' .
			' WHERE c.id = '. (int) $catId;
		$this->_db->setQuery($query, 0, 1);
		$params = $this->_db->loadObject();
		
		$geotagging = array();
		
		if (isset($params)) {
			$longitude	= PhocaGalleryHelper::getParamsArray($params->params, 'longitude');
			$latitude	= PhocaGalleryHelper::getParamsArray($params->params, 'latitude');
			$zoom		= PhocaGalleryHelper::getParamsArray($params->params, 'zoom');
			$geotitle	= PhocaGalleryHelper::getParamsArray($params->params, 'geotitle');
			
			if (!isset($longitude[0]) || (isset($longitude[0]) && ($longitude[0] == '' || $longitude[0] == 0))) {
				$geotagging['longitude'] = '';
			} else {
				$geotagging['longitude'] = $longitude[0];
			}
		
			if (!isset($latitude[0]) || (isset($latitude[0]) && ($latitude[0] == '' || $latitude[0] == 0))) {
				$geotagging['latitude'] = '';
			} else {
				$geotagging['latitude'] = $latitude[0];
			}
			
			if (!isset($zoom[0]) || (isset($zoom[0]) && ($zoom[0] == '' || $zoom[0] == 0))) {
				$geotagging['zoom'] = 2;
			} else {
				$geotagging['zoom'] = $zoom[0];
			}
			
			if (!isset($geotitle[0]) || (isset($geotitle[0]) && $geotitle[0] == '')) {
				$geotagging['geotitle'] = $params->title;
			} else {
				$geotagging['geotitle'] = $geotitle[0];
			}
		} else {
			$geotagging['longitude']	= '';
			$geotagging['latitude']		= '';
			$geotagging['zoom']			= 2;
			$geotagging['geotitle'] 	= '';
		}
		
		// Image (no image because we are in category not in detail
		$geotagging['thumbnail'] 	= '';
		$geotagging['description'] 	= '';
		
		return $geotagging;	
	}
	
	function getCountImages($catId, $published = 1) {
		global $mainframe;
		
		$query = 'SELECT COUNT(i.id) AS countimg'
			.' FROM #__phocagallery AS i'
			.' WHERE i.catid = '. (int) $catId
			.' AND i.published ='.(int)$published;
		$this->_db->setQuery($query, 0, 1);
		$countPublished = $this->_db->loadObject();
		
		return $countPublished;
	}
	
	function getHits($catId) {
		global $mainframe;
		
		$query = 'SELECT cc.hits AS catviewed'
			.' FROM #__phocagallery_categories AS cc'
			.' WHERE cc.id = '. (int) $catId;
		$this->_db->setQuery($query, 0, 1);
		$categoryViewed = $this->_db->loadObject();
		
		return $categoryViewed;
	}
	
	function hit($id) {
	
		global $mainframe;
		$table = & JTable::getInstance('phocagalleryc', 'Table');
		$table->hit($id);
		return true;
	}
	
	function getStatisticsImages($catId, $order, $order2 = 'ASC', $limit = 3) {
	
		$query = 'SELECT i.*'
			.' FROM #__phocagallery AS i'
			.' WHERE i.catid = '.(int) $catId
			.' AND i.published = 1'
			.' ORDER BY '.$order.' '.$order2;
			
		$this->_db->setQuery($query, 0, $limit);
		$statistics = $this->_db->loadObjectList();
		$item = array();
	 
			$count = 0;
			$total = count($statistics);
			for($i = 0; $i < $total; $i++) {
				$statisticsData[$count] = $statistics[$i] ;
				// PHP 4 - [$i]
				$item[$i] 				=& $statisticsData[$count];
				$item[$i]->slug 		= $item[$i]->id.':'.$item[$i]->alias;
				$item[$i]->item_type 	= "image";
				// Get file thumbnail or No Image
				$file_thumbnail    		= PhocaGalleryHelperFront::displayFileOrNoImage($item[$i]->filename, 'medium');
				$item[$i]->linkthumbnailpath  = $file_thumbnail['rel'];
			
				$count++;
			}
			
			
		return $item;
		// return $statistics // php 5
	}
}
?>