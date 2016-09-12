<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('behavior.tooltip'); ?>

<script language="javascript" type="text/javascript">
function submitbutton(pressbutton, parent_id) {
	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}
	
/*	if (form.parentid.value == "0"){
	alert( "<?php echo JText::_( 'You must select a category', true ); ?>" );
} else */ if ( form.title.value == "" ) {
		alert("<?php echo JText::_( 'Category must have a title', true ); ?>");
	} else {
		<?php
		echo $this->editor->save( 'description' ) ; ?>
		submitform(pressbutton);
	}
}
</script>

<form action="index.php" method="post" name="adminForm">

<div class="col60">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>

			<table class="admintable">
			<tr>
				<td class="key">
					<label for="title" width="100">
						<?php echo JText::_( 'Title' ); ?>:
					</label>
				</td>
				<td colspan="2">
					<input class="text_area" type="text" name="title" id="title" value="<?php echo $this->phocagallery->title; ?>" size="50" maxlength="255" title="<?php echo JText::_( 'A long name to be displayed in headings' ); ?>" />
				</td>
			</tr>
			<tr>
				<td class="key">
					<label for="alias">
						<?php echo JText::_( 'Alias' ); ?>:
					</label>
				</td>
				<td colspan="2">
					<input class="text_area" type="text" name="alias" id="alias" value="<?php echo $this->phocagallery->alias; ?>" size="50" maxlength="255" title="<?php echo JText::_( 'A short name to appear in menus' ); ?>" />
				</td>
			</tr>
			
			<tr>
				<td valign="top" align="right" class="key">
					<label for="parentid">
						<?php echo JText::_( 'Parent Category' ); ?>:
					</label>
				</td>
				<td colspan="2">
					<?php echo $this->lists['parentid']; ?>
				</td>
			</tr>
			
			<tr>
				<td width="120" class="key">
					<?php echo JText::_( 'Published' ); ?>:
				</td>
				<td>
					<?php echo $this->lists['published']; ?>
				</td>
			</tr>
		
			<tr>
				<td class="key">
					<label for="ordering">
						<?php echo JText::_( 'Ordering' ); ?>:
					</label>
				</td>
				<td colspan="2">
					<?php echo $this->lists['ordering']; ?>
				</td>
			</tr>
			<tr>
				<td valign="top" class="key">
					<label for="access">
						<?php echo JText::_( 'Access Level' ); ?>:
					</label>
				</td>
				<td>
					<?php echo $this->lists['access']; ?>
				</td>
			</tr>
			<tr>
				<td valign="top" class="key">
					<label for="access">
						<?php echo JText::_( 'Access Rights' ); ?>:
					</label>
				</td>
				<td>
					<?php echo $this->lists['accessusers']; ?>
				</td>
			</tr>
			
			<tr>
				<td valign="top" class="key">
					<label for="access">
						<?php echo JText::_( 'Upload and Add Rights' ); ?>:
					</label>
				</td>
				<td>
					<?php echo $this->lists['uploadusers']; ?>
				</td>
			</tr>
			
			<tr>
				<td valign="top" class="key">
					<label for="access">
						<?php echo JText::_( 'Delete and Publish Rights' ); ?>:
					</label>
				</td>
				<td>
					<?php echo $this->lists['deleteusers']; ?>
				</td>
			</tr>
			
			
			<tr>
				<td valign="middle" align="right" class="key">
					<label for="userfolder">
						<?php echo JText::_( 'User Folder' ); ?>:
					</label>
				</td>
				<td valign="middle">
					<input class="text_area" type="text" name="userfolder" id="userfolder" value="<?php echo $this->userfolder; ?>" size="32" maxlength="250" />
				</td>
				<td align="left" valign="middle">
					<div class="button2-left" style="display:inline">
						<div class="<?php echo $this->button->name; ?>">
							<a class="<?php echo $this->button->modalname; ?>" title="<?php echo $this->button->text; ?>" href="<?php echo $this->button->link; ?>" rel="<?php echo $this->button->options; ?>"  ><?php echo $this->button->text; ?></a>
						</div>
					</div>
				</td>
			</tr>
			
			
			<tr>
				<td valign="middle" align="right" class="key">
					<label for="longitude">
						<?php echo JText::_( 'Longitude' ); ?>:
					</label>
				</td>
				<td valign="middle">
					<input class="text_area" type="text" name="longitude" id="longitude" value="<?php echo $this->tmpl['longitude']; ?>" size="32" maxlength="250" />
				</td>
				<td align="left" valign="middle">
					<div class="button2-left" style="display:inline">
						<div class="<?php echo $this->buttong->name; ?>">
							<a class="<?php echo $this->buttong->modalname; ?>" title="<?php echo $this->buttong->text; ?>" href="<?php echo $this->buttong->link; ?>" rel="<?php echo $this->buttong->options; ?>"  ><?php echo $this->buttong->text; ?></a>
						</div>
					</div>
				</td>
			</tr>
			
			<tr>
				<td valign="middle" align="right" class="key">
					<label for="latitude">
						<?php echo JText::_( 'Latitude' ); ?>:
					</label>
				</td>
				<td valign="middle">
					<input class="text_area" type="text" name="latitude" id="latitude" value="<?php echo $this->tmpl['latitude']; ?>" size="32" maxlength="250" />
				</td>
			</tr>
			
			<tr>
				<td valign="middle" align="right" class="key">
					<label for="zoom">
						<?php echo JText::_( 'Geotagging Zoom' ); ?>:
					</label>
				</td>
				<td valign="middle">
					<input class="text_area" type="text" name="zoom" id="zoom" value="<?php echo $this->tmpl['zoom']; ?>" size="32" maxlength="250" />
				</td>
			</tr>
			
			<tr>
				<td valign="middle" align="right" class="key">
					<label for="geotitle">
						<?php echo JText::_( 'Geotagging Title' ); ?>:
					</label>
				</td>
				<td valign="middle">
					<input class="text_area" type="text" name="geotitle" id="geotitle" value="<?php echo $this->tmpl['geotitle']; ?>" size="32" maxlength="250" />
				</td>
			</tr>
			
			<tr>
				<td valign="top" class="key">
					<label for="access">
						<?php echo JText::_( 'Author' ); ?>:
					</label>
				</td>
				<td>
					<?php echo $this->lists['author']; ?>
				</td>
			</tr>
			
			<tr>
				<td class="key">
					<label for="title" width="100">
						<?php echo JText::_( 'Hits' ); ?>:
					</label>
				</td>
				<td colspan="2">
					<input class="text_area" type="text" name="hits" id="hits" value="<?php echo $this->phocagallery->hits; ?>" size="15" maxlength="11" title="<?php echo JText::_( 'Hits' ); ?>" />
				</td>
			</tr>
			
			<tr>
				<td valign="top" align="right" class="key">
					<label for="date">
						<?php echo JText::_( 'Date' ); ?>:
					</label>
				</td>
				<td colspan="2" valign="middle">
					<?php echo JHTML::_('calendar', $this->phocagallery->date, 'date', 'date', "%Y-%m-%d", array('class'=>'inputbox', 'size'=>'32',  'maxlength'=>'45')); ?>
				</td>
			</tr>
			
			<tr>
				<td class="key">
					<label for="image">
						<?php echo JText::_( 'Image' ); ?>:
					</label>
				</td>
				<td>
					<?php echo $this->lists['image']; ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<label for="image_position">
						<?php echo JText::_( 'Image Position' ); ?>:
					</label>
				</td>
				<td>
					<?php echo $this->lists['image_position']; ?>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
				<script language="javascript" type="text/javascript">
				if (document.forms.adminForm.image.options.value!=''){
					jsimg='../images/stories/' + getSelectedValue( 'adminForm', 'image' );
				} else {
					jsimg='../images/M_images/blank.png';
				}
				document.write('<img src=' + jsimg + ' name="imagelib" width="80" height="80" border="2" alt="<?php echo JText::_( 'Preview', true ); ?>" />');
				</script>
				</td>
			</tr>

		</table>
	</fieldset>

	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Description' ); ?></legend>

		<table class="admintable">
			<tr>
				<td valign="top" colspan="3">
					<?php
					// parameters : areaname, content, width, height, cols, rows, show xtd buttons
					echo $this->editor->display( 'description',  $this->phocagallery->description, '550', '300', '60', '20', array('pagebreak', 'readmore') ) ;
					?>
				</td>
			</tr>
			</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_phocagallery" />
<input type="hidden" name="cid[]" value="<?php echo $this->phocagallery->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="phocagalleryc" />
</form>

	
