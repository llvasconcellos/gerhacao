<?php
	function getNodeProperty ($anode, $propname) {
		$nodes = $anode->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == $propname) {
					$cnode = $node->first_child ();
					if ($cnode)
						return $cnode->node_value ();
					else
						return "";
				}
			}
		}
		return "";
	}

	function getSubnodeProperty ($anode, $propname, $subpropname) {
		$nodes = $anode->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == $propname) {
					return getNodeProperty ($node, $subpropname);
				}
			}
		}
		return "";
	}

	function albumFromNode ($anode) {
		$album = array ();
		$album["type"] = "album";
		$idattr = $anode->get_attribute ("_mngid");
		if ($idattr)
			$album["id"] = intval ($idattr);
		else
			$album["id"] = "";
		$album["title"] = getNodeProperty ($anode, "title");
		$album["contentxmlurl"] = getNodeProperty ($anode, "contentxmlurl");
		$album["thumb"] = array ();
		$album["thumb"]["url"] = getSubnodeProperty ($anode, "thumb", "url");
		$album["description"] = str_replace (" \\n ", "\n", getNodeProperty ($anode, "description"));
		return $album;
	}

	function getAlbumNodeWithID ($cnode, $id) {
		$nodes = $cnode->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "collection") {
					$nd = getAlbumNodeWithID ($node, $id);
					if ($nd)
						return $nd;
				}
				if ($node->node_name () == "album") {
					$nodeid = $node->get_attribute ("_mngid");
					if (intval ($nodeid) == intval ($id))
						return $node;
				}
			}
		}
		return false;
	}

	function getAlbumItemNodeWithID ($cnode, $id) {
		$nodes = $cnode->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "picture") {
					if ($node->get_attribute ("_mngid") == $id)
						return $node;
				}
				if ($node->node_name () == "video") {
					if ($node->get_attribute ("_mngid") == $id)
						return $node;
				}
			}
		}
		return false;
	}

	function getAlbumItemNodeType ($node) {
		return $node->node_name ();
	}

	function getPictureNodeWithID ($cnode, $id) {
		$nodes = $cnode->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "picture") {
					if ($node->get_attribute ("_mngid") == $id)
						return $node;
				}
			}
		}
		return false;
	}

	function getVideoNodeWithID ($cnode, $id) {
		$nodes = $cnode->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "video") {
					if ($node->get_attribute ("_mngid") == $id)
						return $node;
				}
			}
		}
		return false;
	}

	function getCollectionsInNode ($cnode) {
		$colls = array ();
		$nodes = $cnode->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "collection") {
					$colls[] = collectionFromNode ($node);
					$cols = getCollectionsInNode ($node);
					$colls = array_merge ($colls, $cols);
				}
			}
		}
		return $colls;
	}

	function getAlbumsInNode ($cnode) {
		$albums = array ();
		$nodes = $cnode->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "collection") {
					$albs = getAlbumsInNode ($node);
					$albums = array_merge ($albums, $albs);
				}
				if ($node->node_name () == "album") {
					$albums[] = albumFromNode ($node);
				}
			}
		}
		return $albums;
	}

	function getRootCollectionNode ($dom) {
		$root = $dom->document_element();
		$nodes = $root->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "collection") {
					return $node;
				}
			}
		}
	}

	function changeNodeProperty ($anode, $propname, $value) {
		$nodes = $anode->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == $propname) {
					$cnode = $node->first_child ();
					if ($cnode)
						$node->remove_child ($cnode);
					$doc = $node->owner_document ();
					$text_node = $doc->create_text_node ($value);
					$node->append_child ($text_node);
					return;
				}
			}
		}
		$doc = $anode->owner_document ();
		$nnode = $doc->create_element ($propname);
		$anode->append_child ($nnode);
		$text_node = $doc->create_text_node ($value);
		$nnode->append_child ($text_node);
	}

	function collectionFromNode ($node) {
		$coll = array ();
		$coll["type"] = "collection";
		$idattr = $node->get_attribute ("_mngid");
		if ($idattr)
			$coll["id"] = intval ($idattr);
		else
			$coll["id"] = "";
		$coll["items"] = array ();
		$coll["thumb"] = array ();
		$coll["thumb"]["url"] = getSubnodeProperty ($node, "thumb", "url");
		$nodes = $node->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "title") {
					$cnode = $node->first_child ();
					$coll["title"] = $cnode->node_value ();
				}
				if ($node->node_name () == "album") {
					$coll["items"][] = albumFromNode ($node);
				}
				if ($node->node_name () == "collection") {
					$coll["items"][] = collectionFromNode ($node);
				}
			}
		}
		return $coll;
	}

	function pictureFromNode ($node) {
		$pic = array ();
		$pic["type"] = "picture";
		$idattr = $node->get_attribute ("_mngid");
		$nodes = $node->child_nodes ();
		if ($idattr)
			$pic["id"] = intval ($idattr);
		else
			$pic["id"] = "";
		$pic["title"] = getNodeProperty ($node, "title");
		$pic["date"] = getNodeProperty ($node, "date");
		$pic["location"] = getNodeProperty ($node, "location");
		$pic["description"] = str_replace (" \\n ", "\n", getNodeProperty ($node, "description"));
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "image") {
					$pic["image"] = array ();
					$subnodes = $node->child_nodes ();
					for ($j = 0; $j < count ($subnodes); $j++) {
						$node = $subnodes[$j];
						if ($node->node_type () == XML_ELEMENT_NODE) {
							if ($node->node_name () == "url") {
								$cnode = $node->first_child ();
								$pic["image"]["url"] = $cnode->node_value ();
								/*$file = $this->imgFolder() . $cnode->node_value ();
								list($width, $height, $type, $attr) = getimagesize ($file);
								$pic["image"]["width"] = $width;
								$pic["image"]["height"] = $height; */
							}
						}
					}
				}
				if ($node->node_name () == "smallimage") {
					$pic["smallimage"] = array ();
					$subnodes = $node->child_nodes ();
					for ($j = 0; $j < count ($subnodes); $j++) {
						$node = $subnodes[$j];
						if ($node->node_type () == XML_ELEMENT_NODE) {
							if ($node->node_name () == "url") {
								$cnode = $node->first_child ();
								$pic["smallimage"]["url"] = $cnode->node_value ();
								/*$file = $this->imgFolder() . $cnode->node_value ();
								list($width, $height, $type, $attr) = getimagesize ($file);
								$pic["smallimage"]["width"] = $width;
								$pic["smallimage"]["height"] = $height;*/
							}
						}
					}
				}
				if ($node->node_name () == "largeimage") {
					$pic["largeimage"] = array ();
					$subnodes = $node->child_nodes ();
					for ($j = 0; $j < count ($subnodes); $j++) {
						$node = $subnodes[$j];
						if ($node->node_type () == XML_ELEMENT_NODE) {
							if ($node->node_name () == "url") {
								$cnode = $node->first_child ();
								$pic["largeimage"]["url"] = $cnode->node_value ();
								/*$file = $this->imgFolder() . $cnode->node_value ();
								list($width, $height, $type, $attr) = getimagesize ($file);
								$pic["largeimage"]["width"] = $width;
								$pic["largeimage"]["height"] = $height;*/
							}
						}
					}
				}
			}
		}
		return $pic;
	}

	function videoFromNode ($node) {
		$vid = array ();
		$vid["type"] = "video";
		$idattr = $node->get_attribute ("_mngid");
		$nodes = $node->child_nodes ();
		if ($idattr)
			$vid["id"] = intval ($idattr);
		else
			$vid["id"] = "";
		$vid["title"] = getNodeProperty ($node, "title");
		$vid["date"] = getNodeProperty ($node, "date");
		$vid["location"] = getNodeProperty ($node, "location");
		$vid["description"] = str_replace (" \\n ", "\n", getNodeProperty ($node, "description"));
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "video") {
					$vid["streams"] = array ();
					$subnodes = $node->child_nodes ();
					for ($j = 0; $j < count ($subnodes); $j++) {
						$node = $subnodes[$j];
						if ($node->node_type () == XML_ELEMENT_NODE) {
							if ($node->node_name () == "stream") {
								$stream = array ();
								$brattr = $node->get_attribute ("bitrate");
								$stream["bitrate"] = intval ($brattr);
								$csubnodes = $node->child_nodes ();
								for ($k = 0; $k < count ($csubnodes); $k++) {
									$node = $csubnodes[$k];
									if ($node->node_type () == XML_ELEMENT_NODE) {
										if ($node->node_name () == "url") {
											$cnode = $node->first_child ();
											$stream["url"] = $cnode->node_value ();
										}
									}
								}
								$vid["streams"][] = $stream;
							}
						}
					}
				}
				if ($node->node_name () == "thumb") {
					$vid["thumb"] = array ();
					$subnodes = $node->child_nodes ();
					for ($j = 0; $j < count ($subnodes); $j++) {
						$node = $subnodes[$j];
						if ($node->node_type () == XML_ELEMENT_NODE) {
							if ($node->node_name () == "url") {
								$cnode = $node->first_child ();
								$vid["thumb"]["url"] = $cnode->node_value ();
							}
						}
					}
				}
			}
		}
		return $vid;
	}

	function getCollectionNodeWithID ($cnode, $id) {
		$nodes = $cnode->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "collection") {
					$nodeid = $node->get_attribute ("_mngid");
					if ($nodeid == $id)
						return $node;
					else {
						$nd = getCollectionNodeWithID ($node, $id);
						if ($nd)
							return $nd;
					}
				}
			}
		}
		return false;
	}

	function getLargestAlbumID ($node) {
		$nodes = $node->child_nodes ();
		$largestsofar = 0;
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "collection") {
					$largestincoll = getLargestAlbumID ($node);
					if ($largestincoll > $largestsofar)
						$largestsofar = $largestincoll;
					$nodeid = intval ($node->get_attribute ("_mngid"));
					if ($nodeid > $largestsofar)
						$largestsofar = $nodeid;
				}
				if ($node->node_name () == "album") {
					$nodeid = intval ($node->get_attribute ("_mngid"));
					if ($nodeid > $largestsofar)
						$largestsofar = $nodeid;
				}
			}
		}
		return $largestsofar;
	}

	function getLargestAlbumItemID ($node) {
		$nodes = $node->child_nodes ();
		$largestsofar = 0;
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "picture") {
					$pic = pictureFromNode ($node);
					if ($pic["id"] > $largestsofar)
						$largestsofar = $pic["id"];
				}
				if ($node->node_name () == "video") {
					$vid = videoFromNode ($node);
					if ($vid["id"] > $largestsofar)
						$largestsofar = $vid["id"];
				}
			}
		}
		return $largestsofar;
	}

	function getCollectionOrAlbumNodeWithID ($cnode, $id) {
		$nodes = $cnode->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "collection") {
					$nodeid = $node->get_attribute ("_mngid");
					if ($nodeid == $id)
						return $node;
					else {
						$nd = getCollectionOrAlbumNodeWithID ($node, $id);
						if ($nd)
							return $nd;
					}
				}
				if ($node->node_name () == "album") {
					$nodeid = $node->get_attribute ("_mngid");
					if (intval ($nodeid) == intval ($id))
						return $node;
				}
			}
		}
		return false;
	}

	function reprocessAlbumNode ($node, $id, $xmlFolder) {
		$startid = $id;
		$node->set_attribute ("_mngid", $startid);
		$album = albumFromNode ($node);

		$fn = $xmlFolder . $album["contentxmlurl"];
		$dom = domxml_open_file($fn);
		$root = $dom->document_element();
		$nodes = $root->child_nodes ();
		$counter = 1;
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "picture") {
					$node->set_attribute ("_mngid", $counter);
					$counter++;
				}
				if ($node->node_name () == "video") {
					$node->set_attribute ("_mngid", $counter);
					$counter++;
				}
			}
		}
		$dom->dump_file ($fn, false, true);
		$startid++;
		return $startid;
	}

	function reprocessCollectionNode ($cnode, $id, $xmlFolder) {
		$startid = $id;
		$cnode->set_attribute ("_mngid", $startid);
		$startid++;
		$nodes = $cnode->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "collection") {
					$startid = reprocessCollectionNode ($node, $startid, $xmlFolder);
				}
				if ($node->node_name () == "album") {
					$startid = reprocessAlbumNode ($node, $startid, $xmlFolder);
				}
			}
		}
		return $startid;
	}
?>