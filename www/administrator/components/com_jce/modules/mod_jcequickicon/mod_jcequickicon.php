<?php
/**
* @version		$Id: mod_quickicon.php 9764 2007-12-30 07:48:11Z ircmaxell $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if (!defined( '_JCE_QUICKICON_MODULE' ))
{
	/** ensure that functions are declared only once */
	define( '_JCE_QUICKICON_MODULE', 1 );
	
	require_once( JPATH_COMPONENT .DS. 'helper.php' );

	function quickiconButton( $link, $image, $text )
	{
		global $mainframe;
		$lang		=& JFactory::getLanguage();
		$template	= $mainframe->getTemplate();
		
		?>
		<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
			<div class="icon">
				<a href="<?php echo $link; ?>">
					<?php echo JHTML::_('image.site',  $image, '/templates/'. $template .'/images/header/', NULL, NULL, $text ); ?>
					<span><?php echo $text; ?></span></a>
			</div>
		</div>
		<?php
	}

	?>
	<div id="cpanel">
		<?php
		$link = 'index.php?option=com_jce&amp;type=config';
		quickiconButton( $link, 'icon-48-config.png', JText::_( 'Configuration' ) );

		$link = 'index.php?option=com_jce&amp;type=plugin';
		quickiconButton( $link, 'icon-48-plugin.png', JText::_( 'Plugins' ) );
		
		$link = 'index.php?option=com_jce&amp;type=group';
		quickiconButton( $link, 'icon-48-user.png', JText::_( 'Groups' ) );

		$link = 'index.php?option=com_jce&amp;type=install';
		quickiconButton( $link, 'icon-48-install.png', JText::_( 'Install' ) );

		if( !JCEHelper::checkEditorInstall() && JCEHelper::checkEditorPath() ){
			$link = 'index.php?option=com_jce&amp;type=fixinstall-editor';
			quickiconButton( $link, 'icon-48-cpanel.png', JText::_( 'Install Editor' ) );
		}
		if( !JCEHelper::checkPlugins() ){
			$link = 'index.php?option=com_jce&amp;type=fixinstall-plugins';
			quickiconButton( $link, 'icon-48-cpanel.png', JText::_( 'Fix Plugins' ) );
		}
		if( !JCEHelper::checkGroups() ){
			$link = 'index.php?option=com_jce&amp;type=fixinstall-groups';
			quickiconButton( $link, 'icon-48-cpanel.png', JText::_( 'Fix Groups' ) );
		}

		?>
	</div>
    <div class="clr"></div>
	<?php
}