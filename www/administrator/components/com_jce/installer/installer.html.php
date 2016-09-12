<?php
/**
* @version $Id: admin.installer.html.php 85 2005-09-15 23:12:03Z eddieajau $
* @package Joomla
* @subpackage Installer
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

function writableCell( $folder ) {
	echo '<tr>';
	echo '<td class="item">' . $folder . '/</td>';
	echo '<td align="left">';
	echo is_writable( $GLOBALS['mosConfig_absolute_path'] . '/' . $folder ) ? '<b><font color="green">Writeable</font></b>' : '<b><font color="red">Unwriteable</font></b>' . '</td>';
	echo '</tr>';
}

/**
* @package Joomla
*/
class HTML_installer {

	function showInstallForm( $title, $option, $element, $client = "", $p_startdir = "", $backLink="" ) {
        global $mosConfig_absolute_path, $database;

        $database->setQuery( "SELECT lang FROM #__jce_langs WHERE published= '1'" );
        $lang = $database->loadResult();
        require_once( $mosConfig_absolute_path."/administrator/components/com_jce/language/".$lang.".php" );
        ?>
		<script language="javascript" type="text/javascript">
		function submitbutton3(pressbutton) {
			var form = document.adminForm_dir;

			// do field validation
			if (form.userfile.value == ""){
				alert( "Please select a directory" );
			} else {
				form.submit();
			}
		}
		</script>
		<form enctype="multipart/form-data" action="index2.php" method="post" name="filename">
		<table class="adminheading">
		<tr>
			<th class="install">
			<?php echo $title;?>
			</th>
			<td align="right" nowrap="true">
			<?php echo $backLink;?>
			</td>
		</tr>
		</table>

		<table class="adminform">
		<tr>
			<th>
			<?php echo _JCE_INS_PACKAGE_UPLOAD;?>
			</th>
		</tr>
		<tr>
			<td align="left">
			<?php echo _JCE_INS_PACKAGE_FILE;?>:
			<input class="text_area" name="userfile" type="file" size="70"/>
			<input class="button" type="submit" value="<?php echo _JCE_INS_UPLOAD_BUTTON;?>" />
			</td>
		</tr>
		</table>

		<input type="hidden" name="task" value="uploadfile"/>
		<input type="hidden" name="option" value="<?php echo $option;?>"/>
		<input type="hidden" name="element" value="<?php echo $element;?>"/>
		<input type="hidden" name="client" value="<?php echo $client;?>"/>
		</form>
		<br />

		<form enctype="multipart/form-data" action="index2.php" method="post" name="adminForm_dir">
		<table class="adminform">
		<tr>
			<th>
			<?php echo _JCE_INS_INSTALL;?>
			</th>
		</tr>
		<tr>
			<td align="left">
			<?php echo _JCE_INS_INSTALL_DIR;?>:&nbsp;
			<input type="text" name="userfile" class="text_area" size="65" value="<?php echo $p_startdir; ?>"/>&nbsp;
			<input type="button" class="button" value="<?php echo _JCE_INS_INSTALL_BUTTON;?>" onclick="submitbutton3()" />
			</td>
		</tr>
		</table>

		<input type="hidden" name="task" value="installfromdir" />
		<input type="hidden" name="option" value="<?php echo $option;?>"/>
		<input type="hidden" name="element" value="<?php echo $element;?>"/>
		<input type="hidden" name="client" value="<?php echo $client;?>"/>
		</form>
		<?php
	}

	/**
	* @param string
	* @param string
	* @param string
	* @param string
	*/
	function showInstallMessage( $message, $title, $url ) {
		global $PHP_SELF;
		?>
		<table class="adminheading">
		<tr>
			<th class="install">
			<?php echo $title; ?>
			</th>
		</tr>
		</table>

		<table class="adminform">
		<tr>
			<td align="left">
			<strong><?php echo $message; ?></strong>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
			[&nbsp;<a href="<?php echo $url;?>" style="font-size: 16px; font-weight: bold">Continue ...</a>&nbsp;]
			</td>
		</tr>
		</table>
		<?php
	}
}
?>
