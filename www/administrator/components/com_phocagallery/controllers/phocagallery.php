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

class PhocaGalleryCpControllerPhocaGallery extends PhocaGalleryCpController
{
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'thumbs'  , 'thumbs' );
		$this->registerTask( 'multiple'  , 'multiple' );
		$this->registerTask( 'install'  , 'install' );
		$this->registerTask( 'upgrade'  , 'upgrade' );
		$this->registerTask( 'disablethumbs' , 'disablethumbs');
		$this->registerTask( 'rotate'  , 	'rotate' );
		$this->registerTask( 'deletethumbs'  , 	'deletethumbs' );
	}
	
	function deletethumbs()
	{
		$cid	= JRequest::getVar( 'cid', array(0), 'get', 'array' );
		
		$model	= &$this->getModel( 'phocagallery' );
		if ($model->deletethumbs($cid[0])) {
			$msg = JText::_( 'Phoca Gallery Image Thumbnail Deleted' );
		} else {
			$msg = JText::_( 'Error Deleting Phoca Gallery Image Thumbnail' );
		}
		
		
		$link = 'index.php?option=com_phocagallery&view=phocagallerys';
		$this->setRedirect($link, $msg);
	}
	
	function rotate()
	{
		$cid	= JRequest::getVar( 'cid', array(0), 'get', 'array' );
		$angle	= JRequest::getVar( 'angle', 90, 'get', 'int' );
		$model	= &$this->getModel( 'phocagallery' );
		
		$rotateError	= false;
		$rotateReturn 	= $model->rotate($cid[0], $angle);
		$rotateError 	= preg_match("/Error/i", $rotateReturn);
		if ($rotateError) {
			$msg = JText::_( 'Error Rotating Phoca Gallery Image' ) . ': ' . $rotateReturn;
		} else {
			$msg = JText::_( 'Phoca Gallery Image Rotated' );
		}
		
		
		$link = 'index.php?option=com_phocagallery&view=phocagallerys';
		$this->setRedirect($link, $msg);
	}
	
	function install()
	{
		$msg = JText::_( 'Phoca Gallery succesfully installed' );
		$link = 'index.php?option=com_phocagallery';
		$this->setRedirect($link, $msg);
	}
	
	function upgrade()
	{
		$msg = JText::_( 'Phoca Gallery succesfully upgraded' );
		$link = 'index.php?option=com_phocagallery';
		$this->setRedirect($link, $msg);
	}
	
	//if thumbnails are created - show message after creating thumbnails - show that files was saved in database
	function thumbs()
	{
		$msg = JText::_( 'Phoca gallery Saved Multiple' );
		$link = 'index.php?option=com_phocagallery&view=phocagallerys';
		$this->setRedirect($link, $msg);
	}
	
	function disablethumbs()
	{
		
		$model	= &$this->getModel( 'phocagallery' );
		if ($model->disableThumbs()) {
			$msg = JText::_('Phoca Gallery Disabled Thumbs Succes');
		} else {
			$msg = JText::_('Phoca Gallery Disabled Thumbs Error');
		}
		$link = 'index.php?option=com_phocagallery&view=phocagallerys';
		$this->setRedirect($link, $msg);
	}
	
	
	function multiple()
	{
		JRequest::setVar( 'view', 'phocagallerym' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar( 'hidemainmenu', 1 );
		
		parent::display();
		
		// Checkin the Phoca gallery
		//$model = $this->getModel( 'phocagallery' );
		//$model->checkout();
	}
		
	function edit()
	{
		JRequest::setVar( 'view', 'phocagallery' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar( 'hidemainmenu', 1 );

		parent::display();

		// Checkin the Phoca gallery
		$model = $this->getModel( 'phocagallery' );
		$model->checkout();
	}

	function save()
	{
		$post					= JRequest::get('post');
		$cid					= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$post['id'] 			= (int) $cid[0];
		
		$post['description']	= JRequest::getVar( 'description', '', 'post', 'string', JREQUEST_ALLOWRAW );
		
		$vmProductId			= JRequest::getVar( 'vmproductid', 0, 'post', 'int' );
		$videoCode				= JRequest::getVar( 'videocode', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$longitude				= JRequest::getVar( 'longitude', '', 'post', 'string' );
		$latitude				= JRequest::getVar( 'latitude', '', 'post', 'string' );
		$zoom					= JRequest::getVar( 'zoom', '', 'post', 'string' );
		$geotitle				= JRequest::getVar( 'geotitle', '', 'post', 'string' );
		
		$extlink1				= JRequest::getVar( 'extlink1', '', 'post', 'string' );
		$extlinkname1			= JRequest::getVar( 'extlinkname1', '', 'post', 'string' );
		$targetlist1			= JRequest::getVar( 'targetlist1', '_self', 'post', 'string' );
		$displaylist1			= JRequest::getVar( 'displaylist1', 1, 'post', 'int' );
		$extlink2				= JRequest::getVar( 'extlink2', '', 'post', 'string' );
		$extlinkname2			= JRequest::getVar( 'extlinkname2', '', 'post', 'string' );
		$targetlist2			= JRequest::getVar( 'targetlist2', '_self', 'post', 'string' );
		$displaylist2			= JRequest::getVar( 'displaylist2', 1, 'post', 'int' );
		
		if ($extlink1 != '') {
			$extlink1			= str_replace('http://','', $extlink1);
			$post['extlink1'] 	= $extlink1 . '|'.$extlinkname1.'|'.$targetlist1.'|'.$displaylist1;
		}
		if ($extlink2 != '') {
			$extlink2			= str_replace('http://','', $extlink2);
			$post['extlink2'] 	= $extlink2 . '|'.$extlinkname2.'|'.$targetlist2.'|'.$displaylist2;
		}
		
		$post['params'] = '';
		// VirtueMart link
		if ($vmProductId > 0) {
			$post['params'] .= 'vmproductid='.$vmProductId.';';
		}
		// YouTube
		if ($videoCode != '') {
			$post['params'] .= 'videocode='.$videoCode.';';
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

		$model = $this->getModel( 'phocagallery' );

		if ($model->store($post)) {
			$msg = JText::_( 'Phoca gallery Saved' );
		} else {
			$msg = JText::_( 'Error Saving Phoca gallery' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
		$link = 'index.php?option=com_phocagallery&view=phocagallerys';
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

		$model = $this->getModel( 'phocagallery' );
		if(!$model->delete($cid)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
			$msg = JText::_( 'Error Deleting Phoca gallery' );
		}
		else {
			$msg = JText::_( 'Phoca gallery Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_phocagallery&view=phocagallerys', $msg );
	}

	function publish()
	{
		global $mainframe;

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to publish' ) );
		}

		$model = $this->getModel('phocagallery');
		if(!$model->publish($cid, 1)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_phocagallery&view=phocagallerys' );
	}

	function unpublish()
	{
		global $mainframe;

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to unpublish' ) );
		}

		$model = $this->getModel('phocagallery');
		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_phocagallery&view=phocagallerys' );
	}

	function cancel()
	{
		$model = $this->getModel( 'phocagallery' );
		$model->checkin();

		$this->setRedirect( 'index.php?option=com_phocagallery&view=phocagallerys' );
	}

	function orderup()
	{
		$model = $this->getModel( 'phocagallery' );
		$model->move(-1);

		$this->setRedirect( 'index.php?option=com_phocagallery&view=phocagallerys' );
	}

	function orderdown()
	{
		$model = $this->getModel( 'phocagallery' );
		$model->move(1);

		$this->setRedirect( 'index.php?option=com_phocagallery&view=phocagallerys' );
	}

	function saveorder()
	{
		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel( 'phocagallery' );
		$model->saveorder($cid, $order);

		$msg = JText::_( 'New ordering saved' );
		$this->setRedirect( 'index.php?option=com_phocagallery&view=phocagallerys', $msg );
	}
}
?>
