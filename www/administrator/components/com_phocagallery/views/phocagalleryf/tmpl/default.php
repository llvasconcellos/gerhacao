<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php echo $this->loadTemplate('up'); ?>
<?php if (count($this->folders) > 0) { ?>
<div>
		<?php for ($i=0,$n=count($this->folders); $i<$n; $i++) :
			$this->setFolder($i);
			echo $this->loadTemplate('folder');
		endfor; ?>

</div>
<?php } else { ?>
<div>
	<center style="clear:both;font-size:large;font-weight:bold;color:#b3b3b3;font-family: Helvetica, sans-serif;">
		<?php echo JText::_( 'There is no folder' ); ?>
	</center>
</div>
<?php } ?>




