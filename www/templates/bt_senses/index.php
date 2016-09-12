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
$cfg = array();


// CONFIGURATION START ////////////////////////////////////////////////////////

// Name of main menu
// Default value: mainmenu
$cfg['header_menu_name'] = 'mainmenu';

// Main menu's total width in pixels
// Default value: 500
$cfg['header_menu_width'] = 610;

// Main menu's left and right gap in pixels
// Default value: 10
$cfg['header_menu_gap'] = 10;

// Show main menu's sub-items
// Values: true or false
// Default value: true
$cfg['header_menu_show_subitems'] = true;

// Name of quick menu
// Default value: 'topmenu'
$cfg['quick_menu_name'] = 'topmenu';

// Name of footer menu
// Default value: 'othermenu'
$cfg['footer_menu_name'] = 'othermenu';

// CONFIGURATION END //////////////////////////////////////////////////////////


require(dirname(__FILE__) . '/template.inc.php');
$template = new TplBtSenses($cfg);

$iso = explode( '=', _ISO );
echo '<?xml version="1.0" encoding="'. $iso[1] .'"?' .'>' . "\n";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
	mosShowHead();

	if ( $my->id ) {
		initEditor();
	}

	$template_path = $mosConfig_live_site . '/templates/' . $mainframe->getTemplate();
?>
<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />

<link id="css_content" href="<?php echo $template_path; ?>/css/content.css" rel="stylesheet" type="text/css" />
<link id="css_layout" href="<?php echo $template_path; ?>/css/template_css.css" rel="stylesheet" type="text/css" />
<!--[if lt IE 7]>
<link href="<?php echo $template_path; ?>/css/template_css_ie6.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
var blankImg = '<?php echo $template_path; ?>/images/blank.gif';
</script>
<style type="text/css">
.pngfix { behavior: url(<?php echo $template_path; ?>/js/iepngfix.htc); }
</style>
<![endif]-->

<script type="text/javascript">
// <![CDATA[

function Qmenu(aa,ba,ca,da,ea,fa,ga,ha,ia,ja){this.iname=aa;this.refcount=0;this.menu=null;this.menu_shadow=null;this.menu_id=ba;this.cnt=null;this.cnt_shadow=null;this.cnt_id=ca;this.stepmax=da;this.stepdiff=ea;this.h=0;this.mss=0;this.msp=0;this.itv_vis=0;this.sx=fa;this.sy=ga;this.op=ha;this.ops=ia;this.opa=ja;}
Qmenu.prototype.mouseover=function(){this.refcount++;if(this.itv_vis>0){clearTimeout(this.itv_vis);this.itv_vis=0;}}
Qmenu.prototype.mouseout=function(){this.refcount--;if(this.refcount==0&&this.itv_vis==0){this.itv_vis=setTimeout(this.iname+'.h_vis()',300);}}
Qmenu.prototype.h_vis=function(){this.itv_vis=0;if(this.mss>0){this.mss=-1;}}
Qmenu.prototype.tagclick=function(ka){if(this.menu){if(this.mss==0){var x=0,y=ka.offsetHeight;while(ka){x+=ka.offsetLeft;y+=ka.offsetTop;ka=ka.offsetParent;}
this.menu.style.left=x+'px';this.menu_shadow.style.left=(x+this.sx)+'px';this.menu.style.top=y+'px';this.menu_shadow.style.top=(y+this.sy)+'px';this.cnt.style.height='1px';this.cnt_shadow.style.height='1px';this.menu.style.display='block';this.menu_shadow.style.display='block';}
this.mss=this.mss>0?-1:2;}}
Qmenu.prototype.timer=function(){if(this.mss==-1||this.mss==2){this.msp+=this.stepdiff;if(this.msp>this.stepmax){this.msp=this.stepmax;}
if(this.mss==-1){var la=this.cnt.offsetHeight-Math.ceil(this.msp);if(la<0){la=0;this.mss=0;this.msp=0;this.menu.style.display='none';this.menu_shadow.style.display='none';if(this.itv_vis>0){clearTimeout(this.itv_vis);this.itv_vis=0;}}}
else if(this.mss==2){var la=this.cnt.offsetHeight+Math.ceil(this.msp);if(la>this.h){la=this.h;this.mss=1;this.msp=0;}}
this.cnt.style.height=la+'px';this.cnt_shadow.style.height=la+'px';if(this.opa){var ma=Math.round(this.op*la/this.h);this.menu.style.filter='alpha(opacity='+ma+')';this.menu.style.opacity=ma/100;var ma=Math.round(this.ops*la/this.h);this.menu_shadow.style.filter='alpha(opacity='+ma+')';this.menu_shadow.style.opacity=ma/100;}}}
Qmenu.prototype.init=function(){this.menu=document.getElementById(this.menu_id);this.menu_shadow=document.getElementById(this.menu_id+'_shadow');this.cnt=document.getElementById(this.cnt_id);this.cnt_shadow=document.getElementById(this.cnt_id+'_shadow');this.h=this.cnt.offsetHeight;this.menu.style.display='none';this.menu.style.visibility='visible';this.menu_shadow.style.display='none';this.menu_shadow.style.visibility='visible';if(this.opa){this.menu.style.filter='alpha(opacity=0)';this.menu.style.opacity=0;this.menu_shadow.style.filter='alpha(opacity=0)';this.menu_shadow.style.opacity=0;}
else{this.menu.style.filter='alpha(opacity='+this.op+')';this.menu.style.opacity=this.op/100;this.menu_shadow.style.filter='alpha(opacity='+this.ops+')';this.menu_shadow.style.opacity=this.ops/100;}}

<?php $template->echo_header_menu_js(); ?>

function popup(link)
{
	window.open(link, '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550');
}

function BtSensesHdMenu(aa){this.iname=aa;this.refcount=0;this.itv_vis=0;this.submenu=null;this.mid='';this.defaultmid='';}
BtSensesHdMenu.prototype.mouseover=function(ba){this.refcount++;if(this.itv_vis>0){clearTimeout(this.itv_vis);this.itv_vis=0;}
this.show(ba);}
BtSensesHdMenu.prototype.mouseout=function(){this.refcount--;if(this.refcount==0&&this.itv_vis==0){this.itv_vis=setTimeout(this.iname+'.h_vis()',100);}}
BtSensesHdMenu.prototype.h_vis=function(){this.itv_vis=0;this.show(this.defaultmid);}
BtSensesHdMenu.prototype.show=function(ca){if(this.submenu==null||ca=='-'||ca==this.mid){return;}
this.mid=ca;var da=this.submenu.firstChild;while(da){this.submenu.removeChild(da);da=this.submenu.firstChild;}
if(ca!=''&&hdmenu[ca].subitems.length>0){var ea=hdmenu[ca];var fa=0;for(var k in ea.subitems){fa+=ea.subitems[k].len;}
if(ea.subitems.length>1){fa+=(ea.subitems.length-1)*2;}
var x=0,y=2,h=23,total_width=500,char_width=8;if(fa*char_width>500){var ga=true;for(var k in ea.subitems){if(ga){ga=false;}
else{x+=Math.floor(total_width*2/fa);}
var w=Math.floor(total_width*mitem.len/fa);ea.subitems[k].x=x;ea.subitems[k].y=y;ea.subitems[k].w=w;ea.subitems[k].h=h;x+=w;y-=h;}}
else{var ha=fa*char_width;var x=hdmenu[ca].center-Math.round(ha/2);if(x<0){x=0;}
else if(x+ha>total_width){x=total_width-ha;}
var ga=true;for(var k in ea.subitems){if(ga){ga=false;}
else{x+=2*char_width;}
var w=ea.subitems[k].len*char_width;ea.subitems[k].x=x;ea.subitems[k].y=y;ea.subitems[k].w=w;ea.subitems[k].h=h;x+=w;y-=h;}}
var x=0,y=2,h=23;for(var k in ea.subitems){var ia=ea.subitems[k];var ja=document.createElement('div');this.submenu.appendChild(ja);ja.style.position='relative';ja.style.overflow='hidden';ja.style.left=ia.x+'px';ja.style.top=ia.y+'px';ja.style.width=ia.w+'px';ja.style.height=ia.h+'px';ja.className=ia.act?'hdm_sub_mitem_sel':'hdm_sub_mitem_norm';ja.style.textAlign='center';ja.onmouseover=function(){hdm.mouseover('-');}
ja.onmouseout=function(){hdm.mouseout();}
var ka=document.createElement('a');ja.appendChild(ka);ka.appendChild(document.createTextNode(ia.nam));switch(ia.browserNav){case 1:ka.href=ia.lnk;ka.target='_blank';break;case 2:ka.href='javascript:void(0)';ka.setAttribute('link',ia.lnk);ka.onclick=function(){popup(this.getAttribute('link'))};break;case 3:ka.href='javascript:void(0)';break;default:ka.href=ia.lnk;break;}}}}
BtSensesHdMenu.prototype.init=function(){this.submenu=document.getElementById('sns1_2_3_1');for(var k in hdmenu){if(hdmenu[k].act){this.defaultmid=k;this.show(k);break;}}}

var hdm = new BtSensesHdMenu('hdm');

var ver = navigator.userAgent.toLowerCase().match(/msie (\d(.\d*)?)/);
var ie6 = ver && ver[1] && ver[1] < 7;
var qm = new Qmenu('qm', 'quickmenu', 'quickmenu_cnt', 12, 0.8, 3, 3, 95, 10, !ie6);

function addEvent(obj, event, func) { if (obj.addEventListener) obj.addEventListener(event, func, false); else if (obj.attachEvent) obj.attachEvent('on' + event, func); }

function cookie_set(nam,val){document.cookie=nam+'='+escape(val);}function cookie_get(nam){if(typeof nam=='undefined'){return false;}var result='',nam=' '+nam+'=',len=nam.length;var cookies=document.cookie.split(';');for (var k in cookies){var cookie=(cookies[k].substr(0,1)!=' '?' ':'')+cookies[k];if(cookie.substr(0,len)==nam){return unescape(cookie.substr(len));}}return false;}function cookie_del(nam){document.cookie=nam+'=stub;expires='+new Date('January 1, 1970').toGMTString();}

function set_font_big(save) { document.body.style.fontSize = '16px'; if (save) cookie_set('BtSensesFontSize', 'big'); }
function set_font_small(save) { document.body.style.fontSize = '9px'; if (save) cookie_set('BtSensesFontSize', 'small'); }
function set_font_normal(save) { document.body.style.fontSize = '12px'; if (save) cookie_del('BtSensesFontSize'); }

function bt_senses_init()
{
	qm.init();
	setInterval('qm.timer()', 20);

	hdm.init();

	var font_size = cookie_get('BtSensesFontSize');
	if (font_size && font_size == 'big') set_font_big(false);
	else if (font_size && font_size == 'small') set_font_small(false);
}
addEvent(window, 'load', bt_senses_init);

var goto_top_type = -1;
var goto_top_itv = 0;

function goto_top_timer()
{
	var y = goto_top_type == 1 ? document.documentElement.scrollTop : document.body.scrollTop;
	var moveby = 15;

	y -= Math.ceil(y * moveby / 100);
	if (y < 0) {
		y = 0;
	}

	if (goto_top_type == 1) {
		document.documentElement.scrollTop = y;
	}
	else {
		document.body.scrollTop = y;
	}

	if (y == 0) {
		clearInterval(goto_top_itv);
		goto_top_itv = 0;
	}
}

function goto_top()
{
	if (goto_top_itv == 0) {
		if (document.documentElement && document.documentElement.scrollTop) {
			goto_top_type = 1;
		}
		else if (document.body && document.body.scrollTop) {
			goto_top_type = 2;
		}
		else {
			goto_top_type = 0;
		}

		if (goto_top_type > 0) {
			goto_top_itv = setInterval('goto_top_timer()', 50);
		}
	}
}

// ]]>
</script>

</head>

<body>

<div id="wrapper">

	<div id="sns1">
		<div id="sns1_1"><img src="<?php echo $template_path; ?>/images/header_a.gif" alt="" /></div>
		<div id="sns1_2">
			<div id="sns1_2_1"></div>
			<div id="sns1_2_2">
				<div id="sns1_2_2_1"><?php $template->echo_header_menu(); ?></div>
				<!--<div id="sns1_2_2_2" style="border:solid 1px blue;"></div>
				<div id="sns1_2_2_3" onmouseover="qm.mouseover()" onmouseout="qm.mouseout()" onclick="qm.tagclick(this)"></div>
				<div id="sns1_2_2_4" style="border:solid 1px yellow;"></div>-->
			</div>
			<div id="sns1_2_3">
				<div id="sns1_2_3_1"></div>
				<div id="sns1_2_3_2"></div>
			</div>
			<div id="sns1_2_4"><img src="<?php echo $template_path; ?>/images/header_b.gif" alt="" /></div>
		</div>
	</div>

	<div id="sns2">
<?php
	if (mosCountModules( 'left' )) {
?>
		<div id="sns2_1">
			<div id="pos_left">
<?php mosLoadModules ( 'left', -2 ); ?>
			</div>
		</div>
<?php
	}
?>
		<div id="sns2_2<?php echo $template->main_suffix ?>">
			<div id="main<?php echo $template->main_suffix ?>">
				<div id="pathway"><?php mospathway(); ?></div>
<?php

	// build position group: news flash
	if (!empty($template->newflash_grp)) {
?>
			<div id="section_newsflash"><img src="<?php echo $template_path; ?>/images/titlebar_newsflash.gif" alt="news flash" /></div>
<?php
		$first = true;
		foreach ($template->newflash_grp as $position) {
			if ($first) {
				$first = false;
			}
			else {
?>
			<div class="posgrp_newsflash_splitter">&nbsp;</div>
<?php
			}
?>
			<div style="width:<?php echo $template->newflash_item_width; ?>px;" id="pos_<?php echo $position; ?>">
<?php
			mosLoadModules( $position, -2 );
?>
			</div>
<?php

		}
	}
?>
			<div style="clear:both;"></div>
			<div id="section_content"><img src="<?php echo $template_path; ?>/images/titlebar_content.gif" alt="" /></div>
			<a name="content"></a>
<?php
	if (mosCountModules( 'user9' )) {
?>
			<div id="pos_user9"><?php mosLoadModules ( 'user9', -2 ); ?></div>
			<div id="pos_user9_splitter"></div>
<?php
	}
?>

			<div id="pos_content">	
				<?php mosMainBody(); ?>
			</div>

<?php
	if (mosCountModules( 'banner' )) {
?>
			<div id="pos_banner">
				<?php mosLoadModules ( 'banner', -1 ); ?>
			</div>
<?php
	}
?>

			</div>
		</div>
		<div id="sns2_3">
			<div id="sns2_3_1"></div>
			<div id="sns2_3_2"></div>
			<div id="sns2_3_3"></div>
			<div id="sns2_3_4" onclick="set_font_big(true)"></div>
			<div id="sns2_3_5"></div>
			<div id="sns2_3_6" onclick="set_font_small(true)"></div>
			<div id="sns2_3_7"></div>
			<div id="sns2_3_8" onclick="set_font_normal(true)"></div>
			<div id="sns2_3_9"></div>
		</div>
	</div>
<?php

	// build position group: spotlight
	if (!empty($template->spotlight_grp)) {
?>
	<div style="clear:both;"></div>
	<div id="sns3"></div>

	<div id="sns4">
		<div id="sns4_1"></div>
		<div id="sns4_2">
<?php

		$first = true;
		foreach ($template->spotlight_grp as $position) {
			if ($first) {
				$first = false;
			}
			else {
?>
			<div class="posgrp_spotlight_splitter"><img src="<?php echo $template_path; ?>/images/sns4_splitter.gif" alt="" /></div>
<?php
			}
?>
			<div style="width:<?php echo $template->spotlight_item_width; ?>px;" id="pos_<?php echo $position; ?>">
<?php
			mosLoadModules( $position, -2 );
?>
			</div>
<?php

		}
?>
		</div>
		<div id="sns4_3"></div>
	</div>
<?php
	}
?>

	<div id="sns5"></div>

	<div id="sns6">
	
		<div id="sns6_2">
			<div id="pos_footer_menu"><?php $template->echo_footer_menu(); ?></div>

<?php
	if (mosCountModules( 'footer' )) {
?>
			<div id="pos_footer"><?php mosLoadModules ( 'footer', -1 ); ?></div>
<?php
	}
?>
		</div>
		<a href="javascript:void(0)" onclick="goto_top()"><img src="<?php echo $template_path; ?>/images/goto_top.gif" alt="top" border="0" /></a>

	</div>
</div>

<div id="quickmenu_shadow">
	<div id="quickmenu_top_shadow"></div>
	<div id="quickmenu_cnt_shadow"></div>
	<div id="quickmenu_bottom_shadow"></div>
</div>

<div id="quickmenu">
	<div id="quickmenu_top" onmouseover="qm.mouseover()" onmouseout="qm.mouseout()"></div>
	<div id="quickmenu_cnt">
	<?php $template->echo_quick_menu(); ?>
	</div>
	<div id="quickmenu_bottom" onmouseover="qm.mouseover()" onmouseout="qm.mouseout()"></div>
</div>

<?php
	// print debug position
	mosLoadModules( 'debug', -1 );
?>

</body>

</html>
