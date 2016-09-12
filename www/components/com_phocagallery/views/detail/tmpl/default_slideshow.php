<?php
defined('_JEXEC') or die('Restricted access'); 
?><center style="padding-top:10px;">
	<table border="0" width="100%">
		<tr>
			<td colspan="6" align="center" valign="middle" height="<?php echo $this->tmpl['largeheight']; ?>" style="height:<?php echo $this->tmpl['largeheight']; ?>px" >
				<script type="text/javascript"><?php			
				if ( $this->tmpl['slideshowrandom'] == 1 ) {
					echo 'new fadeshow(fadeimages, '.$this->tmpl['largewidth'] .', '. $this->tmpl['largeheight'] .', 0, '. $this->tmpl['slideshowdelay'] .', '. $this->tmpl['slideshowpause'] .', \'R\')';		
				} else {						
					echo 'new fadeshow(fadeimages, '.$this->tmpl['largewidth'] .', '. $this->tmpl['largeheight'] .', 0, '. $this->tmpl['slideshowdelay'] .', '. $this->tmpl['slideshowpause'] .')';		
				} ?>
				</script>
			</td>
		</tr>
		
		<tr>
			<td align="left" width="30%" style="padding-left:48px"><?php echo $this->file->prevbutton ;?></td>
			<td><?php echo $this->file->slideshowbutton ;?></td>
			<td><?php echo str_replace("%onclickreload%", $this->tmpl['detailwindowreload'], $this->file->reloadbutton);?></td>
			<td><?php echo str_replace("%onclickclose%", $this->tmpl['detailwindowclose'], $this->file->closebutton);?></td>
			<td align="right" width="30%" style="padding-right:48px"><?php echo $this->file->nextbutton ;?></td>
		</tr>
	</table>
</center><?php
