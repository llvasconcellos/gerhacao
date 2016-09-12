<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
if( !isset( $_SESSION ) ) { session_start(); }

$cookie_prefix = "catalyst-";
$cookie_time = time()+31536000;
$template_properties = array('fontstyle','fontfamily','bstyle','theme','mtype','hstyle');
$my_session = $mainframe->_session;

foreach ($template_properties as $tprop) {
    
    if (isset($_REQUEST[$tprop])) {
	    $$tprop = mosGetParam( $_REQUEST,$tprop);
    	$my_session->set($cookie_prefix. $tprop, $$tprop);
    	$my_session->update();
    	setcookie ($cookie_prefix. $tprop, $$tprop, $cookie_time, '/', false);    
    }
}

//cludgy special case for prev/next
if (isset($_REQUEST['cstyle']) && ($_REQUEST['cstyle'] == "prev" || $_REQUEST['cstyle'] == "next") ) {
  $bstyle = "style1";
  if (isset($_SESSION[$cookie_prefix. 'bstyle'])) {
    $bstyle = mosGetParam($_SESSION, $cookie_prefix. 'bstyle');
  } elseif (isset($_COOKIE[$cookie_prefix. $tprop])) {
  	$bstyle = mosGetParam($_COOKIE, $cookie_prefix. 'bstyle');
  }

  $stylenum = intval(substr($bstyle,5));

  $stylenum = ($stylenum + (mosGetParam($_REQUEST,'cstyle') == "prev" ? -1 : +1))%6;
  if ($stylenum == 0) $stylenum = 6;
  $bstyle = "style$stylenum";

  $my_session->set($cookie_prefix. 'bstyle', $bstyle);
  $my_session->update();
  setcookie ($cookie_prefix. 'bstyle', $bstyle, $cookie_time, '/', false);
}

function setStyles($theme) {
	global $bstyle,$hstyle;
	
	// themeX  = body_style, header_style
	$themes = array ("theme1" => array ("style1","header1"),
					 "theme2" => array ("style2","header10"),
					 "theme3" => array ("style3","header2"),
					 "theme4" => array ("style4","header4"),
					 "theme5" => array ("style5","header5"),
					 "theme6" => array ("style6","header3"));
									
	$bstyle = $themes[$theme][0];
	$hstyle = $themes[$theme][1];						
	
}

?>
