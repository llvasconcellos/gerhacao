<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php 
	JHTML::_('behavior.tooltip');
	JHTML::_('behavior.modal');
	
	JHTML::script('scripts.js', 'administrator/components/com_jce/js/');
 	JHTML::script('sortables.js', 'administrator/components/com_jce/js/');
	
	JHTML::stylesheet('styles.css', 'administrator/components/com_jce/css/');
	JHTML::stylesheet('icons.css', 'administrator/components/com_jce/css/');
 	JHTML::stylesheet('layout.css', 'administrator/components/com_jce/css/');
	
	JToolBarHelper::title( JText::_( 'JCE Group' ) .': <small><small>[' .JText::_('Edit'). ']</small></small>', 'user.png' );
	JToolBarHelper::save();
	JToolBarHelper::apply();
	JToolBarHelper::cancel( 'cancelEdit', 'Close' );
	jceToolbarHelper::help( 'groups' );
?>

<?php
	// clean item data
	JFilterOutput::objectHTMLSafe( $this->group, ENT_QUOTES, '' );
?>
<script type="text/javascript">
	window.addEvent('domready', function(){
  		new Sortables('.sortableList', {revert: true, onComplete : function(el){
			var state = el.getParent().id == 'groupLayout';
			el.getChildren().each(function(c){
				setParams(c.id, state);	
			});			
		}});
		new Sortables('.sortableRow', {revert: true, onComplete : function(el){
			var state = el.getParent().getParent().id == 'groupLayout';
			setParams(el.id, state);			
		}});
		$ES('div[id^=plugin_params_]', $('plugin_params')).each(function(p){			
			if(p.style.display == 'none'){
				setParams(p.id, false);
			}
		});		
	});
	function setParams(id, state){
		id = id.replace(/[^0-9]/gi, ''); 
		var params = $('plugin_params_' + id) || false;		
		if(params){
			var disabled = state ? '' : 'disabled';
			
			params.style.display = state ? 'block' : 'none';				
			$ES('input', params).each(function(input){
				input.disabled = disabled;
			});
			$ES('select', params).each(function(input){
				input.disabled = disabled;
			});
			$ES('textarea', params).each(function(input){
				input.disabled = disabled;
			});
		}
	}
	function submitbutton(pressbutton) {
		var form = document.adminForm, items = [];
		// Serialize group layout
		$('groupLayout').getChildren().each(function(el){
			items.include(el.getChildren().map(function(o, i){
				return o.id.replace(/[^0-9]/gi, '');
			}).join(','));	
		});
		form.rows.value = items.join(';') || '';
		// Select added users
		for (var i=0; i<form.users.options.length; i++) {
			form.users.options[i].selected = true;
		}	
		// Cancel button
		if (pressbutton == "cancelEdit") {
			submitform(pressbutton);
			return;
		}
		// validation
		if (form.name.value == "") {
			alert( "<?php echo JText::_( 'Group must have a name', true ); ?>" );
		} else {
			submitform(pressbutton);
		}
	}
</script>
<form action="index.php" method="post" name="adminForm">
<div class="col width-60">
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'Details' ); ?></legend>
	<table class="admintable">
		<tr>
			<td width="100" class="key">
				<label for="name">
					<?php echo JText::_( 'Name' ); ?>:
				</label>
			</td>
			<td>
            	<?php if( $this->group->name == 'Default' ){
					echo $this->group->name;
				}else{?>
					<input class="text_area" type="text" name="name" id="name" size="35" value="<?php echo $this->group->name; ?>" />
				<?php }?>
            </td>
		</tr>
        <tr>
			<td width="100" class="key">
				<label for="name">
					<?php echo JText::_( 'Description' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="description" id="description" size="80" value="<?php echo $this->group->description; ?>" />
			</td>
		</tr>
		<tr>
			<td valign="top" class="key">
				<?php echo JText::_( 'Published' ); ?>:
			</td>
			<td>
				<?php if( $this->group->name == 'Default' ){
					echo JText::_('Enabled');
				}else{
					echo $this->lists['published'];
				}?>
			</td>
		</tr>
        <tr>
			<td valign="top" class="key">
				<?php echo JText::_( 'Priority' ); ?>:
			</td>
			<td>
				<?php echo $this->lists['ordering']; ?>
			</td>
		</tr>
        <tr>
			<td valign="top" class="key">
				<?php echo JText::_( 'Components' ); ?>:
			</td>
			<td>
				<?php echo $this->lists['components']; ?>
                <p><a onclick="javascript: jce.selectAll(document, 'components');" href="#"><span class="icon-add"><span class="icon-text"><?php echo JText::_('Add All');?></span></span></a>
            	<a onclick="javascript: jce.selectNone(document, 'components');" href="#"><span class="icon-remove"><span class="icon-text"><?php echo JText::_('Remove All');?></span></span></a></p>
			</td>
        </tr>
        <tr>
			<td valign="top" class="key">
				<?php echo JText::_( 'Types' ); ?>:
			</td>
			<td>
				<?php echo $this->lists['types']; ?>
                <p><a onclick="javascript: jce.selectAll(document, 'types');" href="#"><span class="icon-add"><span class="icon-text"><?php echo JText::_('Add All');?></span></span></a>
            	<a onclick="javascript: jce.selectNone(document, 'types');" href="#"><span class="icon-remove"><span class="icon-text"><?php echo JText::_('Remove All');?></span></span></a></p>
			</td>
        </tr>
        <tr>
			<td valign="top" class="key">
				<?php echo JText::_( 'Users' ); ?>:
			</td>
			<td>
				<?php echo $this->lists['users']; ?>
            	<p><a class="modal" rel="{handler: 'iframe', size: {x: 750, y: 600}}" href="index.php?option=com_jce&tmpl=component&type=group&task=addusers"><span class="icon-add"><span class="icon-text"><?php echo JText::_('Add User');?></span></span></a>
            	<a onclick="javascript: jce.removeSelect(document, 'users');" href="#"><span class="icon-remove"><span class="icon-text"><?php echo JText::_('Remove User');?></span></span></a></p>
			</td>
        </tr>
    </table>
	</fieldset>
    <fieldset class="adminform">
    <legend><?php echo JText::_( 'GROUPS LAYOUT' ); ?></legend>
        <table class="admintable">
            <tr><td><?php echo JText::_( 'GROUPS LAYOUT DESC' ); ?></td></tr>
            <tr><td><a class="modal" rel="{handler: 'iframe', size: {x: 750, y: 600}}" href="index.php?option=com_jce&tmpl=component&type=group&task=legend"><span class="icon-legend"><span class="icon-text"><?php echo JText::_('Icon Legend');?></span></span></a></td></tr>
            <tr>
                <td>
                <fieldset>
                <legend><?php echo JText::_( 'GROUP DEFAULT LAYOUT' ); ?></legend>
                <div class="sortableList">
                <?php 
                $rows 	= JCEGroupsHelper::getRowArray( $this->group->rows );
				$width 	= $this->layout['width'] + 50;
				for( $i=1; $i<=$this->layout['rows']; $i++ ){
				?>
                    <ul class="sortableRow" style="width:<?php echo $width;?>px">
                <?php
					foreach( $this->plugins as $icon ){
						if( !in_array( $icon->id, explode( ',', implode( ',', $rows ) ) ) ){
							if( $icon->layout && $icon->row == $i ){
								$n = "row_li_" .$icon->id;
								$path = $icon->type == 'command' ? '../plugins/editors/jce/tiny_mce/themes/advanced/img/'. $icon->layout .'.gif' : '../plugins/editors/jce/tiny_mce/plugins/'. $icon->name .'/img/'. $icon->layout .'.gif';
								?>
								<li class="sortableItem" id="<?php echo $n;?>"><img src="<?php echo $path;?>" alt="<?php echo $icon->title;?>" title="<?php echo $icon->title;?>" /></li>
							<?php }
						}
					}
					?>
                    </ul>
				<?php }?>
                </div>
                </fieldset>
                <fieldset>
                <legend><?php echo JText::_( 'EDITOR LAYOUT' ); ?></legend>
                <div class="sortableList" id="groupLayout">
               <?php $width = $this->layout['width'] + 50;
				for( $i=1; $i<=count( $rows )+1; $i++ ){?>
                    <ul class="sortableRow" style="width:<?php echo $width;?>px">
                <?php
					for( $x=1; $x<=count( $rows ); $x++ ){
						if( $i == $x ){
							$icons = explode( ',', $rows[$x] );
							foreach( $icons as $icon ){	
								foreach( $this->plugins as $button ){
									if( $button->layout && $button->id == $icon ){
										$n = "group_li_". $button->id;
										$path = $button->type == 'command' ? '../plugins/editors/jce/tiny_mce/themes/advanced/img/'. $button->layout .'.gif' : '../plugins/editors/jce/tiny_mce/plugins/'. $button->name .'/img/'. $button->layout .'.gif';
										?>
										<li class="sortableItem" id="<?php echo $n;?>"><img src="<?php echo $path;?>" alt="<?php echo $button->title;?>" title="<?php echo $button->title;?>" /></li>
									<?php }
								}
							}
						}
					}
					?>
                    </ul>
				<?php }?>
            	</div>
                </fieldset>
                </td>
            </tr>
        </table>
    </fieldset>	
    <fieldset class="adminform">
    	<legend><?php echo JText::_( 'GROUPS OTHER PLUGINS' ); ?></legend>
        <table class="admintable">
            <tr><td colspan="2"><?php echo JText::_('GROUPS OTHER PLUGINS DESC');?></td></tr>
            <tr>
            	<?php 
				$i = 0;
				foreach( $this->plugins as $plugin ){
					 if( $plugin->layout == '' ){
						if( $plugin->editable ){?>
							<tr>
                        		<td><input type="checkbox" id="cb<?php echo $i;?>" name="pid[]" value="<?php echo $plugin->id;?>" onclick="isChecked(this.checked);setParams(this.value, this.checked);" <?php echo in_array( $plugin->id, explode( ',', $this->group->plugins ) ) ? 'checked="checked"' : '';?>/></td>
                        		<td><?php echo $plugin->title;?></td>
                        	</tr>
				 <?php }else{?>
							<tr>
                        		<td><input type="checkbox" id="cb<?php echo $i;?>" name="pid[]" value="<?php echo $plugin->id;?>" onclick="isChecked(this.checked);" <?php echo in_array( $plugin->id, explode( ',', $this->group->plugins ) ) ? 'checked="checked"' : '';?>/></td>
                        		<td><?php echo $plugin->title;?></td>
                        	</tr>
				<?php  }
					}
					$i++;
				}?>
            </tr>
        </table>
    </fieldset>
</div>
<div class="col width-40">
    <div id="editor_params">
        <fieldset class="adminform">
        <legend><?php echo JText::_( 'Editor Parameters' ); ?></legend>
        <?php
            jimport('joomla.html.pane');
            $pane =& JPane::getInstance('sliders');
            echo $pane->startPane("group-pane-editor");
            
            echo $pane->startPanel(JText :: _('Editor Setup'), "param-page");
            if($output = $this->params->render('params', 'groups-editor')) :
                echo $output;
            else :
                echo "<div style=\"text-align: center; padding: 5px; \">".JText::_('There are no parameters for this item')."</div>";
            endif;
            echo $pane->endPanel();
			echo $pane->startPanel(JText :: _('Editor Options'), "param-page");
            if($output = $this->params->render('params', 'groups-options')) :
                echo $output;
            else :
                echo "<div style=\"text-align: center; padding: 5px; \">".JText::_('There are no parameters for this item')."</div>";
            endif;
            echo $pane->endPanel();
			echo $pane->startPanel(JText :: _('Plugin Options'), "param-page");
            if($output = $this->params->render('params', 'groups-plugins')) :
                echo $output;
            else :
                echo "<div style=\"text-align: center; padding: 5px; \">".JText::_('There are no parameters for this item')."</div>";
            endif;
            echo $pane->endPanel();
            echo $pane->endPane();
        ?>
        </fieldset>
    </div>
    <div id="plugin_params">
        <fieldset class="adminform">
        <legend><?php echo JText::_( 'Plugin Parameters' ); ?></legend>
        <?php	
            foreach( $this->plugins as $plugin ){
                if( $plugin->editable ){			
                    jimport('joomla.filesystem.folder');
					jimport('joomla.filesystem.file');
					
					$path		= JPATH_PLUGINS .DS. 'editors' .DS. 'jce' .DS. 'tiny_mce' .DS. 'plugins' .DS. $plugin->name;
					$xmlPath 	= $path . DS . $plugin->name .'.xml';
                    $name 		= trim( $plugin->name ); 
                    $params 	= new JParameter( $this->group->params, $xmlPath );
					
					// Load Language for plugin
                    $lang =& JFactory::getLanguage();
                    $lang->load( 'com_jce_' . trim( $name ), JPATH_SITE );
                    
                    $display = in_array( $plugin->id, explode( ',', $this->group->plugins ) ) ? 'block' : 'none';
                    
                    if( $params->getNumParams('standard') || $params->getNumParams('defaults')|| $params->getNumParams('access') || $params->getNumParams('advanced') ) {
                ?>
                        <div id="plugin_params_<?php echo $plugin->id;?>" style="display:<?php echo $display;?>">
                        <fieldset class="adminform">
                        <legend><?php echo JText::_( $plugin->title ); ?></legend>
                <?php
                        $pane =& JPane::getInstance('sliders');
                        echo $pane->startPane("group-pane-".$name);
                        if($params->getNumParams('standard')) {
                            if($output = $params->render('params', 'standard')){
                                echo $pane->startPanel(JText :: _('STANDARD'), $name."-standard-page");
                                echo $output;
                                echo $pane->endPanel();
                            }
                        }
                        if($params->getNumParams('defaults')) {
                            if($output = $params->render('params', 'defaults')){
                                echo $pane->startPanel(JText :: _('DEFAULTS'), $name."-defaults-page");
                                echo $output;
                                echo $pane->endPanel();
                            }
                        }
                        if($params->getNumParams('access')) {
                            if($output = $params->render('params', 'access')){
                                echo $pane->startPanel(JText :: _('PERMISSIONS'), $name."-access-page");
                                echo $output;
                                echo $pane->endPanel();
                            }
                        }
                        if($params->getNumParams('advanced')) {
                            if($output = $params->render('params', 'advanced')){
                                echo $pane->startPanel(JText :: _('ADVANCED'), $name."-advanced-page");
                                echo $output;
                                echo $pane->endPanel();
                            }
                        }
						if( JFolder::exists( $path .DS. 'extensions' ) ){
							$db	=& JFactory::getDBO();
							$query = 'SELECT *'
							. ' FROM #__jce_extensions'
							. ' WHERE published = 1'
							. ' AND pid = '.(int) $plugin->id;
							;
							
							$db->setQuery( $query );
							$extensions = $db->loadObjectList();
							
							foreach( $extensions as $extension ){
								// Load extension xml file
								$file = $path .DS. 'extensions' .DS. $extension->folder .DS. $extension->extension . '.xml';
								// Load extension language file
								$lang =& JFactory::getLanguage();
                    			$lang->load( 'com_jce_' . trim( $name ) . '_' . trim( $extension->extension ), JPATH_SITE );
								
								if( JFile::exists( $file ) ){
							   		$params = new JParameter( $this->group->params, $file );
									if($params->getNumParams()) {
										if($output = $params->render('params')){
											echo $pane->startPanel(JText :: _( $extension->name ), $extension->extension."-extension-page");
											echo $output;
											echo $pane->endPanel();
										}
									}
								}
							}
						}
                        echo $pane->endPane();
                ?>
                        </fieldset>
                        </div>
            <?php
                    }
                }
            }
        ?>
        </fieldset>
	</div>
</div>
<div class="clr"></div>
	<input type="hidden" name="option" value="com_jce" />
	<input type="hidden" name="id" value="<?php echo $this->group->id; ?>" />
	<input type="hidden" name="cid[]" value="<?php echo $this->group->id; ?>" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="rows" value="" />
    <input type="hidden" name="type" value="group" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>