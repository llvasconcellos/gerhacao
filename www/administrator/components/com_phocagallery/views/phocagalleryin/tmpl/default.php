<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('behavior.tooltip'); ?>

<form action="index.php" method="post" name="adminForm">
<div style="float:right;margin:10px;">
	<?php
	echo JHTML::_('image.site',  'logo-phoca.png', '/components/com_phocagallery/assets/images/', NULL, NULL, 'Phoca.cz' )
	?>
</div>

<?php echo  JHTML::_('image', 'components/com_phocagallery/assets/images/icon-phoca-logo.png', 'Phoca.cz');?>

<h3><?php echo JText::_('Help');?></h3>

<p>
<a href="http://www.phoca.cz/phocagallery/" target="_blank">Phoca Gallery Main Site</a><br />
<a href="http://www.phoca.cz/documentation/" target="_blank">Phoca Gallery User Manual</a><br />
<a href="http://www.phoca.cz/forum/" target="_blank">Phoca Gallery Forum</a><br />
</p>

<h3><?php echo JText::_('Version');?></h3>
<p><?php echo $this->tmpl['version'] ;?></p>

<h3><?php echo JText::_('Copyright');?></h3>
<p>© 2007-2008 Jan Pavelka</p>

<h3><?php echo JText::_('License');?></h3>
<p><a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GPLv2</a></p>


<input type="hidden" name="controller" value="phocagallery" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="option" value="com_phocagallery" />
<input type="hidden" name="controller" value="phocagalleryin" />
</form>
<p>&nbsp;</p>

<div style="border-top:1px solid #c2c2c2"></div>
<div id="pg-update" ><a href="http://www.phoca.cz/version/index.php?phocagallery=<?php echo $this->tmpl['version'] ;?>" target="_blank"><?php echo JText::_('Check for update'); ?></a></div>
<div style="border-top:1px solid #c2c2c2"></div>
<h3><?php echo JText::_('Main Functions Support');?></h3>
<p><?php echo JText::_('List of Functions Support');?>:</p>
<?php echo $this->foutput; ?>
<div>&nbsp;</div>
<div style="border-top:1px solid #c2c2c2"></div>
<h3><?php echo JText::_('Thumbnail Creation Status');?></h3>
<?php echo $this->tmpl['enablethumbcreationstatus']; ?>