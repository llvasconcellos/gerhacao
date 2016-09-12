<?php
/*
 * Created on 31 Mar 2008
 *mod_imageslideshow - mod_briaskUtility.php
 */

 // no direct access
 defined('_JEXEC') or die ('Restricted access');

function briask_resizeImages($picDir, $pxWidth, $pxHeight)
{
	if (!is_dir($picDir.'/briaskThumbs/'))
	{
		jimport('joomla.filesystem.folder');

		if (JFolder::create($picDir.'/briaskThumbs/'))
		{
		}
		else
		{
			echo "Could not create briaskThumbs directory";
		}
	}

	if (is_dir($picDir))
	{
		if ($dh = opendir($picDir))
		{
			while ($file = readdir($dh))
			{
				$uprFile = strtoupper($file);

				if ($uprFile != '.' && $uprFile != '..')
				{
					if (strpos($uprFile, '.GIF',1)||strpos($uprFile, '.JPG',1)||strpos($uprFile, '.PNG',1) ||strpos($uprFile, '.BMP',1))
					{
						$ext = substr($uprFile, strrpos($file, '.') + 1);

						$copyfile = 'briaskThumb_' . $file;

						$sourcefile = $picDir.'/'.$file;
						$targetfile = $picDir.'/briaskThumbs/'.$copyfile;

						if (file_exists($targetfile))
						{
						}
						else
						{
							list($width, $height) = getimagesize($sourcefile);

							$ratio = ($height / $width);

							if ($pxHeight < $pxWidth)
							{
								$rs_h = $pxHeight;
								$rs_w = $rs_h / $ratio;
								if ($rs_w > $pxWidth) {$rs_w = $pxWidth;}
							}
							else
							{
								$rs_w = $pxWidth;
								$rs_h = $ratio * $rs_w ;
								if ($rs_h > $pxHeight) {$rs_h = $pxHeight;}
							}

							$thumb = imagecreatetruecolor($rs_w, $rs_h);
							if ($ext == 'JPG')
							{
								$source = imagecreatefromjpeg($sourcefile);

								imagecopyresampled($thumb, $source, 0, 0, 0, 0, $rs_w, $rs_h, $width, $height);

								imagejpeg($thumb, $targetfile);
							}

							if ($ext == 'GIF')
							{
								$source = imagecreatefromgif($sourcefile);
								imagecopyresized($thumb, $source, 0, 0, 0, 0, $rs_w, $rs_h, $width, $height);

								imagegif($thumb, $targetfile);
							}

							if ($ext == 'PNG')
							{
								$source = imagecreatefrompng($sourcefile);
								imagecopyresized($thumb, $source, 0, 0, 0, 0, $rs_w, $rs_h, $width, $height);

								imagepng($thumb, $targetfile);
							}

							if ($ext == 'BMP')
							{
								echo '<br> BMP files not supported by PHP GD library</br>';
							}
						}
					}
				}
			}
		}
	}
}

function briask_collectPics($params, $module)
{
	$picDir = $params->get('Directory', 0);
	$picH = $params->get('pxHeight', 0);
	$picW = $params->get('pxWidth', 0);
	$picTitle = $params->get('Title', 0);
	$picEnableLnk = $params->get('EnableLink', 0);
	$picURL = $params->get('URL', 0);

	$picSequence = $params->get('Sequence', 0);
	$picResize = $params->get('Resize', 0);

	$picArray = array();

	if ($picResize != 0)
	{
		briask_resizeImages($picDir, $picW, $picH);
		$picDir = $picDir.'/briaskThumbs/';
	}

	echo '<ul id="briask-iss'.$module->id.'" class="briask-iss" style="width:'.$picW.'px;height:'.$picH.'px">';
	if (is_dir($picDir))
	{
		if ($dh = opendir($picDir))
		{
			while ($file = readdir($dh))
			{
				$uprFile = strtoupper($file);
				if ($uprFile != '.' && $uprFile != '..')
				{
					if (strpos($uprFile, '.GIF',1)||strpos($uprFile, '.JPG',1)||strpos($uprFile, '.PNG',1) ||strpos($uprFile, '.BMP',1))
					{
						array_push($picArray,  $file);
					}
				}
			}
			closedir($dh);
		}
		else
		{
			echo "<b>Can't open directory</b>";
		}
	}
	else
	{
		echo "<b>".$picDir." Not a directory</b>";
	}

	switch ($picSequence)
	{
	case 1:// Shuffle at beginning
		shuffle($picArray);
		break;

	case 2: //Natural sort
		natsort($picArray);
		break;
	}

	foreach ($picArray as $file)
	{
	switch ($picEnableLnk)
		{
		case 0:
			echo '<li><img src="'.$picDir.'/'.$file.'" alt="'.$picTitle.'" /></li>';
			break;

		case 1:
			echo '<li><a href="'.$picURL.'"><img src="'.$picDir.'/'.$file.'" alt="'.$picTitle.'" /></a></li>';
			break;

		case 2:
			echo '<li><a href="'.$picURL.'" target="_blank"><img src="'.$picDir.'/'.$file.'" alt="'.$picTitle.'" /></a></li>';
			break;
		}
	}
	echo '</ul>';
}
?>


