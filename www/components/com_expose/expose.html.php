<?php
/****************************************************************************
Component: Expose flash gallery component for Joomla 1.0.X
Version  : 4.6.3 (03/05/2008)
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

if (version_compare(PHP_VERSION,'5','>='))
	include_once("components/com_expose/expose/manager/misc/domxml-php4-to-php5.php");

include_once("components/com_expose/expose/manager/misc/common.inc.php");
include_once("components/com_expose/expose/admin/config.expose.php");

class expose_html {

	// Get Main gallery variables
	function getxml($xmlID) {
		$xmlF = expose_M::rpath2();
		$xmlValue = expose_M::GetAttr($xmlF,$xmlID);
		return $xmlValue;
	}

	function getlang() {
		if (!$langName = expose_M::GetLanguage(realpath2("components/com_expose/expose/config")."/strings.xml")) $langName = 'english';
		return $langName;
	}

	function writeGameFlash($msg) {
		global $mainframe;
		global $mosConfig_live_site;

		if($joom15 = function_exists('jimport'))
			$urlpath = JURI::Base() . 'components/com_expose/expose/';
		else
			$urlpath = $mosConfig_live_site . '/components/com_expose/expose/';

		$mainframe->addCustomHeadTag ('<script language="javascript" type="text/javascript" src="'.$urlpath.'swf/AC_RunActiveContent.js"></script>');
		$mainframe->addCustomHeadTag ('<link rel="stylesheet" type="text/css" href="'.$urlpath.'shadowbox/build/css/shadowbox.css" />');

		?><script language="javascript" type="text/javascript" src="<?php echo $urlpath ?>shadowbox/build/js/lib/yui-utilities.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo $urlpath ?>shadowbox/build/js/adapter/shadowbox-yui.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo $urlpath ?>shadowbox/build/js/shadowbox.js"></script>
					<script language="javascript" type = "text/javascript">
// <!--
function ExpGallLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      if (oldonload) {
        oldonload();
      }
      func();
    }
  }
}

ExpGallLoadEvent(function(){
	Shadowbox.init({
		continuous:		true,
		displayCounter:	false,
		loadingImage:	'<?php echo $urlpath.'shadowbox/images/loading.gif' ?>'
		});
});

function showpic (args) {
	var argsarray =	args.split ("_SPLIT_");
	var imgurl =	argsarray[0];
	var caption =	argsarray[1];
	var date =		argsarray[2];
	var location =	argsarray[3];

	Shadowbox.open( {
		title:		caption,
		type:		'img',
		content:	'<?php echo $urlpath ?>'+imgurl
	});
};

function openurl (args) {
	var url = args;
	window.open (url);
};

var InternetExplorer = navigator.appName.indexOf("Microsoft") != -1;

function expose_DoFSCommand(command, args) {
	var exposeObj = InternetExplorer ? expose : document.expose;
	if (command == "showpic") {
		showpic (args);
	}
	if (command == "openurl") {
		openurl (args);
	}
};
// -->
</script>

		<table align="center" border="0">
			<tr>
				<td>
					<div id="expose-inline" class="hidden">
						<div class="expose-inline-content">
							<script language="javascript" type = "text/javascript">
// <!--
if (navigator.appName && navigator.appName.indexOf("Microsoft") != -1 &&
		navigator.userAgent.indexOf("Windows") != -1 && navigator.userAgent.indexOf("Windows 3.1") == -1) {
	document.write('<SCRIPT LANGUAGE=VBScript\> \n');
	document.write('on error resume next \n');
	document.write('Sub expose_FSCommand(ByVal command, ByVal args)\n');
	document.write('  call expose_DoFSCommand(command, args)\n');
	document.write('end sub\n');
	document.write('</SCRIPT\> \n');
};

var topLevelCollectionID =	<?php if($joom15) echo (int) JArrayHelper::getValue($_REQUEST, 'topcoll'); else echo (int) mosGetParam($_REQUEST, 'topcoll'); ?>;
var autoLoadAlbumID =		<?php if($joom15) echo (int) JArrayHelper::getValue($_REQUEST, 'album'); else echo (int) mosGetParam($_REQUEST, 'album'); ?>;
var autoLoadPhotoID =		<?php if($joom15) echo (int) JArrayHelper::getValue($_REQUEST, 'photo'); else echo (int) mosGetParam($_REQUEST, 'photo'); ?>;
var autoStartSlideShow =	<?php if($joom15) echo "'".JArrayHelper::getValue($_REQUEST, 'playslideshow')."'"; else echo "'".mosGetParam($_REQUEST, 'playslideshow')."'"; ?>;

	AC_FL_RunContent(
		'codebase',		'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0',
		'width',		'<?php echo expose_html::getxml('Gallery width'); ?>',
		'height',		'<?php echo expose_html::getxml('Gallery height'); ?>',
		'src',			'components/com_expose/expose/swf/expose',
		'quality',		'high',
		'wmode',		'transparent',
		'pluginspage',	'http://www.macromedia.com/go/getflashplayer',
		'align',		'middle',
		'play',			'true',
		'id',			'expose',
		'bgcolor',		'#<?php echo expose_html::getxml('Gallery Background'); ?>',
		'name',			'expose',
		'menu',			'false',
		'allowScriptAccess', 'sameDomain',
		'movie',		'components/com_expose/expose/swf/expose',
		'salign',		'',
		'FlashVars',	'bgColor=<?php echo expose_html::getxml('Gallery Background'); ?>&amp;albumsXMLURL=xml/albums.xml&amp;stringsXMLURL=config/strings.xml&amp;formatsXMLURL=config/formats.xml&amp;configXMLURL=config/config.xml&amp;baseXMLURL=xml/&amp;baseImageURL=img/&amp;baseVideoURL=expose/img/&amp;baseAudioURL=img/&amp;topLevelCollectionID=' + topLevelCollectionID + '&amp;autoLoadAlbumID=' + autoLoadAlbumID + '&amp;autoLoadPhotoID=' + autoLoadPhotoID + '&amp;autoStartSlideShow=' + autoStartSlideShow + '&amp;bgImageURL=<?php echo expose_html::getxml('Gallery Background Image'); ?>&amp;fgImageURL=&amp;language=<?php echo expose_html::getlang(); ?>&amp;useEmbeddedFonts=<?php echo expose_html::getxml('Use Embedded Fonts'); ?>',
		'base',			'components/com_expose/expose'
		);
// -->
</script>

<noscript>
	<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
		codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0"
		width="<?php echo expose_html::getxml('Gallery width'); ?>"
		height="<?php echo expose_html::getxml('Gallery height'); ?>"
		name="expose"
		align="middle">
		<param name="menu" value="false" />
		<param name="allowScriptAccess" value="sameDomain" />
		<param name="movie" value="components/com_expose/expose/swf/expose.swf" />
		<param name="quality" value="high" />
		<param name="bgcolor" value="#<?php echo expose_html::getxml('Gallery Background'); ?>" />
		<param name="base" value="components/com_expose/expose" />
		<param name="FlashVars" value="bgColor=<?php echo expose_html::getxml('Gallery Background'); ?>&amp;albumsXMLURL=xml/albums.xml&amp;stringsXMLURL=config/strings.xml&amp;formatsXMLURL=config/formats.xml&amp;configXMLURL=config/config.xml&amp;baseXMLURL=xml/&amp;baseImageURL=img/&amp;baseVideoURL=expose/img/&amp;baseAudioURL=img/&amp;topLevelCollectionID=&amp;autoLoadAlbumID=&amp;autoLoadPhotoID=&amp;autoStartSlideShow=no&amp;bgImageURL=<?php echo expose_html::getxml('Gallery Background Image'); ?>&amp;fgImageURL=&amp;language=<?php echo expose_html::getxml('Language'); ?>&amp;useEmbeddedFonts=yes" />
		<embed src="components/com_expose/expose/swf/expose.swf"
			width =			"<?php echo expose_html::getxml('Gallery width'); ?>"
			height =		"<?php echo expose_html::getxml('Gallery height'); ?>"
			quality =		"high"
			wmode =			"transparent"
			pluginspage =	"http://www.macromedia.com/go/getflashplayer"
			align =			"middle"
			bgcolor =		"#<?php echo expose_html::getxml('Gallery Background'); ?>"
			name =			"expose"
			menu =			"false"
			allowScriptAccess = "sameDomain"
			FlashVars =		"bgColor=<?php echo expose_html::getxml('Gallery Background'); ?>&amp;albumsXMLURL=xml/albums.xml&amp;stringsXMLURL=config/strings.xml&amp;formatsXMLURL=config/formats.xml&amp;configXMLURL=config/config.xml&amp;baseXMLURL=xml/&amp;baseImageURL=img/&amp;baseVideoURL=expose/img/&amp;baseAudioURL=img/&amp;topLevelCollectionID=&amp;autoLoadAlbumID=&amp;autoLoadPhotoID=&amp;autoStartSlideShow=no&amp;bgImageURL=&amp;fgImageURL=&amp;language=<?php echo expose_html::getlang(); ?>&amp;useEmbeddedFonts=<?php echo expose_html::getxml('Use Embedded Fonts'); ?>"
			base =			"components/com_expose/expose"
			type =			"application/x-shockwave-flash" />
	</object>
								</noscript>
							</div>
						</div>
					</td>
				</tr>
			</table><?php

	}
}
?>
