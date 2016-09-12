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

class PhocaGalleryCpViewPhocaGalleryIn extends JView
{
	function display($tpl = null) {
		global $mainframe;
		$tmpl		= array();
		JHTML::stylesheet( 'phocagallery.css', 'administrator/components/com_phocagallery/assets/' );
		JToolBarHelper::title(   JText::_( 'Phoca Gallery Info' ), 'info' );
		JToolBarHelper::cancel( 'cancel', 'Close' );
		JToolBarHelper::help( 'screen.phocagallery', true );
		
		$params = JComponentHelper::getParams('com_phocagallery');
		
		
		$tmpl['version'] 					= PhocaGalleryHelper::getPhocaVersion();
		$tmpl['enablethumbcreation']		= $params->get('enable_thumb_creation', 1 );
		$tmpl['enablethumbcreationstatus'] 	= PhocaGalleryAdminRender::renderThumbnailCreationStatus((int)$tmpl['enablethumbcreation']);
		
		//Main Function support
		
		echo '<table border="1" cellpadding="5" cellspacing="5" style="border:1px solid #ccc;border-collapse:collapse">';
		
		$function = array('getImageSize','imageCreateFromJPEG', 'imageCreateFromPNG', 'imageCreateFromGIF', 'imageRotate', 'imageCreateTruecolor', 'imageCopyResampled', 'imageFill', 'imageColorTransparent', 'imageColorAllocate', 'exif_read_data');
		$fOutput = '';
		foreach ($function as $key => $value) {
			
			if ($key%2==0){
				$fOutput .= '<tr><td>'.$value.'</td>';
			} else {
				$fOutput .= '<tr><td style="background:#fdfdfd">'.$value.'</td>';
			}
			if (function_exists($value)) {
				$fOutput .=  '<td style="background:#CCFFCC"><span style="color:#009900;font-weight:bold;">'. JHTML::_('image.site',  'icon-16-true.png', '/components/com_phocagallery/assets/images/', NULL, NULL, 'Exists' ).'</span></td></tr>';
			} else {
				$fOutput .=  '<td style="background:#ffcccc"><span style="color:#fc0000;font-weight:bold;">'. JHTML::_('image.site',  'icon-16-false.png', '/components/com_phocagallery/assets/images/', NULL, NULL, 'Not Exists' ).'</span></td></tr>';
			}
		}
		$fOutput .= '</table>';

		$this->assignRef('tmpl',	$tmpl);
		$this->assignRef('foutput',	$fOutput);
		
		parent::display($tpl);
	}
}
?>
