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

// Submenu view
$view	= JRequest::getVar( 'view', '', '', 'string', JREQUEST_ALLOWRAW );

if ($view == '' || $view == 'phocagallerycp') {
	JSubMenuHelper::addEntry(JText::_('Control Panel'), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_('Images'), 'index.php?option=com_phocagallery&view=phocagallerys');
	JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_phocagallery&view=phocagallerycs' );
	JSubMenuHelper::addEntry(JText::_('Themes'), 'index.php?option=com_phocagallery&view=phocagalleryt');
	JSubMenuHelper::addEntry(JText::_('Rating'), 'index.php?option=com_phocagallery&view=phocagalleryra');
	JSubMenuHelper::addEntry(JText::_('Comments'), 'index.php?option=com_phocagallery&view=phocagallerycos');
	//JSubMenuHelper::addEntry(JText::_('Users CAtegories'), 'index.php?option=com_phocagallery&view=phocagalleryucs');
	JSubMenuHelper::addEntry(JText::_('Info'), 'index.php?option=com_phocagallery&view=phocagalleryin' );
}

if ($view == 'phocagallerys') {
	JSubMenuHelper::addEntry(JText::_('Control Panel'), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_('Images'), 'index.php?option=com_phocagallery&view=phocagallerys', true);
	JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_phocagallery&view=phocagallerycs' );
	JSubMenuHelper::addEntry(JText::_('Themes'), 'index.php?option=com_phocagallery&view=phocagalleryt');
	JSubMenuHelper::addEntry(JText::_('Rating'), 'index.php?option=com_phocagallery&view=phocagalleryra');
	JSubMenuHelper::addEntry(JText::_('Comments'), 'index.php?option=com_phocagallery&view=phocagallerycos');
	//JSubMenuHelper::addEntry(JText::_('Users CAtegories'), 'index.php?option=com_phocagallery&view=phocagalleryucs');
	JSubMenuHelper::addEntry(JText::_('Info'), 'index.php?option=com_phocagallery&view=phocagalleryin' );
}

if ($view == 'phocagallerycs') {
	JSubMenuHelper::addEntry(JText::_('Control Panel'), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_('Images'), 'index.php?option=com_phocagallery&view=phocagallerys');
	JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_phocagallery&view=phocagallerycs', true );
	JSubMenuHelper::addEntry(JText::_('Themes'), 'index.php?option=com_phocagallery&view=phocagalleryt');
	JSubMenuHelper::addEntry(JText::_('Rating'), 'index.php?option=com_phocagallery&view=phocagalleryra');
	JSubMenuHelper::addEntry(JText::_('Comments'), 'index.php?option=com_phocagallery&view=phocagallerycos');
	//JSubMenuHelper::addEntry(JText::_('Users CAtegories'), 'index.php?option=com_phocagallery&view=phocagalleryucs');
	JSubMenuHelper::addEntry(JText::_('Info'), 'index.php?option=com_phocagallery&view=phocagalleryin' );
}

if ($view == 'phocagalleryt') {
	JSubMenuHelper::addEntry(JText::_('Control Panel'), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_('Images'), 'index.php?option=com_phocagallery&view=phocagallerys');
	JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_phocagallery&view=phocagallerycs' );
	JSubMenuHelper::addEntry(JText::_('Themes'), 'index.php?option=com_phocagallery&view=phocagalleryt', true );
	JSubMenuHelper::addEntry(JText::_('Rating'), 'index.php?option=com_phocagallery&view=phocagalleryra');
	JSubMenuHelper::addEntry(JText::_('Comments'), 'index.php?option=com_phocagallery&view=phocagallerycos');
	//JSubMenuHelper::addEntry(JText::_('Users CAtegories'), 'index.php?option=com_phocagallery&view=phocagalleryucs');
	JSubMenuHelper::addEntry(JText::_('Info'), 'index.php?option=com_phocagallery&view=phocagalleryin' );
}

if ($view == 'phocagalleryra') {
	JSubMenuHelper::addEntry(JText::_('Control Panel'), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_('Images'), 'index.php?option=com_phocagallery&view=phocagallerys');
	JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_phocagallery&view=phocagallerycs' );
	JSubMenuHelper::addEntry(JText::_('Themes'), 'index.php?option=com_phocagallery&view=phocagalleryt');
	JSubMenuHelper::addEntry(JText::_('Rating'), 'index.php?option=com_phocagallery&view=phocagalleryra', true);
	JSubMenuHelper::addEntry(JText::_('Comments'), 'index.php?option=com_phocagallery&view=phocagallerycos');
	//JSubMenuHelper::addEntry(JText::_('Users CAtegories'), 'index.php?option=com_phocagallery&view=phocagalleryucs');
	JSubMenuHelper::addEntry(JText::_('Info'), 'index.php?option=com_phocagallery&view=phocagalleryin' );
}

if ($view == 'phocagallerycos') {
	JSubMenuHelper::addEntry(JText::_('Control Panel'), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_('Images'), 'index.php?option=com_phocagallery&view=phocagallerys');
	JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_phocagallery&view=phocagallerycs' );
	JSubMenuHelper::addEntry(JText::_('Themes'), 'index.php?option=com_phocagallery&view=phocagalleryt' );
	JSubMenuHelper::addEntry(JText::_('Rating'), 'index.php?option=com_phocagallery&view=phocagalleryra');
	JSubMenuHelper::addEntry(JText::_('Comments'), 'index.php?option=com_phocagallery&view=phocagallerycos', true);
	//JSubMenuHelper::addEntry(JText::_('Users CAtegories'), 'index.php?option=com_phocagallery&view=phocagalleryucs');
	JSubMenuHelper::addEntry(JText::_('Info'), 'index.php?option=com_phocagallery&view=phocagalleryin' );
}
/*
if ($view == 'phocagalleryucs') {
	JSubMenuHelper::addEntry(JText::_('Control Panel'), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_('Images'), 'index.php?option=com_phocagallery&view=phocagallerys');
	JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_phocagallery&view=phocagallerycs' );
	JSubMenuHelper::addEntry(JText::_('Themes'), 'index.php?option=com_phocagallery&view=phocagalleryt' );
	JSubMenuHelper::addEntry(JText::_('Rating'), 'index.php?option=com_phocagallery&view=phocagalleryra');
	JSubMenuHelper::addEntry(JText::_('Comments'), 'index.php?option=com_phocagallery&view=phocagallerycos');
	JSubMenuHelper::addEntry(JText::_('Users CAtegories'), 'index.php?option=com_phocagallery&view=phocagalleryucs', true);
	JSubMenuHelper::addEntry(JText::_('Info'), 'index.php?option=com_phocagallery&view=phocagalleryin' );
}
*/

if ($view == 'phocagalleryin') {
	JSubMenuHelper::addEntry(JText::_('Control Panel'), 'index.php?option=com_phocagallery');
	JSubMenuHelper::addEntry(JText::_('Images'), 'index.php?option=com_phocagallery&view=phocagallerys');
	JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_phocagallery&view=phocagallerycs' );
	JSubMenuHelper::addEntry(JText::_('Themes'), 'index.php?option=com_phocagallery&view=phocagalleryt' );
	JSubMenuHelper::addEntry(JText::_('Rating'), 'index.php?option=com_phocagallery&view=phocagalleryra');
	JSubMenuHelper::addEntry(JText::_('Comments'), 'index.php?option=com_phocagallery&view=phocagallerycos');
	//JSubMenuHelper::addEntry(JText::_('Users CAtegories'), 'index.php?option=com_phocagallery&view=phocagalleryucs');
	JSubMenuHelper::addEntry(JText::_('Info'), 'index.php?option=com_phocagallery&view=phocagalleryin',true );
}


class PhocaGalleryCpController extends JController
{
	function display() {
		parent::display();
	}
}
?>
