<?php

if(!(defined('_VALID_MOS')||defined('_JEXEC'))) die( 'Direct Access to this location is not allowed.' );



require_once( $mainframe->getPath( 'front_html' ) );

if(function_exists('jimport')) {
	$Itemid = JRequest::getVar( 'Itemid' );
} else {
	$Itemid = mosGetParam( $_REQUEST, "Itemid", "" );
}

showGame($Itemid);


function showGame($Itemid) {

	$swf="expose.html";

	expose_html::writeGameFlash($swf);

}

?>