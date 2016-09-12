<?php
/****************************************************************************
Component: Expose flash gallery component for Joomla 1.0.x and 1.5.x.
Version  : 4.6.3 (31/03/2008)
Author   : Josh, Steinthor, Bruno, Anything Digital
E-mail   : josh@gotgtek.net, steinthor@setjan.com, bruno@gotgtek.net
Web Site : www.gotgtek.net  www.slooz.com
Copyright: Copyright 2005-2008 by GTEK Technologies
License  : Expose is free for personal use. In a commercial setting, you need to
			purchase a one-site license. Check http://www.slooz.com for more info.
			The implementation into the Joomla CMR is published as GPL
			General Public License. Check http://www.gotgtek.net for details.
*****************************************************************************/

if(!(defined('_VALID_MOS')||defined('_JEXEC'))) die( 'Direct Access to this location is not allowed.' );

/* ?>
<script language="JavaScript" type="text/JavaScript">
function submitbutton(save)
{
	if (save == "save") {
		document.nsave.submit();
	}
	if (save == "uploadbg") {
		<?php  if ('JVERSION' >= '1.5.0') { ?>
			popupWindow('components/com_expose/uploadimg.php?directory=&amp;t=joomla_admin','win1',250,100,'no');
		<?php  }else{ ?>
			popupWindow('components/com_expose/uploadimage.php?directory=&amp;t=joomla_admin','win1',250,100,'no');
		<?php } ?>
	}
}
</SCRIPT>
*/

class HTML_content {
	// Translate all strings in Expose component
	function lbl( $IniVar) {
		if(function_exists('jimport')) { // J! 1.5.x versions
			return JText::_( $IniVar );
		} else { // J! 1.0.x versions
			global $mosConfig_locale;
			$expLocale = str_replace('_', '-', $mosConfig_locale);
			if (isset($expLocale) && is_file('../components/com_expose/'.$expLocale.'/'.$expLocale.'.com_expose.ini')) {
				$IniFile = '../components/com_expose/'.$expLocale.'/'.$expLocale.'.com_expose.ini';
			} else { // No translation file found for this language: use default en-GB
				$IniFile = "../components/com_expose/en-GB/en-GB.com_expose.ini";
			}
			$IniVar = strtoupper($IniVar);
			$Ini_File = file($IniFile);
			$Ini_Value = "";

			for($Ini_Rec=0; $Ini_Rec<sizeof($Ini_File); $Ini_Rec++) {
				$ispos = strpos($Ini_File[$Ini_Rec], '=');
				if (trim(substr($Ini_File[$Ini_Rec], 0, $ispos-1)) == $IniVar) {
					$Ini_Value = trim(substr($Ini_File[$Ini_Rec], $ispos+1));
					return $Ini_Value;
				}
			}

			if ( !$Ini_Value ) {
				return "ERROR .: Key: <b>".strtoupper($IniVar)."</b>, does not exist in <b>".strtoupper($IniFile)."</b> file !"; // Key or Variable NOT FOUND in INI file
			}
		}
	}

	function ShowHeader() {
		?><table id="toolbar" cellpadding="3" cellspacing="0" border="0" width="100%" >
			<tr valign="middle" align="right">
				<td colspan="6"><img src="../components/com_expose/expose/admin/logo.gif" /><br /><br /></td>
			</tr><?php

		//J! 1.0.x doesn't show submenu. Add it manually
		if(!function_exists('jimport')) {

			?><tr valign="middle" align="center">
				<td width="20%"><b><a href="index2.php?option=com_expose&task=manage"><?php echo '[' . HTML_content::lbl( 'TITLE_MANAGE' ) . ']'; ?></a></b></td>
				<td width="20%"><b><a href="index2.php?option=com_expose&task=config"><?php echo '[' . HTML_content::lbl( 'TITLE_CONFIG' ) . ']'; ?></a></b></td>
				<td width="20%"><b><a href="index2.php?option=com_expose&task=check"><?php echo '[' . HTML_content::lbl( 'TITLE_CHECK' ) . ']'; ?></a></b></td>
				<td width="20%"><b><a href="index2.php?option=com_expose&task=wiki"><?php echo '[' . HTML_content::lbl( 'TITLE_WIKI' ) . ']'; ?></a></b></td>
				<td width="20%"><b><a href="index2.php?option=com_expose&task=manual"><?php echo '[' . HTML_content::lbl( 'TITLE_MANUAL' ) . ']'; ?></a></b></td>
			</tr><?php

		}

		?></table><?php
	}

	function ShowManager() { 
		ob_start();
		require_once('../components/com_expose/expose/manager/manager.html.php');
		ob_end_flush();
	}

	function ShowCheck() {
		ob_start();
		require_once('../components/com_expose/expose/manager/check_system.php');
		doCheck();
		ob_end_flush();
	}

	function ShowRedirect($urlAddress, $msg) {
		if(function_exists('jimport')) {
			global $mainframe;
			$mainframe->redirect( $urlAddress, JText::_($msg) );
		} else {
			mosRedirect( $urlAddress, $msg );
		}
	}

	// Create tabs with grouped settings from config.xml and formats.xml
	function ShowConfig() {
		?><table class="adminheading">
			<tr><th nowrap="nowrap" class="config">
				Configuration
			</th></tr>
		</table>

		<form name="adminForm" method="post" action="index2.php?option=com_expose"><?php

		if(function_exists('jimport')) {
			jimport('joomla.html.pane');
			$pane =& JPane::getInstance('tabs');

			echo $pane->startPane("configMain");

			echo $pane->startPanel(HTML_content::lbl( 'TITLE_TAB_GENERAL' ),"general-page");
			HTML_content::ShowGroup("General");
			echo $pane->endPanel();

			echo $pane->startPanel(HTML_content::lbl( 'TITLE_TAB_MAIN' ),"main-page");
			HTML_content::ShowGroup("Main");
			echo $pane->endPanel();

			echo $pane->startPanel(HTML_content::lbl( 'TITLE_TAB_LIST' ),"list-page");
			HTML_content::ShowGroup("List");
			echo $pane->endPanel();

			echo $pane->startPanel(HTML_content::lbl( 'TITLE_TAB_STRIP' ),"strip-page");
			HTML_content::ShowGroup("Strip");
			echo $pane->endPanel();

			echo $pane->startPanel(HTML_content::lbl( 'TITLE_TAB_ALBUM' ),"album-page");
			HTML_content::ShowGroup("Album");
			echo $pane->endPanel();

			echo $pane->startPanel(HTML_content::lbl( 'TITLE_TAB_FONTS' ),"fonts-page");
			HTML_Content::readformats (realpath2 ("../components/com_expose/expose/config").DS);
			echo $pane->endPanel();

			echo $pane->startPanel(HTML_content::lbl( 'TITLE_TAB_SETTINGS' ),"upload-page");
			HTML_Content::readsettings (realpath2 ("../components/com_expose/expose/manager/amfphp/extra").DS);
			echo $pane->endPanel();

			echo $pane->endPane();
		} else {
			$tabs = new mosTabs(0);

			$tabs->startPane("configMain");

			$tabs->startTab(HTML_content::lbl( 'TITLE_TAB_GENERAL' ),"general-page");
			HTML_content::ShowGroup("General");
			$tabs->endTab();

			$tabs->startTab(HTML_content::lbl( 'TITLE_TAB_MAIN' ),"main-page");
			HTML_content::ShowGroup("Main");
			$tabs->endTab();

			$tabs->startTab(HTML_content::lbl( 'TITLE_TAB_LIST' ),"list-page");
			HTML_content::ShowGroup("List");
			$tabs->endTab();

			$tabs->startTab(HTML_content::lbl( 'TITLE_TAB_STRIP' ),"strip-page");
			HTML_content::ShowGroup("Strip");
			$tabs->endTab();

			$tabs->startTab(HTML_content::lbl( 'TITLE_TAB_ALBUM' ),"album-page");
			HTML_content::ShowGroup("Album");
			$tabs->endTab();

			$tabs->startTab(HTML_content::lbl( 'TITLE_TAB_FONTS' ),"fonts-page");
			HTML_Content::readformats (realpath2 ("../components/com_expose/expose/config")."/");
			$tabs->endTab();

			$tabs->startTab(HTML_content::lbl( 'TITLE_TAB_SETTINGS' ),"upload-page");
			HTML_Content::readsettings (realpath2 ("../components/com_expose/expose/manager/amfphp/extra")."/");
			$tabs->endTab();
			$tabs->endPane("configMain");
		}

		//add extra security field, close form
		?><input type="hidden" name="task" value=""/>
		</form><?php

		//load Joomla tooltip script
		//old expose tooltip script: <script src="../components/com_expose/expose/admin/boxover.js"></script>
		//<script  type="text/javascript" src="../includes/js/overlib_mini.js"></script>

	}

	// Load group of settings from config.xml into a table
	function ShowGroup($groupName) {
		if(!$dom = domxml_open_file(realpath2 ('../components/com_expose/expose/config').'/config.xml')) {
			die(str_replace('%xml%','config.xml',HTML_content::lbl('ERR_OPEN_XMLFILE')));
		}
		$root = $dom->document_element();
		$nodes = $root->child_nodes ();

		?><table class="adminlist">
			<tr><th colspan="3">
				<div align="center"><b><u>Params</u></b></div>
			</th></tr><?php
			for ($i = 0; $i < count ($nodes); $i++) {
				$node = $nodes[$i];
				if ($node->node_type () == XML_ELEMENT_NODE || XML_COMMENT_NODE) {
					if ($node->node_name () == "param") {
						if ($node->get_attribute ("group") == $groupName) {
							$setting = $node->get_attribute ("name"); // Name of setting
							$nvalue = $node->get_attribute ("value"); // Actual value
							//$ncomment = $node->get_attribute ("comment"); // Comment, replaced by translated strings file
							$nselect = $node->get_attribute ("select"); // Selectionbox content
							$ntype = $node->get_attribute ("type"); // Type of value that can be used
							//$settings[$setting] = $nvalue;

			?><tr class="row0">
				<td align="left">
					<b><?php echo $setting ?></b>
				</td>
				<td width = 60%" align="left">
					<?php echo HTML_content::lbl($setting); ?>
				</td>
				<td align="right"><?php

							$setting = str_replace(' ','_',$setting);
							if($setting == 'Gallery Background Image') {
								HTML_content::SelectBackground("../components/com_expose/expose/img/",$nvalue);
/*							} elseif ($setting == 'Language') {
								HTML_content::SelectLanguage($nvalue);
*/							} elseif ($nselect != '') {
								HTML_content::SelectContent($setting, $nvalue, $nselect);
								$nselect = '';
/*							} elseif ($ntype == 'boolean') {
								HTML_content::SelectContent($setting, $nvalue, 'yes;no');
*/							} elseif  ($ntype == 'hex') {
								HTML_content::SelectColor($setting, $nvalue);
							} else {
								HTML_content::SelectText($setting, $nvalue);
							}

				?></td>
			</tr><?php

						}
					}
				}
			}

		?></table><?php
		$dom->free();
	}

	// Populate font,color settings from formats.xml file into a table
	function readFormats ($path) {
		$album = array ();
		if(!$dom = domxml_open_file($path . "formats.xml")) {
			die(str_replace('%xml%','formats.xml',HTML_content::lbl('ERR_OPEN_XMLFILE')));
		}
		$root = $dom->document_element();
		$nodes = $root->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "format") {
					$album["id"] = $node->get_attribute ("id");
					$nid = $node->get_attribute ("id");

					?><table class="adminlist">
						<tr><th colspan="2" align="center">
							<b><u><?php echo $nid;  ?></u></b>
						</th></tr>
						<tr class="row0">
							<td align="left"><b><?php echo HTML_content::lbl('FONT_TYPE') ?></b></td>
							<td align="right"><?php HTML_content::SelectFont("font_".$nid, getNodeProperty ($node, "font")); ?></td>
						</tr>
						<tr class="row0">
							<td align="left"><b><?php echo HTML_content::lbl('FONT_SIZE') ?></b></td>
							<td align="right"><?php HTML_content::SelectText("size_".$nid, getNodeProperty ($node, "size")); ?></td>
						</tr>
						<tr class="row0">
							<td align="left"><b><?php echo HTML_content::lbl('FONT_COLOR') ?></b></td>
							<td align="right"><?php HTML_content::SelectColor("color_".$nid, getNodeProperty($node, "color")); ?></td>
						</tr>
						<tr class="row0">
							<td align="left"><b><?php echo HTML_content::lbl('FONT_SHADOWCOLOR') ?></b></td>
							<td align="right"><?php HTML_content::SelectColor("shadowcolor_".$nid, getNodeProperty($node, "shadowcolor")); ?></td>
						</tr>
						<tr class="row0">
							<td align="left"><b><?php echo HTML_content::lbl('FONT_SHADOWALPHA') ?></b></td>
							<td align="right"><?php HTML_content::SelectText("shadowalpha_".$nid, getNodeProperty($node, "shadowalpha")); ?></td>
						</tr>
						<tr class="row0">
							<td align="left"><b><?php echo HTML_content::lbl('FONT_HSHIFT') ?></b></td>
							<td align="right"><?php HTML_content::SelectText("hshift_".$nid, getNodeProperty($node, "hshift")); ?></td>
						</tr>
						<tr class="row0">
							<td align="left"><b><?php echo HTML_content::lbl('FONT_VSHIFT') ?></b></td>
							<td align="right"><?php HTML_content::SelectText("vshift_".$nid, getNodeProperty($node, "vshift")); ?></td>
						</tr>
						<tr class="row0">
							<td align="left"><b><?php echo HTML_content::lbl('FONT_ALIGN') ?></b></td>
							<td align="right"><?php HTML_content::SelectContent("align_".$nid, getNodeProperty($node, "align"), 'left;center;right'); ?></td>
						</tr>
					</table><?php

				}
			}
		}
		$dom->free();
	}

	// Populate Manager picture settings from settings.xml file into a table
	function readSettings ($path) {
//		$album = array ();
		if(!$dom = domxml_open_file($path . "settings.xml")) {
			die(str_replace('%xml%','settings.xml',HTML_content::lbl('ERR_OPEN_XMLFILE')));
		}
		$root = $dom->document_element();
		$nodes = $root->child_nodes ();

		?><table class="adminlist">
			<tr><th colspan="3">
				<div align="center"><b><u>Params</u></b></div>
			</th></tr><?php

		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "setting") {
					$setting = $node->get_attribute ("name");
					$ntype = $node->get_attribute ("type");
					$nvalue = $node->get_content ();

			?><tr class="row0">
				<td align="left">
					<b><?php echo $setting ?></b>
				</td>
				<td width = 60%" align="left">
					<?php echo HTML_content::lbl($setting); ?>
				</td>
				<td align="right"><?php

//							if($setting == 'Gallery Background Image') {
//								HTML_content::SelectBackground("../components/com_expose/expose/img/",$nvalue);
//							} elseif ($setting == 'Language') {
//								HTML_content::SelectLanguage($nvalue); }
							if ($ntype == 'boolean') {
								HTML_content::SelectContent($setting, $nvalue, 'true;false');
							} elseif  ($ntype == 'hex') {
								HTML_content::SelectColor($setting, $nvalue);
							} else {
								HTML_content::SelectText($setting, $nvalue);
							}

				?></td>
			</tr><?php

				}
			}
		}

		?></table><?php

		$dom->free();
	}

	function SelectBackground($dir, $nvalue) {
		echo "<select name='Gallery Background Image'>";
		echo "<option value=''>None</option>";
		$dirpath = $dir;
		$xmlF = expose_M::rpath();
		$dh = opendir($dirpath);

		while (false !== ($file = readdir($dh))) {
			$filename = "img/".$file;
			if($filename == $nvalue) {
				$sel = "SELECTED";
			}
			if (!is_dir("$dirpath/$file")) {
				if ((strcasecmp(substr($file,-4),'.jpg'))) {
				} else {
					echo "<option value='img/$file'>$sel>$file</option>";
				}
			}
			$sel = '';
		}

		closedir($dh);
		echo "</select>&nbsp;&nbsp;";
	}

/*	function SelectLanguage($nvalue) {
		if(!$langdom = domxml_open_file("../components/com_expose/expose/config/strings.xml")) {
			die(str_replace('%xml%','strings.xml',HTML_content::lbl('ERR_OPEN_XMLFILE')));
		}
		$langroot = $langdom->document_element();
		$langnodes = $langroot->child_nodes();
		$sublangnodes = $langnodes[1]->child_nodes();
		$nvalue = strtolower($nvalue);

		echo "<select name='Language'>";
		for ($i = 0; $i < count ($sublangnodes); $i++) {
			if ($sublangnodes[$i]->node_type () == XML_ELEMENT_NODE) {
				$nlang = strtolower($sublangnodes[$i]->node_name());
				echo "<option value='".$nlang."' "; echo $nvalue==$nlang ? 'SELECTED' : ''; echo ">".$nlang."</option>";
			}
		}
		echo "</select>&nbsp;&nbsp;";
	} */

	function SelectContent($setting, $nvalue, $nselection) {
		echo "<select name='$setting'>\n";
		$selects = split(';', $nselection);
		foreach($selects as $select) {
			echo "<option value='$select' ";
			echo ($nvalue == $select) ? 'SELECTED >' : '>';
			echo HTML_content::lbl(strtoupper($select)) . "</option>\n";
		}
		echo "</select>&nbsp;&nbsp;\n";
	}

	function SelectColor($setting, $nvalue) {
		$nvalue = strtolower($nvalue);
		echo "<input type='text' name='$setting' value='$nvalue' size='12' maxlength='6'>&nbsp;&nbsp;";
	}

	function SelectText($setting, $nvalue) {
		//$nvalue = strtolower($nvalue); Some settings are case-sensitive!!
		echo "<input type='text' name='$setting' value='$nvalue' size='12'>&nbsp;&nbsp;";
	}

	function SelectFont($setting, $nvalue) {
		$nvalue = strtolower($nvalue);
		echo "<input type='hidden' readonly name='$setting' value='$nvalue' size='12'>$nvalue&nbsp;&nbsp;";
	}

} //end class
?>
