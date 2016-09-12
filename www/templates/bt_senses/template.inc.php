<?php

/**
 * Senses - Joomla Template
 *
 * @package bt_senses
 * @version 1.0.0.20070601
 * @copyright BonusThemes.com 2007
 * @link http://www.bonusthemes.com/goto/bt_senses
 */

defined( '_VALID_MOS' ) or die( 'Restricted access' );

function json_esc($input)
{
	$result = '';
	$input = (string) $input;
	$conv = array( "'" => "'", '"' => '"', '&' => '&', '\\' => '\\', "\n" => 'n', "\r" => 'r', "\t" => 't', "\b" => 'b', "\f" => 'f' );
	for ($i = 0, $len = strlen($input); $i < $len; $i++) {
		$result .= isset($conv[$input[$i]]) ? '\\' . $conv[$input[$i]] : $input[$i];
	}
	return $result;
} 

function getlen($str)
{
	// get string length ignoring multi-byte characters

	if (function_exists('mb_strlen')) {
		$result = mb_strlen($str);
	}
	else {
		$result = 0;
		for ($i = 0, $len = strlen($str); $i < $len; $i++) {
			if (ord($str{$i}) < 128) {
				$result++;
			}
		}
	}

	return $result < 7 ? 7 : $result;
}

class TplBtSenses
{
	var $header_menu_name, $header_menu_width, $header_menu_gap, $header_menu_show_subitems, $header_menu;
	var $quick_menu_name, $quick_menu;
	var $footer_menu_name, $footer_menu;
	var $main_suffix, $main_width;
	var $newflash_grp, $newflash_item_width;
	var $spotlight_grp, $spotlight_item_width;

	function __construct($params = false)
	{
		// setup configuration parameters
		if (is_array($params)) {
			$vars = get_class_vars(get_class($this));
			foreach ($params as $k => $param) {
				if (array_key_exists($k, $vars)) {
					$this->$k = $param;
				}
			}
		}

		// load header menu
		$this->header_menu = $this->load_menu($this->header_menu_name, $this->header_menu_show_subitems);

		// prepare metrics for header menu
		$x = $this->header_menu_gap;

		$char_count = 0;
		foreach ($this->header_menu as $mitem) {
			$char_count += getlen($mitem->name);
		}

		$total_width = $this->header_menu_width - 2 * $this->header_menu_gap - (count($this->header_menu) - 1) * 3;

		$first = true;
		foreach ($this->header_menu as $k => $mitem) {
			if ($first) {
				$first = false;
			}
			else {
				$x += 3;
			}

			$width = floor($total_width * getlen($mitem->name) / $char_count);
			$this->header_menu[$k]->width = $width;
			$this->header_menu[$k]->center = $x + round($width / 2);

			$x += $width;
		}

		// load quick menu
		$this->quick_menu = $this->load_menu($this->quick_menu_name, false);

		// load footer menu
		$this->footer_menu = $this->load_menu($this->footer_menu_name, false);

		// prepare left position
		if (mosCountModules( 'left' )) {
			$this->main_suffix = '';
			$this->main_width = 584;
		}
		else {
			$this->main_suffix = '_wide';
			$this->main_width = 844;
		}

		// prepare news flash position
		$this->newflash_grp = array();
		for ($pos = 1; $pos <= 3; $pos++) {
			if (mosCountModules( 'user' . $pos )) {
				$this->newflash_grp[] = 'user' . $pos;
			}
		}
		if (!empty($this->newflash_grp)) {
			$this->newflash_item_width = floor(($this->main_width - (count($this->newflash_grp) - 1) * 10) / count($this->newflash_grp));
		}

		// prepare spotlight position
		$this->spotlight_grp = array();
		for ($pos = 4; $pos <= 7; $pos++) {
			if (mosCountModules( 'user' . $pos )) {
				$this->spotlight_grp[] = 'user' . $pos;
			}
		}
		if (!empty($this->spotlight_grp)) {
			$this->spotlight_item_width = floor((801 - (count($this->spotlight_grp) - 1) * 20) / count($this->spotlight_grp));
		}
	}

	function TplBtSenses($params = false)
	{
		$this->__construct($params);
	}

	function load_menu($menu_name, $load_sub_level)
	{
		global $mosConfig_shownoauth, $database, $my;

		// load main level
		$database->setQuery( sprintf(
			"SELECT * FROM #__menu WHERE menutype='%s' AND published='1' %s AND parent=0 ORDER BY ordering",
			$database->getEscaped($menu_name),
			$mosConfig_shownoauth ? "" : "AND access <= '$my->gid'"
			) );
		$menu = $database->loadObjectList('id');

		$main_ids = array();
		foreach ($menu as $k => $mitem) {
			$main_ids[] = $mitem->id;
			$menu[$k]->sub_menu_items = array();
		}

		if ($load_sub_level) {
			// load sub level
			$database->setQuery( sprintf(
				"SELECT * FROM #__menu WHERE menutype='%s' AND published='1' %s AND parent IN (%s) ORDER BY ordering",
				$database->getEscaped($menu_name),
				$mosConfig_shownoauth ? "" : "AND access <= '$my->gid'",
				implode(",", $main_ids)
				) );
			$res = $database->loadObjectList();
			foreach ($res as $row) {
				$menu[$row->parent]->sub_menu_items[] = $row;
			}
		}

		// normalize menu items
		foreach ($menu as $k => $mitem) {
			$this->load_menu_item($mitem);
			$menu[$k]->is_active = $mitem->is_active;
			$menu[$k]->link = $mitem->link;
			$menu[$k]->link_is_href = $mitem->link_is_href;
			$menu[$k]->link_content = $mitem->link_content;
			$menu[$k]->name = $mitem->name;
			$menu[$k]->image = $mitem->image;

			foreach ($mitem->sub_menu_items as $k2 => $mitem2) {
				$this->load_menu_item($mitem2);
				$menu[$k]->sub_menu_items[$k2]->is_active = $mitem2->is_active;
				$menu[$k]->sub_menu_items[$k2]->link = $mitem2->link;
				$menu[$k]->sub_menu_items[$k2]->link_is_href = $mitem2->link_is_href;
				$menu[$k]->sub_menu_items[$k2]->link_content = $mitem2->link_content;
				$menu[$k]->sub_menu_items[$k2]->name = $mitem2->name;
				$menu[$k]->sub_menu_items[$k2]->image = $mitem2->image;

				if ($mitem2->is_active) {
					$menu[$k]->is_active = true;
				}
			}
		}

		return $menu;
	}

	function load_menu_item(&$mitem)
	{
		// returns true if item is active

		global $Itemid, $mosConfig_live_site, $mainframe;

		switch ($mitem->type) {
			case 'separator':
			case 'component_item_link':
				break;
				
			case 'url':
				if ( eregi( 'index.php\?', $mitem->link ) && !eregi( 'http', $mitem->link ) && !eregi( 'https', $mitem->link ) ) {
					if ( !eregi( 'Itemid=', $mitem->link ) ) {
						$mitem->link .= '&Itemid='. $mitem->id;
					}
				}
				break;
				
			case 'content_item_link':
			case 'content_typed':
				// load menu params
				$menuparams = new mosParameters( $mitem->params, $mainframe->getPath( 'menu_xml', $mitem->type ), 'menu' );
				
				$unique_itemid = $menuparams->get( 'unique_itemid', 1 );
				
				if ( $unique_itemid ) {
					$mitem->link .= '&Itemid='. $mitem->id;
				} else {
					$temp = split('&task=view&id=', $mitem->link);
					
					if ( $mitem->type == 'content_typed' ) {
						$mitem->link .= '&Itemid='. $mainframe->getItemid($temp[1], 1, 0);
					} else {
						$mitem->link .= '&Itemid='. $mainframe->getItemid($temp[1], 0, 1);
					}
				}
				break;

			default:
				$mitem->link .= '&Itemid='. $mitem->id;
				break;
		}

		// Active Menu highlighting
		$mitem->is_active = $Itemid == $mitem->id;

		// support for `active_menu` of 'Link - Component Item'	
		if ( !$mitem->is_active && $mitem->type == 'component_item_link' ) {
			parse_str( $mitem->link, $url );
			$mitem->is_active = $url['Itemid'] == $Itemid;
		}
		
		// support for `active_menu` of 'Link - Url' if link is relative
		if ( !$mitem->is_active && $mitem->type == 'url' && strpos( 'http', $mitem->link ) === false) {
			parse_str( $mitem->link, $url );
			$mitem->is_active = isset( $url['Itemid'] ) && $url['Itemid'] == $Itemid;
		}

		// replace & with amp; for xhtml compliance
		$mitem->link = ampReplace( $mitem->link );

		// run through SEF convertor
		$mitem->link = sefRelToAbs( $mitem->link );

		// replace & with amp; for xhtml compliance
		// remove slashes from excaped characters
		$mitem->name = stripslashes( ampReplace($mitem->name) );


		$mitem->link_is_href = true;
		$mitem->link_content = '';
		switch ($mitem->browserNav) {
			// cases are slightly different
			case 1:
				// open in a new window
				$mitem->link_content = 'href="'. $mitem->link .'" target="_blank" ';
				break;

			case 2:
				// open in a popup window
				$mitem->link_content = "href=\"javascript:void(0)\" onclick=\"popup('{$mitem->link}')\" ";
				break;

			case 3:
				// don't link it
				$mitem->link_content = '';
				$mitem->link_is_href = false;
				break;

			default:	
				// open in parent window
				$mitem->link_content = 'href="'. $mitem->link .'" ';
				break;
		}

		// retrieve image
		$menu_params 	= new stdClass();
		$menu_params 	= new mosParameters( $mitem->params );
		$menu_image 	= $menu_params->def( 'menu_image', -1 );
		$mitem->image = $menu_image != '-1' && $menu_image ? $mosConfig_live_site .'/images/stories/'. $menu_image : '';
	}

	function echo_header_menu_js()
	{
		echo 'var hdmenu = {';

		$first = true;
		foreach ($this->header_menu as $mitem) {
			if ($first) {
				$first = false;
			}
			else {
				echo ', ';
			}

			printf(
				"mi%u: {href: %s, lnk: '%s', nam: '%s', act: %s, center: %u, subitems: [",
				$mitem->id,
				$mitem->link_is_href ? 'true' : 'false',
				json_esc(html_entity_decode($mitem->link)),
				json_esc($mitem->name),
				$mitem->is_active ? 'true' : 'false',
				$mitem->center
			);

			if (!empty($mitem->sub_menu_items)) {
				$first_sub = true;
				foreach ($mitem->sub_menu_items as $submitem) {
					if ($first_sub) {
						$first_sub = false;
					}
					else {
						echo ', ';
					}

					printf(
						"{href: %s, lnk: '%s', nam: '%s', act: %s, len: %u, browserNav: %u}",
						$submitem->link_is_href ? 'true' : 'false',
						json_esc(html_entity_decode($submitem->link)),
						json_esc($submitem->name),
						$submitem->is_active ? 'true' : 'false',
						getlen($submitem->name),
						$submitem->browserNav
					);
				}
			}

			echo ']}';
		}

		echo '};';
	}

	function echo_header_menu()
	{
		if (!empty($this->header_menu)) {
			echo '<div style="width:' . $this->header_menu_gap . 'px" class="hdm_mitem_norm"></div>';

			$first = true;
			foreach ($this->header_menu as $mitem) {
				if ($first) {
					$first = false;
				}
				else {
					echo '<div class="hdm_splitter"></div>';
				}

				printf(
					'<div style="width:%upx" class="%s" onmouseover="hdm.mouseover(\'mi%u\')" onmouseout="hdm.mouseout()">',
					$mitem->width,
					$mitem->is_active ? 'hdm_mitem_sel' : 'hdm_mitem_norm',
					$mitem->id
				);

				printf(
					$mitem->link_is_href ? '<a %s>%s</a>' : '<span %s>%s</span>',
					$mitem->link_content,
					htmlspecialchars($mitem->name)
				);

				echo '</div>';
			}
		}
	}

	function echo_quick_menu()
	{
		$first = true;
		foreach ($this->quick_menu as $mitem) {
			if ($first) {
				$first = false;
			}
			else {
				echo '<div class="quickmenu_splitter" onmouseover="qm.mouseover()" onmouseout="qm.mouseout()"></div>' . "\n";
			}

			echo '<div class="quickmenu_item" onmouseover="qm.mouseover()" onmouseout="qm.mouseout()">';
			if ($mitem->image != '') {
				echo '<a href="' . $mitem->link . '"><img src="' . $mitem->image . '" alt="' . $mitem->name . '" border="0" onmouseover="qm.mouseover()" onmouseout="qm.mouseout()" /></a>';
			}
			if ($mitem->image != '' && $mitem->name != '') {
				echo '<br />';
			}
			if ($mitem->name != '') {
				printf(
					$mitem->link_is_href ? '<a %s %s>%s</a>' : '<span %s %s>%s</span>',
					$mitem->link_content,
					'onmouseover="qm.mouseover()" onmouseout="qm.mouseout()"',
					htmlspecialchars($mitem->name)
				);
			}
			echo '</div>' . "\n";
		}
	}

	function echo_footer_menu()
	{
		$first = true;
		foreach ($this->footer_menu as $mitem) {
			if ($first) {
				$first = false;
			}
			else {
				echo ' \\ ';
			}

			printf(
				$mitem->link_is_href ? '<a %s>%s</a>' : '<span %s>%s</span>',
				$mitem->link_content,
				htmlspecialchars($mitem->name)
			);
		}
	}
}

?>