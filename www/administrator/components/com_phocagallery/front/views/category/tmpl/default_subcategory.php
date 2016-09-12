<?php defined('_JEXEC') or die('Restricted access'); 
/*
?><div id="phocagallery-subcategory">	
	<?php
	echo '<div style="font-size:1px;height:1px;margin:0px;padding:0px;">&nbsp;</div>';//because of IE bug
	
	echo '<fieldset>'
		.'<legend>'.JText::_('Create sub category').'</legend>';

	if ($this->tmpl['notregistered']) {
	
		echo '<p>'.JText::_('Only registered and logged in user can create sub catgeories').'</p>';
			
	} else {
		
		?>		
		<form action="<?php echo $this->tmpl['action'];?>" name="phocagallerycreatecatform" id="phocagallery-create-cat-form" method="post" >
			<table>
				<tr>
					<td><?php echo JText::_('Category');?>:</td>
					<td>
						<input type="text" id="categoryname" name="categoryname" maxlength="255" class="comment-input" value="<?php echo $this->tmpl['categoryname'] ;?>" />
					</td>
				</tr>
				
				<tr>
					<td><?php echo JText::_( 'Description' ); ?>:</td>
					<td><textarea id="phocagallery-create-cat-description" name="phocagallerycreatecatdescription" onkeyup="countCharsCreateCat();" cols="30" rows="10" class="comment-input"><?php echo $this->tmpl['categorydescription'] ;?></textarea></td>
				</tr>
				
				<tr>
					<td>&nbsp;</td>
					<td><?php echo JText::_('Characters written');?> <input name="phocagallerycreatecatcountin" value="0" readonly="readonly" class="comment-input2" /> <?php echo JText::_('and left for description');?> <input name="phocagallerycreatecatcountleft" value="<?php echo $this->tmpl['maxcreatecatchar'];?>" readonly="readonly" class="comment-input2" />
					</td>
				</tr>
				
				<tr>
				<td>&nbsp;</td>
				<td align="right">
					<input type="submit" onclick="return(checkCreateCatForm());" id="phocagallerycreatecatsubmit" value="<?php echo $this->tmpl['createoredit']; ?>"/>
				</td>
			</tr>
			</table>

			<?php echo JHTML::_( 'form.token' ); ?>
			<input type="hidden" name="task" value="createSubCategory"/>
			<input type="hidden" name="view" value="category"/>
			<input type="hidden" name="tab" value="<?php echo $this->tmpl['currenttab']['createsubcategory'];?>" />
			<input type="hidden" name="catid" value="<?php echo $this->category->slug ?>"/>
			<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid', 1, 'get', 'int') ?>"/>
		</form>
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		
		<?php
	}
	?>
	
	</fieldset>
</div>
*/?>