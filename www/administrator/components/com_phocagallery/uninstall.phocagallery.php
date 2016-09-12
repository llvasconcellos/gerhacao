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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.filesystem.folder' );

function com_uninstall()
{
	$db			=& JFactory::getDBO();
	$db_prefix 	= $db->getPrefix();
	
	/*
	// <--
	$folder[0][0]	=	'images' . DS . 'phocagallery' . DS . 'thumbs' . DS ;
	$folder[0][1]	= 	JPATH_ROOT . DS .  $folder[0][0];
	*/
	$folder[1][0]	=	'images' . DS . 'phocagallery' . DS ;
	$folder[1][1]	= 	JPATH_ROOT . DS .  $folder[1][0];
	/*
	$message = '';
	$error	 = array();
	foreach ($folder as $key => $value)
	{
		if (JFolder::delete( $value[1]))
		{
			$message .= '<p><b><span style="color:#009933">Folder</span> ' . $value[0] 
					   .' <span style="color:#009933">successfully deleted!</span></b></p>';
			$error[] = 0;
		}	 
		else
		{
			$message .= '<p><b><span style="color:#CC0033">Folder</span> ' . $value[0]
					   .' <span style="color:#CC0033">deletion failed!</span></b> Please delete it manually</p>';
			$error[] = 1;
		}
	}
	*/
	$message = '<p><b><span style="color:#009933">Folder</span> ' . $folder[1][0] 
					   .' <span style="color:#009933">still exists!</span></b> Please delete it manually, if you want.</p>';
					   
/*	$message .= '<p><b><span style="color:#009933">Database tables:</span><br />'
				. '- ' . $db_prefix . 'phocagallery<br />'
				. '- ' . $db_prefix . 'phocagallery_categories<br />'
				.'<span style="color:#009933">were not deleted</span> because of possible upgrading of Phoca Gallery component.</b> Please delete it manually, if you want.</p>';
	*/
	
/*	if (in_array(1, $error))
	{
		return false;
	}
	else
	{
		return true;
	}*/
/*	
	$message .= '<p>' . JText::_('Phoca Gallery Remove or not Remove') .'</p>';
	echo $message;
	echo '<center>';
	echo '<div style="padding:20px;border:1px solid#ff8000;background:#fff">';
	echo '<table border="0" cellpadding="20" cellspacing="20"><tr>';
	echo '<td align="center" valign="middle"><a href="index.php?option=com_phocagallery&amp;controller=phocagalleryuninstall&amp;task=remove"><img src="components/com_phocagallery/assets/images/install.png" alt="Remove" /></a></td>';
	echo '<td align="center" valign="middle"><a href="index.php?option=com_phocagallery&amp;controller=phocagalleryuninstall&amp;task=keep"><img src="components/com_phocagallery/assets/images/upgrade.png" alt="Not remove" /></a></td>';
	echo '</tr></table>';
	echo '</div></center>';
	
	echo '<p>&nbsp;</p><p>&nbsp;</p>';
	echo '<div style="padding:20px;border:1px solid#0080c0;background:#fff">';
	echo '<p><img src="components/com_phocagallery/assets/images/logo.png" alt="www.phoca.cz" /></p>';
	echo '<center><a style="text-decoration:underline" href="http://www.phoca.cz/" target="_blank">www.phoca.cz</a></center>';
	echo '</div>';*/
	
	echo $message;
}

?>