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
class JCE_layout {
    function buildRow( $row, $rownum, $width )
    {
      global $mainframe;

      $command_path = '/mambots/editors/jce/jscripts/tiny_mce/themes/advanced/images/';
      $plugin_path = '/mambots/editors/jce/jscripts/tiny_mce/plugins/';

	  echo "<ul class=\"sortGroup\" id=\"$rownum\" style=\"width:",$width+100,"px;\">\n";
	  $row_out = $rownum . '_out';
      foreach( $row as $item ){
        $row_num = $rownum . '_' . $item['id'];
        $name = $item['name'];
        switch( $item['type'] ){
            case 'command' :
                $img = $mainframe->getCfg('live_site') . $command_path . $item['layout_icon'].'.gif';
                echo "<li class=\"sortableitem\" id=\"$row_num\"><img src=\"$img\" height=\"20\" alt=\"$name\" title=\"$name\" /></li>\n";
            break;
            case 'plugin' :
                $img = $mainframe->getCfg('live_site') . $plugin_path . $item['plugin'].'/images/'.$item['layout_icon'].'.gif';
                echo "<li class=\"sortableitem\" id=\"$row_num\"><img src=\"$img\" height=\"20\" alt=\"$name\" title=\"$name\" /></li>\n";
            break;
        }
      }
         echo "</ul>\n";
         echo "<input type=\"hidden\" id=\"$row_out\" name=\"$rownum\" />\n";
    }
    function buildRowScript( $rows )
    {
         echo"<script type=\"text/javascript\">\n";
		 
         echo"// <![CDATA[\n";
           for( $x=1; $x <= $rows; $x++ ){
                    $arr[] = "'row$x'";
           }
           $row_list = implode( ",", $arr );
           for( $i=1; $i <= $rows; $i++ ){
                $row_out = 'row'.$i.'_out';
                echo "Sortable.create('row$i',\n";
                echo "{dropOnEmpty:true,containment:[$row_list],constraint:false,\n";
                echo "onChange:function(){\$('$row_out').value = Sortable.serialize('row$i') }});\n";
           }
         echo"// ]]>\n";
         echo"</script>";
    }

    /**
	* Writes a list of the defined modules
	* @param array An array of category objects
	*/
	function showLayout( $row1, $row2, $row3, $row4, $row5, $width, $height, $option, $client ) {
		global $my, $mainframe, $database;
		
		$database->setQuery( "SELECT lang FROM #__jce_langs WHERE published= '1'" );
        $lang = $database->loadResult();
        require_once( $mainframe->getCfg('absolute_path') . "/administrator/components/com_jce/language/".$lang.".php" );
		
		mosCommonHTML::loadOverlib();
		
		?>
		<script src="<?php echo $mainframe->getCfg('live_site');?>/administrator/components/com_jce/jscripts/prototype.js" type="text/javascript"></script>
		<script src="<?php echo $mainframe->getCfg('live_site');?>/administrator/components/com_jce/jscripts/scriptaculous.js" type="text/javascript"></script>
        <link href="<?php echo $mainframe->getCfg('live_site');?>/administrator/components/com_jce/css/layout.css" rel="stylesheet" type="text/css" />
		<style type="text/css">
          .editor {
            width: <?php echo $width;?>px;
          }
          </style>
		<form action="index2.php" method="post" name="adminForm">
		
		<table class="adminheading">
		<tr>
		  <th class="modules"><?php echo _JCE_LAYOUT_HEADING;?></th>
		</tr>
		<tr>
            <td>
            <p><?php echo _JCE_LAYOUT_MSG;?></p>
			<p><?php echo _JCE_LAYOUT_PLUGINS;?></p>
            </td>
		</tr>
		<tr>
		<td>
        <div class="editor">
            <?php echo JCE_layout::buildRow( $row1, 'row1', $width );?>
            <?php echo JCE_layout::buildRow( $row2, 'row2', $width );?>
            <?php echo JCE_layout::buildRow( $row3, 'row3', $width );?>
            <?php echo JCE_layout::buildRow( $row4, 'row4', $width );?>
			<?php echo JCE_layout::buildRow( $row5, 'row5', $width );?>
        </div>
        </td>
        </tr>
        </table>

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="client" value="<?php echo $client;?>" />
		<input type="hidden" name="hidemainmenu" value="0" /> 
		
		<?php echo JCE_layout::buildRowScript( 5 );?>      
		</form>
		<?php
	}
}
?>
