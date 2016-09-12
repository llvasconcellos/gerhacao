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
jimport( 'joomla.application.component.view' );
jimport('joomla.client.helper');

class PhocaGalleryCpViewPhocaGalleryT extends JView
{

	function display($tpl = null) {
		global $mainframe;
	
		JHTML::stylesheet( 'phocagallery.css', 'administrator/components/com_phocagallery/assets/' );
		JToolBarHelper::title(   JText::_( 'Phoca Gallery Themes' ), 'theme' );
		JToolBarHelper::cancel( 'cancel', 'Close' );
		JToolBarHelper::help( 'screen.phocagallery', true );
		
		$ftp	=& JClientHelper::setCredentialsFromRequest('ftp');
		
		$themeName	= '';
		if($this->themeName()) {
			$themeName = $this->themeName();
		}
		
		$this->assignRef('themename', $themeName);
		$this->assignRef('ftp', $ftp);
		parent::display($tpl);
	}
	
	
	function themeName() {
		// Get an array of all the xml files from teh installation directory
		$path		= JPATH_SITE.DS.'components'.DS.'com_phocagallery'.DS.'assets'.DS.'images';
		$xmlFiles 	= JFolder::files($path, '.xml$', 1, true);
		
		// If at least one xml file exists
		if (count($xmlFiles) > 0) {
			foreach ($xmlFiles as $file)
			{
				// Is it a valid joomla installation manifest file?
				$manifest = $this->_isManifest($file);				
				if(!is_null($manifest->document->children())) {
					foreach ($manifest->document->children() as $key => $value)
					{
						if ($value->_name == 'name') {
							return $value->_data;
						}
					}
				}
				return false;
			}
			return false;
		} else {
			return false;
		}
	}
	
	function &_isManifest($file) {
		// Initialize variables
		$null	= null;
		$xml	=& JFactory::getXMLParser('Simple');

		// If we cannot load the xml file return null
		if (!$xml->loadFile($file)) {
			// Free up xml parser memory and return null
			unset ($xml);
			return $null;
		}
		
		$root =& $xml->document;
		if (!is_object($root) || ($root->name() != 'install' )) {
			// Free up xml parser memory and return null
			unset ($xml);
			return $null;
		}
		return $xml;
	}
}
?>
