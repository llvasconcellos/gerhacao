<?php
error_reporting(0);

function myErrorHandler($errno, $errstr, $errfile, $errline) {
	switch ($errno) {
		case FATAL:
			logMsg( "<b>FATAL</b> [$errno] $errstr<br />\n");
			logMsg( "  Fatal error in line $errline of file $errfile");
			logMsg( ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n");
			logMsg( "Aborting...<br />\n");
			exit(1);
			break;
		case ERROR:
			logMsg( "<b>ERROR</b> [$errno] $errstr<br />\n");
			break;
		case WARNING:
			logMsg( "<b>WARNING</b> [$errno] $errstr<br />\n");
			break;
		default:
			logMsg( "Unkown error type: [$errno, $errfile, $errline] $errstr<br />\n");
			break;
	}
}

//set_error_handler("myErrorHandler");

include_once ("../../../extra/passhash.inc.php");
include_once ("../../../../misc/common.inc.php");
if (version_compare(PHP_VERSION,'5','>=')) {
	include_once('../../../../misc/domxml-php4-to-php5.php');
}
include_once ("../../../../misc/xmlfunc.inc.php");

class AlbumManager {
	function AlbumManager() {
		$this->methodTable = array(
			"login" => array(
				"description" => "Dummy login method",
				"access" => "remote",
				"roles" => "manager" 
			),
			"logout" => array(
				"description" => "Log out",
				"access" => "remote",
				"roles" => "manager" 
			),
			"changePassword" => array(
				"access" => "remote",
				"arguments" => array ("newPassHash"),
				"description" => "change login password",
				"roles" => "manager"
			),
			"getAlbums" => array(
				"access" => "remote",
				"returns" => "array",
				"description" => "returns an array containing all albums",
				"roles" => "manager"
			),
			"reprocessAlbums" => array(
				"access" => "remote",
				"description" => "reprocesses albums to assign IDs",
				"roles" => "manager"
			),
			"createAlbum" => array(
				"access" => "remote",
				"arguments" => array ("collID", "title"),
				"description" => "creates an album",
				"roles" => "manager"
			),
			"createCollection" => array(
				"access" => "remote",
				"arguments" => array ("collID", "title"),
				"description" => "creates a collection",
				"roles" => "manager"
			),
			"createAlbumThumb" => array(
				"access" => "remote",
				"arguments" => array ("albumID", "albumItemID", "targetID"),
				"description" => "creates an album or collection thumb",
				"roles" => "manager"
			),
			"renameAlbum" => array(
				"access" => "remote",
				"arguments" => array ("albumID", "title"),
				"description" => "renames an album or a collection",
				"roles" => "manager"
			),
			"moveAlbum" => array(
				"access" => "remote",
				"arguments" => array ("itemID", "collID"),
				"description" => "moves an album or a collection to another collection",
				"roles" => "manager"
			),
			"deleteAlbum" => array(
				"access" => "remote",
				"arguments" => array ("itemID"),
				"description" => "deletes an album or a collection",
				"roles" => "manager"
			),
			"moveAlbumUp" => array(
				"access" => "remote",
				"arguments" => array ("itemID"),
				"description" => "moves the album one position higher",
				"roles" => "manager"
			),
			"moveAlbumDown" => array(
				"access" => "remote",
				"arguments" => array ("itemID"),
				"description" => "moves the album one position lower",
				"roles" => "manager"
			),
			"getAlbumItems" => array(
				"access" => "remote",
				"arguments" => array ("albumID"),
				"returns" => "array",
				"description" => "returns an array of the items in an album",
				"roles" => "manager"
			),
			"moveAlbumItemUp" => array(
				"access" => "remote",
				"arguments" => array ("albumID", "itemID"),
				"description" => "moves an album item one position higher",
				"roles" => "manager"
			),
			"moveAlbumItemDown" => array(
				"access" => "remote",
				"arguments" => array ("albumID", "itemID"),
				"description" => "moves an album item one position lower",
				"roles" => "manager"
			),
			"deleteAlbumItems" => array(
				"access" => "remote",
				"arguments" => array ("albumID","itemIDs"),
				"description" => "deletes all items in an album",
				"roles" => "manager"
			),
			"changeAlbumDescription" => array(
				"access" => "remote",
				"arguments" => array ("albumID","description"),
				"description" => "changes the description of an album",
				"roles" => "manager"
			),
			"applyAlbumItemTitleToAll" => array(
				"access" => "remote",
				"arguments" => array ("albumID","itemID"),
				"description" => "applies the title of an album item to rest of album",
				"roles" => "manager"
			),
			"applyAlbumItemDateToAll" => array(
				"access" => "remote",
				"arguments" => array ("albumID","itemID"),
				"description" => "applies the date of an album item to rest of album",
				"roles" => "manager"
			),
			"applyAlbumItemLocationToAll" => array(
				"access" => "remote",
				"arguments" => array ("albumID","itemID"),
				"description" => "applies the location of an album item to rest of album",
				"roles" => "manager"
			),
			"applyAlbumItemDescriptionToAll" => array(
				"access" => "remote",
				"arguments" => array ("albumID","itemID"),
				"description" => "applies the description of an album item to rest of album",
				"roles" => "manager"
			),
			"moveAlbumItems" => array(
				"access" => "remote",
				"arguments" => array ("srcAlbumID","itemIDs", "destAlbumID"),
				"description" => "moves album items to another album",
				"roles" => "manager"
			),
			"changeAlbumItemTitle" => array(
				"access" => "remote",
				"arguments" => array ("albumID","itemID","title"),
				"description" => "renames title of an album item",
				"roles" => "manager"
			),
			"changeAlbumItemDate" => array(
				"access" => "remote",
				"arguments" => array ("albumID","itemID","date"),
				"description" => "renames date of an album item",
				"roles" => "manager"
			),
			"changeAlbumItemLocation" => array(
				"access" => "remote",
				"arguments" => array ("albumID","itemID","location"),
				"description" => "renames location of an album item",
				"roles" => "manager"
			),
			"changeAlbumItemDescription" => array(
				"access" => "remote",
				"arguments" => array ("albumID","itemID","description"),
				"description" => "changes description of an album item",
				"roles" => "manager"
			),
			"getSettings" => array(
				"access" => "remote",
				"returns" => "associative array",
				"description" => "gets the settings",
				"roles" => "manager"
			),
			"changeSettings" => array(
				"access" => "remote",
				"arguments" => array ("settings"),
				"description" => "changes the serttings",
				"roles" => "manager"
			),
			"setVideoThumb" => array(
				"access" => "remote",
				"arguments" => array ("albumID", "videoID", "datastr"),
				"description" => "sets a video thumbnail",
				"roles" => "manager"
			),
			"getItemsInBucket" => array(
				"access" => "remote",
				"arguments" => array (),
				"description" => "returns photos and videos in bucket",
				"roles" => "manager"
			),
			"addBucketItemsToAlbum" => array(
				"access" => "remote",
				"arguments" => array ("albumID", "files", "removeFiles"),
				"description" => "adds given bucket files to album",
				"roles" => "manager"
			)
		);
	}

	function login() {
		$fn = $this->xmlFolder () . "albums.xml";
		if (!file_exists ($fn)) {
			$f = fopen ($fn, "w");
			fwrite ($f, '<?xml version="1.0" encoding="utf-8"?><expose version="2.1"><collection></collection></expose>');
			fclose ($f);
		}
		return true;
	}

	function xmlFolder () {
		return realpath2 ("../../../../../xml/")."/";
	}

	function appFolder () {
		return realpath2 ("../../..")."/";
	}

	function imgFolder () {
		return realpath2 ("../../../../../img/")."/";
	}

	function changePassword($newPassHash) {
		$appFolder = $this->appFolder ();
		$f = fopen ($appFolder . "extra/passhash.inc.php", "w");
		if (!$f) {
			return;
		}
		fwrite ($f, '<?php $GLOBALS["passhash"] = "'.md5 ($newPassHash).'"; ?>');
		fclose ($f);
	}

	function createAlbum($collID, $title) {
		$fn = $this->xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = getRootCollectionNode ($dom);
		$largestid = getLargestAlbumID ($root);
		$albid = $largestid + 1;
		$albnode = $dom->create_element ("album");
		if (strlen ($collID) > 0) {
			$node = getCollectionNodeWithID ($root, $collID);
			if ($node === false)
				return;
			$node->append_child ($albnode);
		} else
			$root->append_child ($albnode);

		if (!file_exists ($this->imgFolder().'albthumbs'))
			mkdir ($this->imgFolder().'albthumbs');

		$albfolder = 'alb_'.$albid;
		if (!file_exists ($this->imgFolder().$albfolder))
			mkdir ($this->imgFolder().$albfolder);

		$albnode->set_attribute ("_mngid", $albid);

		$tnode = $dom->create_element ("title");
		$albnode->append_child ($tnode);
		$tnode_text = $dom->create_text_node ($title);
		$tnode->append_child ($tnode_text);

		$xunode = $dom->create_element ("contentxmlurl");
		$albnode->append_child ($xunode);
		$xunode_text = $dom->create_text_node ("alb_".$albid.".xml");
		$xunode->append_child ($xunode_text);

		$cnnode = $dom->create_element ("contentnumber");
		$albnode->append_child ($cnnode);
		$cnnode_text = $dom->create_text_node ("0");
		$cnnode->append_child ($cnnode_text);
		copy ($this->appFolder () . "extra/albthumb.jpg", 
		$this->imgFolder () . "albthumbs/albth_".$albid.".jpg");
		$thnode = $dom->create_element ("thumb");
		$albnode->append_child ($thnode);
		$thunode = $dom->create_element ("url");
		$thnode->append_child ($thunode);
		$thunode_text = $dom->create_text_node ("albthumbs/albth_".$albid.".jpg");
		$thunode->append_child ($thunode_text);

		$dom->dump_file ($fn, false, true);

		$f = fopen ($this->xmlFolder () . "alb_".$albid.".xml", "w");
		fwrite ($f, '<'.'?xml version="1.0" encoding="utf-8"?'.'><expose version="2.1"></expose>');
		fclose ($f);
	}

	function createCollection($collID, $title) {
		$fn = $this->xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = getRootCollectionNode ($dom);
		$largestid = getLargestAlbumID ($root);
		$albid = $largestid + 1;
		$collnode = $dom->create_element ("collection");
		if (strlen ($collID) > 0) {
			$node = getCollectionNodeWithID ($root, $collID);
			if ($node === false)
				return;
			$node->append_child ($collnode);
		} else
			$root->append_child ($collnode);

		$collnode->set_attribute ("_mngid", $albid);

		$tnode = $dom->create_element ("title");
		$collnode->append_child ($tnode);
		$tnode_text = $dom->create_text_node ($title);
		$tnode->append_child ($tnode_text);

		if (!file_exists ($this->imgFolder().'albthumbs'))
			mkdir ($this->imgFolder().'albthumbs');
		copy ($this->appFolder () . "extra/collthumb.jpg", 
			$this->imgFolder () . "albthumbs/albth_".$albid.".jpg");
		$thnode = $dom->create_element ("thumb");
		$collnode->append_child ($thnode);
		$thunode = $dom->create_element ("url");
		$thnode->append_child ($thunode);
		$thunode_text = $dom->create_text_node ("albthumbs/albth_".$albid.".jpg");
		$thunode->append_child ($thunode_text);

		$dom->dump_file ($fn, false, true);
	}

	function createAlbumThumb ($albumID, $albumItemID, $targetID) {
		$fn = $this->xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = getRootCollectionNode ($dom);
		$albnode = getAlbumNodeWithID ($root, $albumID);
		if ($albnode === false)
			return;
		$album = albumFromNode ($albnode);
		$tnode = getCollectionOrAlbumNodeWithID ($root, $targetID);
		if ($tnode === false)
			return;
		$thfile = getSubnodeProperty ($tnode, "thumb", "url");
		if ($thfile == "")
			return;

		$fn = $this->xmlFolder () . $album["contentxmlurl"];
		$dom = domxml_open_file($fn);
		$root = $dom->document_element();
		$nodes = $root->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "picture") {
					$item = pictureFromNode ($node);
					if ($item["id"] == $albumItemID) {
						$albumItemThumb = $item["smallimage"]["url"];
						break;
					}
				}
				if ($node->node_name () == "video") {
					$item = videoFromNode ($node);
					if ($item["id"] == $albumItemID) {
						$albumItemThumb = $item["thumb"]["url"];
						break;
					}
				}
			}
		}

		$settings = loadSettings ($this->appFolder () . "extra/");

		$img = imagecreatefromjpeg ($this->imgFolder () . $albumItemThumb);
		$img_width = imagesx ($img);
		$img_height = imagesy ($img);

		if ($img_width / $img_height > $settings["AlbumThumbWidth"] / $settings["AlbumThumbHeight"]) {
			$new_height = $img_height * $settings["AlbumThumbWidth"] / $img_width;
			$new_width = $settings["AlbumThumbWidth"];
		} else {
			$new_width = $img_width * $settings["AlbumThumbHeight"] / $img_height;
			$new_height = $settings["AlbumThumbHeight"];
		}

		$nimg = imagecreatetruecolor ($new_width, $new_height);
		imagecopyresampled ($nimg, $img, 0, 0, 1, 1, $new_width, $new_height, $img_width - 2, $img_height - 2);

		if (!file_exists ($this->imgFolder().'albthumbs'))
			mkdir ($this->imgFolder().'albthumbs');
		imagejpeg ($nimg, $this->imgFolder () . $thfile, $settings["AlbumThumbQuality"]);

		imagedestroy ($nimg);
		imagedestroy ($img);
	}

	function getAlbums() {
		$albums = array ();
		$fn = $this->xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = $dom->document_element();
		$nodes = $root->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "collection") {
					$subnodes = $node->child_nodes ();
					for ($j = 0; $j < count ($subnodes); $j++) {
						$node = $subnodes[$j];
						if ($node->node_type () == XML_ELEMENT_NODE) {
							if ($node->node_name () == "album") {
								$albums[] = albumFromNode ($node);
							}
							if ($node->node_name () == "collection") {
								$albums[] = collectionFromNode ($node);
							}
						}
					}
				}
			}
		}

		if ($albums[0] && strlen ($albums[0]["id"]) == 0)
			return array ("needs reprocessing");

		return $albums;
	}

	function reprocessAlbums () {
		$fn = $this->xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = getRootCollectionNode ($dom);
		$nodes = $root->child_nodes ();
		$startid = 1;
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "album") {
					$startid = reprocessAlbumNode ($node, $startid, $this->xmlFolder ());
				}
				if ($node->node_name () == "collection") {
					$startid = reprocessCollectionNode ($node, $startid, $this->xmlFolder ());
				}
			}
		}
		$dom->dump_file ($fn, false, true);
	}

	function renameAlbum ($albumID, $title) {
		$fn = $this->xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = getRootCollectionNode ($dom);
		$node = getCollectionOrAlbumNodeWithID ($root, $albumID);
		if ($node === false)
			return;
		changeNodeProperty ($node, "title", $title);
		$dom->dump_file ($fn, false, true);
	}

	function moveAlbum ($itemID, $collID) {
		$fn = $this->xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = getRootCollectionNode ($dom);
		$node = getCollectionOrAlbumNodeWithID ($root, $itemID);
		if ($node === false)
			return;
		$pnode = $node->parent_node ();
		$pnode->remove_child ($node);
		if (strlen ($collID) > 0) {
			$cnode = getCollectionNodeWithID ($root, $collID);
			if ($cnode === false)
				return;
			$cnode->append_child ($node);
		} else
			$root->append_child ($node);
		$dom->dump_file ($fn, false, true);
	}

	function deleteAlbums ($albums) {
		foreach ($albums as $album) {
			$fn = $this->xmlFolder () . $album["contentxmlurl"];
			$dom = domxml_open_file($fn);
			$root = $dom->document_element();
			$nodes = $root->child_nodes ();
			for ($i = 0; $i < count ($nodes); $i++) {
				$node = $nodes[$i];
				if ($node->node_type () == XML_ELEMENT_NODE) {
					if ($node->node_name () == "picture") {
						$item = pictureFromNode ($node);
					unlink ($this->imgFolder () . $item["smallimage"]["url"]);
					unlink ($this->imgFolder () . $item["image"]["url"]);
					if ($item["largeimage"])
						unlink ($this->imgFolder () . $item["largeimage"]["url"]);
					}
				}
			}
			unlink ($this->xmlFolder () . $album["contentxmlurl"]);
			unlink ($this->imgFolder () . $album["thumb"]["url"]);
			removeEmptyFolder ($this->imgFolder()."alb_".$album['id']);
		}
	}

	function deleteAlbum ($itemID) {
		$fn = $this->xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = getRootCollectionNode ($dom);
		$node = getCollectionOrAlbumNodeWithID ($root, $itemID);
		if ($node === false)
			return;
		$pnode = $node->parent_node ();
		$pnode->remove_child ($node);
		if ($node->node_name () == "collection") {
			$albums = getAlbumsInNode ($node);
			$cols = getCollectionsInNode ($node);
			foreach ($cols as $col) {
				unlink ($this->imgFolder () . $col["thumb"]["url"]);
			}
			$coll = collectionFromNode ($node);
			unlink ($this->imgFolder () . $coll["thumb"]["url"]);
		} else {
			$albums = array ();
			$albums[] = albumFromNode ($node);
		}
		$dom->dump_file ($fn, false, true);
		$this->deleteAlbums ($albums);
	}

	function moveAlbumUp ($itemID) {
		$fn = $this->xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = getRootCollectionNode ($dom);
		$node = getCollectionOrAlbumNodeWithID ($root, $itemID);
		$prevnode = $node;
		do {
			$prevnode = $prevnode->previous_sibling ();
		} while ($prevnode && $prevnode->node_type () != XML_ELEMENT_NODE);
		if ($prevnode) {
			$pnode = $node->parent_node ();
			$pnode->remove_child ($node);
			$pnode->insert_before ($node, $prevnode);
			$dom->dump_file ($fn, false, true);
		}
	}

	function moveAlbumDown ($itemID) {
		$fn = $this->xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = getRootCollectionNode ($dom);
		$node = getCollectionOrAlbumNodeWithID ($root, $itemID);
		$nextnode = $node;
		do {
			$nextnode = $nextnode->next_sibling ();
		} while ($nextnode && $nextnode->node_type () != XML_ELEMENT_NODE);
		if ($nextnode) {
			$pnode = $node->parent_node ();
			$pnode->remove_child ($nextnode);
			$pnode->insert_before ($nextnode, $node);
			$dom->dump_file ($fn, false, true);
		}
	}

	function moveAlbumItemUp ($albumID, $itemID) {
		$fn = $this->xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = getRootCollectionNode ($dom);
		$albnode = getAlbumNodeWithID ($root, $albumID);
		$album = albumFromNode ($albnode);

		$items = array ();
		$fn = $this->xmlFolder () . $album["contentxmlurl"];
		$dom = domxml_open_file($fn);
		$root = $dom->document_element();
		$nodes = $root->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "picture") {
					$item = pictureFromNode ($node);
					if ($item["id"] == $itemID) {
						$prevnode = $node;
						do {
							$prevnode = $prevnode->previous_sibling ();
						} while ($prevnode && $prevnode->node_type () != XML_ELEMENT_NODE);
						if ($prevnode) {
							$pnode = $node->parent_node ();
							$pnode->remove_child ($node);
							$pnode->insert_before ($node, $prevnode);
							$dom->dump_file ($fn, false, true);
						}
					}
				}
				if ($node->node_name () == "video") {
					$item = videoFromNode ($node);
					if ($item["id"] == $itemID) {
						$prevnode = $node;
						do {
							$prevnode = $prevnode->previous_sibling ();
						} while ($prevnode && $prevnode->node_type () != XML_ELEMENT_NODE);
						if ($prevnode) {
							$pnode = $node->parent_node ();
							$pnode->remove_child ($node);
							$pnode->insert_before ($node, $prevnode);
							$dom->dump_file ($fn, false, true);
						}
					}
				}
			}
		}

		return $items;
	}

	function moveAlbumItemDown ($albumID, $itemID) {
		$fn = $this->xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = getRootCollectionNode ($dom);
		$albnode = getAlbumNodeWithID ($root, $albumID);
		$album = albumFromNode ($albnode);

		$items = array ();
		$fn = $this->xmlFolder () . $album["contentxmlurl"];
		$dom = domxml_open_file($fn);
		$root = $dom->document_element();
		$nodes = $root->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "picture") {
					$item = pictureFromNode ($node);
					if ($item["id"] == $itemID) {
						$nextnode = $node;
						do {
							$nextnode = $nextnode->next_sibling ();
						} while ($nextnode && $nextnode->node_type () != XML_ELEMENT_NODE);
						if ($nextnode) {
							$pnode = $node->parent_node ();
							$pnode->remove_child ($nextnode);
							$pnode->insert_before ($nextnode, $node);
							$dom->dump_file ($fn, false, true);
						}
					}
				}
				if ($node->node_name () == "video") {
					$item = videoFromNode ($node);
					if ($item["id"] == $itemID) {
						$nextnode = $node;
						do {
							$nextnode = $nextnode->next_sibling ();
						} while ($nextnode && $nextnode->node_type () != XML_ELEMENT_NODE);
						if ($nextnode) {
							$pnode = $node->parent_node ();
							$pnode->remove_child ($nextnode);
							$pnode->insert_before ($nextnode, $node);
							$dom->dump_file ($fn, false, true);
						}
					}
				}
			}
		}

		return $items;
	}

	function getAlbumItems($albumID) {
		$fn = $this->xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = getRootCollectionNode ($dom);
		$albnode = getAlbumNodeWithID ($root, $albumID);
		$album = albumFromNode ($albnode);

		$items = array ();
		$fn = $this->xmlFolder () . $album["contentxmlurl"];
		$dom = domxml_open_file($fn);
		$root = $dom->document_element();
		$nodes = $root->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "picture") {
					$items[] = pictureFromNode ($node);
				}
				if ($node->node_name () == "video") {
					$items[] = videoFromNode ($node);
				}
			}
		}

		return $items;
	}

	function deleteAlbumItems($albumID, $itemIDs) {
		$fn = $this->xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = getRootCollectionNode ($dom);
		$albnode = getAlbumNodeWithID ($root, $albumID);
		$contnum = intval (getNodeProperty ($albnode, "contentnumber"));
		changeNodeProperty ($albnode, "contentnumber", $contnum - count ($itemIDs));
		$dom->dump_file ($fn, false, true);
		$album = albumFromNode ($albnode);

		$dom = domxml_open_file($this->xmlFolder () . $album["contentxmlurl"]);
		$root = $dom->document_element();
		$nodes = $root->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "picture") {
					$pic = pictureFromNode ($node);
					foreach ($itemIDs as $itemID) {
						if ($pic["id"] == $itemID) {
							$imgfile = $pic["image"]["url"];
							unlink ($this->imgFolder () . $imgfile);
							$imgfile = $pic["smallimage"]["url"];
							unlink ($this->imgFolder () . $imgfile);
							if ($pic["largeimage"]) {
								$imgfile = $pic["largeimage"]["url"];
								unlink ($this->imgFolder () . $imgfile);
							}
							$root->remove_child ($node);
							break;
						}
					}
				}

				if ($node->node_name () == "video") {
					$vid = videoFromNode ($node);
					foreach ($itemIDs as $itemID) {
						if ($vid["id"] == $itemID) {
							$thfile = $vid["thumb"]["url"];
							unlink ($this->imgFolder () . $thfile);
							foreach ($vid["streams"] as $stream) {
								unlink ($this->imgFolder () . $stream["url"]);
							}
							$root->remove_child ($node);
							break;
						}
					}
				}
			}
		}
		$dom->dump_file ($this->xmlFolder () . $album["contentxmlurl"], false, true);
	}

	function changeAlbumDescription($albumID, $description) {
		$fn = $this->xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = getRootCollectionNode ($dom);
		$albnode = getAlbumNodeWithID ($root, $albumID);
		changeNodeProperty ($albnode, "description", str_replace ("\n", " \\n ", $description));
		$dom->dump_file ($fn, false, true);
	}

	function applyAlbumItemTitleToAll ($albumID, $itemID) {
		$fn = $this->xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = getRootCollectionNode ($dom);
		$albnode = getAlbumNodeWithID ($root, $albumID);
		$contnum = intval (getNodeProperty ($albnode, "contentnumber"));
		changeNodeProperty ($albnode, "contentnumber", $contnum - count ($itemIDs));
		$dom->dump_file ($fn, false, true);
		$album = albumFromNode ($albnode);
		$dom = domxml_open_file($this->xmlFolder () . $album["contentxmlurl"]);
		$root = $dom->document_element();

		$node = getAlbumItemNodeWithID ($root, $itemID);
		$nodetype = getAlbumItemNodeType ($node);
		if ($nodetype == "picture") {
			$pic = pictureFromNode ($node);
			$title = $pic["title"];
		}
		if ($nodetype == "video") {
			$vid = videoFromNode ($node);
			$title = $vid["title"];
		}

		$nodes = $root->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "picture") {
					changeNodeProperty ($node, "title", $title);
				}
				if ($node->node_name () == "video") {
					changeNodeProperty ($node, "title", $title);
				}
			}
		}
		$dom->dump_file ($this->xmlFolder () . $album["contentxmlurl"], false, true);
	}

	function applyAlbumItemDateToAll ($albumID, $itemID) {
		$fn = $this->xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = getRootCollectionNode ($dom);
		$albnode = getAlbumNodeWithID ($root, $albumID);
		$contnum = intval (getNodeProperty ($albnode, "contentnumber"));
		changeNodeProperty ($albnode, "contentnumber", $contnum - count ($itemIDs));
		$dom->dump_file ($fn, false, true);
		$album = albumFromNode ($albnode);

		$dom = domxml_open_file($this->xmlFolder () . $album["contentxmlurl"]);
		$root = $dom->document_element();

		$node = getAlbumItemNodeWithID ($root, $itemID);
		$nodetype = getAlbumItemNodeType ($node);
		if ($nodetype == "picture") {
			$pic = pictureFromNode ($node);
			$date = $pic["date"];
		}
		if ($nodetype == "video") {
			$vid = videoFromNode ($node);
			$date = $vid["date"];
		}

		$nodes = $root->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "picture") {
					changeNodeProperty ($node, "date", $date);
				}
				if ($node->node_name () == "video") {
					changeNodeProperty ($node, "date", $date);
				}
			}
		}
		$dom->dump_file ($this->xmlFolder () . $album["contentxmlurl"], false, true);
	}

	function applyAlbumItemLocationToAll ($albumID, $itemID) {
		$fn = $this->xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = getRootCollectionNode ($dom);
		$albnode = getAlbumNodeWithID ($root, $albumID);
		$contnum = intval (getNodeProperty ($albnode, "contentnumber"));
		changeNodeProperty ($albnode, "contentnumber", $contnum - count ($itemIDs));
		$dom->dump_file ($fn, false, true);
		$album = albumFromNode ($albnode);

		$dom = domxml_open_file($this->xmlFolder () . $album["contentxmlurl"]);
		$root = $dom->document_element();

		$node = getAlbumItemNodeWithID ($root, $itemID);
		$nodetype = getAlbumItemNodeType ($node);
		if ($nodetype == "picture") {
			$pic = pictureFromNode ($node);
			$loc = $pic["location"];
		}
		if ($nodetype == "video") {
			$vid = videoFromNode ($node);
			$loc = $vid["location"];
		}

		$nodes = $root->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "picture") {
					changeNodeProperty ($node, "location", $loc);
				}
				if ($node->node_name () == "video") {
					changeNodeProperty ($node, "location", $loc);
				}
			}
		}
		$dom->dump_file ($this->xmlFolder () . $album["contentxmlurl"], false, true);
	}

	function applyAlbumItemDescriptionToAll ($albumID, $itemID) {
		$fn = $this->xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = getRootCollectionNode ($dom);
		$albnode = getAlbumNodeWithID ($root, $albumID);
		$contnum = intval (getNodeProperty ($albnode, "contentnumber"));
		changeNodeProperty ($albnode, "contentnumber", $contnum - count ($itemIDs));
		$dom->dump_file ($fn, false, true);
		$album = albumFromNode ($albnode);

		$dom = domxml_open_file($this->xmlFolder () . $album["contentxmlurl"]);
		$root = $dom->document_element();

		$node = getAlbumItemNodeWithID ($root, $itemID);
		$nodetype = getAlbumItemNodeType ($node);
		if ($nodetype == "picture") {
			$pic = pictureFromNode ($node);
			$desc = $pic["description"];
		}
		if ($nodetype == "video") {
			$vid = videoFromNode ($node);
			$desc = $vid["description"];
		}

		$nodes = $root->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "picture") {
					changeNodeProperty ($node, "description", $desc);
				}
				if ($node->node_name () == "video") {
					changeNodeProperty ($node, "description", $desc);
				}
			}
		}
		$dom->dump_file ($this->xmlFolder () . $album["contentxmlurl"], false, true);
	}

	function moveAlbumItems($srcAlbumID, $itemIDs, $destAlbumID) {
		$fn = $this->xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = getRootCollectionNode ($dom);

		$albnode = getAlbumNodeWithID ($root, $srcAlbumID);
		if ($albnode === false)
			return;
		$contnum = intval (getNodeProperty ($albnode, "contentnumber"));
		changeNodeProperty ($albnode, "contentnumber", $contnum - count ($itemIDs));

		$dest_albnode = getAlbumNodeWithID ($root, $destAlbumID);
		if ($dest_albnode === false)
			return;
		$dest_contnum = intval (getNodeProperty ($dest_albnode, "contentnumber"));
		changeNodeProperty ($dest_albnode, "contentnumber", $dest_contnum + count ($itemIDs));
		$dom->dump_file ($fn, false, true);

		$src_album = albumFromNode ($albnode);
		$dest_album = albumFromNode ($dest_albnode);

		$cnodes = array ();

		$dom = domxml_open_file($this->xmlFolder () . $src_album["contentxmlurl"]);
		$root = $dom->document_element();
		$nodes = $root->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "picture") {
					$pic = pictureFromNode ($node);
					foreach ($itemIDs as $itemID) {
						if ($pic["id"] == $itemID) {
							$root->remove_child ($node);
							$cnodes[] = $node;
							break;
						}
					}
				}
				if ($node->node_name () == "video") {
					$vid = videoFromNode ($node);
					foreach ($itemIDs as $itemID) {
						if ($vid["id"] == $itemID) {
							$root->remove_child ($node);
							$cnodes[] = $node;
							break;
						}
					}
				}
			}
		}
		$dom->dump_file ($this->xmlFolder () . $src_album["contentxmlurl"], false, true);

		$albfolder = 'alb_'.$dest_album['id'];
		if (!file_exists ($this->imgFolder().$albfolder))
			mkdir ($this->imgFolder().$albfolder);

		$dom = domxml_open_file($this->xmlFolder () . $dest_album["contentxmlurl"]);
		$root = $dom->document_element();
		foreach ($cnodes as $cnode) {
			$nodetype = getAlbumItemNodeType ($cnode);
			if ($nodetype == "picture") {
				$item = pictureFromNode ($cnode);

				do {
					$destfile = $albfolder."/img_".time()."_".rand (0,1000).".jpg";
				} while (file_exists ($this->imgFolder () . $destfile));
				do {
					$destsmfile = $albfolder."/img_".time()."_".rand (0,1000)."_sm.jpg";
				} while (file_exists ($this->imgFolder () .$destsmfile));
				do {
					$destlgfile = $albfolder."/img_".time()."_".rand (0,1000)."_lg.jpg";
				} while (file_exists ($this->imgFolder () .$destlgfile));

				rename ($this->imgFolder().$item["image"]["url"], 
					$this->imgFolder().$destfile);
				rename ($this->imgFolder().$item["smallimage"]["url"], 
					$this->imgFolder().$destsmfile);
				if ($item["largeimage"]) {
					rename ($this->imgFolder().$item["largeimage"]["url"],
						$this->imgFolder.$destlgfile);
				}

				$pic = $dom->create_element ("picture");
				$picid = getLargestAlbumItemID ($root) + 1;
				$root->append_child ($pic);
				$pic->set_attribute ("_mngid", $picid);
				$title = $dom->create_element ("title");
				$pic->append_child ($title);
				$title_text = $dom->create_text_node ($item["title"]);
				$title->append_child ($title_text);

				$title = $dom->create_element ("date");
				$pic->append_child ($title);
				$title_text = $dom->create_text_node ($item["date"]);
				$title->append_child ($title_text);

				$title = $dom->create_element ("location");
				$pic->append_child ($title);
				$title_text = $dom->create_text_node ($item["location"]);
				$title->append_child ($title_text);

				$title = $dom->create_element ("description");
				$pic->append_child ($title);
				$title_text = $dom->create_text_node ($item["description"]);
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

				if ($item["largeimage"]) {
					$imgnode = $dom->create_element ("largeimage");
					$pic->append_child ($imgnode);
					$imgurlnode = $dom->create_element ("url");
					$imgnode->append_child ($imgurlnode);
					$text_node = $dom->create_text_node ($destlgfile);
					$imgurlnode->append_child ($text_node);
				}
			}

			if ($nodetype == "video") {
				$item = videoFromNode ($cnode);
				do {
					$destthfile = $albfolder."/img_".time()."_".rand (0,1000).".jpg";
				} while (file_exists ($this->imgFolder () . $destthfile));

				rename ($this->imgFolder().$item["thumb"]["url"], 
					$this->imgFolder().$destthfile);

				$vid = $dom->create_element ("video");
				$vidid = getLargestAlbumItemID ($root) + 1;
				$root->append_child ($vid);
				$vid->set_attribute ("_mngid", $vidid);
				$title = $dom->create_element ("title");
				$vid->append_child ($title);
				$title_text = $dom->create_text_node ($item["title"]);
				$title->append_child ($title_text);

				$title = $dom->create_element ("date");
				$vid->append_child ($title);
				$title_text = $dom->create_text_node ($item["date"]);
				$title->append_child ($title_text);

				$title = $dom->create_element ("location");
				$vid->append_child ($title);
				$title_text = $dom->create_text_node ($item["location"]);
				$title->append_child ($title_text);

				$title = $dom->create_element ("description");
				$vid->append_child ($title);
				$title_text = $dom->create_text_node ($item["description"]);
				$title->append_child ($title_text);

				$thnode = $dom->create_element ("thumb");
				$vid->append_child ($thnode);
				$thurlnode = $dom->create_element ("url");
				$thnode->append_child ($thurlnode);
				$text_node = $dom->create_text_node ($destfile);
				$thurlnode->append_child ($text_node);

				$vidnode = $dom->create_element ("video");
				$vid->append_child ($vidnode);

				foreach ($item["streams"] as $stream) {
					do {
						$destfile = $albfolder."/vid_".time()."_".rand (0,1000).".flv";
					} while (file_exists (imgFolder () . $destfile));

					rename ($this->imgFolder().$stream["url"], 
						$this->imgFolder().$destfile);

					$strnode = $dom->create_element ("stream");
					$strnode->set_attribute ("bitrate", $stream["bitrate"]);
					$vidnode->append_child ($strnode);
					$strurlnode = $dom->create_element ("url");
					$strnode->append_child ($strurlnode);
					$text_node = $dom->create_text_node ($destfile);
					$strurlnode->append_child ($text_node);
				}
			}
		}
		$dom->dump_file ($this->xmlFolder () . $dest_album["contentxmlurl"], false, true);
	}

	function changeAlbumItemTitle ($albumID, $itemID, $title) {
		$fn = $this->xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = getRootCollectionNode ($dom);
		$albnode = getAlbumNodeWithID ($root, $albumID);
		$album = albumFromNode ($albnode);

		$fn = $this->xmlFolder () . $album["contentxmlurl"];
		$dom = domxml_open_file($fn);
		$root = $dom->document_element();
		$nodes = $root->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "picture") {
					$pic = pictureFromNode ($node);
					if ($pic["id"] == $itemID) {
						changeNodeProperty ($node, "title", $title);
						break;
					}
				}
				if ($node->node_name () == "video") {
					$vid = videoFromNode ($node);
					if ($vid["id"] == $itemID) {
						changeNodeProperty ($node, "title", $title);
						break;
					}
				}
			}
		}
		$dom->dump_file ($this->xmlFolder () . $album["contentxmlurl"], false, true);
	}

	function changeAlbumItemDate ($albumID, $itemID, $date) {
		$fn = $this->xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = getRootCollectionNode ($dom);
		$albnode = getAlbumNodeWithID ($root, $albumID);
		$album = albumFromNode ($albnode);

		$fn = $this->xmlFolder () . $album["contentxmlurl"];
		$dom = domxml_open_file($fn);
		$root = $dom->document_element();
		$nodes = $root->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "picture") {
					$pic = pictureFromNode ($node);
					if ($pic["id"] == $itemID) {
						changeNodeProperty ($node, "date", $date);
						break;
					}
				}
				if ($node->node_name () == "video") {
					$vid = videoFromNode ($node);
					if ($vid["id"] == $itemID) {
						changeNodeProperty ($node, "date", $date);
						break;
					}
				}
			}
		}
		$dom->dump_file ($this->xmlFolder () . $album["contentxmlurl"], false, true);
	}

	function changeAlbumItemLocation ($albumID, $itemID, $location) {
		$fn = $this->xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = getRootCollectionNode ($dom);
		$albnode = getAlbumNodeWithID ($root, $albumID);
		$album = albumFromNode ($albnode);

		$fn = $this->xmlFolder () . $album["contentxmlurl"];
		$dom = domxml_open_file($fn);
		$root = $dom->document_element();
		$nodes = $root->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "picture") {
					$pic = pictureFromNode ($node);
					if ($pic["id"] == $itemID) {
						changeNodeProperty ($node, "location", $location);
						break;
					}
				}
				if ($node->node_name () == "video") {
					$vid = videoFromNode ($node);
					if ($vid["id"] == $itemID) {
						changeNodeProperty ($node, "location", $location);
						break;
					}
				}
			}
		}
		$dom->dump_file ($this->xmlFolder () . $album["contentxmlurl"], false, true);
	}

	function changeAlbumItemDescription ($albumID, $itemID, $description) {
		$fn = $this->xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = getRootCollectionNode ($dom);
		$albnode = getAlbumNodeWithID ($root, $albumID);
		$album = albumFromNode ($albnode);

		$fn = $this->xmlFolder () . $album["contentxmlurl"];
		$dom = domxml_open_file($fn);
		$root = $dom->document_element();
		$nodes = $root->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "picture") {
					$pic = pictureFromNode ($node);
					if ($pic["id"] == $itemID) {
						changeNodeProperty ($node, "description", str_replace ("\n", " \\n ", $description));
						break;
					}
				}
				if ($node->node_name () == "video") {
					$vid = videoFromNode ($node);
					if ($vid["id"] == $itemID) {
						changeNodeProperty ($node, "description", str_replace ("\n", " \\n ", $description));
						break;
					}
				}
			}
		}
		$dom->dump_file ($this->xmlFolder () . $album["contentxmlurl"], false, true);
	}

	function getSettings() {
		$settings = loadSettings ($this->appFolder () . "extra/");
		return $settings;
	}

	function changeSettings($settings) {
		$dom = domxml_open_file($this->appFolder () . "extra/settings.xml");
		$root = $dom->document_element();
		$nodes = $root->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "setting") {
					$name = $node->get_attribute ("name");
					foreach ($settings as $setting => $value) {
						if ($name == $setting) {
							$cnode = $node->first_child ();
							if ($cnode)
								$node->remove_child ($cnode);
							$text_node = $dom->create_text_node ($value);
							$node->append_child ($text_node);
							break;
						}
					}
				}
			}
		}
		$dom->dump_file ($this->appFolder () . "extra/settings.xml", false, true);
	}

	function setVideoThumb ($albumID, $videoID, $datastr) {
		$fn = $this->xmlFolder () . "albums.xml";
		$dom = domxml_open_file($fn);
		$root = getRootCollectionNode ($dom);
		$albnode = getAlbumNodeWithID ($root, $albumID);
		if ($albnode === false)
			return;
		$album = albumFromNode ($albnode);

		$fn = $this->xmlFolder () . $album["contentxmlurl"];
		$dom = domxml_open_file($fn);
		$root = $dom->document_element();
		$videonode = getVideoNodeWithID ($root, $videoID);
		if ($videonode === false)
			return;
		$vid = videoFromNode ($videonode);

		$str = utf8_decode ($datastr);
		$jpgstr = "";
		//$f = fopen ($this->imgFolder () . $vid["thumb"]["url"], "wb");
		for ($i = 0; $i < strlen ($str); $i++) {
			if (ord ($str[$i]) != 32) {
				//fwrite ($f, $str[$i]);
				$jpgstr .= $str[$i];
			} else {
				if (ord ($str[$i + 1]) == 32)
					//fwrite ($f, chr (32));
					$jpgstr .= chr (32);
				if (ord ($str[$i + 1]) == 33)
					//fwrite ($f, chr (0));
					$jpgstr .= chr (0);
				$i++;
			}
		}
		$settings = loadSettings ($this->appFolder () . "extra/");
		//fclose ($f);
		$img = imagecreatefromstring ($jpgstr);
		$nimg = imagecreatetruecolor ($settings["VideoThumbWidth"], $settings["VideoThumbHeight"]);
		imagecopyresampled ($nimg, $img, 0, 0, 1, 1, 
			$settings["VideoThumbWidth"], $settings["VideoThumbHeight"], 
			imagesx ($img) - 2, imagesy ($img) - 2);
		imagejpeg ($nimg, $this->imgFolder () . $vid["thumb"]["url"], $settings["VideoThumbQuality"]);
		imagedestroy ($img);
		imagedestroy ($nimg);
	}

	function getItemsInBucket () {
		$items = array ();
		$items["photos"] = array ();
		$items["videos"] = array ();
		$dir = $this->appFolder () . "../bucket";
		getItemsFromBucketFolder ($dir, "", $items);
		return $items;
	}

	function addBucketItemsToAlbum ($albumID, $files, $removeFiles) {
		$settings = loadSettings ($this->appFolder () . "extra/");
		$albfn = $this->xmlFolder () . "albums.xml";
		$albdom = domxml_open_file($albfn);
		$root = getRootCollectionNode ($albdom);

		$albnode = getAlbumNodeWithID ($root, $albumID);
		if ($albnode === false)
			return;
		$album = albumFromNode ($albnode);
		$contnum = intval (getNodeProperty ($albnode, "contentnumber"));

		$dom = domxml_open_file($this->xmlFolder () . $album["contentxmlurl"]);
		$root = $dom->document_element();
		$itemID = getLargestAlbumItemID ($root);

		$albfolder = 'alb_'.$album['id'];
		if (!file_exists ($this->imgFolder().$albfolder))
			mkdir ($this->imgFolder().$albfolder);

		if (count ($files["photos"]) > 0)
		foreach ($files["photos"] as $file) {
			if (validBucketFile ($file) && file_exists ($this->appFolder () . "../bucket/$file")) {
				$contnum++;

			do {
				$destfile = $albfolder."/img_".time()."_".rand (0,1000).".jpg";
			} while (file_exists ($this->imgFolder () . $destfile));
			do {
				$destsmfile = $albfolder."/img_".time()."_".rand (0,1000)."_sm.jpg";
			} while (file_exists ($this->imgFolder () . $destsmfile));
			do {
				$destlgfile = $albfolder."/img_".time()."_".rand (0,1000)."_lg.jpg";
			} while (file_exists ($this->imgFolder () . $destlgfile));

			if ($settings["ImportEmbedCopyright"] == "true") {
				// get size of copyright text
				$im = imagecreate(400, 400);
				$tsize = imagettftext($im, intval ($settings["ImportEmbedCopyrightTextSize"]), 0, 
					0, 0, 
					imagecolorallocate($im, 255, 255, 255),
					$this->appFolder () . "../fonts/embedcopy.ttf",
					$settings["ImportEmbedCopyrightText"]
					);
				imagedestroy ($im);
			}

			// resize to small image
			$img = imagecreatefromjpeg ($this->appFolder () . "../bucket/$file");
			list($img_width, $img_height, $img_type, $img_attr) = getimagesize($this->appFolder () . "../bucket/$file");

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
					$this->appFolder () . "../fonts/embedcopy.ttf",
					$settings["ImportEmbedCopyrightText"]
				);
			if ($settings["ImportEmbedWatermark"] == "true") {
				$wimg = imagecreatefrompng($this->appFolder () . "extra/watermark.png");
				imagealphablending ($wimg, false);
				imagesavealpha ($wimg, true);
				$wwidth=imageSX($wimg);
				$wheight=imageSY($wimg);
				imagecopy ($nimg, $wimg, $new_width - $wwidth, $new_height - $wheight, 0, 0, $wwidth, $wheight);
			}
			imagejpeg ($nimg, $this->imgFolder () . $destfile, $settings["ImportQuality"]);
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
			imagejpeg ($nimg, $this->imgFolder () . $destsmfile, $settings["ImportQuality"]);
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
						$this->appFolder () . "../fonts/embedcopy.ttf",
						$settings["ImportEmbedCopyrightText"]
					);
				if ($settings["ImportEmbedWatermark"] == "true") {
					imagecopy ($nimg, $wimg, $new_width - $wwidth, $new_height - $wheight, 0, 0, $wwidth, $wheight);
				}
				imagejpeg ($nimg, $this->imgFolder () . $destlgfile, $settings["ImportQuality"]);
				imagedestroy ($nimg);
			}
			if ($settings["ImportEmbedWatermark"] == "true")
				imagedestroy ($wimg);

			imagedestroy ($img);
			$itemID++;

			$pic = $dom->create_element ("picture");
			$pic->set_attribute ("_mngid", $itemID);
			$root->append_child ($pic);
			$title = $dom->create_element ("title");
			$pic->append_child ($title);
			$pathinfo = pathinfo ($file);
			$title_str = $pathinfo["basename"];
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

			if ($removeFiles)
				unlink ($this->appFolder () . "../bucket/$file");
			}
		}

		if (count ($files["videos"]) > 0)
			foreach ($files["videos"] as $file) {
				if (validBucketFile ($file) && file_exists ($this->appFolder () . "../bucket/$file")) {
					$contnum++;
					do {
						$destfile = $albfolder."/vid_".time()."_".rand (0,1000).".flv";
					} while (file_exists ($this->imgFolder () . $destfile));
					do {
						$destthfile = $albfolder."/img_".time()."_".rand (0,1000).".jpg";
					} while (file_exists ($this->imgFolder () . $destthfile));
					copy ($this->appFolder () . "../bucket/$file", $this->imgFolder () . $destfile);
					copy ($this->appFolder () . "extra/vidthumb.jpg", $this->imgFolder () . $destthfile);		

					$bitrate = getFLVBitrate ($this->appFolder () . "../bucket/$file");

					$itemID++;

					$vid = $dom->create_element ("video");
					$vid->set_attribute ("_mngid", $itemID);
					$root->append_child ($vid);
					$title = $dom->create_element ("title");
					$vid->append_child ($title);
					$pathinfo = pathinfo ($file);
					$title_str = $pathinfo["basename"];
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
					if ($removeFiles)
						unlink ($this->appFolder () . "../bucket/$file");
				}
			}
		$dom->dump_file ($this->xmlFolder () . $album["contentxmlurl"], false, true);
		changeNodeProperty ($albnode, "contentnumber", $contnum);
		$albdom->dump_file ($albfn, false, true);
		removeEmptyBucketFolders ($this->appFolder () . "../bucket", true);
	}

	function _authenticate($user, $pass) {
		if(md5($pass) == $GLOBALS["passhash"]) {
			return "manager";
		} else {
			return false;
		}
	}

	function logout() {
		Authenticate::logout();
		return true;
	}
}
?>
