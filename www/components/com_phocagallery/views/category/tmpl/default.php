<?php
defined('_JEXEC') or die('Restricted access'); 

// Heading
$heading = '';
if ($this->params->get( 'page_title' ) != '') {
	$heading .= $this->params->get( 'page_title' );
}
if ( $this->tmpl['displaycatnametitle'] == 1) {
	if ($this->category->title != '') {
		if ($heading != '') {
			$heading .= ' - ';
		}
		$heading .= $this->category->title;
	}
}

// Pagetitle
if ($this->tmpl['showpagetitle'] != 0) {
	if ( $heading != '') {
	    echo '<div class="componentheading'.$this->params->get( 'pageclass_sfx' ).'">'
	        .$heading
			.'</div>';
	} 
}

// Image, description
echo '<div class="contentpane'.$this->params->get( 'pageclass_sfx' ).'">';
if ( @$this->tmpl['image'] || @$this->category->description ) {
	echo '<div class="contentdescription'.$this->params->get( 'pageclass_sfx' ).'">';
	if ( isset($this->tmpl['image']) ) {
		echo $this->tmpl['image'];
	}
	echo $this->category->description
		.'</div>';
}
echo '</div>';

echo '<form action="'.$this->tmpl['action'].'" method="post" name="adminForm">';

// Phoca Gallery Width
if ($this->tmpl['phocagallerywidth'] != '') {
	echo '<div id="phocagallery" style="width:'. $this->tmpl['phocagallerywidth'].'px">';
} else {
	echo '<div id="phocagallery">';
}

// Switch image
$noBaseImg 	= false;
$noBaseImg	= preg_match("/phoca_thumb_l_no_image/i", $this->tmpl['basicimage']);
if ($this->tmpl['switchimage'] == 1 && $noBaseImg == false) {
	$switchHeight 	= $this->tmpl['switchheight'];
	$switchCenterH	= ($switchHeight / 2) - 18;
	$switchWidth 	= $this->tmpl['switchwidth'];
	$switchCenterW	= ($switchWidth / 2) - 18;
	$switchHeight	= $switchHeight +5;
	
	?>
	<div>
		<center class="main-switch-image">
			<table border="0" cellspacing="5" cellpadding="5" style="" class="main-switch-image-table">
				<tr>
					<td align="center" valign="middle" style="text-align:center;width:<?php echo $switchWidth;?>px;height:<?php echo $switchHeight;?>px; background: url('<?php echo JURI::root(); ?>components/com_phocagallery/assets/images/icon-switch.gif') <?php echo $switchCenterW ;?>px <?php echo $switchCenterH;?>px no-repeat;margin:0px;padding:0px;">
					<?php echo JHTML::_( 'image.site', ''.str_replace('phoca_thumb_m_','phoca_thumb_l_',$this->tmpl['basicimage']).'', '', '', '', '', ' id="PhocaGalleryobjectPicture"  border="0"'); ?>
					</td>
				</tr>
			</table>
		</center>
	</div>
	<?php
}


// Height of box - all heights need to be same, so it is before foreach of images
$iconBoxWidth = 0;
if ($this->tmpl['displayicondetail'] == 1) {
	$iconBoxWidth = $iconBoxWidth + 20;
}
if ($this->tmpl['displayicondownload'] == 1) {
	$iconBoxWidth = $iconBoxWidth + 20;
}
if ($this->tmpl['displayiconvmbox']	== 1) {
	$iconBoxWidth = $iconBoxWidth + 20;
}
if ($this->tmpl['startpiclens']	== 1) {
	$iconBoxWidth = $iconBoxWidth + 20;
}
if ($this->tmpl['trash'] == 1) {
	$iconBoxWidth = $iconBoxWidth + 20;
}
if ($this->tmpl['publishunpublish']	== 1) {
	$iconBoxWidth = $iconBoxWidth + 20;
}
if ($this->tmpl['displayicongeobox']	== 1  ) {
	$iconBoxWidth = $iconBoxWidth + 20;
}
if ($this->tmpl['displaycamerainfo'] == 1) {
	$iconBoxWidth = $iconBoxWidth + 20;
}
if ($this->tmpl['displayiconextlink1box']	== 1) {
	$iconBoxWidth = $iconBoxWidth + 20;
}
if ($this->tmpl['displayiconextlink2box']	== 1) {
	$iconBoxWidth = $iconBoxWidth + 20;
}

// Categories View in Category View
if ($this->tmpl['displaycategoriescv']) {
	if (!empty($this->items)) {
		
		foreach($this->items as $key => $value) {
			if ($value->type == 3 || $value->type == 4) {			
				$this->categories[] = $value;
				
			}
		}
	}
	echo $this->loadTemplate('categories');
}
// - - - - - - - - - - - - - - - - - 

// Images	
if (!empty($this->items)) {
	
	foreach($this->items as $key => $value) {
		
		if ((int)$value->type < 3) {
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
			
			
			$boxHeightRows = ceil($iconBoxWidth/$boxImageWidth);
			$boxImageHeight = (20 * $boxHeightRows) + $boxImageHeight;
			
			
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
								<a class="<?php echo $value->button->methodname; ?>"<?php
								if ($value->type == 2) {
									if ($value->overlib == 0) {
										echo ' title="'. $value->title.'"';
									}
								}

								echo ' href="'. $value->link.'"';
								
								if ($value->type == 2) {
								
									if ($this->tmpl['detailwindow'] == 1) {
										echo ' onclick="'. $value->button->options.'"';
									} else if ($this->tmpl['detailwindow'] == 4 || $this->tmpl['detailwindow'] == 5) {
										echo ' onclick="'. $this->tmpl['highslideonclick'].'"';
									} else {
										echo ' rel="'.$value->button->options.'"';
									}
								
									// SWITCH OR OVERLIB
									if ($this->tmpl['switchimage'] == 1) {
									?> onmouseover="PhocaGallerySwitchImage('PhocaGalleryobjectPicture', '<?php echo str_replace('phoca_thumb_m_','phoca_thumb_l_', JURI::root(). $value->linkthumbnailpath); ?>');" onmouseout="PhocaGallerySwitchImage('PhocaGalleryobjectPicture', '<?php echo str_replace('phoca_thumb_m_','phoca_thumb_l_',JURI::root(). $value->linkthumbnailpath); ?>');" <?php
									
									} else {
										
										if (!empty($value->description)) {
											$divPadding = 'padding:5px;';
										} else {
											$divPadding = 'padding:0px;margin:0px;';
										}
									
										if ((int)$value->overlib == 1) {
											echo " onmouseover=\"return overlib('".htmlspecialchars( addslashes('<center>' . JHTML::_( 'image.site', str_replace ('phoca_thumb_m_','phoca_thumb_l_',$value->linkthumbnailpath), '', '', '', $value->title ) . "</center>"))."', CAPTION, '". $value->title."', BELOW, RIGHT, BGCLASS,'bgPhocaClass', FGCOLOR, '".$this->tmpl['olfgcolor']."', BGCOLOR, '".$this->tmpl['olbgcolor']."', TEXTCOLOR, '".$this->tmpl['oltfcolor']."', CAPCOLOR, '".$this->tmpl['olcfcolor']."');\"";
											echo " onmouseout=\"return nd();\" ";
										} else if ((int)$value->overlib == 2){
											echo " onmouseover=\"return overlib('".htmlspecialchars( addslashes('<div style="'.$divPadding.'">'.$value->description.'</div>'))."', CAPTION, '". $value->title."', BELOW, RIGHT, CSSCLASS, TEXTFONTCLASS, 'fontPhocaClass', FGCLASS, 'fgPhocaClass', BGCLASS, 'bgPhocaClass', CAPTIONFONTCLASS,'capfontPhocaClass', CLOSEFONTCLASS, 'capfontclosePhocaClass');\"";
											echo " onmouseout=\"return nd();\" ";
										} else if ((int)$value->overlib == 3){
											echo " onmouseover=\"return overlib('".htmlspecialchars( addslashes( '<div style="text-align:center"><center>' . JHTML::_( 'image.site', str_replace ('phoca_thumb_m_','phoca_thumb_l_',$value->linkthumbnailpath), '', '', '', $value->title ) . '</center></div><div style="'.$divPadding.'">' . $value->description . '</div>'))."', CAPTION, '". $value->title."', BELOW, RIGHT, BGCLASS,'bgPhocaClass', FGCLASS,'fgPhocaClass', FGCOLOR, '".$this->tmpl['olfgcolor']."', BGCOLOR, '".$this->tmpl['olbgcolor']."', TEXTCOLOR, '".$this->tmpl['oltfcolor']."', CAPCOLOR, '".$this->tmpl['olcfcolor']."');\"";
											echo " onmouseout=\"return nd();\" ";
										}						
									}
									
									?> ><?php 
									if ($value->overlib == 0) {
										echo JHTML::_( 'image.site', $value->linkthumbnailpath, '', '', '', $value->title );
									} else {
										echo JHTML::_( 'image.site', $value->linkthumbnailpath, '', '', '', '' );
									}

								
									if ($value->enable_piclens == 1) {							
										?><span class="mbf-item">#phocagallerypiclens <?php echo $value->catid ;?>-phocagallerypiclenscode-<?php echo $value->filename;?></span><?php
									}
								} else {
									?> ><?php 
									echo JHTML::_( 'image.site', $value->linkthumbnailpath, '', '', '', $value->title );
								} // if type 2 else type 0, 1 (image, category, folder)
									?></a><?php
									if ( $this->tmpl['detailwindow'] == 5) {
										if ($this->tmpl['displaytitleindescription'] == 1) {
											echo '<div class="highslide-heading">';
											echo $value->title;
											echo '</div>';
										}
										if ($this->tmpl['displaydescriptiondetail'] == 1) {
											echo '<div class="highslide-caption">';
											echo $value->description;
											echo '</div>';
										}
									} ?>	
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
			

			if ($value->displayicondetail == 1 || $value->displayicondownload == 1 || $value->displayiconfolder == 1 || $value->displayiconvm || $value->startpiclens == 1 || $value->trash == 1 || $value->publishunpublish == 1 || $value->displayicongeo == 1 || $value->camerainfo == 1 && $value->displayiconextlink1	== 1 && $value->displayiconextlink2	== 1)
			{
				echo '<div class="detail" style="margin-top:2px">';
				
				if ($value->startpiclens == 1) {							
					?><a href="javascript:PicLensLite.start();" title="PicLens" ><img src="http://lite.piclens.com/images/PicLensButton.png" alt="PicLens" width="16" height="12" border="0" style="margin-bottom:2px" /></a><?php
			  
				}
				
				if ($value->displayicondetail == 1) {
					?> <a class="<?php echo $value->button2->methodname; ?>" title="<?php echo JText::_('Image Detail');//$value->title; ?>" href="<?php echo $value->link2; ?>"<?php
						if ($this->tmpl['detailwindow'] == 1) {
							echo ' onclick="'. $value->button2->options.'"';
						} else if ($this->tmpl['detailwindow'] == 2) {
							echo ' rel="'. $value->button2->options.'"';
						} else if ($this->tmpl['detailwindow'] == 4 ) {
							echo ' onclick="'. $this->tmpl['highslideonclick'].'"';
						} else if ($this->tmpl['detailwindow'] == 5 ) {
							echo ' onclick="'. $this->tmpl['highslideonclick2'].'"';
						} else {
							echo ' rel="'. $value->button2->options.'"';
						}
						echo ' >';
						echo JHTML::_('image', 'components/com_phocagallery/assets/images/icon-view.'.$this->tmpl['formaticon'], JText::_('Image Detail'));
						echo '</a>';
				}
				
				if ($value->displayiconfolder == 1) {
						?> <a class="<?php echo $value->buttonother->methodname; ?>" title="<?php echo JText::_('Sub category');//$value->title; ?>" href="<?php echo $value->link; ?>">
						<?php
						echo JHTML::_('image', 'components/com_phocagallery/assets/images/icon-folder-small.'.$this->tmpl['formaticon'], $value->title);	
						echo '</a>';
				}
				
				if ($value->displayicondownload == 1) {
					?> <a class="<?php echo $value->buttonother->methodname; ?>" title="<?php echo JText::_('Image Download');//$value->title; ?>" href="<?php echo JRoute::_('index.php?option=com_phocagallery&view=detail&catid='.$this->category->slug.'&id='.$value->slug.'&tmpl=component&phocadownload=1'.'&Itemid='. JRequest::getVar('Itemid', 1, 'get', 'int') ); ?>"<?php
						if ($this->tmpl['detailwindow'] == 1) {
							echo ' onclick="'. $value->buttonother->options.'"';
						} else if ($this->tmpl['detailwindow'] == 4 ) {
							echo ' onclick="'. $this->tmpl['highslideonclick'].'"';
						} else if ($this->tmpl['detailwindow'] == 5 ) {
							echo ' onclick="'. $this->tmpl['highslideonclick2'].'"';
						} else {
							echo ' rel="'. $value->buttonother->options.'"';
						}
						echo ' >';
						echo JHTML::_('image', 'components/com_phocagallery/assets/images/icon-download.'.$this->tmpl['formaticon'], JText::_('Image Download'));
						echo '</a>';
				}
				
				if ($value->displayicongeo == 1) {
					?> <a class="<?php echo $value->buttonother->methodname; ?>" title="<?php echo JText::_('Geotagging');//$value->title; ?>" href="<?php echo JRoute::_('index.php?option=com_phocagallery&view=map&catid='.$this->category->slug.'&id='.$value->slug.'&tmpl=component'.'&Itemid='. JRequest::getVar('Itemid', 1, 'get', 'int') ); ?>"<?php
						if ($this->tmpl['detailwindow'] == 1) {
							echo ' onclick="'. $value->buttonother->options.'"';
						} else if ($this->tmpl['detailwindow'] == 4 ) {
							echo ' onclick="'. $this->tmpl['highslideonclick'].'"';
						} else if ($this->tmpl['detailwindow'] == 5 ) {
							echo ' onclick="'. $this->tmpl['highslideonclick2'].'"';
						} else {
							echo ' rel="'. $value->buttonother->options.'"';
						}
						echo ' >';
						echo JHTML::_('image', 'components/com_phocagallery/assets/images/icon-geo.'.$this->tmpl['formaticon'], JText::_('Geotagging'));
						echo '</a>';
				}
				
				if ($value->camerainfo == 1) {
					?> <a class="<?php echo $value->buttonother->methodname; ?>" title="<?php echo JText::_('Camera Info');//$value->title; ?>" href="<?php echo JRoute::_('index.php?option=com_phocagallery&view=info&catid='.$this->category->slug.'&id='.$value->slug.'&tmpl=component'.'&Itemid='. JRequest::getVar('Itemid', 1, 'get', 'int') ); ?>"<?php
						if ($this->tmpl['detailwindow'] == 1) {
							echo ' onclick="'. $value->buttonother->options.'"';
						} else if ($this->tmpl['detailwindow'] == 4 ) {
							echo ' onclick="'. $this->tmpl['highslideonclick'].'"';
						} else if ($this->tmpl['detailwindow'] == 5 ) {
							echo ' onclick="'. $this->tmpl['highslideonclick2'].'"';
						} else {
							echo ' rel="'. $value->buttonother->options.'"';
						}
						echo ' >';
						echo JHTML::_('image', 'components/com_phocagallery/assets/images/icon-info.'.$this->tmpl['formaticon'], JText::_('Camera Info'));
						echo '</a>';
				}
				
				if ($value->displayiconextlink1 == 1) {
					?> <a title="<?php echo $value->extlink1[1] ?>" href="http://<?php echo $value->extlink1[0] ?>" target="<?php echo $value->extlink1[2] ?>" <?php echo $value->extlink1[5]?>><?php echo $value->extlink1[4]; ?></a><?php
					
				}
				if ($value->displayiconextlink2 == 1) {
					?> <a title="<?php echo $value->extlink2[1] ?>" href="http://<?php echo $value->extlink2[0] ?>" target="<?php echo $value->extlink2[2] ?>" <?php echo $value->extlink2[5]?>><?php echo $value->extlink2[4]; ?></a><?php
					
				}
				
				// VirtueMart Link
				if ($value->displayiconvm == 1) {
					echo ' <a title="'.JText::_('Eshop').'" href="'. JRoute::_($value->vmlink).'">';
					echo JHTML::_('image', 'components/com_phocagallery/assets/images/icon-cart.'.$this->tmpl['formaticon'], JText::_('Eshop'));
					echo '</a>';
				}
				
				// Trash for private categories
				if ($value->trash == 1) {
					echo ' <a onclick="return confirm(\''.JText::_('WARNWANTDELLISTEDITEMS').'\')" title="'.JText::_('Delete').'" href="'. JRoute::_('index.php?option=com_phocagallery&view=category&catid='.$this->category->slug.'&id='.$value->slug.'&task=remove'.'&Itemid='. JRequest::getVar('Itemid', 1, 'get', 'int') ).$this->tmpl['limitstarturl'].'">';
						echo JHTML::_('image', 'components/com_phocagallery/assets/images/icon-trash.'.$this->tmpl['formaticon'], JText::_('Delete'));
						echo '</a>';
				}
				
				// Publish Unpublish for private categories
				if ($value->publishunpublish == 1) {
					if ($value->published == 1) {
					
						echo ' <a title="'.JText::_('Unpublish').'" href="'. JRoute::_('index.php?option=com_phocagallery&view=category&catid='.$this->category->slug.'&id='.$value->slug.'&task=unpublish'.'&Itemid='. JRequest::getVar('Itemid', 1, 'get', 'int') ).$this->tmpl['limitstarturl'].'">';
						echo JHTML::_('image', 'components/com_phocagallery/assets/images/icon-publish.'.$this->tmpl['formaticon'], JText::_('Unpublish'));
						echo '</a>';
					}
					if ($value->published == 0) {
					
						echo ' <a title="'.JText::_('Publish').'" href="'. JRoute::_('index.php?option=com_phocagallery&view=category&catid='.$this->category->slug.'&id='.$value->slug.'&task=publish'.'&Itemid='. JRequest::getVar('Itemid', 1, 'get', 'int') ).$this->tmpl['limitstarturl'].'">';
						echo JHTML::_('image', 'components/com_phocagallery/assets/images/icon-unpublish.'.$this->tmpl['formaticon'], JText::_('Publish'));
						echo '</a>';
					
					}
				}

				echo '</div>';
				echo '<div style="clear:both"></div>';
			}
			echo '</div>';
		}
	}
} else {
	echo JText::_('There is no image in this category');
}
?>

	<div style="clear:both"></div>
</div>

<p>&nbsp;</p>

<?php
if (count($this->items)) {
	echo '<div><center>';
	if ($this->params->get('show_pagination_limit')) {
		
		echo '<div style="margin:0 10px 0 10px;display:inline;">'
			.JText::_('Display Num') .'&nbsp;'
			.$this->tmpl['pagination']->getLimitBox()
			.'</div>';
	}
	
	if ($this->params->get('show_pagination')) {
	
		echo '<div style="margin:0 10px 0 10px;display:inline;" class="sectiontablefooter'.$this->params->get( 'pageclass_sfx' ).'" >'
			.$this->tmpl['pagination']->getPagesLinks()
			.'</div>'
		
			.'<div style="margin:0 10px 0 10px;display:inline;" class="pagecounter">'
			.$this->tmpl['pagination']->getPagesCounter()
			.'</div>';
	}
	echo '</center></div>';
}
echo '</form>';

echo '<div>&nbsp;</div>';


if ($this->tmpl['displaytabs'] > 0) {
	echo '<div id="phocagallery-pane">';
	$pane =& JPane::getInstance('Tabs', array('startOffset'=> $this->tmpl['tab']));
	echo $pane->startPane( 'pane' );
	
/*TODO	
	if ((int)$this->tmpl['displaysubcategory'] == 1) {
		echo $pane->startPanel( JHTML::_( 'image.site', 'components/com_phocagallery/assets/images/icon-folder-small.'.$this->tmpl['formaticon'],'', '', '', '', '') . '&nbsp;'.JText::_('Subcategory'), 'subcategory' );
		echo $this->loadTemplate('subcategory');
		echo $pane->endPanel();
	}
*/
	if ((int)$this->tmpl['displayrating'] == 1) {
		echo $pane->startPanel( JHTML::_( 'image.site', 'components/com_phocagallery/assets/images/icon-vote.'.$this->tmpl['formaticon'],'', '', '', '', '') . '&nbsp;'.JText::_('Rating'), 'votes' );
		echo $this->loadTemplate('rating');
		echo $pane->endPanel();
	}

	if ((int)$this->tmpl['displaycomment'] == 1) {
		echo $pane->startPanel( JHTML::_( 'image.site', 'components/com_phocagallery/assets/images/icon-comment.'.$this->tmpl['formaticon'],'', '', '', '', '') . '&nbsp;'.JText::_('Comments'), 'comments' );
		echo $this->loadTemplate('comments');
		echo $pane->endPanel();
	}

	if ((int)$this->tmpl['displaycategorystatistics'] == 1) {
		echo $pane->startPanel( JHTML::_( 'image.site', 'components/com_phocagallery/assets/images/icon-statistics.'.$this->tmpl['formaticon'],'', '', '', '', '') . '&nbsp;'.JText::_('Statistics'), 'statistics' );
		echo $this->loadTemplate('statistics');
		echo $pane->endPanel();
	}
	
	if ((int)$this->tmpl['displaycategorygeotagging'] == 1) {
		echo $pane->startPanel( JHTML::_( 'image.site', 'components/com_phocagallery/assets/images/icon-geo.'.$this->tmpl['formaticon'],'', '', '', '', '') . '&nbsp;'.JText::_('Geotagging'), 'geotagging' );
		echo $this->loadTemplate('geotagging');
		echo $pane->endPanel();
	}
	
	if ((int)$this->tmpl['displayupload'] == 1) {
		echo $pane->startPanel( JHTML::_( 'image.site', 'components/com_phocagallery/assets/images/icon-upload.'.$this->tmpl['formaticon'],'', '', '', '', '') . '&nbsp;'.JText::_('Upload'), 'upload' );
		echo $this->loadTemplate('upload');
		echo $pane->endPanel();
	}

	echo $pane->endPane();
	echo '</div>';// end phocagallery-pane
}

echo $this->tmpl['phocagalleryic'];

?>
