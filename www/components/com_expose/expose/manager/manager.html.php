<?php
/****************************************************************************
Component: Expose flash gallery component for Joomla 1.0.x and 1.5.x.
Version  : 4.6.3 (24/04/2008)
Author   : Josh, Steinthor, Bruno, Anything Digital
E-mail   : josh@gotgtek.net, steinthor@setjan.com, bruno@gotgtek.net
Web Site : www.gotgtek.net  www.slooz.com
Copyright: Copyright 2005-2008 by GTEK Technologies
License  : Expose is free for personal use. In a commercial setting, you need to
			purchase a one-site license. Check http://www.slooz.com for more info.
			The implementation into the Joomla CMR is published as GPL
			General Public License. Check http://www.gotgtek.net for details.
*****************************************************************************/

if(!(defined('_VALID_MOS')||defined('_JEXEC'))) die( 'Direct Access to this location is not allowed.' );

// Get Main gallery variables
function getxml($xmlID) {
	$xmlF = expose_M::rpath();
	$xmlValue = expose_M::GetAttr($xmlF,$xmlID);
	return $xmlValue;
}

function getlang() {
	if (!$langName = expose_M::GetLanguage(realpath2("../components/com_expose/expose/manager/misc")."/strings.xml")) $langName = 'english';
	return $langName;
}

if(function_exists('jimport')) {
	global $mainframe;
	$mainframe->addCustomHeadTag ('<script type="text/javascript" src="' . $mainframe->getSiteURL() . '/components/com_expose/expose/manager/misc/AC_RunActiveContent.js"></script>');
} else {
	global $mosConfig_live_site, $mainframe;
	$mainframe->addCustomHeadTag ('<script type="text/javascript" src="' . $mosConfig_live_site . '/components/com_expose/expose/manager/misc/AC_RunActiveContent.js"></script>');
}

	?><table align="center" border="0">
		<tr>
			<td>

	<script type = "text/javascript">
	// <!--
	AC_FL_RunContent(
		'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0',
		'width', '900',
		'height', '530',
		'src', '../components/com_expose/expose/manager/manager',
		'quality', 'high',
		'pluginspage', 'http://www.macromedia.com/go/getflashplayer',
		'align', 'middle',
		'play', 'true',
		'loop', 'true',
		'scale', 'showall',
		'wmode', 'transparent',
		'devicefont', 'false',
		'id', 'manager',
		'bgcolor', '#ffffff',
		'name', 'manager',
		'menu', 'true',
		'allowScriptAccess','sameDomain',
		'movie', '../components/com_expose/expose/manager/manager',
		'salign', '',
		'FlashVars', 'autoLogin=no&amp;helpEnabled=yes&amp;language=<?php echo getlang(); ?>&amp;textFontFamily=Arial,_serif&amp;textSize=12',
		'base', '../components/com_expose/expose/manager'
	);
	// -->
	</script>

	<noscript>
		<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
			codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0"
			width="900"
			height="530"
			id="manager"
			align="middle">
			<param name="FlashVars" value="autoLogin=no&amp;helpEnabled=yes&amp;language=english&amp;textFontFamily=Arial,_serif&amp;textSize=12" />
			<param name="allowScriptAccess" value="sameDomain" />
			<param name="movie" value="../components/com_expose/expose/manager/manager.swf" />
			<param name="quality" value="high" />
			<param name="bgcolor" value="#ffffff" />
			<param name="wmode" value="transparent" />
			<param name="base" value="../components/com_expose/expose/manager" />
				<embed src="../components/com_expose/expose/manager/manager.swf" 
					FlashVars="autoLogin=no&amp;helpEnabled=yes&amp;language=<?php echo getlang(); ?>&amp;textFontFamily=Arial,_serif&amp;textSize=12"
					quality="high"
					bgcolor="#ffffff"
					width="900"
					height="530"
					name="manager"
					align="middle"
					allowScriptAccess="sameDomain"
					type="application/x-shockwave-flash"
					pluginspage="http://www.macromedia.com/go/getflashplayer"
					base="../components/com_expose/expose/manager" />
		</object>
	</noscript>

			</td>
		</tr>
	</table>
