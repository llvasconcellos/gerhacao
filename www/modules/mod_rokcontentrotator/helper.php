<?php
/**
* @package RokContentRotator
* @copyright Copyright (C) 2007 RocketWerx. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');
jimport('joomla.utilities.date');

class modRokContentRotatorHelper
{
	function prepareContent( $text, $length=300 ) {
		// strips tags won't remove the actual jscript
		$text = preg_replace( "'<script[^>]*>.*?</script>'si", "", $text );
		$text = preg_replace( '/{.+?}/', '', $text);
		// replace line breaking tags with whitespace
        // $text = preg_replace( "'<(br[^/>]*?/|hr[^/>]*?/|/(div|h[1-6]|li|p|td))>'si", ' ', $text );
        // $text = strip_tags( $text );
		if (strlen($text) > $length) $text = substr($text, 0, $length) . "...";
		return $text;
	}	
	
	function getList(&$params)
	{
		global $mainframe;

		$db			=& JFactory::getDBO();
		$user		=& JFactory::getUser();
		$dispatcher	=& JDispatcher::getInstance();
		$userId		= (int) $user->get('id');

		$count		= (int) $params->get('count', 5);
		$catid		= trim( $params->get('catid') );
		$secid		= trim( $params->get('secid') );
		$show_front	= $params->get('show_front', 1);
		$aid		= $user->get('aid', 0);
		$show_title  = trim($params->get( 'show_title', 1 ) );

		$text_length = intval($params->get( 'preview_count', 75) );

		$contentConfig = &JComponentHelper::getParams( 'com_content' );
		$access		= !$contentConfig->get('shownoauth');

		$nullDate	= $db->getNullDate();
		$now		= date('Y-m-d H:i:s', time());

		$where		= 'a.state = 1'
			//. ' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )'
			//. ' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )'
			;
			
		$ordering	= 'a.ordering ASC';

		// User Filter
		switch ($params->get( 'user_id' ))
		{
			case 'by_me':
				$where .= ' AND (created_by = ' . (int) $userId . ' OR modified_by = ' . (int) $userId . ')';
				break;
			case 'not_me':
				$where .= ' AND (created_by <> ' . (int) $userId . ' AND modified_by <> ' . (int) $userId . ')';
				break;
		}

        if ($show_front != 2) {
    		if ($catid)
    		{
    			$ids = explode( ',', $catid );
    			JArrayHelper::toInteger( $ids );
    			$catCondition = ' AND (cc.id=' . implode( ' OR cc.id=', $ids ) . ')';
    		}
    		if ($secid)
    		{
    			$ids = explode( ',', $secid );
    			JArrayHelper::toInteger( $ids );
    			$secCondition = ' AND (s.id=' . implode( ' OR s.id=', $ids ) . ')';
    		}
    	}

		// Content Items only
		$query = 'SELECT a.*, a.introtext as text, ' .
			' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'.
			' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug'.
			' FROM #__content AS a' .
			($show_front == '0' ? ' LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id' : '') .
			($show_front == '2' ? ' INNER JOIN #__content_frontpage AS f ON f.content_id = a.id' : '') .
			' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
			' INNER JOIN #__sections AS s ON s.id = a.sectionid' .
			' WHERE '. $where .' AND s.id > 0' .
			($access ? ' AND a.access <= ' .(int) $aid. ' AND cc.access <= ' .(int) $aid. ' AND s.access <= ' .(int) $aid : '').
			($catid && $show_front != 2 ? $catCondition : '').
			($secid && $show_front != 2 ? $secCondition : '').
			($show_front == '0' ? ' AND f.content_id IS NULL ' : '').
			' AND s.published = 1' .
			' AND cc.published = 1' .
			' ORDER BY '. $ordering;

		//die($query);

		$db->setQuery($query, 0, $count);
		$rows = $db->loadObjectList();

		// Process the prepare content plugins
		JPluginHelper::importPlugin('content');

		$i		= 0;
		$lists	= array();
		foreach ( $rows as $row )
		{
			// process plugin
			$results = $dispatcher->trigger('onPrepareContent', array (& $row, & $params, 0));
			
			$lists[$i]->link = ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid);
			$lists[$i]->title = htmlspecialchars( $row->title );
			$lists[$i]->introtext = modRokContentRotatorHelper::prepareContent($row->text, $params->get('preview_count', 300));
			$lists[$i]->date = new JDate( $row->created );
			$i++;
		}

		return $lists;
	}



}
