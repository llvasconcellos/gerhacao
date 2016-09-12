<?php
	error_reporting(0);
	include_once ("../amfphp/extra/passhash.inc.php");
	include_once ("../misc/common.inc.php");
	if (version_compare(PHP_VERSION,'5','>='))
		include_once('../misc/domxml-php4-to-php5.php');
	include_once ("../misc/xmlfunc.inc.php");
	include_once ("../misc/flvid3.inc.php");
	$hash = $_GET["passhash"];
	
	function imgFolder () {
		return realpath2 ("../../img/")."/";
	}
	
	function xmlFolder () {
		return realpath2 ("../../xml/")."/";
	}
	
	function appFolder () {
		return realpath2 (".")."/";
	}	
			
	if (md5 ($hash) == $GLOBALS["passhash"] && strlen ($_FILES['Filedata']["tmp_name"]) > 0) {
		$settings = loadSettings (appFolder () . "../amfphp/extra/");
		
		$albumID = intval ($_GET["albumid"]);

		$fn = xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = getRootCollectionNode ($dom);

		$albnode = getAlbumNodeWithID ($root, $albumID);
		
		if ($albnode === false)
			return;
		
		$albfolder = 'alb_'.$albumID;
		if (!file_exists (imgFolder().$albfolder))
			mkdir (imgFolder().$albfolder);								
		
		do {
			$destfile = $albfolder."/vid_".time()."_".rand (0,1000).".flv";
		} while (file_exists (imgFolder () . $destfile));
		do {
			$destthfile = $albfolder."/img_".time()."_".rand (0,1000).".jpg";
		} while (file_exists (imgFolder () . $destthfile));
		
		copy ($_FILES['Filedata']["tmp_name"], imgFolder () . $destfile);
		copy (appFolder () . "../amfphp/extra/vidthumb.jpg", imgFolder () . $destthfile);		
		
		$bitrate = getFLVBitrate ($_FILES['Filedata']["tmp_name"]);	

		$contnum = intval (getNodeProperty ($albnode, "contentnumber"));		

		changeNodeProperty ($albnode, "contentnumber", $contnum + 1);
		$album = albumFromNode ($albnode);		
		$dom->dump_file ($fn, false, true);				
				
		$dom = domxml_open_file(xmlFolder () . $album["contentxmlurl"]);
		$root = $dom->document_element();
		
		$vidid = getLargestAlbumItemID ($root) + 1;
		
		$vid = $dom->create_element ("video");
		$vid->set_attribute ("_mngid", $vidid);
		$root->append_child ($vid);
		$title = $dom->create_element ("title");
		$vid->append_child ($title);
		$title_str = $_FILES['Filedata']["name"];
		$title_str = substr ($title_str, 0, strrpos ($title_str, "."));
		$title_text = $dom->create_text_node ($title_str);
		$title->append_child ($title_text);
		
		$thnode = $dom->create_element ("thumb");
		$vid->append_child ($thnode);
		$thurlnode = $dom->create_element ("url");
		$thnode->append_child ($thurlnode);
		$text_node = $dom->create_text_node ($destthfile);
		$thurlnode->append_child ($text_node);
		
		$vidnode = $dom->create_element ("video");
		$vid->append_child ($vidnode);
		$strnode = $dom->create_element ("stream");
		$strnode->set_attribute ("bitrate", $bitrate);
		$vidnode->append_child ($strnode);
		$strurlnode = $dom->create_element ("url");
		$strnode->append_child ($strurlnode);
		$text_node = $dom->create_text_node ($destfile);
		$strurlnode->append_child ($text_node);					
		
		$dom->dump_file (xmlFolder () . $album["contentxmlurl"], false, true);
	} else
	{
		header("HTTP/1.0 403 Forbidden");
	}

?>