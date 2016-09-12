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

jimport('joomla.application.component.controller');
jimport('joomla.client.helper');
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class PhocaGalleryCpControllerPhocaGalleryt extends PhocaGalleryCpController
{
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'themeinstall'  , 	'themeinstall' );	
	}

	function themeinstall()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		$post					= JRequest::get('post');
		
		$theme = array();
		
		if (isset($post['theme_component'])) {
			$theme['component'] = 1;
		}
		if (isset($post['theme_categories'])) {
			$theme['categories'] = 1;
		}
		if (isset($post['theme_category'])) {
			$theme['category'] 	= 1;
		}
		
		if (!empty($theme)) {
		
			$ftp =& JClientHelper::setCredentialsFromRequest('ftp');
		
			
			$model	= &$this->getModel( 'phocagalleryt' );

			if ($model->install($theme)) {
				$cache = &JFactory::getCache('mod_menu');
				$cache->clean();
				$msg = JText::_('New Theme Installed');
			}
		} else {
			$msg = JText::_('Select Application Area');
		}
		
		$this->setRedirect( 'index.php?option=com_phocagallery&view=phocagalleryt', $msg );
	}

	function cancel()
	{
		$this->setRedirect( 'index.php?option=com_phocagallery' );
	}

}
?>
