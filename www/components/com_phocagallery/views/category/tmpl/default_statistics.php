<?php defined('_JEXEC') or die('Restricted access');

?><div id="phocagallery-statistics">
<?php
echo '<div style="font-size:1px;height:1px;margin:0px;padding:0px;">&nbsp;</div>';//because of IE bug
	
	if ($this->tmpl['displaymaincatstat']) {
		echo '<fieldset>'
			.'<legend>'.JText::_('Category').'</legend>'
			.'<table>'
			.'<tr><td>'.JText::_('Number of published images in category') .': </td>'
			.'<td>'.$this->tmpl['numberimgpub'].'</td></tr>'
			.'<tr><td>'.JText::_('Number of unpublished images in category') .': </td>'
			.'<td>'.$this->tmpl['numberimgunpub'].'</td></tr>'
			.'<tr><td>'.JText::_('Category viewed') .': </td>'
			.'<td>'.$this->tmpl['categoryviewed'].' x</td></tr>'
			.'</table>'
			.'</fieldset>';
	}	

// MOST VIEWED			
if ($this->tmpl['displaymostviewedcatstat']) {
	
	echo '<fieldset>'
		.'<legend>'.JText::_('Most viewed images in category').'</legend>';
		
	if (!empty($this->tmpl['mostviewedimg'])) {
		
		foreach($this->tmpl['mostviewedimg'] as $key => $value) {
			
			$imageHeight 	= $this->tmpl['imageheight'];
			if ($imageHeight < 100 ) {
				$imageHeight	= 100;
				$boxImageHeight = 100;
			} else {
				$boxImageHeight = $imageHeight;
			}
			
			$imageWidth		= $this->tmpl['imagewidth'];
			if ($imageWidth < 100 ) {
				$imageWidth    = 100;
				$boxImageWidth = 120;
			} else {
				$boxImageWidth = $imageWidth + 20;
			}

			if ($this->tmpl['displayname'] == 1 || $this->tmpl['displayname'] == 2) {
				$boxImageHeight = $boxImageHeight + 20;
			}
			
			if ($this->tmpl['displayicondetail'] == 1 || $this->tmpl['displayicondownload'] == 1 || $this->tmpl['displayiconfolder == 1'] || $this->tmpl['displayiconvm'] == 1 || $this->tmpl['startpiclens'] == 1 || $this->tmpl['trash'] == 1 || $this->tmpl['publishunpublish'] == 1 || $this->tmpl['displayicongeo']) {
				$boxImageHeight = $boxImageHeight + 20;
			}
			
			if ( $this->tmpl['displayimageshadow'] != 'none' ) {		
				$boxImageHeight = $boxImageHeight + 18;
				$imageHeight	= $imageHeight + 18;
				$imageWidth 	= $imageWidth + 18;
			}
			
			if ( $this->tmpl['categoryboxspace'] > 0 ) {		
				$boxImageHeight = $boxImageHeight + $this->tmpl['categoryboxspace'];
			}
				
			?>
			<div class="phocagallery-box-file" style="height:<?php echo $boxImageHeight; ?>px; width:<?php echo $boxImageWidth; ?>px">
				<center>
					<div class="phocagallery-box-file-first" style="height:<?php echo $imageHeight; ?>px;width:<?php echo $imageWidth; ?>px;">
						<div class="phocagallery-box-file-second">
							<div class="phocagallery-box-file-third">
								<center>
								<a class="<?php echo $value->buttonother->methodname; ?>"<?php
								
								echo ' href="'. $value->link.'"';
								
								if ($this->tmpl['detailwindow'] == 1) {
									echo ' onclick="'. $value->button->options.'"';
								} else if ($this->tmpl['detailwindow'] == 2) {
									echo ' rel="'. $value->button->options.'"';
								} else if ($this->tmpl['detailwindow'] == 4) {
									echo ' onclick="'. $this->tmpl['highslideonclick'].'"';
								} else if ($this->tmpl['detailwindow'] == 5) {
									echo ' onclick="'. $this->tmpl['highslideonclick2'].'"';
								}  else {
									echo ' rel="'. $value->buttonother->options.'"';
								}
								
								?> ><?php echo JHTML::_( 'image.site', $value->linkthumbnailpath, '', '', '', $value->title );

								?></a>
								</center>
							</div>
						</div>
					</div>
				</center>
				
			<?php
			
			// subfolder
			if ($value->type == 1) {
				if ($value->displayname == 1 || $value->displayname == 2) {
					echo '<div class="name" style="font-size:'.$this->tmpl['fontsizename'].'px">'
					.PhocaGalleryHelperFront::wordDelete($value->title, $this->tmpl['charlengthname'], '...').'</div>';
				}
			}
			// image
			if ($value->type == 2) {
				if ($value->displayname == 1) {
					echo '<div class="name" style="font-size:'.$this->tmpl['fontsizename'].'px">'
					.PhocaGalleryHelperFront::wordDelete($value->title, $this->tmpl['charlengthname'], '...').'</div>';
				}
				if ($value->displayname == 2) {
					echo '<div class="name" style="font-size:'.$this->tmpl['fontsizename'].'px">&nbsp;</div>';
				}
			}

			echo '<div class="detail" style="margin-top:2px;text-align:left">';
					
			
			echo JHTML::_('image', 'components/com_phocagallery/assets/images/icon-viewed.'.$this->tmpl['formaticon'], JText::_('Image Detail'));
			echo '&nbsp;&nbsp; '.$value->hits.' x';
		
			echo '</div>';
			echo '<div style="clear:both"></div>';
			
			echo '</div>';
		}
	}
		
	echo '</fieldset>';

} // END MOST VIEWED

// LAST ADDED	
if ($this->tmpl['displaylastaddedcatstat']) {		

	
	echo '<fieldset>'
		.'<legend>'.JText::_('Last added images in category').'</legend>';
		
	if (!empty($this->tmpl['lastaddedimg'])) {
		
		foreach($this->tmpl['lastaddedimg'] as $key => $value) {
			
			$imageHeight 	= $this->tmpl['imageheight'];
			if ($imageHeight < 100 ) {
				$imageHeight	= 100;
				$boxImageHeight = 100;
			} else {
				$boxImageHeight = $imageHeight;
			}
			
			$imageWidth		= $this->tmpl['imagewidth'];
			if ($imageWidth < 100 ) {
				$imageWidth    = 100;
				$boxImageWidth = 120;
			} else {
				$boxImageWidth = $imageWidth + 20;
			}

			if ($this->tmpl['displayname'] == 1 || $this->tmpl['displayname'] == 2) {
				$boxImageHeight = $boxImageHeight + 20;
			}
			
			if ($this->tmpl['displayicondetail'] == 1 || $this->tmpl['displayicondownload'] == 1 || $this->tmpl['displayiconfolder == 1'] || $this->tmpl['displayiconvm'] == 1 || $this->tmpl['startpiclens'] == 1 || $this->tmpl['trash'] == 1 || $this->tmpl['publishunpublish'] == 1 || $this->tmpl['displayicongeo']) {
				$boxImageHeight = $boxImageHeight + 20;
			}
			
			if ( $this->tmpl['displayimageshadow'] != 'none' ) {		
				$boxImageHeight = $boxImageHeight + 18;
				$imageHeight	= $imageHeight + 18;
				$imageWidth 	= $imageWidth + 18;
			}
			
			if ( $this->tmpl['categoryboxspace'] > 0 ) {		
				$boxImageHeight = $boxImageHeight + $this->tmpl['categoryboxspace'];
			}
				
			?>
			<div class="phocagallery-box-file" style="height:<?php echo $boxImageHeight; ?>px; width:<?php echo $boxImageWidth; ?>px">
				<center>
					<div class="phocagallery-box-file-first" style="height:<?php echo $imageHeight; ?>px;width:<?php echo $imageWidth; ?>px;">
						<div class="phocagallery-box-file-second">
							<div class="phocagallery-box-file-third">
								<center>
								<a class="<?php echo $value->buttonother->methodname; ?>"<?php
								
								echo ' href="'. $value->link.'"';
								
								if ($this->tmpl['detailwindow'] == 1) {
									echo ' onclick="'. $value->button->options.'"';
								} else if ($this->tmpl['detailwindow'] == 2) {
									echo ' rel="'. $value->button->options.'"';
								} else if ($this->tmpl['detailwindow'] == 4) {
									echo ' onclick="'. $this->tmpl['highslideonclick'].'"';
								} else if ($this->tmpl['detailwindow'] == 5) {
									echo ' onclick="'. $this->tmpl['highslideonclick2'].'"';
								} else {
									echo ' rel="'. $value->buttonother->options.'"';
								}
								
								?> ><?php echo JHTML::_( 'image.site', $value->linkthumbnailpath, '', '', '', $value->title );

								?></a>
								</center>
							</div>
						</div>
					</div>
				</center>
				
			<?php
			
			// subfolder
			if ($value->type == 1) {
				if ($value->displayname == 1 || $value->displayname == 2) {
					echo '<div class="name" style="font-size:'.$this->tmpl['fontsizename'].'px">'
					.PhocaGalleryHelperFront::wordDelete($value->title, $this->tmpl['charlengthname'], '...').'</div>';
				}
			}
			// image
			if ($value->type == 2) {
				if ($value->displayname == 1) {
					echo '<div class="name" style="font-size:'.$this->tmpl['fontsizename'].'px">'
					.PhocaGalleryHelperFront::wordDelete($value->title, $this->tmpl['charlengthname'], '...').'</div>';
				}
				if ($value->displayname == 2) {
					echo '<div class="name" style="font-size:'.$this->tmpl['fontsizename'].'px">&nbsp;</div>';
				}
			}

			echo '<div class="detail" style="margin-top:2px;text-align:left">';
					
			echo JHTML::_('image', 'components/com_phocagallery/assets/images/icon-date.'.$this->tmpl['formaticon'], JText::_('Image Detail'));
			echo '&nbsp;&nbsp; '.JHTML::Date($value->date, "%d. %m. %Y");
			
		
			echo '</div>';
			echo '<div style="clear:both"></div>';
			
			echo '</div>';
		}
	}

	echo '</fieldset>';

}// END MOST VIEWED	
?>
</div>
