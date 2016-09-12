<?php
/**
* @version $Id: admin.jce.html.php,v 1.0 2006/11/01 Ryan Demmer$
* @package JCE Admin
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class HTML_JCEAdmin {
    function showAdmin()
    {
        global $mainframe;
        ?>
        <form action="index2.php" method="post" name="adminForm">

		<table class="adminheading">
		<tr>
			<th class="cpanel">
			JCE Configuration
			</th>
        </tr>
        <tr>
        <td width="55%" valign="top">
	    <div id="cpanel">
            <div style="float:left;">
        		<div class="icon">
        			<a href="index2.php?option=com_jce&task=config&hidemainmenu=1">
        				<div class="iconimage">
        					<img src="<?php echo $mainframe->getCfg('live_site');?>/administrator/images/config.png" alt="Configuration" align="middle" name="image" border="0" />				</div>
        				Editor Configuration</a>
        		</div>
    		</div>
    		<div style="float:left;">
        		<div class="icon">
        			<a href="index2.php?option=com_jce&task=showplugins">
        				<div class="iconimage">
        					<img src="<?php echo $mainframe->getCfg('live_site');?>/administrator/images/module.png" alt="Show Plugins" align="middle" name="image" border="0" />				</div>
        				Show Plugins</a>
        		</div>
    		</div>
    		<div style="float:left;">
        		<div class="icon">
        			<a href="index2.php?option=com_jce&task=install&element=plugins">
        				<div class="iconimage">
        					<img src="<?php echo $mainframe->getCfg('live_site');?>/administrator/images/install.png" alt="Install Plugins" align="middle" name="image" border="0" />				</div>
        				Install Plugins</a>
        		</div>
    		</div>
    		<div style="float:left;">
        		<div class="icon">
        			<a href="index2.php?option=com_jce&task=editlayout&hidemainmenu=1">
        				<div class="iconimage">
        					<img src="<?php echo $mainframe->getCfg('live_site');?>/administrator/images/templatemanager.png" alt="Edit Layout" align="middle" name="image" border="0" />				</div>
        				Edit Layout</a>
        		</div>
    		</div>
    		<div style="float:left;">
        		<div class="icon">
        			<a href="index2.php?option=com_jce&task=lang&hidemainmenu=1">
        				<div class="iconimage">
        					<img src="<?php echo $mainframe->getCfg('live_site');?>/administrator/images/langmanager.png" alt="Language Manager" align="middle" name="image" border="0" />				</div>
        				Language Manager</a>
        		</div>
    		</div>
		</div>
		</td>
        </tr>
        </table>
        <?php
    }
}
?>
