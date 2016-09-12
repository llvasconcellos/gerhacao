<?php defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.filesystem.file' );
$imageWidth 	= 100;
$imageHeight	= 100;
if (JFile::exists( $this->_tmp_img->linkthumbnailpathabs ))
{
	list($width, $height) = GetImageSize( $this->_tmp_img->linkthumbnailpath );
	
	if ($width > $height) {
		if ($width > 100) {
			$imageWidth		= 100;
			$rate 			= $width / 100;
			$imageHeight	= $height / $rate;
		} else {
			$imageWidth		= $width;
			$imageHeight	= $height;
		}
	}
	else {
		if ($height > 100) {
			$imageHeight	= 100;
			$rate 			= $height / 100;
			$imageWidth 	= $width / $rate;
		} else {
			$imageWidth		= $width;
			$imageHeight	= $height;
		}
	}
}

?>

		
<div class="phocagallery-box-file-i">
	<center>
		<div class="phocagallery-box-file-first-i">
			<div class="phocagallery-box-file-second">
				<div class="phocagallery-box-file-third">
					<center>
					<a href="#" onclick="window.top.document.forms.adminForm.elements.filename.value = '<?php echo $this->_tmp_img->path_with_name_relative_no; ?>';window.parent.document.getElementById('sbox-window').close();">
	<?php echo JHTML::_( 'image.administrator', $this->_tmp_img->linkthumbnailpath, '', null, '', null, array('width' => $imageWidth, 'height' => $imageHeight)); ?></a>
					</center>
				</div>
			</div>
		</div>
	</center>
	
	<div class="name"><?php echo $this->_tmp_img->name; ?></div>
		<div class="detail" style="text-align:right">
			<a href="#" onclick="window.top.document.forms.adminForm.elements.filename.value = '<?php echo $this->_tmp_img->path_with_name_relative_no; ?>';window.parent.document.getElementById('sbox-window').close();"><img src="../administrator/components/com_phocagallery/assets/images/icon-insert.gif" alt="<?php echo JText::_('Insert image') ?>" title="<?php echo JText::_('Insert image') ?>" /></a>
		</div>
	<div style="clear:both"></div>
	
</div>
