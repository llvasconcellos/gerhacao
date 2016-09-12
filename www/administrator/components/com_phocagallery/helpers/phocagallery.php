<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */ 
jimport('joomla.application.component.controller');
jimport( 'joomla.filesystem.folder' ); 
jimport( 'joomla.filesystem.file' );

class PhocaGalleryHelper
{
	var $stopThumbnailsCreating; // display the posibility (link) to disable the thumbnails creating
	var $headerAdded;// HTML Header was added by Stop Thumbnails creating, don't add it into a site again;

	function getPathSet() {
		$path['orig_abs_ds'] 	= JPATH_ROOT . DS . 'images' . DS . 'phocagallery' . DS ;
		$path['orig_abs'] 		= JPATH_ROOT . DS . 'images' . DS . 'phocagallery' ;
		$path['orig_rel_ds'] 	= '../' . "images/phocagallery/";
		return $path;
	}
	
	function dontCreateThumb ($filename) {
		$dontCreateThumb		= 0;
		$dontCreateThumbArray	= array ('watermark-large.png', 'watermark-medium.png');
		foreach ($dontCreateThumbArray as $key => $value) {
			if (strtolower($filename) == strtolower($value)) {
				return 1;
			}
		}
		return 0;
	}
	
	function getRealImageSize ($file_no) {
		$thumbName			= PhocaGalleryHelper::getThumbnailName ($file_no, 'large');
		list($w, $h, $type) = GetImageSize($thumbName['abs']);
		$size = '';
		if (isset($w) && isset($h)) {
			$size['w'] 	= $w;
			$size['h']	= $h;
		} else {
			$size = '';
		}
		return $size;
	}
		
	function getFileResize($size='all') {	
		
		// Get width and height from default settings
		$params = JComponentHelper::getParams('com_phocagallery') ;
		$large_image_width 	= $params->get( 'large_image_width', 640 );
		$large_image_height = $params->get( 'large_image_height', 480 );
		$medium_image_width = $params->get( 'medium_image_width', 100 );
		$medium_image_height= $params->get( 'medium_image_height', 100 );
		$small_image_width 	= $params->get( 'small_image_width', 50 );
		$small_image_height = $params->get( 'small_image_height', 50 );
		
		switch ($size) {			
			case 'large':
			$file_resize['width']	=	$large_image_width;
			$file_resize['height']	=	$large_image_height;
			break;
			
			case 'medium':
			$file_resize['width']	=	$medium_image_width;
			$file_resize['height']	=	$medium_image_height;
			break;
			
			case 'small':
			$file_resize['width']	=	$small_image_width;
			$file_resize['height']	=	$small_image_height;
			break;
			
			default:
			case 'all':
			$file_resize['smallwidth']	=	$small_width;
			$file_resize['smallheight']	=	$small_height;
			$file_resize['mediumwidth']	=	$medium_width;
			$file_resize['mediumheight']=	$medium_height;
			$file_resize['largewidth']	=	$large_width;
			$file_resize['largeheight']	=	$large_height;
			break;			
		}
		return $file_resize;
	}
	
	function rotateImage($thumbName, $size, $angle=90) {
	
		$params 		= JComponentHelper::getParams('com_phocagallery') ;
		$jpeg_quality	=	$params->get( 'jpeg_quality', 85 );
		if ((int)$jpeg_quality < 0) {
			$jpeg_quality = 0;
		}
		if ((int)$jpeg_quality > 100) {
			$jpeg_quality = 100;
		}
	
		// Try to change the size
		$memory = 8;
		$memoryLimitChanged = 0;
		$memory = (int)ini_get( 'memory_limit' );
		if ($memory == 0) {
			$memory = 8;
		}

		$file_in 	= $thumbName['abs'];
		$file_out 	= $thumbName['abs'];
	
		if ($file_in !== '' && file_exists($file_in)) {
			
			//array of width, height, IMAGETYPE, "height=x width=x" (string)
	        list($w, $h, $type) = GetImageSize($file_in);
			
			// we got the info from GetImageSize
			if ($w > 0 && $h > 0 && $type !='') {
				// Change the $w against $h because of rotating
				$src = array(0,0, $w, $h);
				$dst = array(0,0, $h, $w);
			} else {
				return 'ErrorWorHorType';
			}
			
			// Try to increase memory
			if ($memory < 50) {
				ini_set('memory_limit', '50M');
				$memoryLimitChanged = 1;
			}
			
	        switch($type)
	        {
	            case IMAGETYPE_JPEG:
					if (!function_exists('ImageCreateFromJPEG')) {
						return 'ErrorNoJPGFunction';
					}
					$image1 = ImageCreateFromJPEG($file_in);
					break;
	            case IMAGETYPE_PNG :
					if (!function_exists('ImageCreateFromPNG')) {
						return 'ErrorNoPNGFunction';
					}
					$image1 = ImageCreateFromPNG($file_in);
					break;
	            case IMAGETYPE_GIF :
					if (!function_exists('ImageCreateFromGIF')) {
						return 'ErrorNoGIFFunction';
					}
					$image1 = ImageCreateFromGIF($file_in);
					break;
	            case IMAGETYPE_WBMP:
					if (!function_exists('ImageCreateFromWBMP')) {
						return 'ErrorNoWBMPFunction';
					}
					$image1 = ImageCreateFromWBMP($file_in);
					break;
	            default:
					return 'ErrorNotSupportedImage';
					break;
	        }
			
			if ($image1)
	        {
				// Building image for ROTATING
			/*	$image2 = @ImageCreateTruecolor($dst[2], $dst[3]);
				if (!$image2) {
					return 'ErrorNoImageCreateTruecolor';
				}*/
				
				if(!function_exists("imagerotate")) {
					return 'ErrorNoImageRotate';
				}
				switch($type)
				{
					case IMAGETYPE_PNG:
					//	imagealphablending($image1, false);
					//	imagesavealpha($image1, true);
						if(!function_exists("imagecolorallocate")) {
							return 'ErrorNoImageColorAllocate';
						}
						if(!function_exists("imagefill")) {
							return 'ErrorNoImageFill';
						}
						if(!function_exists("imagecolortransparent")) {
							return 'ErrorNoImageColorTransparent';
						}
						$colBlack 	= imagecolorallocate($image1, 0, 0, 0);
						$image2 	= imagerotate($image1, $angle, $colBlack);
						imagefill($image2, 0, 0, $colBlack);
						imagecolortransparent($image2, $colBlack);
					break;
					default:
						$image2 = imageRotate($image1, $angle, 0);
					break;
				}

				// Get the image size and resize the rotated image if necessary
				$rotateWidth 	= imagesx($image2);// Get the size from rotated image
				$rotateHeight 	= imagesy($image2);// Get the size from rotated image
				$parameterSize 	= PhocaGalleryHelper::getFileResize($size);
				$newWidth		= $parameterSize['width']; // Get maximum sizes, they can be displayed
				$newHeight		= $parameterSize['height'];// Get maximum sizes, they can be displayed
					
				$scale = (($newWidth / $rotateWidth) < ($newHeight / $rotateHeight)) ? ($newWidth / $rotateWidth) : ($newHeight / $rotateHeight); // smaller rate
				$src = array(0,0, $rotateWidth, $rotateHeight);
				$dst = array(0,0, floor($rotateWidth*$scale), floor($rotateHeight*$scale));
						
				// If original is smaller than thumbnail size, don't resize it
				if ($src[2] > $dst[2] || $src[3] > $dst[3]) {
					
					// Building image for RESIZING THE ROTATED IMAGE
					$image3 = @ImageCreateTruecolor($dst[2], $dst[3]);
					if (!$image3) {
						return 'ErrorNoImageCreateTruecolor';
					}
					ImageCopyResampled($image3, $image2, $dst[0],$dst[1], $src[0],$src[1], $dst[2],$dst[3], $src[2],$src[3]);
					switch($type)
					{
						case IMAGETYPE_PNG:
						//	imagealphablending($image2, true);
						//	imagesavealpha($image2, true);
							if(!function_exists("imagecolorallocate")) {
								return 'ErrorNoImageColorAllocate';
							}
							if(!function_exists("imagefill")) {
								return 'ErrorNoImageFill';
							}
							if(!function_exists("imagecolortransparent")) {
								return 'ErrorNoImageColorTransparent';
							}
							$colBlack 	= imagecolorallocate($image3, 0, 0, 0);
							imagefill($image3, 0, 0, $colBlack);
							imagecolortransparent($image3, $colBlack);
						break;
					}
						
				} else {
					$image3 = $image2;
					
				}
				
				switch($type)
				{
		            case IMAGETYPE_JPEG:
						if (!function_exists('ImageJPEG')) {
							return 'ErrorNoJPGFunction';
						}	
						if (!@ImageJPEG($image3, $file_out, $jpeg_quality)) {
							return 'ErrorWriteFile';
						}
						break;
		            case IMAGETYPE_PNG :
						if (!function_exists('ImagePNG')) {
							return 'ErrorNoPNGFunction';
						}
						if (!@ImagePNG($image3, $file_out)) {
							return 'ErrorWriteFile';
						}
						break;
		            case IMAGETYPE_GIF :
						if (!function_exists('ImageGIF')) {
							return 'ErrorNoGIFFunction';
						}
						if (!@ImageGIF($image3, $file_out)) {
							return 'ErrorWriteFile';
						}
						break;
		            default:
						return 'ErrorNotSupportedImage';
						break;
				}
				
				// free memory
				ImageDestroy($image1);// Original
	            ImageDestroy($image2);// Rotated
				ImageDestroy($image3);// Resized
	            
				if ($memoryLimitChanged == 1) {
					$memoryString = $memory . 'M';
					ini_set('memory_limit', $memoryString);
				}
	            return 'Success'; // Success
	        } else {
				return 'Error1';
			}
			
			if ($memoryLimitChanged == 1) {
				$memoryString = $memory . 'M';
				ini_set('memory_limit', $memoryString);
			}
	    }
		return 'Error2';
	}
	
	//---------------------------------
	//Main Thumbnail creating function
	//---------------------------------
	//file 		= abc.jpg
	//file_no	= folder/abc.jpg
	//if small, medium, large = 1, create small, medium, large thumbnail
	function getOrCreateThumbnail($orig_path, $file_no, $refresh_url, $small=0, $medium=0,$large=0,$frontUpload=0)
	{
		if ($frontUpload) {
			$returnFrontMessage = '';
		}
		
		$onlyThumbnailInfo = 0;
		if ($small == 0 && $medium == 0 && $large == 0) {
			$onlyThumbnailInfo = 1;
		}
		
		$path 				= PhocaGalleryHelper::getPathSet();
		$orig_path_server 	= str_replace(DS, '/', $path['orig_abs'] .'/');
	
	
		$file['name']							= PhocaGalleryHelper::getTitleFromFilenameWithExt($file_no);
		$file['path_with_name']					= str_replace(DS, '/', JPath::clean($orig_path.DS.$file_no));
		$file['path_with_name_relative']		= $path['orig_rel_ds'] . str_replace($orig_path_server, '', $file['path_with_name']);
		$file['path_with_name_relative_no']		= str_replace($orig_path_server, '', $file['path_with_name']);
		
		$file['path_without_name']				= str_replace(DS, '/', JPath::clean($orig_path.DS));
		$file['path_without_name_relative']		= $path['orig_rel_ds'] . str_replace($orig_path_server, '', $file['path_without_name']);
		$file['path_without_name_relative_no']	= str_replace($orig_path_server, '', $file['path_without_name']);
		$file['path_without_name_thumbs'] 		= $file['path_without_name'] .'thumbs';
		$file['path_without_name_no'] 			= str_replace($file['name'], '', $file['path_with_name']);
		$file['path_without_name_thumbs_no'] 	= str_replace($file['name'], '', $file['path_with_name'] .'thumbs');
		
		
		$ext = strtolower(JFile::getExt($file['name']));
		switch ($ext) {
			case 'jpg':
			case 'png':
			case 'gif':
			case 'jpeg':

			//Get File thumbnails name
			$thumbnail_file_s 	= PhocaGalleryHelper::getThumbnailName ($file_no, 'small');
			$file['thumb_name_s_no_abs'] = $thumbnail_file_s['abs'];
			$file['thumb_name_s_no_rel'] = $thumbnail_file_s['rel'];
			//$file['thumb_name_s_no']= str_replace($file['name'], 'thumbs/' . $file['thumb_name_s'], $file_no);
			
			$thumbnail_file_m  	= PhocaGalleryHelper::getThumbnailName ($file_no, 'medium');
			$file['thumb_name_m_no_abs'] = $thumbnail_file_m['abs'];
			$file['thumb_name_m_no_rel'] = $thumbnail_file_m['rel'];
			//$file['thumb_name_m_no']= str_replace($file['name'], 'thumbs/' . $file['thumb_name_m'], $file_no);
			
			$thumbnail_file_l	= PhocaGalleryHelper::getThumbnailName ($file_no, 'large');
			$file['thumb_name_l_no_abs'] = $thumbnail_file_l['abs'];
			$file['thumb_name_l_no_rel'] = $thumbnail_file_l['rel'];
			//$file['thumb_name_l_no']= str_replace($file['name'], 'thumbs/' . $file['thumb_name_l'], $file_no);
			

			
			// Don't create thumbnails from watermarks...			
			$dontCreateThumb	= PhocaGalleryHelper::dontCreateThumb ($file['name']);
			if ($dontCreateThumb == 1) {
				$onlyThumbnailInfo = 1; // WE USE $onlyThumbnailInfo FOR NOT CREATE A THUMBNAIL CLAUSE
			}
			// We want only information from the pictures OR
			if ( $onlyThumbnailInfo == 0 ) {

				//Create thumbnail folder if not exists
				$creatingFolder = "ErrorCreatingFolder";
				$creatingFolder = PhocaGalleryHelper::createFolderThumbnail($file['path_without_name_no'], $file['path_without_name_thumbs_no'] . '/' );
				
				$thumbInfo = $file_no;	
				
				switch ($creatingFolder)
				{
					case 'Success':
					//case 'ThumbnailExists':
					case 'DisabledThumbCreation':
					//case 'OnlyInformation':
					break;
					
					default:
						// BACKEND OR FRONTEND
						if ($frontUpload !=1) {
							PhocaGalleryHelper::getProcessPage( $file['name'], $thumbInfo,  $refresh_url, $creatingFolder, $frontUpload);exit;
						} else {
							$returnFrontMessage = $creatingFolder;
						}
						
					break;	
				}
				
				// Folder must exist
				if (JFolder::exists($file['path_without_name_thumbs_no']))
				{				
					//There are a lot of photos, please create thumbnails
					
					
					
					//Small thumbnail
					if ($small == 1) {
						$creatingS = PhocaGalleryHelper::createFileThumbnail($file['path_with_name'], $file['thumb_name_s_no_abs'], 'small', $frontUpload);
					} else {
						$creatingS = 'ThumbnailExists';// in case we only need medium or large, because of if clause bellow
					}
					
					//Medium thumbnail
					if ($medium == 1) {
						$creatingM = PhocaGalleryHelper::createFileThumbnail($file['path_with_name'], $file['thumb_name_m_no_abs'], 'medium', $frontUpload);
					} else {
						$creatingM = 'ThumbnailExists'; // in case we only need small or large, because of if clause bellow
					}
					
					//Large thumbnail
					if ($large == 1) {
						$creatingL = PhocaGalleryHelper::createFileThumbnail($file['path_with_name'], $file['thumb_name_l_no_abs'], 'large', $frontUpload);
					} else {
						$creatingL = 'ThumbnailExists'; // in case we only need small or medium, because of if clause bellow
					}
					
					
					// Error messages for all 3 thumbnails (if the message contains error string, we got error
					// Other strings can be:
					// - ThumbnailExists  - do not display error message nor success page
					// - OnlyInformation - do not display error message nor success page
					// - DisabledThumbCreation - do not display error message nor success page
					
					$creatingSError = false;
					$creatingMError = false;
					$creatingLError = false;
					$creatingSError = preg_match("/Error/i", $creatingS);
					$creatingMError = preg_match("/Error/i", $creatingM);
					$creatingLError = preg_match("/Error/i", $creatingL);
					
					
					// BACKEND OR FRONTEND
					if ($frontUpload !=1) {

						// There is an error while creating thumbnail in m or in s or in l
						if ($creatingSError || $creatingMError || $creatingLError) {
							if ($creatingSError) {
								$creatingError = $creatingS;
							}
							if ($creatingMError) {
								$creatingError = $creatingM;
							}
							if ($creatingLError) {
								$creatingError = $creatingL;// if all or two errors appear, we only display the last error message	
							}								// because the errors in this case is the same
						
							PhocaGalleryHelper::getProcessPage( $file['name'], $thumbInfo, $refresh_url, $creatingError);exit;
						} else if ($creatingS == 'Success' && $creatingM == 'Success' && $creatingL == 'Success') {
							PhocaGalleryHelper::getProcessPage( $file['name'], $thumbInfo, $refresh_url);exit;
						} else if ($creatingS == 'Success' && $creatingM == 'Success' && $creatingL == 'ThumbnailExists') {
							PhocaGalleryHelper::getProcessPage( $file['name'], $thumbInfo, $refresh_url);exit;
						} else if ($creatingS == 'Success' && $creatingM == 'ThumbnailExists' && $creatingL == 'ThumbnailExists') {
							PhocaGalleryHelper::getProcessPage( $file['name'], $thumbInfo, $refresh_url);exit;
						} else if ($creatingS == 'Success' && $creatingM == 'ThumbnailExists' && $creatingL == 'Success') {
							PhocaGalleryHelper::getProcessPage( $file['name'], $thumbInfo, $refresh_url);exit;
						} else if ($creatingS == 'ThumbnailExists' && $creatingM == 'ThumbnailExists' && $creatingL == 'Success') {
							PhocaGalleryHelper::getProcessPage( $file['name'], $thumbInfo, $refresh_url);exit;
						} else if ($creatingS == 'ThumbnailExists' && $creatingM == 'Success' && $creatingL == 'Success') {
							PhocaGalleryHelper::getProcessPage( $file['name'], $thumbInfo, $refresh_url);exit;
						} else if ($creatingS == 'ThumbnailExists' && $creatingM == 'Success' && $creatingL == 'ThumbnailExists') {
							PhocaGalleryHelper::getProcessPage( $file['name'], $thumbInfo, $refresh_url);exit;
						}
					} else {
						// There is an error while creating thumbnail in m or in s or in l
						if ($creatingSError || $creatingMError || $creatingLError) {
							if ($creatingSError) {
								$creatingError = $creatingS;
							}
							if ($creatingMError) {
								$creatingError = $creatingM;
							}
							if ($creatingLError) {
								$creatingError = $creatingL;// if all or two errors appear, we only display the last error message	
							}								// because the errors in this case is the same
						
							$returnFrontMessage = $creatingError;
						} else if ($creatingS == 'Success' && $creatingM == 'Success' && $creatingL == 'Success') {
							$returnFrontMessage = 'Success';
						} else if ($creatingS == 'Success' && $creatingM == 'Success' && $creatingL == 'ThumbnailExists') {
							$returnFrontMessage = 'Success';
						} else if ($creatingS == 'Success' && $creatingM == 'ThumbnailExists' && $creatingL == 'ThumbnailExists') {
							$returnFrontMessage = 'Success';
						} else if ($creatingS == 'Success' && $creatingM == 'ThumbnailExists' && $creatingL == 'Success') {
							$returnFrontMessage = 'Success';
						} else if ($creatingS == 'ThumbnailExists' && $creatingM == 'ThumbnailExists' && $creatingL == 'Success') {
							$returnFrontMessage = 'Success';
						} else if ($creatingS == 'ThumbnailExists' && $creatingM == 'Success' && $creatingL == 'Success') {
							$returnFrontMessage = 'Success';
						} else if ($creatingS == 'ThumbnailExists' && $creatingM == 'Success' && $creatingL == 'ThumbnailExists') {
							$returnFrontMessage = 'Success';
						}
					}
					
					if ($frontUpload == 1) {
						return $returnFrontMessage;
					}

						
					// Old Error handling (not for all thumbs)
					//Refresh the site after creating thumbnails - we can do e.g. 100 thumbanails
				/*	switch ($creating)
					{
						case 'Success':
							PhocaGalleryHelper::getProcessPage( $file['name'], $refresh_url);
						break;
						
						case 'ThumbnailExists':
						case 'DisabledThumbCreation':
						case 'OnlyInformation':
						break;
						
						default:
							PhocaGalleryHelper::getProcessPage( $file['name'], $refresh_url, $creating);
						break;						
					}*/
				}
			}
			break;
		}
		return $file;
	}

	//Get thumbnailname
	function getThumbnailName ($filename, $size) {
		$path 					= PhocaGalleryHelper::getPathSet();
		$filename_orig_path_abs	= str_replace(DS, '/', JPath::clean($path['orig_abs_ds'] . $filename));
		$filename_orig_path_rel	= str_replace(DS, '/', JPath::clean($path['orig_rel_ds'] . $filename));
		$filename_orig 			= PhocaGalleryHelper::getTitleFromFilenameWithExt($filename);
		
		switch ($size)
		{
			case 'large':
			$filename_thumbl 			= 'phoca_thumb_l_'. $filename_orig;
			$thumbnail_name['abs']		= str_replace ($filename_orig, 'thumbs/' . $filename_thumbl, $filename_orig_path_abs);
			$thumbnail_name['rel']		= str_replace ($filename_orig, 'thumbs/' . $filename_thumbl, $filename_orig_path_rel);
			break;
			
			case 'medium':
			$filename_thumbm 			= 'phoca_thumb_m_'. $filename_orig;
			$thumbnail_name['abs']		= str_replace ($filename_orig, 'thumbs/' . $filename_thumbm, $filename_orig_path_abs);
			$thumbnail_name['rel']		= str_replace ($filename_orig, 'thumbs/' . $filename_thumbm, $filename_orig_path_rel);
			break;
			
			default:
			case 'small':
			$filename_thumbs 			= 'phoca_thumb_s_'. $filename_orig;
			$thumbnail_name['abs']		= str_replace ($filename_orig, 'thumbs/' . $filename_thumbs, $filename_orig_path_abs);
			$thumbnail_name['rel']		= str_replace ($filename_orig, 'thumbs/' . $filename_thumbs, $filename_orig_path_rel);
			
			break;	
		}
		return $thumbnail_name;
	}
	
	function createFolderThumbnail($folder_original, $folder_thumbnail) {	
		$paramsC = JComponentHelper::getParams('com_phocagallery');
		$enable_thumb_creation = 1;
		if ($paramsC->get( 'enable_thumb_creation' ) != '') {
			$enable_thumb_creation = $paramsC->get( 'enable_thumb_creation' );
		}
		// disable or enable the thumbnail creation
		if ($enable_thumb_creation == 1) {

			if (JFolder::exists($folder_original))
			{
				if (strlen($folder_thumbnail) > 0)
				{
					$folder_thumbnail = JPath::clean($folder_thumbnail);				
					if (!is_dir($folder_thumbnail) && !is_file($folder_thumbnail))
					{
						@JFolder::create($folder_thumbnail, 0777 );
						@JFile::write($folder_thumbnail.DS."index.html", "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>");
						// folder was not created
						if (!is_dir($folder_thumbnail)) {
							return "ErrorCreatingFolder";	
						}
					}
				}
			}
			return "Success";
		} else {
			return 'DisabledThumbCreation'; // User have disabled the thumbanil creation e.g. because of error
		}
	}
	
	function createFolder($folder) {	
		$path 					= PhocaGalleryHelper::getPathSet();
		$filename_orig_path_abs	= str_replace(DS, '/', JPath::clean($path['orig_abs_ds'] . $folder));
	//	$filename_orig_path_rel	= str_replace(DS, '/', JPath::clean($path['orig_rel_ds'] . $folder));

			
		if (strlen($filename_orig_path_abs) > 0) {
			$folder = JPath::clean($filename_orig_path_abs);				
			if (!is_dir($filename_orig_path_abs) && !is_file($filename_orig_path_abs)) {
				@JFolder::create($filename_orig_path_abs, 0777 );
				@JFile::write($filename_orig_path_abs.DS."index.html", "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>");
				// folder was not created
				if (!is_dir($filename_orig_path_abs)) {
					return "[PhocaError]CreatingFolder";	
				}
			} else {
				return "[PhocaError]FolderExists";
			}
		} else {
			return "[PhocaError]FolderNameEmpty";
		}
		return "[Success]";
	}
	
	
	function createFileThumbnail($file_original, $file_thumbnail, $size, $frontUpload=0) {	
		
		$paramsC = JComponentHelper::getParams('com_phocagallery');
		
		// Watermark
		$watermarkParams['create']	= $paramsC->get( 'create_watermark', 0 );
		$watermarkParams['x'] 		= $paramsC->get( 'watermark_position_x', 'center' );
		$watermarkParams['y']		= $paramsC->get( 'watermark_position_y', 'middle' );
		
		// Crop or not
		$crop_thumbnail				= $paramsC->get( 'crop_thumbnail', 5);
		$crop = null;
		switch ($size) {
			
			case 'small':
				if ($crop_thumbnail == 3 || $crop_thumbnail == 5 || $crop_thumbnail == 6 || $crop_thumbnail == 7 ) {
					$crop = 1;
				}
			break;
			
			case 'medium':
				if ($crop_thumbnail == 2 || $crop_thumbnail == 4 || $crop_thumbnail == 5 || $crop_thumbnail == 7 ) {
					$crop = 1;
				}
			break;
			
			case 'large':
			default:
				if ($crop_thumbnail == 1 || $crop_thumbnail == 4 || $crop_thumbnail == 6 || $crop_thumbnail == 7 ) {
					$crop = 1;
				}
			break;


		}		
		
		$enable_thumb_creation 		= $paramsC->get( 'enable_thumb_creation', 1);
		
		// disable or enable the thumbnail creation
		if ($enable_thumb_creation == 1) {
		
			$file_original 	= str_replace(DS, '/', JPath::clean($file_original));
			$file_thumbnail = str_replace(DS, '/', JPath::clean($file_thumbnail));	
			$file_resize	= PhocaGalleryHelper::getFileResize($size);

			if (JFile::exists($file_original)) {
				//file doesn't exist, create thumbnail
				if (!JFile::exists($file_thumbnail)) {
					$createdThumb = 'Error4';
					//Don't do thumbnail if the file is smaller (width, height) than the possible thumbnail
					list($width, $height) = GetImageSize($file_original);
					//larger
					if ($width > $file_resize['width'] || $height > $file_resize['height']) {
					
						$createdThumb = PhocaGalleryHelper::imageMagic($file_original, $file_thumbnail, $file_resize['width'] , $file_resize['height'],$crop, null, $watermarkParams, $frontUpload);
						
					} else {
						$createdThumb = PhocaGalleryHelper::imageMagic($file_original, $file_thumbnail, $width , $height, $crop, null, $watermarkParams, $frontUpload);
					}
					return $createdThumb;//thumbnail now created
				} else {
					return 'ThumbnailExists';//thumbnail exists
				}	
			} else {
				return 'ErrorFileOriginalNotExists';
			}
			return 'Error3';
		} else {
			return 'DisabledThumbCreation'; // User have disabled the thumbanil creation e.g. because of error
		}
	}
	
	
	function displayStopThumbnailsCreating() {
		// 1 ... link was displayed
		// 0 ... display the link "Stop ThumbnailsCreation
		
		if (!isset($this->stopThumbnailsCreating)) {
			$this->stopThumbnailsCreating = 0;
		}
		
		$uri 		= & JFactory::getURI();
		$positioni = strpos($uri->toString(), "view=phocagalleryi");
		$positiond = strpos($uri->toString(), "view=phocagalleryd");
			
		if ($positioni === false && $positiond === false)//we are in whole window - not in modal box
		{
			if ($this->stopThumbnailsCreating == 0)
			{
				// Add stop thumbnails creation in case e.g. of Fatal Error which returns 'ImageCreateFromJPEG'
				// test utf-8 ä, ö, ü, č, ř, ž, ß
				$stopText = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
				$stopText .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-en" lang="en-en" dir="ltr" >'. "\n";
				$stopText .= '<head>'. "\n";
				$stopText .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'. "\n\n";
				$stopText .= '<title>'.JText::_( 'Creating of Thumbnail').'</title>'. "\n";
				$stopText .= '</head>'. "\n";
				$stopText .= '<body>'. "\n";
				
				
				$stopText .= '<div style="text-align:right;padding:10px"><a style="font-family: sans-serif, Arial;font-weight:bold;color:#fc0000;font-size:14px;" href="index.php?option=com_phocagallery&controller=phocagallery&task=disablethumbs">' .JText::_( 'Stop Thumbnails Creation' ).'</a></div>';
				$this->stopThumbnailsCreating = 1;// it was added to the site, don't add the same code (because there are 3 thumnails - small, medium, large)
				$this->headerAdded = 1;
				return $stopText;
				
			} else {
				return '';
			}
		} else {
			$this->stopThumbnailsCreating = 1;
		}
	}			

	/**
	* need GD library (first PHP line WIN: dl("php_gd.dll"); UNIX: dl("gd.so");
	* www.boutell.com/gd/
	* interval.cz/clanky/php-skript-pro-generovani-galerie-obrazku-2/
	* cz.php.net/imagecopyresampled
	* www.linuxsoft.cz/sw_detail.php?id_item=871
	* www.webtip.cz/art/wt_tech_php/liquid_ir.html
	* php.vrana.cz/zmensovani-obrazku.php
	* diskuse.jakpsatweb.cz/
	*
	* @param string $file_in Vstupni soubor (mel by existovat)
	* @param string $file_out Vystupni soubor, null ho jenom zobrazi (taky kdyz nema pravo se zapsat :)
	* @param int $width Vysledna sirka (maximalni)
	* @param int $height Vysledna vyska (maximalni)
	* @param bool $crop Orez (true, obrazek bude presne tak velky), jinak jenom Resample (udane maximalni rozmery)
	* @param int $type_out IMAGETYPE_type vystupniho obrazku
	* @return bool Chyba kdyz vrati false
	*/
	function imageMagic($file_in, $file_out=null, $width=null, $height=null, $crop=null, $type_out=null, $watermarkParams=array(), $frontUpload=0) {

		$params 		= JComponentHelper::getParams('com_phocagallery') ;
		$jpeg_quality	=	$params->get( 'jpeg_quality', 85 );
		if ((int)$jpeg_quality < 0) {
			$jpeg_quality = 0;
		}
		if ((int)$jpeg_quality > 100) {
			$jpeg_quality = 100;
		}

		$fileWatermark = '';
		// While front upload we don't display the process page
		if ($frontUpload == 0) {
			$stopText = PhocaGalleryHelper::displayStopThumbnailsCreating();
			echo $stopText;
		}
		$memory = 8;
		$memoryLimitChanged = 0;
		$memory = (int)ini_get( 'memory_limit' );
		if ($memory == 0) {
			$memory = 8;
		}

		if ($file_in !== '' && file_exists($file_in)) {
			
			//array of width, height, IMAGETYPE, "height=x width=x" (string)
	        list($w, $h, $type) = GetImageSize($file_in);
			
			if ($w > 0 && $h > 0) {// we got the info from GetImageSize

		        // size of the image
		        if ($width == null || $width == 0) { // no width added
		            $width = $w;
		        }
				else if ($height == null || $height == 0) { // no height, adding the same as width
		            $height = $width;
		        }
				if ($height == null || $height == 0) { // no height, no width
		            $height = $h;
		        }
				
		        // miniaturizing
		        if (!$crop) { // new size - nw, nh (new width/height)
		            $scale = (($width / $w) < ($height / $h)) ? ($width / $w) : ($height / $h); // smaller rate
		            $src = array(0,0, $w, $h);
		            $dst = array(0,0, floor($w*$scale), floor($h*$scale));
		        }
		        else { // will be cropped
		            $scale = (($width / $w) > ($height / $h)) ? ($width / $w) : ($height / $h); // greater rate
		            $newW = $width/$scale;    // check the size of in file
		            $newH = $height/$scale;

		            // which side is larger (rounding error)
		            if (($w - $newW) > ($h - $newH)) {
		                $src = array(floor(($w - $newW)/2), 0, floor($newW), $h);
		            }
		            else {
		                $src = array(0, floor(($h - $newH)/2), $w, floor($newH));
		            }

		            $dst = array(0,0, floor($width), floor($height));
		        }
				
				// Watermark
				if (!empty($watermarkParams) && $watermarkParams['create'] == 1) {
				
					$thumbnailSmall		= false;
					$thumbnailMedium	= false;
					$thumbnailLarge		= false;
					
					$thumbnailMedium	= preg_match("/phoca_thumb_m_/i", $file_out);
					$thumbnailLarge 	= preg_match("/phoca_thumb_l_/i", $file_out);
					
					
					$fileName 			= PhocaGalleryHelper::getTitleFromFilenameWithExt ($file_in);
					
					// Which Watermark will be used
					if ($thumbnailMedium) {
						$fileWatermark	= str_replace($fileName, 'watermark-medium.png', $file_in);
					} else if ($thumbnailLarge) {
						$fileWatermark	= str_replace($fileName, 'watermark-large.png', $file_in);
					} else {
						$fileWatermark	= '';
					}
					
					if (!file_exists($fileWatermark)) {
						$fileWatermark = '';
					}
					
					if ($fileWatermark != '') {
						list($wW, $hW, $typeW)	= GetImageSize($fileWatermark);
					
						
						switch ($watermarkParams['x']) {
							case 'left':
								$locationX	= 0;
							break;
							
							case 'right':
								$locationX	= $dst[2] - $wW;
							break;
							
							case 'center':
							default:
								$locationX	= ($dst[2] / 2) - ($wW / 2);
							break;
						}
						
						switch ($watermarkParams['y']) {
							case 'top':
								$locationY	= 0;
							break;
							
							case 'bottom':
								$locationY	= $dst[3] - $hW;
							break;
							
							case 'middle':
							default:
								$locationY	= ($dst[3] / 2) - ($hW / 2);
							break;
						}
					}
				} else {
					$fileWatermark = '';
				}
			}

			
			if ($memory < 50) {
				ini_set('memory_limit', '50M');
				$memoryLimitChanged = 1;
			}
			// Resampling
			// in file
			
			// Watemark
			if ($fileWatermark != '') {
				if (!function_exists('ImageCreateFromPNG')) {
					return 'ErrorNoPNGFunction';
				}
				$waterImage1=ImageCreateFromPNG($fileWatermark);
			}
			// End Watermark
			
	        switch($type)
	        {
	            case IMAGETYPE_JPEG:
					if (!function_exists('ImageCreateFromJPEG')) {
						return 'ErrorNoJPGFunction';
					}
					$image1 = ImageCreateFromJPEG($file_in);
					break;
	            case IMAGETYPE_PNG :
					if (!function_exists('ImageCreateFromPNG')) {
						return 'ErrorNoPNGFunction';
					}
					$image1 = ImageCreateFromPNG($file_in);
					break;
	            case IMAGETYPE_GIF :
					if (!function_exists('ImageCreateFromGIF')) {
						return 'ErrorNoGIFFunction';
					}
					$image1 = ImageCreateFromGIF($file_in);
					break;
	            case IMAGETYPE_WBMP:
					if (!function_exists('ImageCreateFromWBMP')) {
						return 'ErrorNoWBMPFunction';
					}
					$image1 = ImageCreateFromWBMP($file_in);
					break;
	            default:
					return 'ErrorNotSupportedImage';
					break;
	        }
			
			if ($image1)
	        {
	            // protection against invalid image dimensions
				/*foreach ($dst as $kdst =>$vdst) {
					if ($dst[$kdst] == 0 ) {
					$dst[$kdst] = 1;
					}
				} */
				$image2 = @ImageCreateTruecolor($dst[2], $dst[3]);
				if (!$image2) {
					return 'ErrorNoImageCreateTruecolor';
				}
				
				switch($type)
				{
					case IMAGETYPE_PNG:
						//imagealphablending($image1, false);
						@imagealphablending($image2, false);
						//imagesavealpha($image1, true);
						@imagesavealpha($image2, true);
					break;
				}
				
				ImageCopyResampled($image2, $image1, $dst[0],$dst[1], $src[0],$src[1], $dst[2],$dst[3], $src[2],$src[3]);
				// Watermark
				if ($fileWatermark != '') {
					ImageCopy($image2,$waterImage1,$locationX,$locationY,0,0,$wW,$hW);
				}
				// End Watermark
				
				
	            // display the image
	            if ($file_out == null) {
	                header("Content-type: ". image_type_to_mime_type($type_out));
	            }
				
				// out file
		        if ($type_out == null) {    // no bitmap
		            $type_out = ($type == IMAGETYPE_WBMP) ? IMAGETYPE_PNG : $type;
		        }
				switch($type_out)
				{
		            case IMAGETYPE_JPEG:
						if (!function_exists('ImageJPEG')) {
							return 'ErrorNoJPGFunction';
						}	
						if (!@ImageJPEG($image2, $file_out, $jpeg_quality)) {
							return 'ErrorWriteFile';
						}
						break;
		            case IMAGETYPE_PNG :
						if (!function_exists('ImagePNG')) {
							return 'ErrorNoPNGFunction';
						}
						if (!@ImagePNG($image2, $file_out)) {
							return 'ErrorWriteFile';
						}
						break;
		            case IMAGETYPE_GIF :
						if (!function_exists('ImageGIF')) {
							return 'ErrorNoGIFFunction';
						}
						if (!@ImageGIF($image2, $file_out)) {
							return 'ErrorWriteFile';
						}
						break;
		            default:
						return 'ErrorNotSupportedImage';
						break;
				}
				
				// free memory
				ImageDestroy($image1);
	            ImageDestroy($image2);
				if (isset($waterImage1)) {
					ImageDestroy($waterImage1);
				}
	            
				if ($memoryLimitChanged == 1) {
					$memoryString = $memory . 'M';
					ini_set('memory_limit', $memoryString);
				}
	            return 'Success'; // Success
	        } else {
				return 'Error1';
			}
			if ($memoryLimitChanged == 1) {
				$memoryString = $memory . 'M';
				ini_set('memory_limit', $memoryString);
			}
	    }
		return 'Error2';
	}
	
	function deleteFile ($filename) {			
		//Get folder variables from Helper
		$path 				= PhocaGalleryHelper::getPathSet();
		$filename_orig_path	= str_replace(DS, '/', JPath::clean($path['orig_abs_ds'] . $filename));
		if (JFile::exists($filename_orig_path)){
			JFile::delete($filename_orig_path);
		}
	}

	
	function deleteFileThumbnail ($filename, $small=0, $medium=0, $large=0) {			
		//Get folder variables from Helper
		$path 				= PhocaGalleryHelper::getPathSet();
		$filename_orig_path	= str_replace(DS, '/', JPath::clean($path['orig_abs_ds'] . $filename));
		$filename_orig 		= PhocaGalleryHelper::getTitleFromFilenameWithExt($filename);
		
		
		if ($small == 1) {
			$filename_thumbs = PhocaGalleryHelper::getThumbnailName ($filename, 'small');
			//$filename_thumbs = str_replace ($filename_orig, 'thumbs/' . $filename_thumbs, $filename_orig_path);
			if (JFile::exists($filename_thumbs['abs'])) {
				JFile::delete($filename_thumbs['abs']);
			}
		}
		
		if ($medium == 1) {
			$filename_thumbm = PhocaGalleryHelper::getThumbnailName ($filename, 'medium');
			//$filename_thumbm = str_replace ($filename_orig, 'thumbs/' . $filename_thumbm, $filename_orig_path);
			if (JFile::exists($filename_thumbm['abs'])) {
				JFile::delete($filename_thumbm['abs']);
			}
		}
		
		if ($large == 1) {
			$filename_thumbl = PhocaGalleryHelper::getThumbnailName ($filename, 'large');
			//$filename_thumbl = str_replace ($filename_orig, 'thumbs/' . $filename_thumbl, $filename_orig_path);
			if (JFile::exists($filename_thumbl['abs'])) {
				JFile::delete($filename_thumbl['abs']);
			}
		}
		return true;
	}
	
	
	/*
	 * Clear Thumbs folder - if there are files in the thumbs directory but not original files e.g.:
	 * phoca_thumbs_l_some.jpg exists in thumbs directory but some.jpg doesn't exists - delete it
	 */
	function cleanThumbsFolder() {
		//Get folder variables from Helper
		$path = PhocaGalleryHelper::getPathSet();
		
		// Initialize variables
		$orig_path = $path['orig_abs_ds'];
		$orig_path_server = str_replace(DS, '/', $path['orig_abs'] .'/');

		// Get the list of files and folders from the given folder
		$file_list 		= JFolder::files($orig_path, '', true, true);
			
		// Iterate over the files if they exist
		if ($file_list !== false)
		{
			foreach ($file_list as $file)
			{	
				if (is_file($file) && substr($file, 0, 1) != '.' && strtolower($file) !== 'index.html')
				{
					//Clean absolute path
					$file = str_replace(DS, '/', JPath::clean($file));
					
					$positions = strpos($file, "phoca_thumb_s_");//is there small thumbnail
					$positionm = strpos($file, "phoca_thumb_m_");//is there medium thumbnail
					$positionl = strpos($file, "phoca_thumb_l_");//is there large thumbnail
					
					//Clean small thumbnails if original file doesn't exist
					if ($positions === false) {}
					else 
					{
						$filename_thumbs = $file;//only thumbnails will be listed
						$filename_origs	= str_replace ('thumbs/phoca_thumb_s_', '', $file);//get fictive original files 
						
						//There is Thumbfile but not Originalfile - we delete it
						if (JFile::exists($filename_thumbs) && !JFile::exists($filename_origs))
						{
							JFile::delete($filename_thumbs);
						}
					//  Reverse
					//  $filename_thumb = PhocaGalleryHelper::getTitleFromFilenameWithExt($file);
					//	$filename_original = PhocaGalleryHelper::getTitleFromFilenameWithExt($file);	
					//	$filename_thumb = str_replace ($filename_original, 'thumbs/phoca_thumb_m_' . $filename_original, $file); 
					}
					
					//Clean medium thumbnails if original file doesn't exist
					if ($positionm === false) {}
					else 
					{
						$filename_thumbm = $file;//only thumbnails will be listed
						$filename_origm 	= str_replace ('thumbs/phoca_thumb_m_', '', $file);//get fictive original files 
						
						//There is Thumbfile but not Originalfile - we delete it
						if (JFile::exists($filename_thumbm) && !JFile::exists($filename_origm))
						{
							JFile::delete($filename_thumbm);
						}
					}
					
					//Clean large thumbnails if original file doesn't exist
					if ($positionl === false) {}
					else 
					{
						$filename_thumbl = $file;//only thumbnails will be listed
						$filename_origl 	= str_replace ('thumbs/phoca_thumb_l_', '', $file);//get fictive original files 
						
						//There is Thumbfile but not Originalfile - we delete it
						if (JFile::exists($filename_thumbl) && !JFile::exists($filename_origl))
						{
							JFile::delete($filename_thumbl);
						}
					}
				}
			}
		}
	}
	
	function getFileOriginal($filename) {
		$path		= PhocaGalleryHelper::getPathSet();
		$file_original 	= $path['orig_abs_ds'] . $filename;//original file
		
		return $file_original;
	}
	
	function existsFileOriginal($filename) {
		$file_original = PhocaGalleryHelper::getFileOriginal ($filename);
		if (JFile::exists($file_original)) {
			return true;
		} else {
			return false;
		}
	}
	
	function getAliasName($name) {
		if (function_exists('iconv')) {
		    $name = preg_replace('~[^\\pL0-9_.]+~u', '-', $name);
		    $name = trim($name, "-");
		    $name = iconv("utf-8", "us-ascii//TRANSLIT", $name);
		    $name = strtolower($name);
		    $name = preg_replace('~[^-a-z0-9_.]+~', '', $name);
		} else {
			$name = JFilterOutput::stringURLSafe($name);
			if(trim(str_replace('-','',$name)) == '') {
				$datenow =& JFactory::getDate();
				$name = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
			}
		}
		return $name;
	}
	
	function getTitleFromFilenameWithoutExt (&$filename) {
		$folder_array		= explode('/', $filename);//Explode the filename (folder and file name)
		$count_array		= count($folder_array);//Count this array
		$last_array_value 	= $count_array - 1;//The last array value is (Count array - 1)	
		
		return PhocaGalleryHelper::removeExtension($folder_array[$last_array_value]);
	}
	
	function getTitleFromFilenameWithExt (&$filename) {
		$folder_array		= explode('/', $filename);//Explode the filename (folder and file name)
		$count_array		= count($folder_array);//Count this array
		$last_array_value 	= $count_array - 1;//The last array value is (Count array - 1)	
		
		return $folder_array[$last_array_value];
	}
	
	function removeExtension($file_name) {
		return substr($file_name, 0, strrpos( $file_name, '.' ));
	}
	
	function wordDelete($string,$length,$end='...') {
		if (JString::strlen($string) < $length || JString::strlen($string) == $length) {
			return $string;
		} else {
			return JString::substr($string, 0, $length) . $end;
		}
	}
	
	function CategoryTreeOption($data, $tree, $id=0, $text='', $currentId) {		

		foreach ($data as $key) {	
			$show_text =  $text . $key->text;
			
			if ($key->parentid == $id && $currentId != $id && $currentId != $key->value) {	
				$tree[$key->value] 			= new JObject();
				$tree[$key->value]->text 	= $show_text;
				$tree[$key->value]->value 	= $key->value;
				$tree = PhocaGalleryHelper::CategoryTreeOption($data, $tree, $key->value, $show_text . " &raquo; ", $currentId );	
			}	
		}
		return($tree);
	}

	function getProcessPage ( $filename, $thumbInfo, $refresh_url, $errorMsg = '' ) {
		$countImg 		= (int)JRequest::getVar( 'countimg', 0, 'get', 'INT' );
		$currentImg 	= (int)JRequest::getVar( 'currentimg',0, 'get','INT' );
		if ($currentImg == 0) {
			$currentImg = 1;
		}
		
		$nextImg = $currentImg + 1;
		if (!isset($this->headerAdded) || (isset($this->headerAdded) && $this->headerAdded == 0)) {
			
			echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
			echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-en" lang="en-en" dir="ltr" >'. "\n";
			echo '<head>'. "\n";
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'. "\n\n";
			echo '<title>'.JText::_( 'Creating of Thumbnail').'</title>'. "\n";
			echo '</head>'. "\n";
			echo '<body>'. "\n";
			
		}
		
		
		echo '<center><div style="width:70%;border:1px solid #ccc; margin-top:30px;font-family: sans-serif, Arial;font-weight:normal;color:#666;font-size:14px;padding:10px">';
		echo '<span>'. JText::_( 'Creating of thumbnail Please Wait' ) . '</span>';
		
		if ( $errorMsg == '' ) {
			echo '<p>' .JText::_( 'Creating of thumbnail' ) 
			.' <span style="color:#0066cc;">'. $filename . '</span>' 
			.' ... <b style="color:#009900">'.JText::_( 'OK' ).'</b><br />'
			.'(<span style="color:#0066cc;">' . $thumbInfo . '</span>)</p>';
		} else {
			echo '<p>' .JText::_( 'Creating of thumbnail' ) 
			.' <span style="color:#0066cc;padding:0;margin:0"> '. $filename . '</span>' 
			.' ... <b style="color:#fc0000">'.JText::_( 'Error' ).'</b><br />'
			.'(<span style="color:#0066cc;">' . $thumbInfo . '</span>)</p>';
		}
	
		if ($countImg == 0) {
			// BEGIN ---------------------------------------------------------------------------
			echo '<p>' . JText::_('Rebuilding Process') . '</p>';
			// END -----------------------------------------------------------------------------
		} else {
			// Creating thumbnails info
			$per = 0; // display percents
			if ($countImg > 0) {
				$per = round(($currentImg / $countImg)*100, 0);
			}
			$perCSS = ($per * 400/100) - 400;
			$bgCSS = 'background: url(\''.JURI::base(true).'/components/com_phocagallery/assets/images/process.png\') '.$perCSS.'px 0 repeat-y;';
			
			// BEGIN -----------------------------------------------------------------------
			echo '<p>' . JText::_('Creating'). ': <span style="color:#0066cc">'. $currentImg .'</span> '.JText::_('From'). ' <span style="color:#0066cc">'. $countImg .'</span> '.JText::_('Thumbnail(s)').'</p>';
			
			//echo '<p>'.$per.' &#37;</p>';
			echo '<div style="width:400px;height:20px;font-size:20px;border-top:2px solid #666;border-left:2px solid #666;border-bottom:2px solid #ccc;border-right:2px solid #ccc;'.$bgCSS.'"><span style="font-size:10px;font-weight:bold">'.$per.' &#37;</div>';
			// END -------------------------------------------------------------------------
		}

		if ( $errorMsg != '' ) {
		
			$errorMessage = '';
			switch ($errorMsg) {
				case 'ErrorNotSupportedImage':
				$errorMessage = JText::_('ErrorNotSupportedImage');
				break;
				
				case 'ErrorNoJPGFunction':
				$errorMessage = JText::_('ErrorNoJPGFunction');
				break;
				
				case 'ErrorNoPNGFunction':
				$errorMessage = JText::_('ErrorNoPNGFunction');
				break;
				
				case 'ErrorNoGIFFunction':
				$errorMessage = JText::_('ErrorNoGIFFunction');
				break;
				
				case 'ErrorNoWBMPFunction':
				$errorMessage = JText::_('ErrorNoWBMPFunction');
				break;
				
				case 'ErrorWriteFile':
				$errorMessage = JText::_('ErrorWriteFile');
				break;
				
				case 'ErrorFileOriginalNotExists':
				$errorMessage = JText::_('ErrorFileOriginalNotExists');
				break;

				case 'ErrorCreatingFolder':
				$errorMessage = JText::_('ErrorCreatingFolder');
				break;
				
				case 'ErrorNoImageCreateTruecolor':
				$errorMessage = JText::_('ErrorNoImageCreateTruecolor');
				break;
				
				case 'Error1':
				case 'Error2':
				case 'Error3':
				case 'Error4':
				case 'Error5':
				default:
					$errorMessage = JText::_('ErrorWhileCreatingThumb') . ' ('.$errorMsg.')';
				break;	
			}
			
			$positioni = strpos($refresh_url, "view=phocagalleryi");
			$positiond = strpos($refresh_url, "view=phocagalleryd");
			
			if ($positioni === false && $positiond === false)//we are in whole window - not in modal box
			{
			
				echo '<div style="text-align:left;margin: 10px 5px">';
				echo '<table border="0" cellpadding="7"><tr><td>'.JText::_('Error Message').':</td><td><span style="color:#fc0000">'.$errorMessage.'</span></td></tr>';
				
				echo '<tr><td colspan="1" rowspan="4" valign="top" >'.JText::_('What to do now').' :</td>';
				
				echo '<td>&raquo; ' .JText::_( 'PG Solution Begin' ).' <br /><ul><li>'.JText::_( 'PG Solution Image' ).'</li><li>'.JText::_( 'PG Solution GD' ).'</li><li>'.JText::_( 'PG Solution Permission' ).'</li></ul>'.JText::_( 'PG Solution End' ).'<br /> <a href="'.$refresh_url.'&countimg='.$countImg.'&currentimg='.$currentImg .'">' .JText::_( 'Phoca Gallery Back' ).'</a><hr /></td></tr>';
				
				echo '<tr><td>&raquo; ' .JText::_( 'Disable Creating Thumbnails Solution' ).' <br /> <a href="index.php?option=com_phocagallery&controller=phocagallery&task=disablethumbs">' .JText::_( 'Phoca Gallery Back Disable Thumbnails Creating' ).'</a> <br />'.JText::_( 'Enable Thumbnails Creating in Default Settings' ).'<hr /></td></tr>';
				
				echo '<tr><td>&raquo; ' .JText::_( 'Media Manager Solution' ).' <br /> <a href="index.php?option=com_media">' .JText::_( 'Media Manager link' ).'</a><hr /></td></tr>';
				
				echo '<tr><td>&raquo; <a href="http://www.phoca.cz/documentation/" target="_blank">' .JText::_( 'Go to Phoca Gallery User Manual' ).'</a></td></tr>';
				
				echo '</table>';
				echo '</div>';

			}
			else //we are in modal box
			{
				echo '<div style="text-align:left">';
				echo '<table border="0" cellpadding="3"
			cellspacing="3"><tr><td>'.JText::_('Error Message').':</td><td><span style="color:#fc0000">'.$errorMessage.'</span></td></tr>';
				
				echo '<tr><td colspan="1" rowspan="3" valign="top">'.JText::_('What to do now').' :</td>';
				
				echo '<td>&raquo; ' .JText::_( 'PG Solution Begin' ).' <br /><ul><li>'.JText::_( 'PG Solution Image' ).'</li><li>'.JText::_( 'PG Solution GD' ).'</li><li>'.JText::_( 'PG Solution Permission' ).'</li></ul>'.JText::_( 'PG Solution End' ).'<br /> <a href="'.$refresh_url.'&countimg='.$countImg.'&currentimg='.$currentImg .'">' .JText::_( 'Phoca Gallery Back' ).'</a><hr /></td></tr>';
				
				echo '<td>&raquo; ' .JText::_( 'No Solution' ).' <br /> <a href="#" onclick="window.parent.document.getElementById(\'sbox-window\').close();">' .JText::_( 'Phoca Gallery Back' ).'</a></td></tr>';
				
				echo '</table>';
				echo '</div>';
			}
			
			echo '</div></center></body></html>';
			exit;
		}
		
			
		if ($countImg ==  $currentImg || $currentImg > $countImg) {
			echo '<meta http-equiv="refresh" content="1;url='.$refresh_url.'&imagesid='.md5(time()).'" />';
		} else {
			echo '<meta http-equiv="refresh" content="0;url='.$refresh_url.'&countimg='.$countImg.'&currentimg='.$nextImg.'" />';
		}
		
		echo '</div></center></body></html>';
		exit;
	}
	
	
	/**
	 * Method to display multiple select box
	 * @param string $name Name (id, name parameters)
	 * @param array $active Array of items which will be selected
	 * @param int $nouser Select no user
	 * @param string $javascript Add javascript to the select box
	 * @param string $order Ordering of items
	 * @param int $reg Only registered users
	 * @return array of id
	 */
	
	function usersList( $name, $active, $nouser = 0, $javascript = NULL, $order = 'name', $reg = 1 ) {
		$db =& JFactory::getDBO();
		$and = '';
		if ( $reg ) {
		// does not include registered users in the list
			$and = ' AND gid > 18';
		}

		$query = 'SELECT id AS value, name AS text'
		. ' FROM #__users'
		. ' WHERE block = 0'
		. $and
		. ' ORDER BY '. $order
		;
		$db->setQuery( $query );
		if ( $nouser ) {
			
			// Access rights (default open for all)
			// Upload and Delete rights (default closed for all)
			switch ($name) {
				case 'accessuserid[]':
					$idInput1 	= -1;
					$idText1	= JText::_( 'All Registered Users' );
					$idInput2 	= -2;
					$idText2	= JText::_( 'Nobody' );
				break;
				
				default:
					$idInput1 	= -2;
					$idText1	= JText::_( 'Nobody' );
					$idInput2 	= -1;
					$idText2	= JText::_( 'All Registered Users' );
				break;
			}
			
			$users[] = JHTML::_('select.option',  $idInput1, '- '. $idText1 .' -' );
			$users[] = JHTML::_('select.option',  $idInput2, '- '. $idText2 .' -' );
			
			$users = array_merge( $users, $db->loadObjectList() );
		} else {
			$users = $db->loadObjectList();
		}

		$users = JHTML::_('select.genericlist',   $users, $name, 'class="inputbox" size="4" multiple="multiple"'. $javascript, 'value', 'text', $active );

		return $users;
	}
	
	
	function usersListAuthor( $name, $active, $nouser = 0, $javascript = NULL, $order = 'name', $reg = 1 ) {
		$db =& JFactory::getDBO();
		$and = '';
		if ( $reg ) {
		// does not include registered users in the list
			$and = ' AND gid > 18';
		}

		$query = 'SELECT id AS value, name AS text'
		. ' FROM #__users'
		. ' WHERE block = 0'
		. $and
		. ' ORDER BY '. $order
		;
		$db->setQuery( $query );
		if ( $nouser ) {
			
			$idInput1 	= -1;
			$idText1	= JText::_( 'Nobody' );
			$users[] = JHTML::_('select.option',  -1, '- '. $idText1 .' -' );
			
			$users = array_merge( $users, $db->loadObjectList() );
		} else {
			$users = $db->loadObjectList();
		}

		$users = JHTML::_('select.genericlist',   $users, $name, 'class="inputbox" size="4" '. $javascript, 'value', 'text', $active );

		return $users;
	}
	
	/**
	 * Method to get the array of values for one parameters saved in param array
	 * @param string $params
	 * @param string $param param: e.g. accessuserid, uploaduserid, deleteuserid, userfolder
	 * @return array of values from one param in params array which is saved in db table in 'params' column
	 */
	
	function getParamsArray($params='', $param='accessuserid')  {	
		// All params from category / params for userid only
		if ($params != '') {
			$paramsArray	= trim ($params);
			$paramsArray	= explode( ';', $params );
								
			if (is_array($paramsArray))
			{
				foreach ($paramsArray as $value)
				{
					$find = '/'.$param.'=/i';
					$replace = $param.'=';
					
					$idParam = preg_match( "".$find."" , $value );
					if ($idParam) {
						$paramsId = str_replace($replace, '', $value);
						if ($paramsId != '') {
							$paramsIdArray	= trim ($paramsId);
							$paramsIdArray	= explode( ',', $paramsId );
							// Unset empty keys
							foreach ($paramsIdArray as $key2 => $value2)
							{
								if ($value2 == '') {
									unset($paramsIdArray[$key2]);
								}
							}
							
							return $paramsIdArray;
						}
					}
				}
			}
		}
		return array();
	}
	
	/**
	 * Method to check if the user have access to category
	 * Display or hide the not accessible categories - subcat folder will be not displayed
	 * Check whether category access level allows access
	 *
	 * E.g.: Should the link to Subcategory or to Parentcategory be displayed
	 * E.g.: Should the delete button displayed, should be the upload button displayed
	 *
	 * @param string $params Category Params (parent category or subcategory e.g.)
	 * @param string $params RightId - users id for: accessuserid, uploaduserid, deleteuserid
	 * @param int $params ItemAccess - category can be accessed for public, registered or special 
	 * @param int $params userAID - users AID (public, special, registerd)
	 * @param int $params Additional param - e.g. $display_access_category (Should be unaccessed cat displayed)
	 * @return boolean 1 or 0
	 */
	
	function getUserRight($categoryParams = '', $param = 'accessuserid', $ItemAccess = 0, $userAID = 0, $userId = 0 , $additionalParam = 0 ) {	
		$paramsIdRightArray = array();
		if (isset($categoryParams) && PhocaGalleryHelper::getParamsArray($categoryParams, $param)) {
			$paramsIdRightArray = PhocaGalleryHelper::getParamsArray($categoryParams, $param);
		} else {
			$paramsIdRightArray = array();
		}

		$rightDisplay = 1;
		if ($additionalParam == 0) { // We want not to display unaccessable categories ($display_access_category)
			if ($ItemAccess != 0) {
			
				if ($ItemAccess > $userAID) {
					$rightDisplay  = 0;
				} else { // Access level only for one registered user
					if (!empty($paramsIdRightArray)) {
						// Check if the user is contained in selected array
						$userIsContained = 0;
						foreach ($paramsIdRightArray as $key => $value)
						{
							if ($userId == $value) {
								$userIsContained = 1;// check if the user id is selected in multiple box
							}
							// for access (-1 not selected - all registered, 0 all users)
							if ($value == -1) {
								$userIsContained = 1;// in multiple select box is selected - All registered users
							}
						}

						if ($userIsContained == 0) {
							$rightDisplay = 0;
						}
					} else {
						
						// Access rights (default open for all)
						// Upload and Delete rights (default closed for all)
						switch ($param) {
							case 'accessuserid':
								$rightDisplay = 1;
							break;
							
							default:
								$rightDisplay = 0;
							break;
						}
					}
				}	
			}
		}
		return $rightDisplay;
	}
	

	function getPhocaIc($ic){
		$v	= PhocaGalleryHelper::getPhocaVersion();
		$i	= str_replace('.', '',substr($v, 0, 3));
		$n	= '<p>&nbsp;</p>';
		$l	= 'h'.'t'.'t'.'p'.':'.'/'.'/'.'w'.'w'.'w'.'.'.'p'.'h'.'o'.'c'.'a'.'.'.'c'.'z'.'/';
		$p	= 'P'.'h'.'o'.'c'.'a'.' '.'G'.'a'.'l'.'l'.'e'.'r'.'y';
		$im = 'i'.'c'.'o'.'n'.'-'.'p'.'h'.'o'.'c'.'a'.'-'.'l'.'o'.'g'.'o'.'-'.'s'.'m'.'a'.'l'.'l'.'.'.'p'.'n'.'g';
		$s	= 's'.'t'.'y'.'l'.'e'.'='.'"'.'t'.'e'.'x'.'t'.'-'.'d'.'e'.'c'.'o'.'r'.'a'.'t'.'i'.'o'.'n'.':'.'n'.'o'.'n'.'e'.'"';
		$b	= 't'.'a'.'r'.'g'.'e'.'t'.'='.'"'.'_'.'b'.'l'.'a'.'n'.'k'.'"';
		$im2 = 'i'.'c'.'o'.'n'.'-'.'p'.'h'.'o'.'c'.'a'.'-'.'l'.'o'.'g'.'o'.'-'.'s'.'e'.'a'.'l'.'.'.'p'.'n'.'g';
		$i	= (int)$i * (int)$i;
		$output	= '';
		if ($ic != $i) {
			$output		.= $n;
			$output		.= '<div style="text-align:center">';
		}
		if ($ic == 1) {
			//$output	.= '<a href="'.$l.'" '.$s.' '.$b.' title="'.$p.'">'. JHTML::_('image', 'components/com_phocagallery/assets/images/'.$im, $p). '</a>';
			//$output	.= ' <a href="http://www.phoca.cz/" '.$s.' '.$b.' title="'.$p.'">'. $v .'</a>';
		} else if ($ic == 2 || $ic == 3) {
			//$output	.= '<a  href="'.$l.'" '.$s.' '.$b.' title="'.$p.'">'. JHTML::_('image', 'components/com_phocagallery/assets/images/'.$im, $p). '</a>';
		} else if ($ic == 4) {
			//$output	.= ' <a href="'.$l.'" '.$s.' '.$b.' title="'.$p.'">Phoca Gallery</a>';
		} else if ($ic == 5) {
			//$output	.= ' <a href="'.$l.'" '.$s.' '.$s.' '.$b.' title="'.$p.'">'.$p.' '.$v.'</a>';
		} else if ($ic == 6) {
			//$output	.= ' <a href="'.$l.'" '.$s.' '.$b.' title="'.$p.'">'. JHTML::_('image', 'components/com_phocagallery/assets/images/'.$im2, $p). '</a>';
		} else if ($ic == $i) {
			//$output	.= '<!-- <a href="'.$l.'">site: www.phoca.cz | version: '.$v.'</a> -->';
		} else {
			//$output	.= '<a href="'.$l.'" '.$s.' '.$b.' title="'.$p.'">'. JHTML::_('image', 'components/com_phocagallery/assets/images/'.$im, $p). '</a>';
			//$output	.= ' <a href="http://www.phoca.cz/" '.$s.' '.$b.' title="'.$p.'">'. $v .'</a>';
		}
		if ($ic != $i) {
			$output		.= '</div>' . $n;
		}
		return $output;
	}
	
	/**
	 * Method to get Phoca Version
	 * @return string Version of Phoca Gallery
	 */
	function getPhocaVersion() {
		$folder = JPATH_ADMINISTRATOR .DS. 'components'.DS.'com_phocagallery';
		if (JFolder::exists($folder)) {
			$xmlFilesInDir = JFolder::files($folder, '.xml$');
		} else {
			$folder = JPATH_SITE .DS. 'components'.DS.'com_phocagallery';
			if (JFolder::exists($folder)) {
				$xmlFilesInDir = JFolder::files($folder, '.xml$');
			} else {
				$xmlFilesInDir = null;
			}
		}

		$xml_items = '';
		if (count($xmlFilesInDir))
		{
			foreach ($xmlFilesInDir as $xmlfile)
			{
				if ($data = JApplicationHelper::parseXMLInstallFile($folder.DS.$xmlfile)) {
					foreach($data as $key => $value) {
						$xml_items[$key] = $value;
					}
				}
			}
		}
		
		if (isset($xml_items['version']) && $xml_items['version'] != '' ) {
			return $xml_items['version'];
		} else {
			return '';
		}
	}
	
	function strTrimAll($input) {
		$output	= '';;
	    $input	= trim($input);
	    for($i=0;$i<strlen($input);$i++) {
	        if(substr($input, $i, 1) != " ") {
	            $output .= trim(substr($input, $i, 1));
	        } else {
	            $output .= " ";
	        }
	    }
	    return $output;
	}
	
	/*
	 *if no lat or lng will be set, it will be automatically set by category
	 */
	function findLatLngFromCategory($categories) {
		$returnLng = '';
		$returnLat = '';
		foreach ($categories as $category) {
			if (isset($category->params)) {
				$longitude	= PhocaGalleryHelper::getParamsArray($category->params, 'longitude');
				$latitude	= PhocaGalleryHelper::getParamsArray($category->params, 'latitude');

				if (!isset($longitude[0]) || (isset($longitude[0]) && ($longitude[0] == '' || $longitude[0] == 0))) {
					$returnLng = '';
				} else {
					$returnLng = $longitude[0];
				}

				if (!isset($latitude[0]) || (isset($latitude[0]) && ($latitude[0] == '' || $latitude[0] == 0))) {
					$returnLat = '';
				} else {
					$returnLat = $latitude[0];
				}
				
				if ($returnLat != '' || $returnLng != '') {
					$latLng['lat'] = $returnLat;
					$latLng['lng'] = $returnLng;
					return $latLng;
				}
			} 
		}
		// if nothing will be found, paste some lng, lat
		$latLng['lat'] = 50.079623358200884;
		$latLng['lng'] = 14.429919719696045;
		return $latLng;
	}
}
?>