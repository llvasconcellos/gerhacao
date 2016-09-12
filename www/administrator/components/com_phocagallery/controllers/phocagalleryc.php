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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class PhocaGalleryCpControllerPhocaGalleryc extends PhocaGalleryCpController
{
	function __construct()
	{
		parent::__construct();
		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'apply'  , 'save' );
		$this->registerTask( 'accesspublic', 'accessMenu');
		$this->registerTask( 'accessregistered', 'accessMenu');
		$this->registerTask( 'accessspecial', 'accessMenu');
		$this->registerTask( 'PicLens', 'piclens');

	}
	
	function edit()
	{
		
		JRequest::setVar( 'view', 'phocagalleryc' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar( 'hidemainmenu', 1 );

		parent::display();

		// Checkin the Phoca gallery
		$model = $this->getModel( 'phocagalleryc' );
		$model->checkout();
	}

	function save()
	{
		$post					= JRequest::get('post');
		$cid					= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$post['description']	= JRequest::getVar( 'description', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$post['parent_id']		= JRequest::getVar( 'parentid', '', 'post', 'int' );
		$post['id'] 	= (int) $cid[0];

		// DEFAULT VALUES FOR Rights in FRONTEND
		// ACCESS -  0: all users can see the category (registered or not registered)
		//             if registered or not registered it will be set in ACCESS LEVEL not here)
		//			   if -1 - user was not selected so every registered or special users can see category
		// UPLOAD - -2: nobody can upload or add images in front (if 0 - every users can do it)
		// DELETE - -2: nobody can upload or add images in front (if 0 - every users can do it)
		
		
		$accessUserId	= JRequest::getVar( 'accessuserid', 0, 'post', 'array' );
		$uploadUserId	= JRequest::getVar( 'uploaduserid', -2, 'post', 'array' );
		$deleteUserId	= JRequest::getVar( 'deleteuserid', -2, 'post', 'array' );
		$userFolder		= JRequest::getVar( 'userfolder', '', 'post', 'string' );
		$longitude		= JRequest::getVar( 'longitude', '', 'post', 'string' );
		$latitude		= JRequest::getVar( 'latitude', '', 'post', 'string' );
		$zoom			= JRequest::getVar( 'zoom', '', 'post', 'string' );
		$geotitle		= JRequest::getVar( 'geotitle', '', 'post', 'string' );
		
		// Set all registered users if not selected but 'registered' selected
		if (isset($post['access']) && (int)$post['access'] > 0 && (int)$accessUserId[0] == 0) {
			
			$accessUserId[0]	= -1;
		}
		
		// Access level for selected users
		$post['params'] = '';
		if (!empty($accessUserId)) {
			$post['params'] .= 'accessuserid=';
			foreach ($accessUserId as $key => $value) {
				$post['params'] .= $value.',';
			}
			$post['params'] .=';';
		} 

		// Upload (add) level for selected users
		if (!empty($uploadUserId)) {
			$post['params'] .= 'uploaduserid=';
			foreach ($uploadUserId as $key => $value) {
				$post['params'] .= $value.',';
			}
			$post['params'] .=';';
		}

		// Delete (publish) level for selected users
		if (!empty($uploadUserId)) {
			$post['params'] .= 'deleteuserid=';
			foreach ($deleteUserId as $key => $value) {
				$post['params'] .= $value.',';
			}
			$post['params'] .=';';
		}
		// User folder for selected users
		if (!empty($userFolder)) {
			$post['params'] .= 'userfolder=';
			$post['params'] .= $userFolder;
			
			$post['params'] .=';';
		}
		
		// longitude
		if (!empty($longitude)) {
			$post['params'] .= 'longitude=';
			$post['params'] .= $longitude;
			
			$post['params'] .=';';
		}
		
		// longitude
		if (!empty($latitude)) {
			$post['params'] .= 'latitude=';
			$post['params'] .= $latitude;
			
			$post['params'] .=';';
		}
		
		// geotagging zoom
		if (!empty($zoom)) {
			$post['params'] .= 'zoom=';
			$post['params'] .= $zoom;
			
			$post['params'] .=';';
		}
		
		// geotagging title
		if (!empty($geotitle)) {
			$post['params'] .= 'geotitle=';
			$post['params'] .= $geotitle;
			
			$post['params'] .=';';
		}
		
		
		$model = $this->getModel( 'phocagalleryc' );
		
		switch ( JRequest::getCmd('task') )
		{
			case 'apply':
				$id	= $model->store($post);//you get id and you store the table data
				if ($id && $id > 0) {
					
					// Set author of category by Administrator in administration
					if (isset($post['authorid'])) {
						$data['userid']	= $post['authorid'];
						$data['catid']	= $id;
						// is there some item in phocagallery_user_category about this user
						$data['id']		= $model->getUserCategoryId($data['userid']);
						$userCategoryId = $model->storeUserCategory($data);
					
						if (!$userCategoryId) {
							$msg = JText::_( 'Error Saving Phoca Gallery Categories' ) . ' - ' . JText('Author');
							$this->setRedirect( 'index.php?option=com_phocagallery&controller=phocagalleryc&task=edit&cid[]='. $id, $msg );
						}
					}
					// -----------------------------------------------------------
					
					$msg = JText::_( 'Changes to Phoca Gallery Categories Saved' );
					//$id		= $model->store($post);
				} else {
					$msg = JText::_( 'Error Saving Phoca Gallery Categories' );
					$id		= $post['id'];
				}
				$this->setRedirect( 'index.php?option=com_phocagallery&controller=phocagalleryc&task=edit&cid[]='. $id, $msg );
				break;

			case 'save':
			default:
				$id	= $model->store($post);//you get id and you store the table data
				if ($id && $id > 0) {
					
					// Set author of category by Administrator in administration
					if (isset($post['authorid'])) {
						$data['userid']	= $post['authorid'];
						$data['catid']	= $id;
						// is there some item in phocagallery_user_category about this user
						$data['id']		= $model->getUserCategoryId($data['userid']);
						$userCategoryId = $model->storeUserCategory($data);
					
						if (!$userCategoryId) {
							$msg = JText::_( 'Error Saving Phoca Gallery Categories' ) . ' - ' . JText('Author');
							$this->setRedirect( 'index.php?option=com_phocagallery&view=phocagallerycs', $msg );
						}
					}
					// -----------------------------------------------------------
					
					$msg = JText::_( 'Phoca Gallery Categories Saved' );
				} else {
					$msg = JText::_( 'Error Saving Phoca Gallery Categories' );
				}
				$this->setRedirect( 'index.php?option=com_phocagallery&view=phocagallerycs', $msg );
				break;
		}
		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
	}
	
	function accessMenu()
	{
		$post			= JRequest::get('post');
		$cid			= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$access			= $post['task'];
		
		switch ($access)
		{
			case 'accessregistered':
			$access_id= 1;
			break;

			case 'accessspecial':
			$access_id= 2;
			break;
			
			case 'accesspublic':
			default:
			$access_id= 0;
			break;
		}
		
		$model = $this->getModel( 'phocagalleryc' );

		$model->accessmenu($cid[0],$access_id);
		$model->checkin();
		$link = 'index.php?option=com_phocagallery&view=phocagallerycs';
		$this->setRedirect($link, $msg);
	}

	function remove()
	{
		global $mainframe;

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to delete' ) );
		}

	/*	
		$cids = implode( ',', $cid );
		
		$query = 'SELECT c.id, c.name, c.title, COUNT( s.catid ) AS numcat'
		. ' FROM #__phocagallery_categories AS c'
		. ' LEFT JOIN #__phocagallery AS s ON s.catid = c.id'
		. ' WHERE c.id IN ( '.$cids.' )'
		. ' GROUP BY c.id'
		;
		
		$db->setQuery( $query );

		if (!($rows = $db->loadObjectList())) {
			JError::raiseError( 500, $db->stderr() );
			return false;
		}*/
		
		
		$model = $this->getModel( 'phocagalleryc' );
		if(!$model->delete($cid))
		{
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
			$msg = JText::_( 'Error Deleting Phoca Gallery Categories' );
		}
		else {
			$msg = JText::_( 'Phoca Gallery Categories Deleted' );
		}

		$link = 'index.php?option=com_phocagallery&view=phocagallerycs';
		$this->setRedirect( $link, $msg );
	}

	function publish()
	{
		global $mainframe;

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to publish' ) );
		}

		$model = $this->getModel('phocagalleryc');
		if(!$model->publish($cid, 1)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
		$link = 'index.php?option=com_phocagallery&view=phocagallerycs';
		$this->setRedirect($link);
	}

	function unpublish()
	{
		global $mainframe;

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to unpublish' ) );
		}

		$model = $this->getModel('phocagalleryc');
		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
		$link = 'index.php?option=com_phocagallery&view=phocagallerycs';
		$this->setRedirect($link);
	}

	function cancel()
	{
		$model = $this->getModel( 'phocagalleryc' );
		$model->checkin();

		$link = 'index.php?option=com_phocagallery&view=phocagallerycs';
		$this->setRedirect( $link );
	}

	function orderup()
	{
		$model = $this->getModel( 'phocagalleryc' );
		$model->move(-1);

		$link = 'index.php?option=com_phocagallery&view=phocagallerycs';
		$this->setRedirect( $link );
	}

	function orderdown()
	{
		$model = $this->getModel( 'phocagalleryc' );
		$model->move(1);

		$link = 'index.php?option=com_phocagallery&view=phocagallerycs';
		$this->setRedirect( $link );
	}

	function saveorder()
	{
		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel( 'phocagalleryc' );
		$model->saveorder($cid, $order);

		$msg = JText::_( 'New ordering saved' );
		$link = 'index.php?option=com_phocagallery&view=phocagallerycs';
		$this->setRedirect( $link, $msg  );
	}
	
	function piclens()
	{
		$cids	= JRequest::getVar( 'cid', array(0), 'post', 'array' );	
		
		$model = $this->getModel( 'phocagalleryc' );
		
		if(!$model->piclens($cids))
		{
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
			$msg = JText::_( 'Error Creating PicLens RSS File(s)' );
		}
		else {
			$msg = JText::_( 'PicLens RSS File(s) created' );
		}
		
		$link = 'index.php?option=com_phocagallery&view=phocagallerycs';
		$this->setRedirect( $link, $msg  );
	}
}
?>
