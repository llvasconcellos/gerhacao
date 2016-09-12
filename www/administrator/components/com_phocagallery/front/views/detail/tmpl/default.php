<?php defined('_JEXEC') or die('Restricted access');

$largeHeight = (int)$this->tmpl['largeheight'] + 6 ;

?><center style="padding-top:10px">
<table border="0" width="100%">
	<tr>
		<td colspan="6" align="center" valign="middle" height="<?php echo $largeHeight; ?>" >
			<div id="image-box" style="width:<?php echo $this->tmpl['realimagewidth'];?>px;"><a  href="#" onclick="<?php echo $this->tmpl['detailwindowclose']; ?>"><?php echo JHTML::_( 'image.site', $this->file->linkthumbnailpath, ''); ?></a><?php
			
			$titleDesc = '';
			if ($this->tmpl['displaytitleindescription'] == 1) {
				$titleDesc .= $this->file->title;
				if ($this->file->description != '' && $titleDesc != '') {
					$titleDesc .= ' - ';
				}
			}
			
			// LIGHTBOX DESCRIPTION
			if ($this->tmpl['displaydescriptiondetail'] == 2 && (!empty($this->file->description) || !empty($titleDesc))){
				?>
				<div id="description-msg" style="background:<?php echo $this->tmpl['descriptionlightboxbgcolor'];?>"><div id="description-text" style="background:<?php echo $this->tmpl['descriptionlightboxbgcolor'];?>;color:<?php echo $this->tmpl['descriptionlightboxfontcolor'];?>;font-size:<?php echo $this->tmpl['descriptionlightboxfontsize'];?>px"><?php echo $titleDesc . $this->file->description;?></div></div>
				<?php
			}
		?></div>
		</td>
	</tr>
	
	<?php
	// STANDARD DESCRIPTION
	if ($this->tmpl['displaydescriptiondetail'] == 1) {
	
		?>
		<tr>
			<td colspan="6" align="left" valign="top" height="<?php echo $this->tmpl['descriptiondetailheight']; ?>">
				<div style="font-size:<?php echo $this->tmpl['fontsizedesc']; ?>px;height:<?php echo $this->tmpl['descriptiondetailheight']; ?>px;padding:0 20px 0 20px;color:<?php echo $this->tmpl['fontcolordesc']; ?>"><?php echo $titleDesc . $this->file->description ?></div>
			</td>
		</tr>
		<?php
	
	}

	if ($this->tmpl['detailbuttons'] == 1){
	
		?>
		<tr>
			<td align="left" width="30%" style="padding-left:48px"><?php echo $this->file->prevbutton ;?></td>
			<td><?php echo $this->file->slideshowbutton ;?></td>
			<td><?php echo str_replace("%onclickreload%", $this->tmpl['detailwindowreload'], $this->file->reloadbutton);?></td>
			<?php
			if ($this->tmpl['detailwindow'] == 4 || $this->tmpl['detailwindow'] == 5) {
			} else {	
				echo '<td>' . str_replace("%onclickclose%", $this->tmpl['detailwindowclose'], $this->file->closebutton). '</td>';
			}
			?>
			
			
			<td align="right" width="30%" style="padding-right:48px"><?php echo $this->file->nextbutton ;?></td>
		</tr>
		<?php
	}
		?>	
</table>
</center>
<!-- <a href="http://www.phoca.cz/">http://www.phoca.cz/</a> -->