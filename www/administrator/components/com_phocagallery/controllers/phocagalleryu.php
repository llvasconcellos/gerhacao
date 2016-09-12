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

defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.client.helper');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class PhocaGalleryCpControllerPhocaGalleryu extends PhocaGalleryCpController
{
	function __construct() {
		parent::__construct();
		// Register Extra tasks
		$this->registerTask( 'upload' , 'upload');
		$this->registerTask( 'createfolder' , 'createfolder');
		$this->registerTask( 'javaupload' , 'javaupload');
	}

	/*
	 * Folder
	 */
	
	function createfolder() {
		global $mainframe;
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');

		$path			= PhocaGalleryHelper::getPathSet();
		$folderNew		= JRequest::getCmd( 'foldername', '');
		$folderCheck	= JRequest::getVar( 'foldername', null, '', 'string', JREQUEST_ALLOWRAW);
		$parent			= JRequest::getVar( 'folderbase', '', '', 'path' );
		$viewBack		= JRequest::getVar( 'viewback', '', '', '' );
		
		$link = '';
		switch ($viewBack) {
			case 'phocagalleryi':
				$link = 'index.php?option=com_phocagallery&view=phocagalleryi&tmpl=component&folder='.$parent;
			break;
		
			case 'phocagallerym':
				$link = 'index.php?option=com_phocagallery&view=phocagallerym&layout=form&hidemainmenu=1&folder='.$parent;
			break;
			
			default:
				$mainframe->redirect('index.php?option=com_phocagallery', 'Controller U Error');
			break;
		
		}

		JRequest::setVar('folder', $parent);

		if (($folderCheck !== null) && ($folderNew !== $folderCheck)) {
			$mainframe->redirect($link, JText::_('WARNDIRNAME'));
		}

		if (strlen($folderNew) > 0) {
			$path = JPath::clean($path['orig_abs_ds'].DS.$parent.DS.$folderNew);
			if (!is_dir($path) && !is_file($path))
			{
				JFolder::create($path);
				JFile::write($path.DS."index.html", "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>");
				
				$mainframe->redirect($link, JText::_('Folder Created'));
			} else {
				$mainframe->redirect($link, JText::_('Folder exists'));
			}
			//JRequest::setVar('folder', ($parent) ? $parent.'/'.$folder : $folder);
		}
		
		$mainframe->redirect($link);
	}
	
	/*
	 * Java Upload
	 */
	
	function javaupload() {			    
		global $mainframe;
		// Check for request forgeries
		JRequest::checkToken( 'request' ) or jexit( 'Invalid Token' );
		$errUploadMsg	= '';	

		if (!PhocaGalleryCpControllerPhocaGalleryu::_realJavaUpload($errUploadMsg)	) {		
			exit( 'ERROR: '.$errUploadMsg);		
		} else {					
			exit( 'SUCCESS');		
		}
	}
	
	
	function _realJavaUpload(&$errUploadMsg) {		
		global $mainframe;
		// Check for request forgeries
		JRequest::checkToken( 'request' ) or jexit( 'Invalid Token' );
			
		foreach ($_FILES as $file => $fileArray) {
			echo('File key: '. $file . "\n");
			foreach ($fileArray as $item=>$val) {
				echo(' Data received: ' . $item.'=>'.$val . "\n");
			}
			if (!PhocaGalleryCpControllerPhocaGalleryu::_uploadJava($errUploadMsg, $fileArray)) {
				$errUploadMsg = JText::_($errUploadMsg);
				return false;
			}
		}
		return true;
	}
	
	function _uploadJava(&$errUploadMsg, $file = '') {
		global $mainframe;
		// Check for request forgeries
		JRequest::checkToken( 'request' ) or jexit( 'Invalid Token' );

		// Set FTP credentials, if given
		$ftp =& JClientHelper::setCredentialsFromRequest('ftp');
		$path		= PhocaGalleryHelper::getPathSet();
		$folder		= JRequest::getVar( 'folder', '', '', 'path' );

		// Make the filename safe
		if (isset($file['name'])) {
			$file['name'] = JFile::makeSafe($file['name']);
		}
	
		if (isset($file['name'])) {
			$filepath = JPath::clean($path['orig_abs_ds'].$folder.DS.strtolower($file['name']));

			
			
			if (!PhocaGalleryHelperUpload::canUpload( $file, $errUploadMsg )) {
				$errUploadMsg = JText::_($errUploadMsg);
				return false;
			}

			if (JFile::exists($filepath)) {
				$errUploadMsg = JText::_('Error. File already exists');
				return false;
			}

			if (!JFile::upload($file['tmp_name'], $filepath)) {
				$errUploadMsg = JText::_('Error. Unable to upload file');
				return false;
			} 
			return true;
		} else {
			$errUploadMsg = JText::_('Error. Unable to upload file');
			return false;
		}
	}
	
	/*
	 * Standard Upload
	 * Flash Upload
	 */
	
	function upload() {
		global $mainframe;
	
		// Check for request forgeries
		JRequest::checkToken( 'request' ) or jexit( 'Invalid Token' );

		// Set FTP credentials, if given
		$ftp =& JClientHelper::setCredentialsFromRequest('ftp');
		
		$path			= PhocaGalleryHelper::getPathSet();
		$file 			= JRequest::getVar( 'Filedata', '', 'files', 'array' );
		$folder			= JRequest::getVar( 'folder', '', '', 'path' );
		$format			= JRequest::getVar( 'format', 'html', '', 'cmd');
		$return			= JRequest::getVar( 'return-url', null, 'post', 'base64' );
		$viewBack		= JRequest::getVar( 'viewback', '', '', '' );
		$errUploadMsg	= '';

		// Make the filename safe
		if (isset($file['name'])) {
			$file['name']	= JFile::makeSafe($file['name']);
		}
		
		
		// All HTTP header will be overwritten with js message
		if (isset($file['name'])) {
			$filepath = JPath::clean($path['orig_abs_ds'].$folder.DS.strtolower($file['name']));

			if (!PhocaGalleryHelperUpload::canUpload( $file, $errUploadMsg )) {
				
				if ($format == 'json') {					
					switch ($errUploadMsg) {
						case 'WARNFILETOOLARGE':
							header('HTTP/1.0 413 Request Entity Too Large');
							jexit(JText::_($errUploadMsg));
						break;
						
						default:
							header('HTTP/1.0 415 Unsupported Media Type');
							jexit(JText::_($errUploadMsg));
						break;
					}	
				} else {
					JError::raiseNotice(100, JText::_($errUploadMsg));
					// REDIRECT
					if ($return) {
						$mainframe->redirect(base64_decode($return).'&folder='.$folder);
					}
					return;
				}
			}

			if (JFile::exists($filepath)) {
				if ($format == 'json') {
					header('HTTP/1.0 409 Conflict');
					jexit('Error. File already exists');
				} else {
					JError::raiseNotice(100, JText::_('Error. File already exists'));
					// REDIRECT
					if ($return) {
						$mainframe->redirect(base64_decode($return).'&folder='.$folder);
					}
					return;
				}
			}

			if (!JFile::upload($file['tmp_name'], $filepath)) {
				if ($format == 'json') {
					header('HTTP/1.0 406 Not Acceptable');
					jexit('Error. Unable to upload file');
				} else {
					JError::raiseWarning(100, JText::_('Error. Unable to upload file'));
					// REDIRECT
					if ($return) {
						$mainframe->redirect(base64_decode($return).'&folder='.$folder);
					}
					return;
				}
			} else {
				if ($format == 'json') {
					header('HTTP/1.0 400');// With 400 error will be not displayed (?? - ok)
					jexit('Upload complete');
				} else {
					$mainframe->enqueueMessage(JText::_('Phoca Gallery, Upload complete'));
					// REDIRECT
					if ($return) {
						$mainframe->redirect(base64_decode($return).'&folder='.$folder);
					}
					return;
				}
			}
		} else {
			$msg = JText::_('WARNFILETYPE');
			if ($format == 'json') {
					header('HTTP/1.0 415 Unsupported Media Type');
					jexit('Error. Unable to upload file');
				} else {
				if ($return) {
					$mainframe->redirect(base64_decode($return).'&folder='.$folder, $msg);
				} else {
					switch ($viewBack) {
						case 'phocagalleryi':
							$mainframe->redirect('index.php?option=com_phocagallery&view=phocagalleryi&tmpl=component&folder='.$folder, $msg);
						break;
					
						case 'phocagallerym':
							$mainframe->redirect('index.php?option=com_phocagallery&view=phocagallerym&layout=form&hidemainmenu=1&folder='.$folder, $msg);
						break;
						
						default:
							$mainframe->redirect('index.php?option=com_phocagallery', $msg);
						break;
					
					}
				}
			}
		}
	}
}
