<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

$subnav = null;
if ($mtype!="module") :
    require($mosConfig_absolute_path."/templates/" . $mainframe->getTemplate() . "/rt_rokmenu.php");
endif;

if ($mtype=="splitmenu") :
	$topnav = new RokMenu($menu_name,"top","",-1,false,false,1,2);
	$subnav = new RokMenu($menu_name,"","-menu",-3,true,false,2);
elseif ($mtype=="moomenu" or $mtype=="suckerfish") :
	$topnav = new RokMenu($menu_name,"top","",-1,false,true,1);
	$subnav = null;
endif;

//Are we in edit mode
$editmode = false;
if (  !empty( $_REQUEST['task'])  && $_REQUEST['task'] == 'edit'  ) :
	$editmode = true;
endif;

$featuredmods_count = (mosCountModules('advert1')>0) + (mosCountModules('advert2')>0) + (mosCountModules('advert3')>0) + (mosCountModules('advert4')>0);
$featuredmods_width = $featuredmods_count > 0 ? ' w' . floor(99 / $featuredmods_count) : '';
$mainmod_count = (mosCountModules('user1')>0) + (mosCountModules('user2')>0) + (mosCountModules('user3')>0);
$mainmod_width = $mainmod_count > 0 ? ' w' . floor(99 / $mainmod_count) : '';
$bottommods_count = (mosCountModules('user4')>0) + (mosCountModules('user5')>0) + (mosCountModules('user6')>0) + (mosCountModules('user7')>0);
$bottommods_width = $bottommods_count > 0 ? ' w' . floor(99 / $bottommods_count) : '';

$leftcolumn_width = (mosCountModules('left')>0 or $subnav) ? $leftcolumn_width : "0";
$rightcolumn_width = (mosCountModules('right')>0 or $subnav) ? $rightcolumn_width : "0";

$template_width = 'margin: 0 auto; width: ' . $template_width . 'px;';

$template_path = $mosConfig_live_site ."/templates/" . $mainframe->getTemplate(); 

function rok_isIe7() {
	$agent=$_SERVER['HTTP_USER_AGENT'];
	if (stristr($agent, 'msie 7')) return true;
	return false;
}

function rok_isIe6() {
	$agent=$_SERVER['HTTP_USER_AGENT'];
	if (stristr($agent, 'msie 6')) return true;
	return false;
}

// looks for a reference to mootools in the 
function mootoolsExists() {
	global $mainframe;

	foreach($mainframe->_head['custom'] as $row) {
		if (strpos($row,'mootools') > 0 && strpos($row,'.js') > 0) {
			return true;
		}
	}
	
	return false;
}

?>