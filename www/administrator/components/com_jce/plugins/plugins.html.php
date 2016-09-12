<?php
/**
* @version $Id: admin.mambots.html.php 85 2005-09-15 23:12:03Z eddieajau $
* @package Joomla
* @subpackage Mambots
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

/**
* @package Joomla
* @subpackage Mambots
*/
class JCE_plugins {
    function AccessList(){
        $access_list = array(
                    mosHTML::makeOption( '','Select Access Level' ),
					mosHTML::makeOption( '0','Guest' ),
                    mosHTML::makeOption( '18','-Registered' ),
                    mosHTML::makeOption( '19','--Author' ),
                    mosHTML::makeOption( '20','---Editor' ),
                    mosHTML::makeOption( '21','----Publisher' ),
                    mosHTML::makeOption( '23','-----Manager' ),
                    mosHTML::makeOption( '24','------Administrator' ),
                    mosHTML::makeOption( '25','-------Super Administrator' )
        );

        $lists['access'] = mosHTML::selectList( $access_list, 'access', 'class="inputbox" size="1"', 'value', 'text' );

        return $lists['access'];
    }

    /**
	* Writes a list of the defined modules
	* @param array An array of category objects
	*/
	function showPlugins( &$rows, $client, &$pageNav, $option, &$lists, $search ) {
		global $my, $mosConfig_live_site, $mosConfig_absolute_path, $database;

        $database->setQuery( "SELECT lang FROM #__jce_langs WHERE published= '1'" );
        $lang = $database->loadResult();
        require_once( $mosConfig_absolute_path."/administrator/components/com_jce/language/".$lang.".php" );

		mosCommonHTML::loadOverlib();
		$access = JCE_plugins::AccessList();
		?>
		<form action="index2.php" method="post" name="adminForm">

		<table class="adminheading">
		<tr>
			<th class="modules">
			<?php echo _JCE_PLUGIN_HEADING;?> <small><small>[ <?php echo $client == 'admin' ? 'Administrator' : 'Site';?> ]</small></small>
			</th>
			<td>
			<?php echo _JCE_PLUGIN_FILTER;?>:
			</td>
			<td>
			<input type="text" name="search" value="<?php echo $search;?>" class="text_area" onChange="document.adminForm.submit();" />
			</td>
			<td width="right">
			<?php echo $lists['type'];?>
			</td>
		</tr>
		<tr>
            <td colspan="4" align="right">
			<?php echo _JCE_PLUGIN_ACCESS_LIST;?>: <?php echo $access;?>
			</td>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th width="20">#</th>
			<th width="20">
            <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows );?>);" />
            </th>
			<th class="title">
			<?php echo _JCE_PLUGIN_NAME;?>
			</th>
			<th nowrap="nowrap" width="10%">
	  		<?php echo _JCE_PUBLISHED;?>
			</th>
			<th width="2%">
			<?php echo _JCE_PLUGIN_ROW;?>
			</th>
			<th width="2%">
			<?php echo _JCE_PLUGIN_ORDER;?>
			</th>
  	        <th nowrap="nowrap" width="10%">
			<?php echo _JCE_PLUGIN_ACCESS_LVL;?>
			</th>
			<th nowrap="nowrap" width="10%">
			<?php echo _JCE_PLUGIN_CORE;?>
			</th>
			<th nowrap="nowrap" width="10%">
			<?php echo _JCE_PLUGIN_TYPE;?>
			</th>
			<th nowrap="nowrap" width="10%">
			<?php echo _JCE_PLUGIN_ICON;?>
			</th>
			<th nowrap="nowrap" width="10%">
			<?php echo _JCE_PLUGIN_PLUGCOM;?>
			</th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row 	= &$rows[$i];
            $link = 'index2.php?option=com_jce&client='. $client .'&task=editplugin&hidemainmenu=1&id='. $row->id;
            //$access 	= JCE_plugins::AccessProcessing( $row, $i );
            $checked 	= mosCommonHTML::CheckedOutProcessing( $row, $i );
			$published 	= mosCommonHTML::PublishedProcessing( $row, $i );
			$core = ( $row->iscore == 1 ) ? 'Yes' : 'No';
			switch( $row->access ){
                case '0':
                    $access_value = 'Guest';
                break;
				case '18':
                    $access_value = 'Registered';
                break;
                case '19':
                    $access_value = 'Author';
                break;
                case '20':
                    $access_value = 'Editor';
                break;
                case '21':
                    $access_value = 'Publisher';
                break;
                case '23':
                    $access_value = 'Manager';
                break;
                case '24':
                    $access_value = 'Administrator';
                break;
                case '25':
                    $access_value = 'Super Administrator';
                break;
            }
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td align="right"><?php echo $pageNav->rowNumber( $i ); ?></td>
				<td>
				<?php echo $checked; ?>
				</td>
				<td>
				<?php
				if ( $row->checked_out && ( $row->checked_out != $my->id ) || $row->editable == '0') {
					echo $row->name;
				} else {
					?>
					<a href="<?php echo $link; ?>">
					<?php echo $row->name; ?>
					</a>
					<?php
				}
				?>
				</td>
				<td align="center">
				<?php echo $published;?>
				</td>
				<td align="center">
				<?php echo $row->row; ?>
				</td>
				<td align="center">
				<?php echo $row->ordering; ?>
				</td>
				<td align="center">
                <?php echo $access_value;?>
				</td>
				<td align="center">
                <?php echo $core;?>
				</td>
				<td align="center">
				<?php echo $row->type;?>
				</td>
				<td align="center">
				<?php if( !empty( $row->layout_icon )  ){
                    if( $row->type == 'plugin' ){
                        $icon_path = $mosConfig_live_site."/mambots/editors/jce/jscripts/tiny_mce/plugins/".$row->plugin."/images/".$row->layout_icon.".gif";
                    }else{
                        $icon_path = $mosConfig_live_site."/mambots/editors/jce/jscripts/tiny_mce/themes/advanced/images/".$row->layout_icon.".gif";
                    }
                ?>
                    <img src="<?php echo $icon_path;?>" alt="<?php echo $row->name;?>" title="<?php echo $row->name;?>" height="20" />
                <?php }?>
                </td>
				<td align="center">
				<?php echo $row->plugin;?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>

		<?php echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="showplugins" />
		<input type="hidden" name="client" value="<?php echo $client;?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
		<?php
	}
	/**
	* Writes the edit form for new and existing module
	*
	* A new record is defined when <var>$row</var> is passed with the <var>id</var>
	* property set to 0.
	* @param mosCategory The category object
	* @param array <p>The modules of the left side.  The array elements are in the form
	* <var>$leftorder[<i>order</i>] = <i>label</i></var>
	* where <i>order</i> is the module order from the db table and <i>label</i> is a
	* text label associciated with the order.</p>
	* @param array See notes for leftorder
	* @param array An array of select lists
	* @param object Parameters
	*/
	function editPlugins( &$row, &$lists, &$params, $option ) {
		global $mainframe, $database;

        $database->setQuery( "SELECT lang FROM #__jce_langs WHERE published= '1'" );
        $lang = $database->loadResult();
        require_once( $mainframe->getCfg('absolute_path')."/administrator/components/com_jce/language/" . $lang . ".php" );

		$row->nameA = '';
		if ( $row->id ) {
			$row->nameA = '<small><small>[ '. $row->name .' ]</small></small>';
		}
		$row_row = ( $row->row ) ? $row->row : '4';
		$row_ordering = ( $row->ordering ) ? $row->ordering : '1';
		?>
		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			if (pressbutton == "cancelplugin") {
				submitform(pressbutton);
				return;
			}
			// validation
			var form = document.adminForm;
			submitform(pressbutton);
		}
		</script>
		<table class="adminheading">
		<tr>
			<th class="mambots">
			JCE Plugin:
			<small>
			<?php echo $row->id ? _JCE_PLUGIN_EDIT : _JCE_PLUGIN_NEW;?>
			</small>
			<?php echo $row->nameA; ?>
			</th>
		</tr>
		</table>

		<form action="index2.php" method="post" name="adminForm">
		<table cellspacing="0" cellpadding="0" width="100%">
		<tr valign="top">
			<td width="60%" valign="top">
				<table class="adminform">
				<tr>
					<th colspan="2">
					<?php echo _JCE_PLUGIN_DETAILS;?>
					</th>
				<tr>
				<tr>
					<td width="100" align="left">
					<?php echo _JCE_PLUGIN_NAME;?>:
					</td>
					<td>
					<input class="text_area" type="text" name="name" size="35" value="<?php echo $row->name; ?>" />
					</td>
				</tr>
				<tr>
					<td valign="top" align="left">
					<?php echo _JCE_PLUGIN_PLUGIN;?>:
					</td>
					<td>
					<input class="text_area" type="text" name="plugin" size="35" value="<?php echo $row->plugin; ?>" />
					</td>
				</tr>
				<tr>
					<td valign="top" align="left">
					<?php echo _JCE_PLUGIN_TYPE;?>:
					</td>
					<td>
                    <?php echo _JCE_PLUGIN_PLUGIN;?>
                    <input type="hidden" name="type" value="plugin" />
					</td>
				</tr>
				<tr>
					<td valign="top" align="left">
					<?php echo _JCE_PLUGIN_ICON;?>:
					</td>
					<td>
					<input class="text_area" type="text" name="icon" size="35" value="<?php echo $row->icon; ?>" />
					</td>
				</tr>
				<tr>
					<td valign="top" align="left">
					<?php echo _JCE_PLUGIN_LAYOUT_ICON;?>:
					</td>
					<td>
					<input class="text_area" type="text" name="layout_icon" size="35" value="<?php echo $row->layout_icon; ?>" />
					</td>
				</tr>
				<tr>
					<td valign="top" align="left">
					<?php echo _JCE_PLUGIN_ACCESS_LVL;?>:
					</td>
					<td>
                    <?php echo $lists['access']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="left">
					<?php echo _JCE_PLUGIN_ROW;?>:
					</td>
					<td>
                    <?php echo $row_row; ?><input type="hidden" name="row" value="<?php echo $row_row;?>" />
					</td>
				</tr>
				<tr>
					<td valign="top" align="left">
					<?php echo _JCE_PLUGIN_ORDER;?>:
					</td>
					<td>
                    <?php echo $row_ordering; ?><input type="hidden" name="ordering" value="<?php echo $row_ordering; ?>" />
					</td>
				</tr>
				<tr>
					<td valign="top">
					<?php echo _JCE_PUBLISHED;?>:
					</td>
					<td>
					<?php echo $lists['published']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top">
					<?php echo _JCE_PLUGIN_ELMS;?>:
					</td>
					<td><input class="text_area" type="text" name="elements" size="35" value="<?php echo $row->elements; ?>" />
					</td>
				</tr>
				<tr>
					<td valign="top">
					<?php echo _JCE_PLUGIN_DESC;?>:
					</td>
					<td>
					<?php echo $row->description; ?>
					</td>
				</tr>
				</table>
			</td>
			<td width="40%">
				<table class="adminform">
				<tr>
					<th colspan="2">
					<?php echo _JCE_PLUGIN_PARAMS;?>
					</th>
				<tr>
				<tr>
					<td>
					<?php
					if ( $row->id ) {
						echo $params->render();
					} else {
						echo '<i>No Parameters</i>';
					}
					?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>

		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="client" value="<?php echo $row->client_id; ?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<script language="Javascript" src="<?php echo $mainframe->getCfg('live_site');?>/includes/js/overlib_mini.js"></script>
		<?php
	}
}
?>
