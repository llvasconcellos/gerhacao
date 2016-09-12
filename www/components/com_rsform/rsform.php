<?php
/**
* @version 1.0.1
* @package RSform!Pro 1.0.1
* @copyright (C) 2007-2008 www.rsjoomla.com
* @license Commercial License, http://www.rsjoomla.com/license/rsformpro.html
*/
@session_start();
require_once(dirname(__FILE__).'/controller/adapter.php');

//create the RSadapter
$RSadapter = new RSadapter();
$GLOBALS['RSadapter'] = $RSadapter;
//require classes
require_once(_RSFORM_BACKEND_ABS_PATH.'/admin.rsform.html.php');
require_once(_RSFORM_FRONTEND_ABS_PATH.'/rsform.class.php');

//require controller
require_once(_RSFORM_FRONTEND_ABS_PATH.'/controller/functions.php');
require_once(_RSFORM_FRONTEND_ABS_PATH.'/controller/validation.php');

//require backend language file
require_once(_RSFORM_FRONTEND_ABS_PATH.'/languages/'._RSFORM_FRONTEND_LANGUAGE.'.php');

$formId = $RSadapter->getMenuParam('formId',0);
if(!$formId) $formId = intval( $RSadapter->getParam( $_REQUEST,'formId',0));

$task 			= $RSadapter->getParam( $_REQUEST, 'task' );


switch($task){

	case 'captcha':
		captcha();
	break;

	case 'showJs':
		showJs();
	break;
	
	default:
		formsShow($formId);
	break;
}

function showJs()
{
	echo _RSFORM_FRONTEND_CALENDARJS;
	exit();
}

function captcha()
{
	global $RSadapter;	
	$componentId	= intval( $RSadapter->getParam( $_GET,'componentId'));

	$captcha = new captcha($componentId);

	$_SESSION['CAPTCHA'.$componentId] = $captcha->getCaptcha();
	exit;
}

function formsShow($formId)
{
	
	$RSadapter = $GLOBALS['RSadapter'];
		
	if(isset($_SESSION['form'][$formId]['thankYouMessage']) && !empty($_SESSION['form'][$formId]['thankYouMessage']))
	{
		echo RSshowThankyouMessage($formId);
	}
	else
	{
		if(!empty($_POST))
		{			
			$invalid = RSprocessForm($formId);		
			if($invalid)
			{
				//the invalid variable is returned
				echo RSshowForm($formId, $_POST['form'], $invalid);
			}		
		}
		else
		{
			if(isset($_SESSION['form'][$formId]['thankYouMessage']) && empty($_SESSION['form'][$formId]['thankYouMessage']))
			{
				unset($_SESSION['form'][$formId]['thankYouMessage']);
				
				//is there a return url?
				$query = mysql_query("SELECT ReturnUrl FROM `{$RSadapter->tbl_rsform_forms}` WHERE `formId` = '$formId'");
				$returnUrl = mysql_fetch_assoc($query);
				if(!empty($returnUrl['ReturnUrl'])) 
				{
					$returnUrl['ReturnUrl'] = stripslashes($returnUrl['ReturnUrl']);
					if(!isset($_SESSION['form'][$formId]['submissionId']))$_SESSION['form'][$formId]['submissionId'] = '';
					$returnUrl['ReturnUrl'] = RSprocessField($returnUrl['ReturnUrl'],$_SESSION['form'][$formId]['submissionId']);
					//unset($_SESSION['form'][$formId]['submissionId']);
					
					$RSadapter->redirect($returnUrl['ReturnUrl']);
				}
								
				echo _RSFORM_FRONTEND_THANKYOU;
			}
			echo RSshowForm($formId);
		}
	}
}

?>