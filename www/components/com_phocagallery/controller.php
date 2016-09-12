<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');
require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_phocagallery'.DS.'helpers'.DS.'phocagalleryupload.php' );

class PhocaGalleryController extends JController
{
	function display() {
		if ( ! JRequest::getCmd( 'view' ) ) {
			JRequest::setVar('view', 'categories' );
		}
		parent::display();
    }

	function remove() {
		global $mainframe;
		$user 		= &JFactory::getUser();
		$view 		= JRequest::getVar( 'view', '', 'get', '', JREQUEST_NOTRIM  );
		$id 		= JRequest::getVar( 'id', '', 'get', 'string', JREQUEST_NOTRIM  );
		$catid 		= JRequest::getVar( 'catid', '', 'get', 'string', JREQUEST_NOTRIM  );
		$Itemid		= JRequest::getVar( 'Itemid', 0, '', 'int');
		$limitStart	= JRequest::getVar( 'limitstart', 0, '', 'int');
		
		$model = $this->getModel('category');
		
		// Get catid of an id in case catid will be not send (SEF)
		$catidAlias = $catid; // because of JRoute redirect
		if ($id > 0 && $catid == '') {
			$catidObject 		= $model->getCategoryIdFromImageId($id);
			$catid 				= (int)$catidObject->catid;
			$catidAliasObject 	= $model->getCategoryAlias($catid);
			if ($catidAliasObject->alias !='') {
				$catidAlias		= $catid . ':' . $catidAliasObject->alias;
			}
		}
		
		// USER RIGHT - DELETE =======================================
		// 2, 2 means that user access will be ignored in function getUserRight for display Delete button
		$rightDisplayDelete = 0;
		$catParams	= $model->getCategoryParams((int)$catid);
		
		if (isset($catParams->params)) {
			$rightDisplayDelete = PhocaGalleryHelper::getUserRight($catParams->params, 'deleteuserid', 2, 2, $user->get('id', 0), 0);
		}
		// ============================================================	
		
		if ($view != 'category') {
			$this->setRedirect( JRoute::_('index.php?option=com_phocagallery', false) );
		}
		
		if ((int)$id  < 1) {
			$this->setRedirect( JRoute::_('index.php?option=com_phocagallery', false)  );
		}
		
		if ($rightDisplayDelete == 1) {
			if(!$model->delete((int)$id)) {
			$msg = JText::_('Error Deleting Phoca gallery');
			} else {
			$msg = JText::_('Phoca gallery Deleted');
			} 
		} else {
			$mainframe->redirect(JRoute::_('index.php?option=com_user&view=login', false), JText::_("NOT AUTHORISED TO DO ACTION"));
			exit;
		}

		$countItem = $model->countItem((int)$catid);
		if ($countItem) {
			if ((int)$countItem[0] == $limitStart) {
				$limitStart = 0;
			}
		} else {
			$limitStart = 0;
		}
		
		if ($limitStart > 0) {
			$limitStartUrl	= '&limitstart='.$limitStart;	
		} else {
			$limitStartUrl	= '';
		}
		$this->setRedirect( JRoute::_('index.php?option=com_phocagallery&view=category&id='.$catidAlias.'&Itemid='. $Itemid . $limitStartUrl, false), $msg );
	}

	function publish() {
		global $mainframe;
		$user 		=& JFactory::getUser();
		$view 		= JRequest::getVar( 'view', '', 'get', '', JREQUEST_NOTRIM  );
		$id 		= JRequest::getVar( 'id', '', 'get', 'string', JREQUEST_NOTRIM  );
		$catid 		= JRequest::getVar( 'catid', '', 'get', 'string', JREQUEST_NOTRIM  );
		$Itemid		= JRequest::getVar( 'Itemid', 0, '', 'int');
		$limitStart	= JRequest::getVar( 'limitstart', 0, '', 'int');
		
		$model = $this->getModel('category');
		
		// Get catid of an id in case catid will be not send (SEF)
		$catidAlias = $catid; // because of JRoute redirect
		if ($id > 0 && $catid == '') {
		$catidObject 		= $model->getCategoryIdFromImageId($id);
			$catid 				= (int)$catidObject->catid;
			$catidAliasObject 	= $model->getCategoryAlias($catid);
			if ($catidAliasObject->alias !='') {
				$catidAlias		= $catid . ':' . $catidAliasObject->alias;
			}
		}
		
		// USER RIGHT - DELETE =======================================
		// 2, 2 means that user access will be ignored in function getUserRight for display Delete button
		$rightDisplayDelete = 0;
		$catParams	= $model->getCategoryParams((int)$catid);
		
		if (isset($catParams->params)) {
			$rightDisplayDelete = PhocaGalleryHelper::getUserRight($catParams->params, 'deleteuserid', 2, 2, $user->get('id', 0), 0);
		}
		// ============================================================	
		
		if ($view != 'category') {
			$this->setRedirect( JRoute::_('index.php?option=com_phocagallery', false) );
		}
		
		if ((int)$id  < 1) {
			$this->setRedirect( JRoute::_('index.php?option=com_phocagallery', false) );
		}
		
		if ($rightDisplayDelete == 1) {
			if(!$model->publish((int)$id, 1)) {
			$msg = JText::_('ERROR PUBLISHING PHOCA GALLERY');
			} else {
			$msg = JText::_('PHOCA GALLERY PUBLISHED');
			} 
		} else {
			$mainframe->redirect(JRoute::_('index.php?option=com_user&view=login', false), JText::_("NOT AUTHORISED TO DO ACTION"));
			exit;
		}

		if ($limitStart > 0) {
			$limitStartUrl	= '&limitstart='.$limitStart;	
		} else {
			$limitStartUrl	= '';
		}
		$this->setRedirect( JRoute::_('index.php?option=com_phocagallery&view=category&id='.$catidAlias.'&Itemid='. $Itemid . $limitStartUrl, false), $msg );
	}

	function unpublish() {
	
		global $mainframe;
		$user 		=& JFactory::getUser();
		$view 		= JRequest::getVar( 'view', '', 'get', '', JREQUEST_NOTRIM  );
		$id 		= JRequest::getVar( 'id', '', 'get', 'string', JREQUEST_NOTRIM  );
		$catid 		= JRequest::getVar( 'catid', '', 'get', 'string', JREQUEST_NOTRIM  );
		$Itemid		= JRequest::getVar( 'Itemid', 0, '', 'int');
		$limitStart	= JRequest::getVar( 'limitstart', 0, '', 'int');
		
		
		$model = $this->getModel('category');
		
		// Get catid of an id in case catid will be not send (SEF)
		$catidAlias = $catid; // because of JRoute redirect
		if ($id > 0 && $catid == '') {
			$catidObject 		= $model->getCategoryIdFromImageId($id);
			$catid 				= (int)$catidObject->catid;
			$catidAliasObject 	= $model->getCategoryAlias($catid);
			if ($catidAliasObject->alias !='') {
				$catidAlias		= $catid . ':' . $catidAliasObject->alias;
			}
		}

		// USER RIGHT - DELETE =======================================
		// 2, 2 means that user access will be ignored in function getUserRight for display Delete button
		$rightDisplayDelete = 0;
		$catParams	= $model->getCategoryParams((int)$catid);
		
		if (isset($catParams->params)) {
			$rightDisplayDelete = PhocaGalleryHelper::getUserRight($catParams->params, 'deleteuserid', 2, 2, $user->get('id', 0), 0);
		}
		// ============================================================	
		
		if ($view != 'category') {
			$this->setRedirect( JRoute::_('index.php?option=com_phocagallery', false) );
		}
		
		if ((int)$id  < 1) {
			$this->setRedirect( JRoute::_('index.php?option=com_phocagallery', false) );
		}

		if ($rightDisplayDelete == 1) {
			if(!$model->publish((int)$id, 0)) {
				$msg = JText::_('ERROR UNPUBLISHING PHOCA GALLERY');
			} else {
				$msg = JText::_('PHOCA GALLERY UNPUBLISHED');
			}
		} else {
			$mainframe->redirect(JRoute::_('index.php?option=com_user&view=login', false), JText::_("NOT AUTHORISED TO DO ACTION"));
			exit;
		}

		if ($limitStart > 0) {
			$limitStartUrl	= '&limitstart='.$limitStart;	
		} else {
			$limitStartUrl	= '';
		}
		$this->setRedirect( JRoute::_('index.php?option=com_phocagallery&view=category&id='.$catidAlias.'&Itemid='. $Itemid . $limitStartUrl, false), $msg );
	}
	
	
	/*
	 * Java Upload
	 */
	function javaupload() {			    
		global $mainframe;
		// Check for request forgeries
		JRequest::checkToken( 'request' ) or jexit( 'Invalid Token' );
		$errUploadMsg	= '';
		$redirectUrl 	= '';		

		if (!PhocaGalleryController::_realJavaUpload($errUploadMsg, $redirectUrl)	) {		
			exit( 'ERROR: '.$errUploadMsg);		
		} else {					
			exit( 'SUCCESS');		
		}
	}
	
	/*
	 * Upload
	 */
	function upload() {			    
		global $mainframe;		
		$errUploadMsg	= '';	
	    $redirectUrl 	= '';
		$fileArray 		= JRequest::getVar( 'Filedata', '', 'files', 'array' );
		PhocaGalleryController::_uploadSingleFile($errUploadMsg, $fileArray, $redirectUrl);
		$mainframe->redirect($redirectUrl, $errUploadMsg);
		exit;	
	}

	
	function _realJavaUpload(&$errUploadMsg, &$redirectUrl) {		
		global $mainframe;
		// Check for request forgeries
		JRequest::checkToken( 'request' ) or jexit( 'Invalid Token' );
			
		foreach ($_FILES as $file => $fileArray) {
			echo('File key: '. $file . "\n");
			foreach ($fileArray as $item=>$val) {
				echo(' Data received: ' . $item.'=>'.$val . "\n");
			}
			if (!PhocaGalleryController::_uploadSingleFile($errUploadMsg, $fileArray, $redirectUrl)) {
				$errUploadMsg = JText::_($errUploadMsg);
				return false;
			}
		}
		return true;
	}
	

	function _uploadSingleFile(&$errUploadMsg, $file, &$redirectUrl) {
		global $mainframe;
		// Check for request forgeries
		JRequest::checkToken( 'request' ) or jexit( 'Invalid Token' );

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		$ftp =& JClientHelper::setCredentialsFromRequest('ftp');
		
		$user 		=& JFactory::getUser();
		$path		= PhocaGalleryHelper::getPathSet();
		$folder		= JRequest::getVar( 'folder', '', '', 'path' );
		$tab		= JRequest::getVar( 'tab', 0, '', 'int' );
		$format		= JRequest::getVar( 'format', 'html', '', 'cmd');
		$return			= JRequest::getVar( 'return-url', null, 'post', 'base64' );
		$viewBack	= JRequest::getVar( 'viewback', '', '', '' );
		$view 		= JRequest::getVar( 'view', '', 'get', '', JREQUEST_NOTRIM  );
		$catid 		= JRequest::getVar( 'id', '', 'get', 'string', JREQUEST_NOTRIM  );
		//$catid 	= JRequest::getVar( 'catid', '', 'post', 'string', JREQUEST_NOTRIM  );
		$Itemid		= JRequest::getVar( 'Itemid', 0, '', 'int');
		$limitStart	= JRequest::getVar( 'limitstart', 0, '', 'int');
		$paramsC 	= JComponentHelper::getParams('com_phocagallery') ;
		
		$catidAlias	= $catid;// for return
		// Set the limistart (TODO)
		if ($limitStart > 0) {
			$limitStartUrl	= '&limitstart='.$limitStart;	
		} else {
			$limitStartUrl	= '';
		}
		
		// From which view the image is uploaded
		switch($view) {
		
			case 'user':
				
				// UCP is disabled (security reasons)
				$enable_user_cp	= $paramsC->get( 'enable_user_cp', 0 );
				if ($enable_user_cp == 0) {				    
					$errUploadMsg	= JText::_("User Control Panel is disabled");	
					$redirectUrl 	= JURI::base(true);
				}
				
				$return	= JRoute::_('index.php?option=com_phocagallery&view=user&tab='.$tab.'&Itemid='.$Itemid, false);

				// Get user catid, we are not in the category, so we must find the catid
				$modelUser 		= $this->getModel('user');
				$userCatId 		= $modelUser->getUserCategory($user->id);
													
				// User has no category, he (she) can create one
				if (!empty($userCatId->categoryid)) {
					$catid = $userCatId->categoryid;
				} else {		
					$errUploadMsg = JText::_("Error Uploading Phoca Gallery User Control Image");		
					$redirectUrl = $return;
					return false;
				}
				
			break;
			
			case 'category':
			default:
				$return	= JRoute::_('index.php?option=com_phocagallery&view=category&id='.$catidAlias.'&tab='.$tab.'&Itemid='.$Itemid.$limitStartUrl, false);
				$redirectUrl = $return;
			break;
	
		}
		$model 	= $this->getModel('category');
		
		// USER RIGHT - UPLOAD ========================================
		// 2, 2 means that user access will be ignored in function getUserRight for display Delete button
		$rightDisplayUpload	= 0;
		$catParams			= $model->getCategoryParams((int)$catid);

		if (isset($catParams->params)) {
			$rightDisplayUpload = PhocaGalleryHelper::getUserRight($catParams->params, 'uploaduserid', 2, 2, $user->get('id', 0), 0);
		}
		
		// ============================================================	
		// USER RIGHT - FOLDER ========================================		
		$rightFolder[0] = '';
		if (isset($catParams->params)) {
			$rightFolder = PhocaGalleryHelper::getParamsArray($catParams->params, 'userfolder');
		}
		// ============================================================	
	
		
		if ($rightDisplayUpload == 1) {
		
			if ($rightFolder[0] == '') {
				$errUploadMsg = JText::_("User Folder Not Defined");
				$redirectUrl = $return;
				return false;
			}
			if (!JFolder::exists($path['orig_abs_ds'] . $rightFolder[0] . DS)) {
				$errUploadMsg = JText::_("Defined User Folder Does Not Exist");
				$redirectUrl = $return;
				return false;
			}
		
			// Check if the size will be not over the category folder size
			jimport( 'joomla.filesystem.folder' );
			$path 		= PhocaGalleryHelper::getPathset();
			$catPath 	= $path['orig_abs_ds'] . $rightFolder[0] . DS;
			$files 		= JFolder::files( $catPath );
			
			// Get size of all images in the folder
			$allFileSize = 0;
			foreach ($files as $fileInFolder) {
				$fileSize = PhocaGalleryHelperFront::getFileSizePhoca($rightFolder[0] . DS .$fileInFolder, 0);
				$allFileSize = $allFileSize + (int)$fileSize;
			}
			
			// Get the size of all images include new uploaded image in Bytes
			if (isset($file['size'])) {
				$allFileSize = $allFileSize + (int)$file['size'];
			}
			
			
			$maxFolderSize = (int) $paramsC->get( 'cat_folder_maxsize', 20000000 );
			
			if ($maxFolderSize > 0 && (int) $allFileSize > $maxFolderSize) {
				$errUploadMsg = JText::_("WARNFILETOOLARGEFOLDER");	
				$redirectUrl = $return;
				return false;
			}

			// Make the filename safe
			if (isset($file['name'])) {
				$file['name']	= JFile::makeSafe($file['name']);
			}
			
				
			if (isset($file['name'])) {
				$filepath = JPath::clean($path['orig_abs_ds'].$rightFolder[0].DS.$file['name']);

				if (!PhocaGalleryHelperUpload::canUpload( $file, $errUploadMsg )) {
					$errUploadMsg 	= JText::_($errUploadMsg);
					$redirectUrl 	= $return;
					return false;
				}

				if (JFile::exists($filepath)) {
					$errUploadMsg = JText::_("File already exists");
					return false;
				}

				if (!JFile::upload($file['tmp_name'], $filepath)) {
					$errUploadMsg = JText::_("Unable to upload file");
					$redirectUrl = $return;
					return false;
				} else {
				
					// Saving file name into database with relative path
					$file['name']	= $rightFolder[0] . '/' . $file['name'];
					$succeeded 		= false;
					PhocaGalleryController::save((int)$catid, $file['name'], $return, $succeeded, $errUploadMsg, false);
					$redirectUrl 	= $return;
					return $succeeded;
				}
			} else {				
				$errUploadMsg = JText::_("WARNFILETYPE");	
				$redirectUrl = $return;				
				return false;
			}
		} else {			
			$errUploadMsg = JText::_("NOT AUTHORISED TO DO ACTION");			
			$redirectUrl = JRoute::_('index.php?option=com_user&view=login', false);
			return false;
		}		
		return false;		
	}
	
	
	function save($catid, $filename, $return, &$succeeded, &$errSaveMsg, $redirect=true) {
		
		global $mainframe;
		
		$post['filename']		= $filename;
		$post['title']			= JRequest::getVar( 'phocagalleryuploadtitle', '', 'post', 'string', 0 );
		$post['description']	= JRequest::getVar( 'phocagalleryuploaddescription', '', 'post', 'string', 0 );
		$post['catid']			= $catid;
		$post['published']		= 1;
		
		$paramsC 				= JComponentHelper::getParams('com_phocagallery') ;
		$maxUploadChar			= $paramsC->get( 'max_upload_char', 1000 );
		$post['description']	= substr($post['description'], 0, (int)$maxUploadChar);
		
		$model = $this->getModel( 'category' );
		
		if ($model->store($post, $return)) {
			$succeeded = true;
			$errSaveMsg = JText::_( 'Phoca gallery Saved' );
		} else {
			$succeeded = false;
			$errSaveMsg = JText::_( 'Error Saving Phoca gallery' );
		}
		
		if ($redirect) {
			$mainframe->redirect($return, $errSaveMsg);
			exit;
		}
	}
	
	
	function rate() {
		global $mainframe;
		$user 		=& JFactory::getUser();
		$view 		= JRequest::getVar( 'view', '', 'get', '', JREQUEST_NOTRIM  );
		//$id 		= JRequest::getVar( 'id', '', 'get', 'string', JREQUEST_NOTRIM  );
		$catid 		= JRequest::getVar( 'id', '', 'get', 'string', JREQUEST_NOTRIM  );
		$rating		= JRequest::getVar( 'rating', '', 'get', 'string', JREQUEST_NOTRIM  );
		$Itemid		= JRequest::getVar( 'Itemid', 0, '', 'int');
		$limitStart	= JRequest::getVar( 'limitstart', 0, '', 'int');
		$tab		= JRequest::getVar( 'tab', 0, '', 'int' );
	
		
		$post['catid'] 	= (int)$catid;
		$post['userid']	= $user->id;
		$post['rating']	= (int)$rating;
	
		$catidAlias 	= $catid; //Itemid
		if ($view != 'category') {
			$this->setRedirect( JRoute::_('index.php?option=com_phocagallery', false) );
		}
		
		
		$model = $this->getModel('category');
		
		$checkUserVote	= $model->checkUserVote( $post['catid'], $post['userid'] );
		
		// User has already rated this category
		if ($checkUserVote) {
			$msg = JText::_('You have already rated this category');
		} else {
			if ($post['rating']  < 1 && $post['rating'] > 5) {
				$this->setRedirect( JRoute::_('index.php?option=com_phocagallery', false)  );
			}
			
			if ($user->aid > 0 && $user->id > 0) {
				if(!$model->rate($post)) {
				$msg = JText::_('Error Rating Phoca Gallery');
				} else {
				$msg = JText::_('Phoca Gallery Rated');
				} 
			} else {
				$mainframe->redirect(JRoute::_('index.php?option=com_user&view=login', false), JText::_("NOT AUTHORISED TO DO ACTION"));
				exit;
			}
		}

		// Limit Start
		$countItem = $model->countItem((int)$catid);
		if ($countItem) {
			if ((int)$countItem[0] == $limitStart) {
				$limitStart = 0;
			}
		} else {
			$limitStart = 0;
		}
		
		if ($limitStart > 0) {
			$limitStartUrl	= '&limitstart='.$limitStart;	
		} else {
			$limitStartUrl	= '';
		}
		
		
		$this->setRedirect( JRoute::_('index.php?option=com_phocagallery&view=category&id='.$catidAlias.'&tab='.$tab.'&Itemid='. $Itemid . $limitStartUrl, false), $msg );
	}
	
	function comment() {
	
		JRequest::checkToken() or jexit( 'Invalid Token' );
		global $mainframe;
		$user 			=& JFactory::getUser();
		$view 			= JRequest::getVar( 'view', '', 'post', '', 0  );
		$catid 			= JRequest::getVar( 'catid', '', 'post', 'string', 0  );
		$post['title']	= JRequest::getVar( 'phocagallerycommentstitle', '', 'post', 'string', 0  );
		$post['comment']= JRequest::getVar( 'phocagallerycommentseditor', '', 'post', 'string', 0  );
		$Itemid			= JRequest::getVar( 'Itemid', 0, '', 'int');
		$limitStart		= JRequest::getVar( 'limitstart', 0, '', 'int');
		$tab			= JRequest::getVar( 'tab', 0, '', 'int' );

		$paramsC 		= JComponentHelper::getParams('com_phocagallery') ;
		$maxCommentChar	= $paramsC->get( 'max_comment_char', 1000 );
		// Maximum of character, they will be saved in database
		$post['comment']	= substr($post['comment'], 0, (int)$maxCommentChar);
		
		// Close Tags
		$post['comment'] = PhocaGalleryHelperComment::closeTags($post['comment'], '[u]', '[/u]');
		$post['comment'] = PhocaGalleryHelperComment::closeTags($post['comment'], '[i]', '[/i]');
		$post['comment'] = PhocaGalleryHelperComment::closeTags($post['comment'], '[b]', '[/b]');
		
		
		$post['catid'] 	= (int)$catid;
		$post['userid']	= $user->id;
		
		$catidAlias 	= $catid; //Itemid
		if ($view != 'category') {
			$this->setRedirect( JRoute::_('index.php?option=com_phocagallery', false) );
		}
		
		$model = $this->getModel('category');
		
		$checkUserComment	= $model->checkUserComment( $post['catid'], $post['userid'] );
		
		// User has already submitted a comment
		if ($checkUserComment) {
			$msg = JText::_('You have already submitted comment');
		} else {
			// If javascript will not protect the empty form
			$msg 		= '';
			$emptyForm	= 0;
			if ($post['title'] == '') {
				$msg .= JText::_('Error Comment Phoca Gallery - Title') . ' ';
				$emtyForm = 1;
			}
			if ($post['comment'] == '') {
				$msg .= JText::_('Error Comment Phoca Gallery - Comment');
				$emtyForm = 1;
			}
			if ($emptyForm == 0) {
				if ($user->aid > 0 && $user->id > 0) {
					if(!$model->comment($post)) {
					$msg = JText::_('Error Comment Phoca Gallery');
					} else {
					$msg = JText::_('Phoca Gallery Comment Submitted');
					} 
				} else {
					$mainframe->redirect(JRoute::_('index.php?option=com_user&view=login', false), JText::_("NOT AUTHORISED TO DO ACTION"));
					exit;
				}
			}
		}
		
		// Limit Start
		$countItem = $model->countItem((int)$catid);
		if ($countItem) {
			if ((int)$countItem[0] == $limitStart) {
				$limitStart = 0;
			}
		} else {
			$limitStart = 0;
		}
		
		if ($limitStart > 0) {
			$limitStartUrl	= '&limitstart='.$limitStart;	
		} else {
			$limitStartUrl	= '';
		}
		
		$this->setRedirect( JRoute::_('index.php?option=com_phocagallery&view=category&id='.$catidAlias.'&tab='.$tab.'&Itemid='. $Itemid . $limitStartUrl, false), $msg );
	}
/*	
	function createSubCategory() {
		global $mainframe;	
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$user 					=& JFactory::getUser();
		$view 					= JRequest::getVar( 'view', '', 'post', '', 0  );
		$post['title']			= JRequest::getVar( 'categoryname', 'post', 'string', 0  );
		$post['description']	= JRequest::getVar( 'phocagallerycreatecatdescription', '', 'post', 'string', 0  );
		$Itemid					= JRequest::getVar( 'Itemid', 0, '', 'int');
		$catid					= (int)JRequest::getVar( 'catid', 0, '', 'int');
		$tab					= JRequest::getVar( 'tab', 0, '', 'int' );
		
		// Params
		$paramsC 			= JComponentHelper::getParams('com_phocagallery') ;
		$maxCreateCatChar	= $paramsC->get( 'max_create_cat_char', 1000 );
		$post['description']= substr($post['description'], 0, (int)$maxCreateCatChar);
		$post['alias'] 		= PhocaGalleryHelper::getAliasName($post['title']);
	
		$msg = "";
		if ($user->aid > 0 && $user->id > 0) {
			
			if ($post['title'] != '') {
				$model 		= $this->getModel('user');
				$userCatId 	= $model->getUserCategory($user->id);
			
				// Create an user folder on the server 
				$userFolder	= PhocaGalleryHelper::getAliasName($user->username) .'-'.substr($post['alias'], 0, 10) .'-'. substr(md5(uniqid(time())), 0, 4);
			
				$createdFolderError	= '';
				$createdFolder = PhocaGalleryHelper::createFolder($userFolder);
				$createdFolderError = preg_match("/.\[PhocaError\]/i", $createdFolder);
				
				if ($createdFolderError) {
					$msg = JText::_('Error Folder Creating'). ': ' .str_replace ('[PhocaError]', '', $createdFolder);
				}
				// -----------------------------------
				
				// Folder Created, all right
				if ($msg == '') {
				
					// set default values
					$post['access'] 		= 0;
					//$post['access'] 		= 1;
					$post['parent_id'] 		= $catid;
					$post['image_position']	= 'left';
					$post['published']		= 1;
					$post['params']			= 'accessuserid=-1;'
					//'accessuserid='.$user->id.';'
						 .'uploaduserid='.$user->id.';'
						 .'deleteuserid='.$user->id.';'
						 .'userfolder='.$userFolder.';';
				
					// Create new category
					$id	= $model->store($post);
				}
			}
		}
		$this->setRedirect( JRoute::_('index.php?option=com_phocagallery&view=user&tab='.$tab.'&Itemid='. $Itemid , false), $msg );
	}
	*/
	function createCategory() {
	
		global $mainframe;
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$user 					=& JFactory::getUser();
		$view 					= JRequest::getVar( 'view', '', 'post', '', 0  );
		$post['title']			= JRequest::getVar( 'categoryname', '', 'post', 'string', 0  );
		$post['description']	= JRequest::getVar( 'phocagallerycreatecatdescription', '', 'post', 'string', 0  );
		$Itemid					= JRequest::getVar( 'Itemid', 0, '', 'int');
		$tab					= JRequest::getVar( 'tab', 0, '', 'int' );
		
		// Params
		$paramsC 			= JComponentHelper::getParams('com_phocagallery') ;
		
		// UCP is disabled (security reasons)
		$enable_user_cp		 		= $paramsC->get( 'enable_user_cp', 0 );
		if ($enable_user_cp == 0) {
			$mainframe->redirect(JURI::base(true), JText::_("User Control Panel is disabled"));
			exit;
		}
		
		$maxCreateCatChar	= $paramsC->get( 'max_create_cat_char', 1000 );
		$post['description']= substr($post['description'], 0, (int)$maxCreateCatChar);
		$post['alias'] 		= PhocaGalleryHelper::getAliasName($post['title']);
		
		// user is logged in
		if ($user->aid > 0 && $user->id > 0) {
			
			if ($post['title'] != '') {
				$model 		= $this->getModel('user');
				$userCatId 	= $model->getUserCategory($user->id);
			
				// User has no category, he (she) can create one
				if (empty($userCatId->id)) {
					
					// NEW
				
					$msg = '';
					// Create an user folder on the server 
					$userFolder	= PhocaGalleryHelper::getAliasName($user->username) .'-'.substr($post['alias'], 0, 10) .'-'. substr(md5(uniqid(time())), 0, 4);
				
					$createdFolderError	= '';
					$createdFolder = PhocaGalleryHelper::createFolder($userFolder);
					$createdFolderError = preg_match("/.\[PhocaError\]/i", $createdFolder);
					
					if ($createdFolderError) {
						$msg = JText::_('Error Folder Creating'). ': ' .str_replace ('[PhocaError]', '', $createdFolder);
					}
					// -----------------------------------
					
					// Folder Created, all right
					if ($msg == '') {
					
						// set default values
						$post['access'] 		= 0;
						//$post['access'] 		= 1;
						$post['parent_id'] 		= 0;
						$post['image_position']	= 'left';
						$post['published']		= 1;
						$post['params']			= 'accessuserid=-1;'
												//'accessuserid='.$user->id.';'
												 .'uploaduserid='.$user->id.';'
												 .'deleteuserid='.$user->id.';'
												 .'userfolder='.$userFolder.';';
					
						// Create new category
						$id	= $model->store($post);
						if ($id && $id > 0) {
							
							$data['userid']	= $user->id;
							$data['catid']	= $id;
							$userCategoryId = $model->storeUserCategory($data);
							
							if ($userCategoryId && $userCategoryId > 0) {
								$msg = JText::_( 'Phoca Gallery User Control Category Saved' );
							} else {
								$msg = JText::_( 'Error Saving Phoca Gallery User Control Category' );
							}
						} else {
							$msg = JText::_( 'Error Saving Phoca Gallery User Control Category' );
						}
					}
				} else {
				
					if ($post['title'] != '') {
						// EDIT
						$post['id']	= $userCatId->categoryid;
						
						$id			= $model->store($post);
						if ($id && $id > 0) {
							$msg = JText::_( 'Phoca Gallery User Control Category Edited' );
						} else {
							$msg = JText::_( 'Error Editing Phoca Gallery User Control Category' );
						}
					}
				}
			} else {
				$msg = JText::_( 'ERROR CREATE CATEGORY PHOCA GALLERY - TITLE' );
			}


			$this->setRedirect( JRoute::_('index.php?option=com_phocagallery&view=user&tab='.$tab.'&Itemid='. $Itemid , false), $msg );

		} else {
			$this->setRedirect(JRoute::_('index.php?option=com_user&view=login', false), JText::_("NOT AUTHORISED TO DO ACTION"));
			exit;
		}
	
	}
}
?>