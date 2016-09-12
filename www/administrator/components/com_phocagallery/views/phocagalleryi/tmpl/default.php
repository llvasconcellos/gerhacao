<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php echo $this->loadTemplate('up'); ?>
<?php if (count($this->images) > 0 || count($this->folders) > 0) { ?>
<div>
		<?php for ($i=0,$n=count($this->folders); $i<$n; $i++) :
			$this->setFolder($i);
			echo $this->loadTemplate('folder');
		endfor; ?>

		<?php for ($i=0,$n=count($this->images); $i<$n; $i++) :
			$this->setImage($i);
			echo $this->loadTemplate('image');
		endfor; ?>

</div>
<?php } else { ?>
<div>
	<center style="clear:both;font-size:large;font-weight:bold;color:#b3b3b3;font-family: Helvetica, sans-serif;">
		<?php echo JText::_( 'There is no image folder' ); ?>
	</center>
</div>
<?php } ?>





<div style="clear:both">
<div style="border-bottom:1px solid #cccccc;margin-bottom: 10px">&nbsp;</div>

<?php
if ($this->tmpl['displaytabs'] > 0) {
	echo '<div id="phocagallery-pane">';
	$pane =& JPane::getInstance('Tabs', array('startOffset'=> $this->tmpl['tab']));
	echo $pane->startPane( 'pane' );

	echo $pane->startPanel( JHTML::_( 'image.site', 'components/com_phocagallery/assets/images/icon-16-upload.png','', '', '', '', '') . '&nbsp;'.JText::_('Upload'), 'votes' );
	echo $this->loadTemplate('upload');
	echo $pane->endPanel();


	echo $pane->startPanel( JHTML::_( 'image.site', 'components/com_phocagallery/assets/images/icon-16-upload-java.png','', '', '', '', '') . '&nbsp;'.JText::_('Java Upload'), 'votes' );
	echo $this->loadTemplate('javaupload');
	echo $pane->endPanel();



	echo $pane->startPanel( JHTML::_( 'image.site', 'components/com_phocagallery/assets/images/icon-16-upload-flash.png','', '', '', '', '') . '&nbsp;'.JText::_('Flash Upload'), 'votes' );
	echo $this->loadTemplate('flashupload');
	echo $pane->endPanel();

	echo $pane->endPane();
	echo '</div>';// end phocagallery-pane
}
?>


<?php /*
$currentFolder = '';
if (isset($this->state->folder) && $this->state->folder != '') {
 $currentFolder = $this->state->folder;
}
?>

<form action="<?php echo JURI::base(); ?>index.php?option=com_phocagallery&controller=phocagalleryu&amp;task=upload&amp;tmpl=component&amp;<?php echo $this->session->getName().'='.$this->session->getId(); ?>&amp;<?php echo JUtility::getToken();?>=1&amp;viewback=phocagalleryi&amp;folder=<?php echo $currentFolder?>" id="uploadForm" method="post" enctype="multipart/form-data">

<!-- File Upload Form -->
<?php if ($this->require_ftp): ?>

	<fieldset title="<?php echo JText::_('DESCFTPTITLE'); ?>">
		<legend><?php echo JText::_('DESCFTPTITLE'); ?></legend>
		<?php echo JText::_('DESCFTP2'); ?>
		<table class="adminform nospace">
			<tr>
				<td width="120">
					<label for="username"><?php echo JText::_('Username'); ?>:</label>
				</td>
				<td>
					<input type="text" id="username" name="username" class="input_box" size="70" value="" />
				</td>
			</tr>
			<tr>
				<td width="120">
					<label for="password"><?php echo JText::_('Password'); ?>:</label>
				</td>
				<td>
					<input type="password" id="password" name="password" class="input_box" size="70" value="" />
				</td>
			</tr>
		</table>
	</fieldset>

<?php endif; ?>

	<fieldset>
		<legend><?php echo JText::_( 'Upload File' ); ?> [ <?php echo JText::_( 'Max' ); ?>&nbsp;<?php echo ($this->uploadmaxsize / 1000000); ?>M ]</legend>
		<fieldset class="actions">
			<input type="file" id="file-upload" name="Filedata" />
			<input type="submit" id="file-upload-submit" value="<?php echo JText::_('Start Upload'); ?>"/>
			<span id="upload-clear"></span>
		</fieldset>
		<ul class="upload-queue" id="upload-queue">
			<li style="display: none" ></li>
		</ul>
	</fieldset>
	<input type="hidden" name="return-url" value="<?php echo base64_encode('index.php?option=com_phocagallery&view=phocagalleryi&tmpl=component'); ?>" />
</form>

<form action="<?php echo JURI::base(); ?>index.php?option=com_phocagallery&controller=phocagalleryu&amp;task=createfolder&amp;<?php echo $this->session->getName().'='.$this->session->getId(); ?>&amp;<?php echo JUtility::getToken();?>=1&amp;viewback=phocagalleryi&amp;folder=<?php echo $currentFolder?>" name="folderForm" id="folderForm" method="post">
	<fieldset id="folderview">
		<legend><?php echo JText::_( 'Folder' ); ?></legend>
		<div class="path">
			<input class="inputbox" type="text" id="foldername" name="foldername"  />
			<input class="update-folder" type="hidden" name="folderbase" id="folderbase" value="<?php echo $currentFolder; ?>" />
			<button type="submit"><?php echo JText::_( 'Create Folder' ); ?></button>
		</div>
    </fieldset>
	<?php echo JHTML::_( 'form.token' ); ?>
</form>

</div>

*/?>
