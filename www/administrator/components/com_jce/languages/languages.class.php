<?php
/**
* @version $Id: mambot.class.php 186 2005-09-19 09:09:25Z stingrey $
* @package Joomla
* @subpackage Installer
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

class jceLanguages extends mosDBTable {
	/** @var int */
	var $id					= null;
	/** @var varchar */
	var $name				= null;
	/** @var varchar */
	var $lang				= null;
	/** @var varchar */
	var $published			= null;

	function jceLanguages( &$db ) {
		$this->mosDBTable( '#__jce_langs', 'id', $db );
	}
}
?>
