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
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filter.input');

class TablePhocaGalleryUserCategory extends JTable
{

	var $id 				= null;
	var $catid 				= null;
	var $userid 			= null;

	function __construct(& $db) {
		parent::__construct('#__phocagallery_user_category', 'id', $db);
	}
}
?>