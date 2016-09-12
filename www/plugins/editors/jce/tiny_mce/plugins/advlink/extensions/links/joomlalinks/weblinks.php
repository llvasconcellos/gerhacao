<?php
// no direct access
defined( '_JCE_EXT' ) or die( 'Restricted access' );
class AdvlinkWeblinks {
	function getOptions(){
		$advlink =& AdvLink::getInstance();
		$list = '';
		if( $advlink->checkAccess( 'advlink_weblinks', '1' ) ){
			$list = '<li id="index.php?option=com_weblinks&view=categories"><div class="tree-row"><div class="tree-image"></div><span class="folder weblink nolink"><a href="javascript:;">' . JText::_('WEBLINKS') . '</a></span></div></li>';
		}
		return $list;	
	}
	function getItems( $args ){
		require_once( JPATH_SITE.DS.'includes'.DS.'application.php' );
		require_once( JPATH_SITE.DS.'components'.DS.'com_weblinks'.DS.'helpers'.DS.'route.php' );
		
		$items = array();

		switch( $args->view ){
			// Get all WebLink categories
			case 'categories':
				$categories = AdvLink::getCategory( 'com_weblinks' );
				foreach( $categories as $category ){
					$items[] = array(
						'id'		=>	'index.php?option=com_weblinks&view=category&id=' . $category->value,
						'name'		=>	$category->text,
						'class'		=>	'folder weblink'
					);
				}
				break;
			// Get all links in the category
			case 'category':				
				$weblinks = AdvlinkWeblinks::_weblinks( $args->id );
				foreach( $weblinks as $weblink ){
					$items[] = array(
						'id'		=>	WeblinksHelperRoute::getWeblinkRoute( $weblink->slug, $weblink->catslug ),
						'name'		=>	$weblink->text,
						'class'		=>	'file'
					);
				}
				break;
		}
		return $items;
	}
	function _weblinks( $id ){
		$db		=& JFactory::getDBO();
		$user	=& JFactory::getUser();
		
		$query = 'SELECT a.title AS text,'
		. ' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END as slug, '
		. ' CASE WHEN CHAR_LENGTH(b.alias) THEN CONCAT_WS(\':\', b.id, b.alias) ELSE b.id END as catslug'
		. ' FROM #__weblinks AS a'
		. ' INNER JOIN #__categories AS b ON b.id = '.(int) $id
		. ' WHERE a.published = 1'
		. ' AND a.catid = '.(int) $id
		. ' AND b.published = 1'
		. ' AND b.access <= '.(int) $user->get( 'aid' )
		. ' ORDER BY a.title'
		;
		
		$db->setQuery( $query, 0 );
		return $db->loadObjectList();
	}
}
?>
