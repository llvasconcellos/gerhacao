<?php defined('_JEXEC') or die('Restricted access'); ?>
<table border="0" width="100%">
	<tr>
		<td align="center" valign="middle" height="486"><?php if ($this->file->linkthumbnailpath=='')
				{
					?>
					<center style="font-size:large;font-weight:bold;color:#b3b3b3;font-family: Helvetica, sans-serif;">
			<?php echo JText::_( 'Filename does not exist' ); ?>
		</center>
					<?php
				}
				else
				{
				?>
					<a href="#" onclick="window.parent.document.getElementById('sbox-window').close();"><?php echo JHTML::_('image.administrator', $this->file->linkthumbnailpath .'?imagesid='.md5(uniqid(time())), ''); ?></a>
				
				<?php
				}
				?>
		</td>
	</tr>
</table>
