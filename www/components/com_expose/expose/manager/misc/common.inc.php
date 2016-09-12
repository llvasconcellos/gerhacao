<?php
	function logMsg ($msg) {
		$f = fopen ($_SERVER["DOCUMENT_ROOT"] . "/log.txt", "a");
		fwrite ($f, "[".date("Y-m-d H:i:s"). "] " . $msg."\r\n");
		fclose ($f);
	}

	function loadSettings ($path) {
		$settings = array ();
		if(!$dom = domxml_open_file($path . "settings.xml"))
			return str_replace('%xml%','strings.xml',HTML_content::lbl('ERR_OPEN_XMLFILE'));
		//$dom = domxml_open_file($path . "settings.xml");

		$root = $dom->document_element();
		$nodes = $root->child_nodes ();
		for ($i = 0; $i < count ($nodes); $i++) {
			$node = $nodes[$i];
			if ($node->node_type () == XML_ELEMENT_NODE) {
				if ($node->node_name () == "setting") {
					$setting = $node->get_attribute ("name");
					$cnode = $node->first_child ();
					if ($cnode)
						$settings[$setting] = $cnode->node_value (); 
					else
						$settings[$setting] = ""; 
				}
			}
		}
		return $settings;
	}

	// function by info at dreystone dot com
	function ToDouble($data) {
		$t = unpack("C*", pack("S*", 256));
		if($t[1] == 1) {
			$a = unpack("d*", $data);
		} else {
			$a = unpack("d*", strrev($data));
		}
		return (double)$a[1];
	}

	function getFLVBitrate ($fname) {
		$fd = fopen ($fname, "rb");
		$datastr = fread($fd, 1024);
		fclose ($fd);

		$tagAudiodatarate = "audiodatarate";
		$tagVideodatarate = "videodatarate";

		$bitrate = 0;

		for ($i = 0; $i < 1000; $i++) {
			if (strcmp ($tagAudiodatarate, substr ($datastr, $i, strlen ($tagAudiodatarate))) == 0) {
				$rate = ToDouble (substr ($datastr, $i + strlen ($tagAudiodatarate) + 1, 8));
				$bitrate += $rate;
			}
			if (strcmp ($tagVideodatarate, substr ($datastr, $i, strlen ($tagVideodatarate))) == 0) {
				$rate = ToDouble (substr ($datastr, $i + strlen ($tagVideodatarate) + 1, 8));
				$bitrate += $rate;
			}
		}
		return $bitrate;
	}

	function getItemsFromBucketFolder ($dir, $path, &$items) {
		$subdirs = array ();
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if ($file == "." || $file == "..")
					continue;
				if (is_dir ($dir . "/$file")) {
					$subdirs[] = $file;
				} else {
					$pathinfo = pathinfo ($file);
					if (strtolower ($pathinfo["extension"]) == "jpg")
						$items["photos"][] = $path . $file;
					if (strtolower ($pathinfo["extension"]) == "flv")
						$items["videos"][] = $path . $file;
				}
			}
			closedir($dh);
			foreach ($subdirs as $subdir) {
				getItemsFromBucketFolder ($dir . "/$subdir", "$subdir/", $items);
			}
		}
	}

	function validBucketFile ($file) {
		if (!strpos ($file, "../"))
			return true;
		else
			return false;
	}

	function removeEmptyFolder ($dir) {
		if ($dh = opendir($dir)) {
			$foundsomething = false;
			while (($file = readdir($dh)) !== false) {
				if ($file == "." || $file == "..")
					continue;
				$foundsomething = true;
			}
			closedir($dh);
			if (!$foundsomething) {
				rmdir ($dir);
			}
		}		
	}

	function removeEmptyBucketFolders ($dir, $isRoot) {
		if ($dh = opendir($dir)) {
			$subdirs = array ();
			while (($file = readdir($dh)) !== false) {
				if ($file == "." || $file == "..")
					continue;
				if (is_dir ($dir . "/$file")) {
					$subdirs[] = $file;
				}
			}
			closedir($dh);
			foreach ($subdirs as $subdir)
				removeEmptyBucketFolders ($dir . "/$subdir", false);
		}
		if ($isRoot)
			return;
		if ($dh = opendir($dir)) {
			$foundsomething = false;
			while (($file = readdir($dh)) !== false) {
				if ($file == "." || $file == "..")
					continue;
				$foundsomething = true;
			}
			closedir($dh);
			if (!$foundsomething) {
				rmdir ($dir);
			}
		}
	}

	// realpath2 by nospam at savvior dot com
	function realpath2($path) {
		// check if realpath is working
		if (strlen(realpath($path))>0)
			return realpath($path);

		// if its not working use another method
		$p=getenv("PATH_TRANSLATED");
		$p=str_replace("\\","/",$p);
		$p=str_replace(basename(getenv("PATH_INFO")),"",$p);
		$p.="/";
		if ($path==".")
			return $p;
		//now check for back directory//
		$p=$p.$path;

		$dirs=split("/",$p);
		foreach($dirs as $k => $v) {
			if ($v=="..") {
				$dirs[$k]="";
				$dirs[$k-2]="";
			}
		}
		$p="";
		foreach($dirs as $k => $v) {
			if (strlen($v)>0)
				$p.=$v."/";
		}
		$p=substr($p,0,strlen($p)-1);

		if (is_dir($p))
			return $p;
		if (is_file($p))
			return $p;   

		return false;
	}
?>