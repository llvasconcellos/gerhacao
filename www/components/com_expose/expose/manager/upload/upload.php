<?php
	error_reporting(0);
	include_once ("../amfphp/extra/passhash.inc.php");
	include_once ("../misc/common.inc.php");

	if (version_compare(PHP_VERSION,'5','>='))
		include_once('../misc/domxml-php4-to-php5.php');
	include_once ("../misc/xmlfunc.inc.php");
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
			$destfile = $albfolder."/img_".time()."_".rand (0,1000).".jpg";
		} while (file_exists (imgFolder () . $destfile));
		do {
			$destsmfile = $albfolder."/img_".time()."_".rand (0,1000)."_sm.jpg";
		} while (file_exists (imgFolder () . $destsmfile));
		do {
			$destlgfile = $albfolder."/img_".time()."_".rand (0,1000)."_lg.jpg";
		} while (file_exists (imgFolder () . $destlgfile));
		
		if ($settings["ImportEmbedCopyright"] == "true") {
			// get size of copyright text
			$im = imagecreate(400, 400);
			$tsize = imagettftext($im, intval ($settings["ImportEmbedCopyrightTextSize"]), 0, 
				0, 0, 
				imagecolorallocate($im, 255, 255, 255),
				appFolder () . "../fonts/embedcopy.ttf",
				$settings["ImportEmbedCopyrightText"]
			);
			imagedestroy ($im);	
		}
		
		// resize to small image
		$img = imagecreatefromjpeg ($_FILES['Filedata']["tmp_name"]);
		list($img_width, $img_height, $img_type, $img_attr) = getimagesize($_FILES['Filedata']["tmp_name"]);
		
		if ($img_width / $img_height > $settings["ImportWidth"] / $settings["ImportHeight"]) {
			$new_height = $img_height * $settings["ImportWidth"] / $img_width;
			$new_width = $settings["ImportWidth"];
		} else {
			$new_width = $img_width * $settings["ImportHeight"] / $img_height;
			$new_height = $settings["ImportHeight"];
		}
		$nimg = imagecreatetruecolor ($new_width, $new_height);
		imagecopyresampled ($nimg, $img, 0, 0, 1, 1, $new_width, $new_height, $img_width - 2, $img_height - 2);
		if ($settings["ImportEmbedCopyright"] == "true")
			imagettftext($nimg, intval ($settings["ImportEmbedCopyrightTextSize"]), 0, 
				$new_width - 10 - $tsize[2], $new_height - 10, 
				imagecolorallocatealpha($nimg, 255, 255, 255, 
					127 - intval ($settings["ImportEmbedCopyrightTextColorAlpha"]) * 127 / 100),
				appFolder () . "../fonts/embedcopy.ttf",
				$settings["ImportEmbedCopyrightText"]
			);
		if ($settings["ImportEmbedWatermark"] == "true") {
			$wimg = imagecreatefrompng(appFolder () . "../amfphp/extra/watermark.png");
			imagealphablending ($wimg, false);
			imagesavealpha ($wimg, true);
			$wwidth=imageSX($wimg);
			$wheight=imageSY($wimg);
			imagecopy ($nimg, $wimg, $new_width - $wwidth, $new_height - $wheight, 0, 0, $wwidth, $wheight);
		}
		imagejpeg ($nimg, imgFolder () . $destfile, $settings["ImportQuality"]);
		imagedestroy ($nimg);
		
		if ($img_width / $img_height > $settings["ImportSmallWidth"] / $settings["ImportSmallHeight"]) {
			$new_height = $img_height * $settings["ImportSmallWidth"] / $img_width;
			$new_width = $settings["ImportSmallWidth"];
		} else {
			$new_width = $img_width * $settings["ImportSmallHeight"] / $img_height;
			$new_height = $settings["ImportSmallHeight"];
		}
		$nimg = imagecreatetruecolor ($new_width, $new_height);
		imagecopyresampled ($nimg, $img, 0, 0, 1, 1, $new_width, $new_height, $img_width - 2, $img_height - 2);
		imagejpeg ($nimg, imgFolder () . $destsmfile, $settings["ImportQuality"]);
		imagedestroy ($nimg);
		
		if ($settings["ImportLarge"] == "true") {
			if ($img_width / $img_height > $settings["ImportLargeWidth"] / $settings["ImportLargeHeight"]) {
				$new_height = $img_height * $settings["ImportLargeWidth"] / $img_width;
				$new_width = $settings["ImportLargeWidth"];
			} else {
				$new_width = $img_width * $settings["ImportLargeHeight"] / $img_height;
				$new_height = $settings["ImportLargeHeight"];
			}
			$nimg = imagecreatetruecolor ($new_width, $new_height);
			imagecopyresampled ($nimg, $img, 0, 0, 1, 1, $new_width, $new_height, $img_width - 2, $img_height - 2);
			if ($settings["ImportEmbedCopyright"] == "true")
				imagettftext($nimg, intval ($settings["ImportEmbedCopyrightTextSize"]), 0, 
					$new_width - 10 - $tsize[2], $new_height - 10, 
					imagecolorallocatealpha($nimg, 255, 255, 255, 
						127 - intval ($settings["ImportEmbedCopyrightTextColorAlpha"]) * 127 / 100),
					appFolder () . "../fonts/embedcopy.ttf",
					$settings["ImportEmbedCopyrightText"]
				);
			if ($settings["ImportEmbedWatermark"] == "true") {
				imagecopy ($nimg, $wimg, $new_width - $wwidth, $new_height - $wheight, 0, 0, $wwidth, $wheight);
			}
			imagejpeg ($nimg, imgFolder () . $destlgfile, $settings["ImportQuality"]);
			imagedestroy ($nimg);
		}
		if ($settings["ImportEmbedWatermark"] == "true")
			imagedestroy ($wimg);

		imagedestroy ($img);


		$contnum = intval (getNodeProperty ($albnode, "contentnumber"));

		changeNodeProperty ($albnode, "contentnumber", $contnum + 1);
		$album = albumFromNode ($albnode);
		$dom->dump_file ($fn, false, true);

		$dom = domxml_open_file(xmlFolder () . $album["contentxmlurl"]);
		$root = $dom->document_element();

		$picid = getLargestAlbumItemID ($root) + 1;

		$pic = $dom->create_element ("picture");
		$pic->set_attribute ("_mngid", $picid);
		$root->append_child ($pic);
		$title = $dom->create_element ("title");
		$pic->append_child ($title);
		$title_str = $_FILES['Filedata']["name"];
		$title_str = substr ($title_str, 0, strrpos ($title_str, "."));
		$title_text = $dom->create_text_node ($title_str);
		$title->append_child ($title_text);

		$imgnode = $dom->create_element ("image");
		$pic->append_child ($imgnode);
		$imgurlnode = $dom->create_element ("url");
		$imgnode->append_child ($imgurlnode);
		$text_node = $dom->create_text_node ($destfile);
		$imgurlnode->append_child ($text_node);

		$imgnode = $dom->create_element ("smallimage");
		$pic->append_child ($imgnode);
		$imgurlnode = $dom->create_element ("url");
		$imgnode->append_child ($imgurlnode);
		$text_node = $dom->create_text_node ($destsmfile);
		$imgurlnode->append_child ($text_node);

		if ($settings["ImportLarge"] == "true") {
			$imgnode = $dom->create_element ("largeimage");
			$pic->append_child ($imgnode);
			$imgurlnode = $dom->create_element ("url");
			$imgnode->append_child ($imgurlnode);
			$text_node = $dom->create_text_node ($destlgfile);
			$imgurlnode->append_child ($text_node);
		}

		$dom->dump_file (xmlFolder () . $album["contentxmlurl"], false, true);
	} else
	{
		header("HTTP/1.0 403 Forbidden");
	}

?>