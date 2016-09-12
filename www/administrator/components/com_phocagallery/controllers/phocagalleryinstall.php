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

class PhocaGalleryCpControllerPhocaGalleryinstall extends PhocaGalleryCpController
{
	function __construct() {
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'install'  , 'install' );
		$this->registerTask( 'upgrade'  , 'upgrade' );		
	}

	
	
	function install() {		
		$db			= &JFactory::getDBO();
		$dbPref 	= $db->getPrefix();
		$msgSQL 	= '';
		$msgFile	= '';
		$msgError	= '';
		
		// ------------------------------------------
		// PHOCAGALLERY
		// ------------------------------------------
		
		$query =' DROP TABLE IF EXISTS `'.$dbPref.'phocagallery`;';
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query=' CREATE TABLE `'.$dbPref.'phocagallery`('."\n";
		$query.=' `id` int(11) unsigned NOT NULL auto_increment,'."\n";
		$query.=' `catid` int(11) NOT NULL default \'0\','."\n";
		$query.=' `sid` int(11) NOT NULL default \'0\','."\n";
		$query.=' `title` varchar(250) NOT NULL default \'\','."\n";
		$query.='  `alias` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `filename` varchar(250) NOT NULL default \'\','."\n";
		$query.='  `description` text NOT NULL default \'\','."\n";
		$query.='  `date` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `hits` int(11) NOT NULL default \'0\','."\n";
		$query.='  `published` tinyint(1) NOT NULL default \'0\','."\n";
		$query.='  `checked_out` int(11) NOT NULL default \'0\','."\n";
		$query.='  `checked_out_time` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `ordering` int(11) NOT NULL default \'0\','."\n";
		$query.='  `params` text NOT NULL,'."\n";
		$query.='  `extlink1` text NOT NULL,'."\n";
		$query.='  `extlink2` text NOT NULL,'."\n";
		$query.='  PRIMARY KEY  (`id`),'."\n";
		$query.='  KEY `catid` (`catid`,`published`)'."\n";
		$query.=') TYPE=MyISAM CHARACTER SET `utf8`;'."\n";
		$query.=''."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		
		// ------------------------------------------
		// PHOCAGALLERY CATEGORIES
		// ------------------------------------------
		
		$query=' DROP TABLE IF EXISTS `'.$dbPref.'phocagallery_categories`;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query=' CREATE TABLE `'.$dbPref.'phocagallery_categories` ('."\n";
		$query.='  `id` int(11) NOT NULL auto_increment,'."\n";
		$query.='  `parent_id` int(11) NOT NULL default 0,'."\n";
		$query.='  `title` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `name` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `alias` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `image` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `section` varchar(50) NOT NULL default \'\','."\n";
		$query.='  `image_position` varchar(30) NOT NULL default \'\','."\n";
		$query.='  `description` text NOT NULL,'."\n";
		$query.='  `date` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `published` tinyint(1) NOT NULL default \'0\','."\n";
		$query.='  `checked_out` int(11) unsigned NOT NULL default \'0\','."\n";
		$query.='  `checked_out_time` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `editor` varchar(50) default NULL,'."\n";
		$query.='  `ordering` int(11) NOT NULL default \'0\','."\n";
		$query.='  `access` tinyint(3) unsigned NOT NULL default \'0\','."\n";
		$query.='  `count` int(11) NOT NULL default \'0\','."\n";
		$query.='  `hits` int(11) NOT NULL default \'0\','."\n";
		$query.='  `params` text NOT NULL,'."\n";
		$query.='  PRIMARY KEY  (`id`),'."\n";
		$query.='  KEY `cat_idx` (`section`,`published`,`access`),'."\n";
		$query.='  KEY `idx_access` (`access`),'."\n";
		$query.='  KEY `idx_checkout` (`checked_out`)'."\n";
		$query.=') TYPE=MyISAM CHARACTER SET `utf8`;';
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		// ------------------------------------------
		// PHOCAGALLERY VOTES
		// ------------------------------------------
		
		$query ='DROP TABLE IF EXISTS `'.$dbPref.'phocagallery_votes`;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		
		$query ='CREATE TABLE `'.$dbPref.'phocagallery_votes` ('."\n";
		$query.='  `id` int(11) NOT NULL auto_increment,'."\n";
		$query.='  `catid` int(11) NOT NULL default 0,'."\n";
		$query.='  `userid` int(11) NOT NULL default 0,'."\n";
		$query.='  `date` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `rating` tinyint(1) NOT NULL default \'0\','."\n";
		$query.='  `published` tinyint(1) NOT NULL default \'0\','."\n";
		$query.='  `checked_out` int(11) unsigned NOT NULL default \'0\','."\n";
		$query.='  `checked_out_time` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `ordering` int(11) NOT NULL default \'0\','."\n";
		$query.='  `params` text NOT NULL,'."\n";
		$query.='  PRIMARY KEY  (`id`)'."\n";
		$query.=') TYPE=MyISAM CHARACTER SET `utf8`;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		

		// ------------------------------------------
		// PHOCAGALLERY COMMENTS
		// ------------------------------------------
		
		$query ='DROP TABLE IF EXISTS `'.$dbPref.'phocagallery_comments`;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		

		$query ='CREATE TABLE `'.$dbPref.'phocagallery_comments` ('."\n";
		$query.='  `id` int(11) NOT NULL auto_increment,'."\n";
		$query.='  `catid` int(11) NOT NULL default 0,'."\n";
		$query.='  `userid` int(11) NOT NULL default 0,'."\n";
		$query.='  `date` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `title` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `comment` text NOT NULL,'."\n";
		$query.='  `published` tinyint(1) NOT NULL default \'0\','."\n";
		$query.='  `checked_out` int(11) unsigned NOT NULL default \'0\','."\n";
		$query.='  `checked_out_time` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `ordering` int(11) NOT NULL default \'0\','."\n";
		$query.='  `params` text NOT NULL,'."\n";
		$query.='  PRIMARY KEY  (`id`)'."\n";
		$query.=') TYPE=MyISAM CHARACTER SET `utf8`;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		
		// ------------------------------------------
		// PHOCAGALLERY VOTES STATISTICS
		// ------------------------------------------
		
		$query ='DROP TABLE IF EXISTS `'.$dbPref.'phocagallery_votes_statistics`;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		

		$query ='CREATE TABLE `'.$dbPref.'phocagallery_votes_statistics` ('."\n";
		$query.='  `id` int(11) NOT NULL auto_increment,'."\n";
		$query.='  `catid` int(11) NOT NULL default 0,'."\n";
		$query.='  `count` tinyint(11) NOT NULL default \'0\','."\n";
		$query.='  `average` float(8,6) NOT NULL default \'0\','."\n";
		$query.='  PRIMARY KEY  (`id`)'."\n";
		$query.=') TYPE=MyISAM CHARACTER SET `utf8`;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		// ------------------------------------------
		// PHOCAGALLERY USER CATEGORY
		// ------------------------------------------
		
		$query ='DROP TABLE IF EXISTS `'.$dbPref.'phocagallery_user_category`;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		

		$query ='CREATE TABLE `'.$dbPref.'phocagallery_user_category` ('."\n";
		$query.='  `id` int(11) NOT NULL auto_increment,'."\n";
		$query.='  `catid` int(11) NOT NULL default 0,'."\n";
		$query.='  `userid` int(11) NOT NULL default 0,'."\n";
		$query.='  PRIMARY KEY  (`id`),'."\n";
		$query.='  KEY `catid` (`catid`,`userid`)'."\n";
		$query.=') TYPE=MyISAM CHARACTER SET `utf8`;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		/*
		// Heading Parameter
		jimport('joomla.client.helper');
		jimport('joomla.filesystem.file');
		$ftp 	=& JClientHelper::setCredentialsFromRequest('ftp');
		$src 	= JPATH_ROOT . DS . 'administrator' .DS.'components' . DS . 'com_phocagallery' . DS . 'helpers' . DS . 'heading.php';
		$dest 	= JPATH_ROOT . DS . 'libraries' . DS . 'joomla' . DS . 'html' . DS . 'parameter' . DS . 'element' . DS . 'heading.php';
		if (file_exists($src)) {
			JFile::copy($src, $dest);
		}
		
		if (!file_exists($dest)) {
			$msgFile = 'Heading.php: ' . JText::_( 'File Not Copied' )
					. '<br />' . JText::_( 'Source' ). ': ' . $src
					. '<br />' . JText::_( 'Destination' ). ': ' . $dest;

		}
		*/
		// Error
		if ($msgSQL !='') {
			$msgError .= '<br />' . $msgSQL;
		}
		/*
		if ($msgFile !='') {
			$msgError .= '<br />' . $msgFile;
		}
		*/	
		// End Message
		if ($msgError !='') {
			$msg = JText::_( 'Phoca Gallery not successfully installed' ) . ': ' . $msgError;
		} else {
			$msg = JText::_( 'Phoca Gallery successfully installed' );
		}
		
		$link = 'index.php?option=com_phocagallery';
		$this->setRedirect($link, $msg);
	}
	
	
	function upgrade() {
		
		$db			=& JFactory::getDBO();
		$dbPref 	= $db->getPrefix();
		$msgSQL 	= '';
		$msgFile	= '';
		$msgError	= '';
		
		// UPGRADE PHOCA GALLERY 2 VERSION
		// ------------------------------------------
		// PHOCAGALLERY CATEGORIES
		// ------------------------------------------
		$updateHit = false;
		$updateHit = $this->AddColumnIfNotExists("".$dbPref."phocagallery_categories", "hits", "INT( 11 ) NOT NULL DEFAULT '0'", "count" );
		if (!$updateHit) {
			$msgSQL .= 'Error while updating HITS column';
		}
		
		// ------------------------------------------
		// PHOCAGALLERY VOTES
		// ------------------------------------------		
		
		$query ='CREATE TABLE IF NOT EXISTS `'.$dbPref.'phocagallery_votes` ('."\n";
		$query.='  `id` int(11) NOT NULL auto_increment,'."\n";
		$query.='  `catid` int(11) NOT NULL default 0,'."\n";
		$query.='  `userid` int(11) NOT NULL default 0,'."\n";
		$query.='  `date` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `rating` tinyint(1) NOT NULL default \'0\','."\n";
		$query.='  `published` tinyint(1) NOT NULL default \'0\','."\n";
		$query.='  `checked_out` int(11) unsigned NOT NULL default \'0\','."\n";
		$query.='  `checked_out_time` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `ordering` int(11) NOT NULL default \'0\','."\n";
		$query.='  `params` text NOT NULL,'."\n";
		$query.='  PRIMARY KEY  (`id`)'."\n";
		$query.=') TYPE=MyISAM CHARACTER SET `utf8`;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		

		// ------------------------------------------
		// PHOCAGALLERY COMMENTS
		// ------------------------------------------

		$query ='CREATE TABLE IF NOT EXISTS `'.$dbPref.'phocagallery_comments` ('."\n";
		$query.='  `id` int(11) NOT NULL auto_increment,'."\n";
		$query.='  `catid` int(11) NOT NULL default 0,'."\n";
		$query.='  `userid` int(11) NOT NULL default 0,'."\n";
		$query.='  `date` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `title` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `comment` text NOT NULL,'."\n";
		$query.='  `published` tinyint(1) NOT NULL default \'0\','."\n";
		$query.='  `checked_out` int(11) unsigned NOT NULL default \'0\','."\n";
		$query.='  `checked_out_time` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `ordering` int(11) NOT NULL default \'0\','."\n";
		$query.='  `params` text NOT NULL,'."\n";
		$query.='  PRIMARY KEY  (`id`)'."\n";
		$query.=') TYPE=MyISAM CHARACTER SET `utf8`;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		
		// ------------------------------------------
		// PHOCAGALLERY VOTES STATISTICS
		// ------------------------------------------

		$query ='CREATE TABLE IF NOT EXISTS `'.$dbPref.'phocagallery_votes_statistics` ('."\n";
		$query.='  `id` int(11) NOT NULL auto_increment,'."\n";
		$query.='  `catid` int(11) NOT NULL default 0,'."\n";
		$query.='  `count` tinyint(11) NOT NULL default \'0\','."\n";
		$query.='  `average` float(8,6) NOT NULL default \'0\','."\n";
		$query.='  PRIMARY KEY  (`id`)'."\n";
		$query.=') TYPE=MyISAM CHARACTER SET `utf8`;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		// ------------------------------------------
		// PHOCAGALLERY USER CATEGORY
		// ------------------------------------------

		$query ='CREATE TABLE IF NOT EXISTS `'.$dbPref.'phocagallery_user_category` ('."\n";
		$query.='  `id` int(11) NOT NULL auto_increment,'."\n";
		$query.='  `catid` int(11) NOT NULL default 0,'."\n";
		$query.='  `userid` int(11) NOT NULL default 0,'."\n";
		$query.='  PRIMARY KEY  (`id`),'."\n";
		$query.='  KEY `catid` (`catid`,`userid`)'."\n";
		$query.=') TYPE=MyISAM CHARACTER SET `utf8`;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		// UPGRADE PHOCA GALLERY 2.2 VERSION
		// ------------------------------------------
		// PHOCAGALLERY
		// ------------------------------------------
		$updateExtl1 = false;
		$updateExtl1 = $this->AddColumnIfNotExists("".$dbPref."phocagallery", "extlink1", "text NOT NULL", "params" );
		if (!$updateExtl1) {
			$msgSQL .= 'Error while updating Extlink1 column';
		}
		$updateExtl2 = false;
		$updateExtl2 = $this->AddColumnIfNotExists("".$dbPref."phocagallery", "extlink2", "text NOT NULL", "extlink1" );
		if (!$updateExtl2) {
			$msgSQL .= 'Error while updating Extlink2 column';
		}
		
		// UPGRADE PHOCA GALLERY 2.2.2 VERSION
		// ------------------------------------------
		// PHOCAGALLERY CATEGORIES
		// ------------------------------------------
		$updateDate = false;
		$updateDate = $this->AddColumnIfNotExists("".$dbPref."phocagallery_categories", "date", "datetime NOT NULL default '0000-00-00 00:00:00'", "description" );
		if (!$updateDate) {
			$msgSQL .= 'Error while updating Date column';
		}
		
		
		// CHECK TABLES
		
		$query =' SELECT * FROM `'.$dbPref.'phocagallery` LIMIT 1;';
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($db->getErrorNum()) {
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		
		
		$query=' SELECT * FROM `'.$dbPref.'phocagallery_categories` LIMIT 1;'."\n";
		
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($db->getErrorNum()) {
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		
		$query=' SELECT * FROM `'.$dbPref.'phocagallery_votes` LIMIT 1;'."\n";
		
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($db->getErrorNum()) {
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		
		$query=' SELECT * FROM `'.$dbPref.'phocagallery_comments` LIMIT 1;'."\n";
		
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($db->getErrorNum()) {
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		
		$query=' SELECT * FROM `'.$dbPref.'phocagallery_votes_statistics` LIMIT 1;'."\n";
		
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($db->getErrorNum()) {
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		
		$query=' SELECT * FROM `'.$dbPref.'phocagallery_user_category` LIMIT 1;'."\n";
		
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($db->getErrorNum()) {
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		
		/*
		// Heading Parameter
		jimport('joomla.client.helper');
		jimport('joomla.filesystem.file');
		$ftp 	=& JClientHelper::setCredentialsFromRequest('ftp');
		$src 	= JPATH_ROOT . DS . 'administrator' .DS.'components' . DS . 'com_phocagallery' . DS . 'helpers' . DS . 'heading.php';
		$dest 	= JPATH_ROOT . DS . 'libraries' . DS . 'joomla' . DS . 'html' . DS . 'parameter' . DS . 'element' . DS . 'heading.php';
		if (file_exists($src)) {
			JFile::copy($src, $dest);
		}
		
		if (!file_exists($dest)) {
			$msgFile = 'Heading.php: ' . JText::_( 'File Not Copied' )
					. '<br />' . JText::_( 'Source' ). ': ' . $src
					. '<br />' . JText::_( 'Destination' ). ': ' . $dest;

		}
		*/
		// Error
		if ($msgSQL !='') {
			$msgError .= '<br />' . $msgSQL;
		}
		/*
		if ($msgFile !='') {
			$msgError .= '<br />' . $msgFile;
		}
		*/	
		// End Message
		if ($msgError !='') {
			$msg = JText::_( 'Phoca Gallery not successfully upgraded' ) . ': ' . $msgError;
		} else {
			$msg = JText::_( 'Phoca Gallery successfully upgraded' );
		}
		
		$link = 'index.php?option=com_phocagallery';
		$this->setRedirect($link, $msg);
	}
	
	
	function AddColumnIfNotExists($table, $column, $attributes = "INT( 11 ) NOT NULL DEFAULT '0'", $after = '' ) {
		
		global $mainframe;
		$db				=& JFactory::getDBO();
		$columnExists 	= false;

		$query = 'SHOW COLUMNS FROM '.$table;
		$db->setQuery( $query );
		if (!$result = $db->query()){return false;}
		$columnData = $db->loadObjectList();
		
		
		foreach ($columnData as $valueColumn) {
			if ($valueColumn->Field == $column) {
				$columnExists = true;
				break;
			}
		}
		
		if (!$columnExists) {
			if ($after != '') {
				$query = "ALTER TABLE `".$table."` ADD `".$column."` ".$attributes." AFTER `".$after."`";
			} else {
				$query = "ALTER TABLE `".$table."` ADD `".$column."` ".$attributes."";
			}
			$db->setQuery( $query );
			if (!$result = $db->query()){return false;}
		}
		
		return true;
	}
}
// utf-8 test: ä,ö,ü,ř,ž
?>