<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
class PhocaGalleryAdminRender
{		
	function renderThumbnailCreationStatus($status = 1) {
		switch ($status) {
			case 0:
				$statusData = array('disabled', 'false');
			break;
			case 1:
			default:
				$statusData = array('enabled', 'true');
			break;
		}
		return '<span class="hasTip" title="'.JText::_('Thumbnail Creation is ' . $statusData[0]) . '::'.JText::_('Thumbnail Creation Status Info').'">'.JText::_('Thumbnail Creation Status') . ': '. JHTML::_('image.site',  'icon-16-'.$statusData[1].'.png', '/components/com_phocagallery/assets/images/', NULL, NULL, $statusData[0] ) . '</span>';
	}
}