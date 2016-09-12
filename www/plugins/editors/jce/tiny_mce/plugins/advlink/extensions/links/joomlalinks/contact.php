<?php
// no direct access
defined( '_JCE_EXT' ) or die( 'Restricted access' );
class AdvlinkContact {
	function getOptions(){
		//Reference to JConentEditor (JCE) instance
		$advlink =& AdvLink::getInstance();
		$list = '';
		if( $advlink->checkAccess( 'advlink_contacts', '1' ) ){	
			$list .= '<li id="index.php?option=com_contact"><div class="tree-row"><div class="tree-image"></div><span class="folder contact nolink"><a href="javascript:;">' . JText::_('CONTACTS') . '</a></span></div></li>';
		}
		return $list;	
	}
	function getItems( $args ){
		$items 	= array();
		$view	= isset( $args->view ) ? $args->view : '';
		switch( $view ){
			default:
				$categories = AdvLink::getCategory( 'com_contact_details' );
				foreach( $categories as $category ){
					$items[] = array(
						'id' 	=> 'index.php?option=com_contact&view=category&id=' . $category->value,
						'name' 	=> $category->text,
						'class'	=> 'folder contact'
					);
				}
				break;
			case 'category':
				$contacts = AdvlinkContact::_contacts( $args->id );
				foreach( $contacts as $contact ){
					$items[] = array(
						'id' 	=> 'index.php?option=com_contact&view=contact&catid='. $args->id .'&id='.$contact->slug,
						'name' 	=> $contact->text,
						'class'	=> 'file'
					);
				}
				break;
		}
		return $items;
	}
	function _contacts( $id ){
		$db		=& JFactory::getDBO();
		$user	=& JFactory::getUser();	
		
		$query	= 'SELECT CONCAT_WS( name, " /", con_position, "" ) AS text, '
		. ' CASE WHEN CHAR_LENGTH(alias) THEN CONCAT_WS(":", id, alias) ELSE id END as slug'
		. ' FROM #__contact_details'
		. ' WHERE catid = '.(int) $id
		. ' AND published = 1'
		. ' AND access <= '.(int) $user->get( 'aid' )
		//. ' GROUP BY id'
		. ' ORDER BY name'
		;
		$db->setQuery( $query );
		return $db->loadObjectList();
	}
}
?>
