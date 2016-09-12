<?php

defined('_JEXEC') or die('Restricted access');

function com_install() {
    global $mainframe;
	$db =& JFactory::getDBO();
	jimport('joomla.filesystem.folder');
	
	$src 	= 'components' .DS. 'com_jce' .DS. 'modules' .DS. 'mod_jcequickicon';
	$dest 	= 'modules' .DS. 'mod_jcequickicon';
	
	if( !JFolder::copy( $src, $dest, JPATH_ADMINISTRATOR, true ) ){
		$mainframe->enqueueMessage( JText::_('Unable to install control panel icon module!') );
	}

	$row = & JTable::getInstance('module');
	$row->title = 'JCE Latest News';
	$row->ordering = $row->getNextOrder( "position='jce_cpanel'" );
	$row->position = 'jce_cpanel';
	$row->published = 1;
	$row->showtitle = 1;
	$row->iscore = 0;
	$row->access = 0;
	$row->client_id = 1;
	$row->module = 'mod_feed';
	$row->params = 'cache=1
	cache_time=15
	moduleclass_sfx=
	rssurl=http://www.joomlacontenteditor.net/index.php?option=com_rss&feed=RSS2.0&type=com_frontpage&Itemid=1
	rssrtl=0
	rsstitle=0
	rssdesc=0
	rssimage=0
	rssitems=3
	rssitemdesc=1
	word_count=100';
	if (!$row->store()) {
		$mainframe->enqueueMessage( JText::_('Unable to insert feed Module data!') );
	}	
	
	$row = & JTable::getInstance('module');
	$row->title = 'JCE Control Panel Icons';
	$row->ordering = $row->getNextOrder( "position='jce_icon'" );
	$row->position = 'jce_icon';
	$row->published = 1;
	$row->showtitle = 0;
	$row->iscore = 0;
	$row->access = 0;
	$row->client_id = 1;
	$row->module = 'mod_jcequickicon';
	$row->params = '';

	if (!$row->store()) {
		$mainframe->enqueueMessage( JText::_('Unable to insert Control Panel icon Module data!') );
	}
	
	unset( $row );
}
function com_uninstall() {
	global $mainframe;
	$db =& JFactory::getDBO();
	jimport('joomla.filesystem.folder');
	jimport('joomla.filesystem.file');
	
	$path = JPATH_ADMINISTRATOR .DS. 'modules' .DS. 'mod_jcequickicon';
	
	if( !JFolder::delete( $path ) ){
		$mainframe->enqueueMessage( JText::_('Unable to remove control panel icon module!') );
	}
	
	$query = "DELETE FROM #__modules"
	." WHERE module = 'mod_feed'"
	." AND position = 'jce_cpanel'"
	." AND title = 'JCE Latest News'";
	$db->setQuery( $query );
	
	if( !$db->query() ){
		$mainframe->enqueueMessage( JText::_('Unable to remove Feed Module data!') );
	}
	
	$query = "DELETE FROM #__modules"
	." WHERE module = 'mod_jcequickicon'";
	$db->setQuery( $query );
	
	if( !$db->query() ){
		$mainframe->enqueueMessage( JText::_('Unable to remove Control Panel icon Module data!') );
	}
	
	$query = "DROP TABLE IF EXISTS #__jce_plugins";
	$db->setQuery( $query );
	
	if( !$db->query() ){
		$mainframe->enqueueMessage( JText::_('Unable to remove JCE Plugins Table!'), 'error' );
	}
	
	$query = "DROP TABLE IF EXISTS #__jce_extensions";
	$db->setQuery( $query );
	
	if( !$db->query() ){
		$mainframe->enqueueMessage( JText::_('Unable to remove JCE Extensions Table!'), 'error' );
	}
	
	$query = "DROP TABLE IF EXISTS #__jce_groups";
	$db->setQuery( $query );
	
	if( !$db->query() ){
		$mainframe->enqueueMessage( JText::_('Unable to remove JCE Groups Table!'), 'error' );
	}
	
	// Remove plugin
	
	$query = 'DELETE FROM #__plugins'
	. ' WHERE folder = '. $db->Quote( 'editors' )
	. ' AND element = '. $db->Quote( 'jce' )
	;
	$db->setQuery( $query );
	
	if( !$db->query() ){
		$mainframe->enqueueMessage( JText::_('Unable to remove JCE Plugin!'), 'error' );
	}
	
	if( !JFolder::delete( JPATH_PLUGINS .DS. 'editors' .DS. 'jce' ) ){
		$mainframe->enqueueMessage( JText::_('Unable to delete plugin folder!') );
	}
	
	if( !JFile::delete( JPATH_PLUGINS .DS. 'editors' .DS. 'jce.xml' ) && !JFile::delete( JPATH_PLUGINS .DS. 'editors' .DS. 'jce.php' ) ){
		$mainframe->enqueueMessage( JText::_('Unable to delete plugin files!') );
	}
	
	// Remove languages
	$exclude = array(
		'en-GB.com_jce.ini', 
		'en-GB.com_jce.xml', 
		'en-GB.com_jce_media.ini', 
		'en-GB.com_jce_imgmanager.ini', 
		'en-GB.com_jce_advlink.ini', 
		'en-GB.com_jce_paste.ini', 
		'en-GB.com_jce_spellchecker.ini'
	);
	$files = array_merge( 
		JFolder::files( JPATH_SITE .DS. 'language', '(com_jce)', true, true, $exclude ), 
		JFolder::files( JPATH_ADMINISTRATOR .DS. 'language', '(com_jce)', true, true, array( 'en-GB.com_jce.ini' ) ) 
	);
	JFile::delete( $files );
}