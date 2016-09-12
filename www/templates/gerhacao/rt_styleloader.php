<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

// set default vars
$fontstyle = "f-" . $default_font;
$fontfamily = $font_family;
$theme = $default_theme;
$mtype = $menu_type;
$bstyle = $body_style;
$hstyle = $header_style;
$thisurl = $_SERVER['PHP_SELF'] . rebuildQueryString();



//array of properties to look for and store
foreach ($template_properties as $tprop) {
    if (isset($_SESSION[$cookie_prefix. $tprop])) {
        $$tprop = mosGetParam($_SESSION, $cookie_prefix. $tprop);
    } elseif (isset($_COOKIE[$cookie_prefix. $tprop])) {
    	$$tprop = mosGetParam($_COOKIE, $cookie_prefix. $tprop);
    }
}

// rebuild the querystring when needed
function rebuildQueryString() {
  global $template_properties;

  if (!empty($_SERVER['QUERY_STRING'])) {
      $parts = explode("&", $_SERVER['QUERY_STRING']);
      $newParts = array();
      foreach ($parts as $val) {
          $val_parts = explode("=", $val);
          if (!in_array($val_parts[0], $template_properties)) {
            array_push($newParts, $val);
          }
      }
      if (count($newParts) != 0) {
          $qs = implode("&amp;", $newParts);
      } else {
          return "?";
      }
      return "?" . $qs . "&amp;"; // this is your new created query string
  } else {
      return "?";
  } 
}
?>
