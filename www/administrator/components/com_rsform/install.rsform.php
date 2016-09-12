<?php
/**
* @version 1.0.1
* @package RSform!Pro 1.0.1
* @copyright (C) 2007-2008 www.rsjoomla.com
* @license Commercial License, http://www.rsjoomla.com/license/rsformpro.html
*/

function com_install()
{
	//Init RS Adapter
	require_once(dirname(__FILE__).'/../../../components/com_rsform/controller/adapter.php');
	require_once(dirname(__FILE__).'/../../../components/com_rsform/controller/functions.php');
	
	$RSadapter = new RSadapter();
	$GLOBALS['RSadapter'] = $RSadapter;

	//require backend language file
	require_once(_RSFORM_FRONTEND_ABS_PATH.'/languages/'._RSFORM_FRONTEND_LANGUAGE.'.php');
	
	$message = '';
	
	//Update Administrator Menu
	$q = "UPDATE {$RSadapter->config['dbprefix']}components SET admin_menu_img='../administrator/components/com_rsform/images/logo.gif' WHERE admin_menu_link='option=com_rsform'";
	mysql_query($q);
	
	//Create RSform!Pro tables
	$result = RSparse_mysql_dump(_RSFORM_BACKEND_ABS_PATH.'/tmp/rsform-install.sql');
	if($result=='ok') $message .= _RSFORM_INSTALLER_TABLES_OK;
	else $message .= sprintf(_RSFORM_INSTALLER_TABLES_ERROR,$result);
	
		
	//Try setting directories permissions
	$result = @chmod(_RSFORM_BACKEND_ABS_PATH.'/tmp/',0777);
	if($result==true) $message .= sprintf(_RSFORM_INSTALLER_PERMISSIONS_OK,_RSFORM_BACKEND_ABS_PATH.'/tmp/','0777');
	else $message .= sprintf(_RSFORM_INSTALLER_PERMISSIONS_ERROR,_RSFORM_BACKEND_ABS_PATH.'/tmp/','0777');
	
	$result = @chmod(_RSFORM_FRONTEND_ABS_PATH.'/uploads/',0777);
	if($result==true) $message .= sprintf(_RSFORM_INSTALLER_PERMISSIONS_OK,_RSFORM_FRONTEND_ABS_PATH.'/tmp/','0777');
	else $message .= sprintf(_RSFORM_INSTALLER_PERMISSIONS_ERROR,_RSFORM_FRONTEND_ABS_PATH.'/uploads/','0777');
	
	//Add sample forms
	$result = RSparse_mysql_dump(_RSFORM_BACKEND_ABS_PATH.'/tmp/sample-install.sql');
	if($result=='ok') $message .= _RSFORM_INSTALLER_DB_OK;
	else $message .= sprintf(_RSFORM_INSTALLER_DB_ERROR,$result);
	
	?>
	<div align="left" width="100%">
 		<img src="../components/com_rsform/images/rsform.gif" alt="RSform!Pro Logo"/>
 	</div>
 	<br/>
	<table class="adminform">
		<tr>
			<td align="left">
			<?php echo _RSFORM_INSTALLER_WELCOME;?>
			</td>
		</tr>
	</table><br/>
	<table class="adminform">
		<tr>
			<td align="left">
			<?php echo $message;?>
			</td>
		</tr>
	</table><br/><br/>
	<div align="left" width="100%"><b>RSform!Pro 1.0.0 Installed</b></div>
<?php	
	}
?>