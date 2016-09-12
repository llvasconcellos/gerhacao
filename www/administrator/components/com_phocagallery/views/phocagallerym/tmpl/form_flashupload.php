<?php 
defined('_JEXEC') or die('Restricted access');
$currentFolder = '';
if (isset($this->state->folder) && $this->state->folder != '') {
 $currentFolder = $this->state->folder;
}

?><div id="phocagallery-flashupload">
	<div style="font-size:1px;height:1px;margin:0px;padding:0px;">&nbsp;</div>
<form action="<?php echo JURI::base(); ?>index.php?option=com_phocagallery&controller=phocagalleryu&amp;task=upload&amp;<?php echo $this->session->getName().'='.$this->session->getId(); ?>&amp;<?php echo JUtility::getToken();?>=1&amp;viewback=phocagallerym&amp;folder=<?php echo $currentFolder?>&amp;tab=<?php echo $this->tmpl['currenttab']['flashupload']?>" id="uploadForm" method="post" enctype="multipart/form-data">

<!-- File Upload Form -->
<?php
if ($this->require_ftp) {
	echo PhocaGalleryHelperUpload::renderFTPaccess();
} ?>

	<fieldset>
		<legend><?php echo JText::_( 'Upload File' ); ?> [ <?php echo JText::_( 'Max' ); ?>&nbsp;<?php echo ($this->tmpl['uploadmaxsize'] / 1000000); ?>M ]</legend>
		<fieldset class="actions">
			<input type="file" id="file-upload" name="Filedata" />
			<input type="submit" id="file-upload-submit" value="<?php echo JText::_('Start Upload'); ?>"/>
			<?php /*<span id="upload-clear"></span> */ ?>
		</fieldset>
		<ul class="upload-queue" id="upload-queue">
			<li style="display: none" ></li>
		</ul>
	</fieldset>
	<input type="hidden" name="return-url" value="<?php echo base64_encode('index.php?option=com_phocagallery&view=phocagallerym&layout=form&tab='.$this->tmpl['currenttab']['flashupload']); ?>" />
	<?php /* !!!!!! $refreshSite for flash upload is set in view.html.php */ ?>
</form>
<?php
echo PhocaGalleryHelperUpload::renderCreateFolder($this->session->getName(), $this->session->getId(), $currentFolder, 'phocagallerym' );
?>
</div>