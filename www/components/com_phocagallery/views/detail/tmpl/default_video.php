<?php
defined('_JEXEC') or die('Restricted access');
?><table border="0" style="width:<?php echo $this->tmpl['windowwidth'];?>px;height:<?php echo $this->tmpl['windowheight'];?>px;">
	<tr>
		<td colspan="5" style="text-align:center;vertical-align:middle" align="center" valign="middle">
			<?php echo $this->tmpl['videocode']; ?>
		</td>
	</tr><?php
		
	if ($this->tmpl['displaydescriptiondetail'] == 1) {
		?>
		<tr>
			<td colspan="6" align="left" valign="top" height="<?php echo $this->tmpl['descriptiondetailheight']; ?>">
			<div style="font-size:<?php echo $this->tmpl['fontsizedesc']; ?>px;height:<?php echo $this->tmpl['descriptiondetailheight']; ?>px;padding:0 20px 0 20px;color:<?php echo $this->tmpl['fontcolordesc']; ?>"><?php echo $this->file->description ?></div>
			</td>
		</tr><?php
	}
	
	if ($this->tmpl['detailbuttons'] == 1) {
		?>
		<tr>
			<td align="left" width="30%" style="padding-left:48px"><?php echo $this->file->prevbutton ;?></td>
			<td><?php /*echo $this->file->slideshowbutton */;?></td>
			<td><?php echo str_replace("%onclickreload%", $this->tmpl['detailwindowreload'], $this->file->reloadbutton);?></td>
			<td><?php echo str_replace("%onclickclose%", $this->tmpl['detailwindowclose'], $this->file->closebutton);?></td>
			<td align="right" width="30%" style="padding-right:48px"><?php echo $this->file->nextbutton ;?></td>
		</tr>
		<?php
	}
	?></table>