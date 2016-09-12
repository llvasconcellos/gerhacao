<?php
defined('_JEXEC') or die('Restricted access');
$user 	=& JFactory::getUser();

//Ordering allowed ?
$ordering = ($this->lists['order'] == 'a.ordering');

JHTML::_('behavior.tooltip');
?>

<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm">
	<table>
		<tr>
			<td align="left" width="100%"><?php echo JText::_( 'Filter' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php
				echo $this->tmpl['enablethumbcreationstatus'] . ' &nbsp;';
				echo $this->lists['catid'];
				echo $this->lists['state'];
				?>
			</td>
		</tr>
	</table>

	<div id="editcell">
		<table class="adminlist">
			<thead>
				<tr>
					<th width="5"><?php echo JText::_( 'NUM' ); ?></th>
					<th width="5"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
					<th class="image" width="70" align="center"><?php echo JText::_( 'Image' ); ?></th>
					<th class="title" width="40%"><?php echo JHTML::_('grid.sort',  'Title', 'a.title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th width="20%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'Filename', 'a.filename', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th width="10%" nowrap="nowrap"><?php echo JText::_('Functions'); ?>
					</th>
					<th width="5%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'Published', 'a.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th width="14%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort',  'Order', 'a.ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>
						<?php echo JHTML::_('grid.order',  $this->items ); ?></th>
					<th width="15%"  class="title">
						<?php echo JHTML::_('grid.sort',  'Category', 'category', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
						
					<th width="5%"><?php echo JHTML::_('grid.sort',  'Hits', 'a.hits', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					
					<th width="1%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'ID', 'a.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
				</tr>
			</thead>
			
			<tbody>
				<?php
				$k = 0;
				for ($i=0, $n=count( $this->items ); $i < $n; $i++)
				{
					$row 			= &$this->items[$i];
					$link 			= JRoute::_( 'index.php?option=com_phocagallery&controller=phocagallery&task=edit&cid[]='. $row->id );
					$linkRotate90 	= JRoute::_( 'index.php?option=com_phocagallery&controller=phocagallery&task=rotate&angle=90&cid[]='. $row->id );
					$linkRotate270 	= JRoute::_( 'index.php?option=com_phocagallery&controller=phocagallery&task=rotate&angle=270&cid[]='. $row->id );
					$linkDeleteThumbs= JRoute::_( 'index.php?option=com_phocagallery&controller=phocagallery&task=deletethumbs&cid[]='. $row->id );
					$checked 	= JHTML::_('grid.checkedout', $row, $i );
					$published 	= JHTML::_('grid.published', $row, $i );
					$row->cat_link 	= JRoute::_( 'index.php?option=com_phocagallery&controller=phocagalleryc&task=edit&cid[]='. $row->catid );
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
					<td><?php echo $checked; ?></td>
					<td align="center" valign="middle">
						<div class="phocagallery-box-file">
							<center>
								<div class="phocagallery-box-file-first">
									<div class="phocagallery-box-file-second">
										<div class="phocagallery-box-file-third">
											<center>
											<?php if (isset ($row->fileoriginalexist) && $row->fileoriginalexist == 1)
											{
											?>
												<a class="<?php echo $this->button->modalname; ?>" title="<?php echo $this->button->text; ?>" href="<?php echo $this->button->link . '&amp;cid[]='.$row->id; ?>" rel="<?php echo $this->button->options; ?>" ><?php echo JHTML::_( 'image.administrator', $row->linkthumbnailpath.'?imagesid='.md5(uniqid(time())), ''); ?></a>
											<?php
											}
											else
											{
												echo JHTML::_( 'image.site', 'phoca_thumb_s_no_image.gif', '../administrator/components/com_phocagallery/assets/images/');
											}
											?>
											</center>
										</div>
									</div>
								</div>
							</center>
						</div>
					</td>
					<td>
						<?php
						if (  JTable::isCheckedOut($this->user->get ('id'), $row->checked_out ) ) {
							echo $row->title;
						} else {
						?>
							<a href="<?php echo $link; ?>" title="<?php echo JText::_( 'Edit Phoca gallery' ); ?>">
								<?php echo $row->title; ?></a>
						<?php
						}
						?>
					</td>
					<td><?php echo $row->filename;?></td>
					<td align="center">
					<a href="<?php echo $linkRotate90; ?>" title="<?php echo JText::_( 'Rotate Left' ); ?>"><?php echo JHTML::_( 'image.administrator', 'icon-rotate-left.gif', 'components/com_phocagallery/assets/images/','','', JText::_( 'Rotate Left' ));?></a> 
					
					<a href="<?php echo $linkRotate270; ?>" title="<?php echo JText::_( 'Rotate Right' ); ?>"><?php echo JHTML::_( 'image.administrator', 'icon-rotate-right.gif', 'components/com_phocagallery/assets/images/','','', JText::_( 'Rotate Right' ));?></a> 
					
					<a href="<?php echo $linkDeleteThumbs; ?>" title="<?php echo JText::_( 'Delete and Recreate Thumbnail' ); ?>"><?php echo JHTML::_( 'image.administrator', 'icon-remove-create.gif', 'components/com_phocagallery/assets/images/','','', JText::_( 'Delete and Recreate Thumbnail' ));?></a>
					
					<a href="#" onclick="window.location.reload(true);" title="<?php echo JText::_( 'Reload Site' ); ?>"><?php echo JHTML::_( 'image.administrator', 'icon-reload.gif', 'components/com_phocagallery/assets/images/','','', JText::_( 'Reload Site' ));?></a> 
					
					</td>
					<td align="center"><?php echo $published;?></td>
					<td class="order">
						<span><?php echo $this->pagination->orderUpIcon( $i, ($row->catid == @$this->items[$i-1]->catid),'orderup', 'Move Up', $ordering ); ?></span>
						<span><?php echo $this->pagination->orderDownIcon( $i, $n, ($row->catid == @$this->items[$i+1]->catid), 'orderdown', 'Move Down', $ordering ); ?></span>
					<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
					</td>
					<td><a href="<?php echo $row->cat_link; ?>" title="<?php echo JText::_( 'Edit Category' ); ?>"><?php echo $row->category; ?></a>
					</td>

					<td align="center"><?php echo $row->hits; ?></td>
					
					<td align="center"><?php echo $row->id; ?></td>
				</tr>
				<?php
				$k = 1 - $k;
				}
			?>
			</tbody>
			
			<tfoot>
				<tr>
					<td colspan="11"><?php echo $this->pagination->getListFooter(); ?></td>
				</tr>
			</tfoot>
		</table>
	</div>

<input type="hidden" name="controller" value="phocagallery" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
</form>