<?php
// no direct access
defined( '_JCE_EXT' ) or die( 'Restricted access' );
class AdvlinkMenu {
	function getOptions(){
		$advlink =& AdvLink::getInstance();
		$list = '';
		if( $advlink->checkAccess( 'advlink_menu', '1' ) ){
			$list = '<li id="index.php?option=com_menu"><div class="tree-row"><div class="tree-image"></div><span class="folder menu nolink"><a href="javascript:;">' . JText::_('MENU') . '</a></span></div></li>';
		}
		return $list;	
	}
	function getItems( $args ){		
		$items 	= array();
		$view	= isset( $args->view ) ? $args->view : '';
		switch( $view ){
			default:
				$menus = AdvlinkMenu::_menu();
				foreach( $menus as $menu ){
					$children = AdvlinkMenu::_children( $menu->id );
					$items[] = array(
						'id'		=>	$children ? 'index.php?option=com_menu&view=submenu&id=' . $menu->id : $menu->value . '&Itemid=' . $menu->id,
						'url'		=>	$children ? '' : $menu->value . '&Itemid=' . $menu->id,
						'name'		=>	$menu->text,
						'class'		=>	$children ? 'folder menu' : 'file'
					);
				}
				break;
			case 'submenu':
				$menus = AdvlinkMenu::_menu( $args->id );
				foreach( $menus as $menu ){
					$children = AdvlinkMenu::_children( $menu->id );
					$items[] = array(
						'id'		=>	$children ? 'index.php?option=com_menu&view=submenu&id=' . $menu->id : '',
						'url'		=>	$menu->value . '&Itemid=' . $menu->id,
						'name'		=>	$menu->text,
						'class'		=>	$children ? 'folder menu' : 'file'
					);
				}
				break;
		}
		return $items;
	}
	function _children( $id ){
		$db		=& JFactory::getDBO();
		$user	=& JFactory::getUser();
		
		$query = 'SELECT COUNT(id)'
		. ' FROM #__menu'
		. ' WHERE published = 1'
		. ' AND parent = '.(int) $id
		. ' AND access <= '.(int) $user->get( 'aid' )
		. ' AND type = ' . $db->Quote('component')
		;
		
		$db->setQuery( $query, 0 );
		return $db->loadResult();
	}
	function _menu( $id=0 ){
		$db		=& JFactory::getDBO();
		$user	=& JFactory::getUser();
		
		$query = 'SELECT name AS text, link as value, id as id'
		. ' FROM #__menu'
		. ' WHERE published = 1'
		. ' AND parent = '.(int) $id
		. ' AND access <= '.(int) $user->get( 'aid' )
		. ' AND type = '.$db->Quote('component')
		. ' ORDER BY text'
		;
		
		$db->setQuery( $query, 0 );
		return $db->loadObjectList();
	}
}
?>
