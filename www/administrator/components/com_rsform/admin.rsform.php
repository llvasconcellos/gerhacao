<?php
/**
* @version 1.0.1
* @package RSform!Pro 1.0.1
* @copyright (C) 2007-2008 www.rsjoomla.com
* @license Commercial License, http://www.rsjoomla.com/license/rsformpro.html
*/
ini_set('max_execution_time','300');
require_once(dirname(__FILE__).'/../../../components/com_rsform/controller/adapter.php');

//create the RSadapter
$GLOBALS['RSadapter'] = new RSadapter();
$RSadapter = $GLOBALS['RSadapter'];

//$RSadapter = $GLOBALS['RSadapter'];


//require classes
require_once(_RSFORM_BACKEND_ABS_PATH.'/admin.rsform.html.php');
require_once(_RSFORM_FRONTEND_ABS_PATH.'/rsform.class.php');

//require controller
require_once(_RSFORM_FRONTEND_ABS_PATH.'/controller/functions.php');

//require backend language file
require_once(_RSFORM_FRONTEND_ABS_PATH.'/languages/'._RSFORM_BACKEND_LANGUAGE.'.php');

//get task
$task           = $RSadapter->getParam($_REQUEST, 'task');
// get form id
$formId 		= $RSadapter->getParam($_REQUEST, 'formId');
 /*
$cid 	= mosGetParam($_REQUEST, 'cid', array());


$layout= mosGetParam($_GET, 'layout', null);

$limit 			= intval( mosGetParam( $_REQUEST, 'limit', 15 ) );
$limitstart 	= intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );
*/

switch($task)
{
	case 'richtext.show':
		richtextShow();
	break;
	
//FORMS
    case 'forms.manage':
        formsManage();
    break;

    case 'forms.edit':
        formsEdit($formId);
    break;

    case 'forms.cancel':
        formsCancel($option);
    break;

    case 'forms.save':
        formsSave($option, 0);
    break;

    case 'forms.apply':
        formsSave($option, 1);
    break;

    case 'forms.delete':
        formsDelete($option);
    break;

    case 'forms.copy':
    	formsCopy($option);
    break;

	case "forms.publish":
		formsPublish( $option, 1);
	break;

	case "forms.unpublish":
		formsPublish( $option, 0 );
	break;

	case "forms.preview":
		formsPreview( $option );
	break;

	case "forms.menuadd.screen":
		formsMenuaddScreen( $option );
	break;

	case "forms.menuadd.process":
		formsMenuaddProcess( $option );
	break;

    case 'forms.changeAutoGenerateLayout':
        formsChangeAutoGenerateLayout($option, $formId);
        exit();
    break;

//COMPONENTS

	case 'components.validate.name':
		componentsValidateName($option);
		exit();
	break;

	case 'components.display':
		componentsDisplay($option);
		exit();
	break;

	case 'components.movedown':
		componentsMoveDown($option);
	break;

	case 'components.moveup':
		componentsMoveUp($option);
	break;

	case 'components.copy.screen':
		componentsCopyScreen($option);
	break;

	case 'components.copy.process':
		componentsCopyProcess($option);
	break;

	case 'components.cancel':
		componentsCancel($option);
	break;

	case 'components.changestatus':
		componentsChangeStatus($option);
		exit();
	break;

	case 'components.remove':
		componentsRemove($option);
		exit();
	break;

//LAYOUTS
	case 'layouts.generate':
		layoutsGenerate($option, $formId);
		exit();
	break;

	case 'layouts.saveLayoutName':
		layoutsSaveName($formId);
		exit();
	break;
//SUBMISSIONS
	case 'submissions.manage':
		submissionsManage($option, $formId);
	break;
	case 'submissions.edit':
		submissionsEdit($option, $formId);
	break;
	case 'submissions.delete':
		submissionsDelete($option);
	break;
	case 'submissions.delete.all':
		submissionsDelete($option,-1);
	break;
	case 'submissions.export':
		submissionsExport($option);
	break;
	case 'submissions.export.process':
		submissionsExportProcess($option);
	break;

//CONFIGURATION
	case 'configuration.save':
		configurationSave($option);
	break;

	case 'configuration.edit':
		configurationEdit($option);
	break;

//BACKUP/RESTORE
	case 'backup.restore':
		backupRestore($option);
	break;

	case 'backup.download':
		backupDownload($option);
	break;
	
//MIGRATION
	case 'migration.process':
		migrationProcess($option);
	break;
	

//UPDATE
	case 'updates.manage':
		updatesManage($option);
	break;

	case 'update.upload.process':
		updateUploadProcess($option);
	break;

//CONTROL PANEL
    case 'saveRegistration':
        saveRegistration($option);
    break;

	default:
		rsform_HTML::controlPanel();
	break;
}

function richtextShow()
{
	$RSadapter = $GLOBALS['RSadapter'];
	$formId = $RSadapter->getParam($_GET,'formId');
	$openerId = $RSadapter->getParam($_GET, 'openerId');
	
	$additionalHTML = '
	<script language="javascript">
		window.opener.document.getElementById(\''.$openerId.'\').innerHTML = document.getElementById(\''.$openerId.'\').value;
	</script>
	
	';
	
	if(isset($_POST['act']))
	{
		switch($_POST['act'])
		{
			case 'save':
			default:
				$query = "UPDATE `{$RSadapter->tbl_rsform_forms}` SET `$openerId` = '{$_POST[$openerId]}' WHERE FormId = '$formId'";
				mysql_query($query) or die(mysql_error());
			break;
			
			case 'saveclose':
				$query = "UPDATE `{$RSadapter->tbl_rsform_forms}` SET `$openerId` = '{$_POST[$openerId]}' WHERE FormId = '$formId'";
				mysql_query($query) or die(mysql_error());
				$additionalHTML .= '
				<script language="javascript">
					window.close();
				</script>
				';
			break;
			
		}
	}
	
	//get value
	$rez = mysql_query("SELECT $openerId FROM `{$RSadapter->tbl_rsform_forms}` WHERE FormId = '$formId'");
	$r = mysql_fetch_assoc($rez);
	
	rsform_HTML::richtextShow($formId, $openerId, $r[$openerId], $additionalHTML);
}
//////////////////////////////////////// FORMS ////////////////////////////////////////
/**
* @desc Forms Manager Screen
*/
function formsManage()
{
    $RSadapter = $GLOBALS['RSadapter'];

    $q="select * from `{$RSadapter->tbl_rsform_forms}`";
    $rez = mysql_query($q) or die(mysql_error());

    $i = 0;
    $rows = array();
    while($r=mysql_fetch_assoc($rez)){
        //build today, this month, this year
        $q = "SELECT count(*) cnt FROM `{$RSadapter->tbl_rsform_submissions}` WHERE date_format(DateSubmitted,'%Y-%m-%d') = '".date('Y-m-d')."' AND FormId='{$r['FormId']}'";

        $rez1 = mysql_query($q) or die(mysql_error());
        $cnt = mysql_fetch_assoc($rez1);
        $r['_todaySubmissions'] = $cnt['cnt'];

        $q = "SELECT count(*) cnt FROM `{$RSadapter->tbl_rsform_submissions}` WHERE date_format(DateSubmitted,'%Y-%m') = '".date('Y-m')."' AND FormId='{$r['FormId']}'";
        $rez1 = mysql_query($q) or die(mysql_error());
        $cnt = mysql_fetch_assoc($rez1);
        $r['_monthSubmissions']=$cnt['cnt'];

        $q = "SELECT count(*) cnt FROM `{$RSadapter->tbl_rsform_submissions}` WHERE FormId='{$r['FormId']}'";
        $rez1 = mysql_query($q) or die(mysql_error());
        $cnt = mysql_fetch_assoc($rez1);
        $r['_allSubmissions']=$cnt['cnt'];

        $rows[$i] = $r;
        $i++;
    }
    rsform_HTML::formsManage($rows);
}

/**
 * Forms Publish/Unpublish Process
 *
 * @param str $option
 * @param int $publishform
 */
function formsPublish( $option, $publishform=1 )
{

	$RSadapter = $GLOBALS['RSadapter'];

  	$formIds = $RSadapter->getParam($_POST,'cid');

  	$total = count ( $formIds );
  	$cids = implode( ',', $formIds );

  	$query = mysql_query("UPDATE $RSadapter->tbl_rsform_forms SET Published = '".intval($publishform)."' WHERE FormId IN ($cids)");

    switch ( $publishform ) {
		case 1:
			$msg = $total ._RSFORM_BACKEND_SUC_PUBL_FORM." ";
		break;
		case 0:
		default:
			$msg = $total ._RSFORM_BACKEND_SUC_UNPUBL_FORM." ";
		break;
	}

	$RSadapter->redirect( _RSFORM_BACKEND_SCRIPT_PATH.'?option='.$option.'&task=forms.manage', $msg );

}

/**
 * Forms Menu Add Screen
 *
 * @param str $option
 */
function formsMenuaddScreen($option)
{
	$RSadapter = $GLOBALS['RSadapter'];

	$formId = $RSadapter->getParam($_REQUEST,'formId');

	//get form title
	$q = "SELECT FormTitle FROM `$RSadapter->tbl_rsform_forms` WHERE FormId = '$formId'";
	$rez = mysql_query($q) or die($q.mysql_error());
	$r = mysql_fetch_assoc($rez);
	$formTitle = stripslashes($r['FormTitle']);

	$menus = $RSadapter->getMenus();

	rsform_HTML::formsMenuaddScreen($option, $menus, $formId, $formTitle);
}

/**
 * Forms Menu Add Process
 *
 * @param str $option
 */
function formsMenuaddProcess($option)
{
	$RSadapter = $GLOBALS['RSadapter'];

	$formId = $RSadapter->getParam($_REQUEST,'formId');
	$menu = $RSadapter->getParam($_REQUEST,'menu');
	$menuTitle = $RSadapter->getParam($_REQUEST,'menutitle');

	//get form title
	$q = "SELECT FormTitle FROM `$RSadapter->tbl_rsform_forms` WHERE FormId = '$formId'";
	$rez = mysql_query($q) or die($q.mysql_error());
	$r = mysql_fetch_assoc($rez);
	$formTitle = stripslashes($r['FormTitle']);

	//insert
	$RSadapter->addMenu($formId, $menuTitle, $menu);

	$RSadapter->redirect( _RSFORM_BACKEND_SCRIPT_PATH.'?option='.$option.'&task=forms.manage', _RSFORM_BACKEND_FORMS_MENUADD_ADDED );
}
/**
 * Forms Preview Process
 *
 * @param str $option
 */
function formsPreview($option)
{
	$RSadapter = $GLOBALS['RSadapter'];

	$formId = $RSadapter->getParam($_REQUEST,'formId');

	?>
	<script language="javascript">
		window.open('<?php echo _RSFORM_FRONTEND_SCRIPT_PATH.'/index.php?option='.$option.'&formId='.$formId;?>');
		document.location='<?php echo _RSFORM_BACKEND_SCRIPT_PATH.'?option='.$option.'&task=forms.edit&formId='.$formId;?>';
	</script>
	<?php
}

/**
 * Forms Copy Process
 */
function formsCopy($option)
{
	$RSadapter = $GLOBALS['RSadapter'];
	$formIds = $RSadapter->getParam($_POST,'cid');

	if(!empty($formIds))
	{
		foreach($formIds as $formId)
		{
			RScopyForm($formId);
		}
	}
	$msg = count($formIds) ._RSFORM_BACKEND_FORMS_COPY." ";
	$RSadapter->redirect( _RSFORM_BACKEND_SCRIPT_PATH.'?option='. $option .'&task=forms.manage', $msg );
}
/**
 * Forms Delete Process
 *
 * @param str $option
 */
function formsDelete($option)
{
	$RSadapter = $GLOBALS['RSadapter'];

	$formIds = $RSadapter->getParam($_POST,'cid');

	if(!empty($formIds))
	{
		foreach($formIds as $formId)
		{
			//Delete Submissions
			$query = mysql_query("SELECT SubmissionId FROM $RSadapter->tbl_rsform_submissions WHERE FormId = '$formId'");
			while($r = mysql_fetch_assoc($query))
			{
				$queryDelete = mysql_query("DELETE FROM $RSadapter->tbl_rsform_submission_values WHERE SubmissionId = '{$r['SubmissionId']}'");
				$queryDelete = mysql_query("DELETE FROM $RSadapter->tbl_rsform_submissions WHERE SubmissionId = '{$r['SubmissionId']}'");
			}

			//Delete Components
			$query = mysql_query("SELECT ComponentId FROM $RSadapter->tbl_rsform_components WHERE FormId = '$formId'");
			while($r = mysql_fetch_assoc($query))
			{
				$queryDelete = mysql_query("DELETE FROM $RSadapter->tbl_rsform_properties WHERE ComponentId = '{$r['ComponentId']}'");
				$queryDelete = mysql_query("DELETE FROM $RSadapter->tbl_rsform_components WHERE ComponentId = '{$r['ComponentId']}'");
			}

			//Delete Forms
			$queryDelete = mysql_query("DELETE FROM $RSadapter->tbl_rsform_forms WHERE FormId = '{$formId}'");
		}
	}
	$msg = $total ._RSFORM_BACKEND_FORMS_DEL." ";
	$RSadapter->redirect( _RSFORM_BACKEND_SCRIPT_PATH.'?option='. $option .'&task=forms.manage', $msg );
}

/**
 * Forms Edit Screen
 *
 * @param int $formId
 */
function formsEdit($formId)
{
	$RSadapter = $GLOBALS['RSadapter'];
    global $option;

    if(isset($_POST['ordering']))
    {
        $formId=$_POST['formId'];
        $order=$_POST['ordering'];
        asort($order);
        $i=1;
        foreach($order as $key=>$val)
        {
            $val=$i++;
            $q="update `{$RSadapter->tbl_rsform_components}` set `Order`='$val' where ComponentId='$key'";
            mysql_query($q) or die("$q<br>".mysql_error());
        }
    }
    if(isset($_GET['formId']))
    {
        $formId=$_GET['formId'];
    }
    else if (!isset($_POST['formId']))
    {



        $q="insert into `{$RSadapter->tbl_rsform_forms}` (`FormName`,`FormTitle`,`FormLayout`,`FormLayoutName`,`FormLayoutAutogenerate`) values('"._RSFORM_BACKEND_FORMS_EDIT_NO_FORM_NAME."','"._RSFORM_BACKEND_FORMS_EDIT_NO_FORM_TITLE."','','inline','1')";
        mysql_query($q) or die("$q<br>".mysql_error());
        $formId=mysql_insert_id();

        $layout = @include(_RSFORM_BACKEND_ABS_PATH.'/layouts/inline.php');
        $q = "update `{$RSadapter->tbl_rsform_forms}` SET `FormLayout` = '$layout' WHERE FormId = '$formId'";
        mysql_query($q) or die("$q<br>".mysql_error());
    }
    if(isset($_POST['COMPONENTTYPE']))
    {
        if($_POST['componentIdToEdit']!=-1)
        {
            foreach($_POST['param'] as $key=>$val)
            {            	
            	$val=addslashes($val);
                $q="update `{$RSadapter->tbl_rsform_properties}` set PropertyValue='$val' where ComponentId='{$_POST['componentIdToEdit']}' and PropertyName='{$key}'";
                mysql_query($q) or die(mysql_error());
            }
        }
        else
        {
        	$q="select max(`Order`) as MO from `{$RSadapter->tbl_rsform_components}` where FormId='$formId'";
            $rez=mysql_query($q) or die(mysql_error());
            $r=mysql_fetch_assoc($rez);
            $nextOrder=$r['MO']+1;

            $q="insert into `{$RSadapter->tbl_rsform_components}` (FormId,ComponentTypeId,`Order`) values ('$_POST[formId]','$_POST[COMPONENTTYPE]','$nextOrder')";
            mysql_query($q) or die(mysql_error());

            $q="select max(ComponentId) as MCI from `{$RSadapter->tbl_rsform_components}`";
            $rez=mysql_query($q) or die(mysql_error());
            $r=mysql_fetch_assoc($rez);
            $componentId=$r['MCI'];
            $values=$_POST['param'];
            foreach($values as $key=>$value)
            {
            	$value=addslashes($value);
                $q="insert into `{$RSadapter->tbl_rsform_properties}` (ComponentId,PropertyName,PropertyValue) values ('$componentId','$key','$value')";
                mysql_query($q) or die(mysql_error());
            }
        }
        $formId=$_POST['formId'];
    header('Location: '.$_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING']);
    }

    $i=1;
    $q="select ComponentId from `{$RSadapter->tbl_rsform_components}` order by `Order`";
    $rez=mysql_query($q) or die(mysql_error());
    while($r=mysql_fetch_assoc($rez))
    {
        $q1="update `{$RSadapter->tbl_rsform_components}` set `Order`='$i' where FormId='$formId'";
        mysql_query($q) or die(mysql_error());
        $i++;
    }

    $query = mysql_query("SELECT * FROM `{$RSadapter->tbl_rsform_forms}` WHERE FormId='{$formId}'");
    $row = mysql_fetch_assoc($query);
    foreach($row as $key=>$value){
    	$row[$key] = stripslashes($row[$key]);
    }

    rsform_HTML::formsEdit($formId, $row);
}

/**
 * Forms Save Process
 *
 * @param str $option
 * @param int $apply
 */
function formsSave($option,$apply=0)
{
    $RSadapter = $GLOBALS['RSadapter'];

    foreach($_POST as $key=>$value){
    	$row[$key] = addslashes($RSadapter->getParam($_POST,$key));
    }
//    	`FormLayoutAutogenerate`= '{$row['FormLayoutAutogenerate']}',
    $query = mysql_query("
    UPDATE `{$RSadapter->tbl_rsform_forms}` SET
    	`FormName` 				= '{$row['FormName']}',
    	`FormLayout` 			= '{$row['FormLayout']}',
    	`FormTitle`				= '{$row['FormTitle']}',
    	`ReturnUrl`				= '{$row['ReturnUrl']}',
    	`UserEmailTo`			= '{$row['UserEmailTo']}',
    	`UserEmailFrom`			= '{$row['UserEmailFrom']}',
    	`UserEmailFromName`		= '{$row['UserEmailFromName']}',
    	`UserEmailSubject`		= '{$row['UserEmailSubject']}',
    	`UserEmailMode`			= '{$row['UserEmailMode']}',
    	".($row['UserEmailMode'] ? '':"
    	`UserEmailText`			= '{$row['UserEmailText']}',")."
    	".($row['AdminEmailMode'] ? '':"
    	`AdminEmailText`			= '{$row['AdminEmailText']}',")."
    	
    	`AdminEmailTo`			= '{$row['AdminEmailTo']}',
    	`AdminEmailFrom`		= '{$row['AdminEmailFrom']}',
    	`AdminEmailFromName`	= '{$row['AdminEmailFromName']}',
    	`AdminEmailSubject`		= '{$row['AdminEmailSubject']}',
    	`AdminEmailMode`		= '{$row['AdminEmailMode']}',
    	`ScriptProcess`			= '{$row['ScriptProcess']}',
    	`ScriptDisplay`			= '{$row['ScriptDisplay']}'
    WHERE
    	`FormId` 				= '{$row['formId']}';") or die(mysql_error());

    if(!$apply)    $RSadapter->redirect(_RSFORM_BACKEND_SCRIPT_PATH."?option=$option&task=forms.manage", _RSFORM_BACKEND_FORMS_SAVE." ");
    else $RSadapter->redirect(_RSFORM_BACKEND_SCRIPT_PATH."?option=$option&task=forms.edit&formId=".$row['formId'], _RSFORM_BACKEND_FORMS_SAVE." ");

}

/**
 * Closes the form
 *
 * @param str $option
 */
function formsCancel($option)
{
	$RSadapter = $GLOBALS['RSadapter'];

    $RSadapter->redirect(_RSFORM_BACKEND_SCRIPT_PATH."?option=$option&task=forms.manage" );
}
/**
 * Change the AutoGenerate layout
 *
 * @param unknown_type $option
 * @param unknown_type $formId
 * @param unknown_type $formLayoutName
 */
function formsChangeAutoGenerateLayout($option, $formId)
{
	$RSadapter = $GLOBALS['RSadapter'];

	$formLayoutName = $RSadapter->getParam($_GET, 'formLayoutName');
    $q=mysql_query("UPDATE `{$RSadapter->tbl_rsform_forms}` SET `FormLayoutAutogenerate` = ABS(FormLayoutAutogenerate-1), `FormLayoutName`='$formLayoutName' WHERE `FormId` = '$formId'") or die(mysql_error());
}

//////////////////////////////////////// COMPONENTS ////////////////////////////////////////
/**
 * Validates a component name
 *
 * @param str $option
 */
function componentsValidateName($option)
{
	$RSadapter = $GLOBALS['RSadapter'];

	$componentName 		= $RSadapter->getParam($_GET, 'componentName');
	$currentComponentId = $RSadapter->getParam($_GET, 'currentComponentId');
	$componentId		= $RSadapter->getParam($_GET, 'componentId');
	$componentType		= $RSadapter->getParam($_GET, 'componentType');
	$destination		= $RSadapter->getParam($_GET, 'destination');
	$formId				= $RSadapter->getParam($_GET, 'formId');

	$componentName=trim($componentName);
	if (empty($componentName))
	{
		echo _RSFORM_BACKEND_COMPONENTS_VALIDATE_ERROR_UNIQUE_NAME;
		return;
	}
	//on file upload component, check destination
	if($componentType==9)
	{
		if (empty($destination))
		{
			echo _RSFORM_BACKEND_COMPONENTS_VALIDATE_ERROR_DESTINATION;
			return;
		}
		if(!is_dir($destination))
		{
			echo _RSFORM_BACKEND_COMPONENTS_VALIDATE_ERROR_DESTINATION_NOT_DIR;
			return;
		}
		if(!is_writable($destination))
		{
			echo _RSFORM_BACKEND_COMPONENTS_VALIDATE_ERROR_DESTINATION_NOT_WRITABLE;
			return;
		}
	}
	if(!isset($_GET['currentComponentId']))
		$q="select
				`{$RSadapter->tbl_rsform_forms}`.`FormId`,
				`{$RSadapter->tbl_rsform_properties}`.`PropertyName`,
				`{$RSadapter->tbl_rsform_properties}`.`PropertyValue`
			from `{$RSadapter->tbl_rsform_components}`
			left join `{$RSadapter->tbl_rsform_properties}` on `{$RSadapter->tbl_rsform_components}`.`ComponentId`=`{$RSadapter->tbl_rsform_properties}`.`ComponentId`
			left join {$RSadapter->tbl_rsform_forms} on `{$RSadapter->tbl_rsform_components}`.`FormId`=`{$RSadapter->tbl_rsform_forms}`.`FormId`
			where `{$RSadapter->tbl_rsform_forms}`.`FormId`='$_GET[formId]' and `{$RSadapter->tbl_rsform_properties}`.PropertyName='NAME' and `{$RSadapter->tbl_rsform_properties}`.PropertyValue='$_GET[componentName]'";
	else
		$q="select
			{$RSadapter->tbl_rsform_forms}.FormId,
			{$RSadapter->tbl_rsform_properties}.PropertyName,
			{$RSadapter->tbl_rsform_properties}.PropertyValue
			from {$RSadapter->tbl_rsform_components}
			left join `{$RSadapter->tbl_rsform_properties}` on `{$RSadapter->tbl_rsform_components}`.ComponentId={$RSadapter->tbl_rsform_properties}.ComponentId
			left join {$RSadapter->tbl_rsform_forms} on `{$RSadapter->tbl_rsform_components}`.FormId={$RSadapter->tbl_rsform_forms}.FormId
			where {$RSadapter->tbl_rsform_forms}.FormId='$formId' and `{$RSadapter->tbl_rsform_properties}`.PropertyName='NAME' and `{$RSadapter->tbl_rsform_properties}`.PropertyValue='$componentName' and `{$RSadapter->tbl_rsform_components}`.ComponentId!=$_GET[currentComponentId]";

	mysql_query($q) or die(mysql_error());
	if (mysql_affected_rows()!=0) echo _RSFORM_BACKEND_COMPONENTS_VALIDATE_ERROR_UNIQUE_NAME;
	else echo 'Ok';

	exit();
}

/**
 * Displays a component in the backend.
 *
 * @param unknown_type $option
 */
function componentsDisplay($option)
{
	$RSadapter = $GLOBALS['RSadapter'];
	$componentId= $RSadapter->getParam($_GET, 'componentId');
	$componentType= $RSadapter->getParam($_GET, 'componentType');

	$q="select * from `{$RSadapter->tbl_rsform_component_type_fields}` where ComponentTypeId='{$componentType}' order by Ordering";
	$rez=mysql_query($q) or die(mysql_error());
	$data=array();
	$out='';
	if(isset($componentId))
	{
		$data=RSgetComponentProperties($componentId);
	}
	$out.='<table class="componentForm" border="0" cellspacing="0" cellpadding="0">';
	$counter = 0;
	while($r=mysql_fetch_assoc($rez))
	{
		if($counter==2&&mysql_num_rows($rez)>3)
		$out.="<tr><td><input type=\"button\" onclick=\"processComponent($componentType)\" value=\""._RSFORM_BACKEND_COMP_SAVE."\" style=\"float:right;margin-right:20px;\"></td></tr>";
		$out.='<tr>';
		switch($r['FieldType'])
		{
			case 'textbox':
			{

				$out.="<td>".constant('_RSFORM_BACKEND_COMP_FIELD_'.$r['FieldName'])."<br/>";
				if(isset($componentId))
				{
					$val = (defined('_RSFORM_BACKEND_COMP_FVALUE_'.$data[$r['FieldName']]) ? constant('_RSFORM_BACKEND_COMP_FVALUE_'.$data[$r['FieldName']]) : $data[$r['FieldName']]);
					$out.="<input type=\"text\" id=\"$r[FieldName]\" name=\"param[".$r['FieldName']."]\" value=\"".$data[$r['FieldName']]."\" class=\"wide\"></td>";
				}
				else
				{
					$val = (defined('_RSFORM_BACKEND_COMP_FVALUE_'.RSisCode($r['FieldValues'])) ? constant('_RSFORM_BACKEND_COMP_FVALUE_'.RSisCode($r['FieldValues'])) : RSisCode($r['FieldValues']));
					$out.="<input type=\"text\" id=\"$r[FieldName]\" name=\"param[".$r['FieldName']."]\" value=\"".$val."\" class=\"wide\"></td>";
				}
			}break;

			case 'textarea':
			{
				$out.="<td>".constant('_RSFORM_BACKEND_COMP_FIELD_'.$r['FieldName'])."<br/>";

				if(isset($componentId))
				{
					$val = (defined('_RSFORM_BACKEND_COMP_FVALUE_'.$data[$r['FieldName']]) ? constant('_RSFORM_BACKEND_COMP_FVALUE_'.$data[$r['FieldName']]) : $data[$r['FieldName']]);
					$out.="<textarea id=\"$r[FieldName]\" name=\"param[".$r['FieldName']."]\" rows=\"5\" cols=\"20\" class=\"wide\">".$val."</textarea></td>";
				}
				else
				{
					$val = (defined('_RSFORM_BACKEND_COMP_FVALUE_'.RSisCode($r['FieldValues'])) ? constant('_RSFORM_BACKEND_COMP_FVALUE_'.RSisCode($r['FieldValues'])) : RSisCode($r['FieldValues']));
					$out.="<textarea id=\"$r[FieldName]\" name=\"param[".$r['FieldName']."]\" rows=\"5\" cols=\"20\" class=\"wide\">$val</textarea></td>";
				}
			}break;
			case 'select':
			{

				$out.="<td>".constant('_RSFORM_BACKEND_COMP_FIELD_'.$r['FieldName'])."<br/>";
				$out.="<select id=\"$r[FieldName]\" name=\"param[".$r['FieldName']."]\">";
				$r['FieldValues']=str_replace("\r","",$r['FieldValues']);
				$r['FieldValues']=RSisCode($r['FieldValues']);
				$buff=explode("\n",$r['FieldValues']);
				foreach($buff as $val)
				{
					$label = (defined('_RSFORM_BACKEND_COMP_FVALUE_'.$val) ? constant('_RSFORM_BACKEND_COMP_FVALUE_'.$val) : $val);
					if(isset($componentId) && $data[$r['FieldName']]==$val)
					{
						$out.="<option selected=\"selected\" value=\"$val\">$label</option>";
					}
					else $out.="<option value=\"$val\">$label</option>";
				}
				$out.="</select></td>";
			}break;
			case 'hidden':
			{
				$val = (defined('_RSFORM_BACKEND_COMP_FVALUE_'.$r['FieldValues']) ? constant('_RSFORM_BACKEND_COMP_FVALUE_'.$r['FieldValues']) : $r['FieldValues']);
				$out.="";
				$out.="<td><input type=\"hidden\" id=\"$r[FieldName]\" name=\"$r[FieldName]\" value=\"$val\"></td>";
			}break;
		}
		if(isset($componentId)) $out.='<input type="hidden" name="updateComponent">';
		$out.='</tr>';
		$counter ++;
	}
	$out.="<tr><td><input type=\"button\" onclick=\"processComponent($componentType)\" value=\""._RSFORM_BACKEND_COMP_SAVE."\" style=\"float:right;margin-right:20px;\"></td></tr>";
	$out.="<tr><td>&nbsp;</td></tr>";
	$out.="</table>";

	echo $out;
}

/**
 * Moves the component up
 *
 * @param str $option
 */
function componentsMoveUp($option)
{
	$RSadapter = $GLOBALS['RSadapter'];
	$componentId= $RSadapter->getParam($_GET, 'componentId');
	$formId= $RSadapter->getParam($_GET, 'formId');

	$q="select `Order` from `{$RSadapter->tbl_rsform_components}` where FormId='{$formId}' and ComponentId='{$componentId}'";
	$rez=mysql_query($q) or die(mysql_error());
	$r=mysql_fetch_assoc($rez);

	$order=$r['Order'];
	if($order>1)
	{
		$order-=1;

		$q="select ComponentId from `{$RSadapter->tbl_rsform_components}` where FormId='{$formId}' and `Order`='$order'";
		$rez=mysql_query($q) or die(mysql_error());
		$r=mysql_fetch_assoc($rez);

		$id=$r['ComponentId'];

		$q="update `{$RSadapter->tbl_rsform_components}` set `Order`=`Order`-1 where ComponentId='{$componentId}' and FormId='{$formId}'";
		mysql_query($q) or die(mysql_error());
		$q="update `{$RSadapter->tbl_rsform_components}` set `Order`=`Order`+1 where ComponentId='$id' and FormId='{$formId}'";
		mysql_query($q) or die(mysql_error());
	}
}

/**
 * Moves the component down
 *
 * @param str $option
 */
function componentsMoveDown($option)
{
	$RSadapter = $GLOBALS['RSadapter'];
	$componentId= $RSadapter->getParam($_GET, 'componentId');
	$formId= $RSadapter->getParam($_GET, 'formId');

	$q="select count(ComponentId) as number from `{$RSadapter->tbl_rsform_components}` where FormId='{$formId}'";
	$rez=mysql_query($q) or die(mysql_error());
	$r=mysql_fetch_assoc($rez);
	$max=$r['number'];

	$q="select `Order` from `{$RSadapter->tbl_rsform_components}` where FormId='{$formId}' and ComponentId='{$componentId}'";
	$rez=mysql_query($q) or die(mysql_error());
	$r=mysql_fetch_assoc($rez);

	$order=$r['Order'];
	if($order<$max)
	{
		$order+=1;

		$q="select ComponentId from `{$RSadapter->tbl_rsform_components}` where FormId='{$formId}' and `Order`='$order'";
		$rez=mysql_query($q) or die(mysql_error());
		$r=mysql_fetch_assoc($rez);

		$id=$r['ComponentId'];

		$q="update `{$RSadapter->tbl_rsform_components}` set `Order`=`Order`+1 where ComponentId='{$componentId}' and FormId='{$formId}'";
		mysql_query($q) or die(mysql_error());
		$q="update `{$RSadapter->tbl_rsform_components}` set `Order`=`Order`-1 where ComponentId='$id' and FormId='{$formId}'";
		mysql_query($q) or die(mysql_error());
	}
}

/**
 * Components Cancel
 *
 * @param str $option
 */
function componentsCancel($option)
{
	$RSadapter = $GLOBALS['RSadapter'];

	$formId= $RSadapter->getParam($_POST, 'formId');

	$RSadapter->redirect(_RSFORM_BACKEND_SCRIPT_PATH.'?option='.$option.'&task=forms.edit&formId='.$formId);

}
/**
 * Components Copy Process
 *
 * @param str $option
 */
function componentsCopyProcess($option)
{
	$RSadapter = $GLOBALS['RSadapter'];
	$formId= $RSadapter->getParam($_POST, 'formId');
	$toFormId= $RSadapter->getParam($_POST, 'toFormId', 0);
	$componentsToCopy = $RSadapter->getParam($_POST, 'componentId', array());

	if($toFormId)
	{
		if(!empty($componentsToCopy))
		{
			foreach($componentsToCopy as $componentToCopyId)
			{
				RScopyComponent($componentToCopyId,$toFormId);
			}
		}
	}

	$RSadapter->redirect(_RSFORM_BACKEND_SCRIPT_PATH.'?option='.$option.'&task=forms.edit&formId='.$toFormId,_RSFORM_BACKEND_COMPONENTS_COPY_OK);
}
/**
 * Components Copy Screen
 *
 * @param str $option
 */
function componentsCopyScreen($option)
{
	$RSadapter = $GLOBALS['RSadapter'];
	$formId= $RSadapter->getParam($_REQUEST, 'formId');
	$components = $RSadapter->getParam($_REQUEST,'checks',array());
	//load all forms
	$q = "SELECT FormId, FormTitle FROM `{$RSadapter->tbl_rsform_forms}`";
	$rez = mysql_query($q) or die($q.mysql_error());

	$forms = array();
	while($r = mysql_fetch_array($rez))
	{
		$forms[$r['FormId']] = stripslashes($r['FormTitle']);
	}
	rsform_HTML::componentsCopyScreen($option, $forms, $components, $formId);
}

/**
 * Publish / Unpublish a component
 *
 * @param str $option
 */
function componentsChangeStatus($option)
{
	$RSadapter = $GLOBALS['RSadapter'];
	$componentId= $RSadapter->getParam($_GET, 'componentId');

	//get current status
	$q="select `Published` from `{$RSadapter->tbl_rsform_components}` where ComponentId='$componentId'";
	$rez=mysql_query($q) or die(mysql_error());
	$r=mysql_fetch_assoc($rez);

	$currentStatus = $r['Published'];
	$newStatus = ($currentStatus) ? 0:1;
	$q="UPDATE `{$RSadapter->tbl_rsform_components}` SET published = '$newStatus' WHERE ComponentId='$componentId'";
	mysql_query($q) or die(mysql_error());
}
/**
 * Remove Component
 *
 * @param str $option
 */
function componentsRemove($option)
{
	$RSadapter = $GLOBALS['RSadapter'];
	$componentId= $RSadapter->getParam($_GET, 'componentId');
	$formId= $RSadapter->getParam($_GET, 'formId');

	$q="delete from `{$RSadapter->tbl_rsform_components}` where ComponentId='$componentId'";
	mysql_query($q) or die(mysql_error());
	$q="delete from `{$RSadapter->tbl_rsform_properties}` where ComponentId='$componentId'";
	mysql_query($q) or die(mysql_error());
	$q="select ComponentId from `{$RSadapter->tbl_rsform_components}` where FormId='$formId' order by `Order`";
	$rez=mysql_query($q) or die(mysql_error());
	$i=1;
	while($r=mysql_fetch_assoc($rez))
	{
		mysql_query("update `{$RSadapter->tbl_rsform_components}` set `Order`='$i' where ComponentId='$r[ComponentId]'") or die(mysql_error());
		$i++;
	}
}



//////////////////////////////////////// LAYOUTS ////////////////////////////////////////


function layoutsGenerate($option, $formId)
{
	$RSadapter = $GLOBALS['RSadapter'];
	$layout = $RSadapter->getParam($_GET,'layout');

	require_once(_RSFORM_BACKEND_ABS_PATH.'/layouts/'.$layout.'.php');
}

function layoutsSaveName($formId)
{
	$RSadapter = $GLOBALS['RSadapter'];

	$formLayoutName = $RSadapter->getParam($_GET,'formLayoutName');

	$q="update {$RSadapter->tbl_rsform_forms} set FormLayoutName='$formLayoutName' where FormId='$formId'";
	mysql_query($q) or die(mysql_error());
}



//////////////////////////////////////// SUBMISSIONS ////////////////////////////////////////
/**
 * Submissions Manager Screen
 *
 * @param str $option
 * @param int $formId
 */
function submissionsManage($option, $formId)
{
	$RSadapter = $GLOBALS['RSadapter'];

	if(!$formId){
		//get the first form
		$query = mysql_query("SELECT FormId FROM {$RSadapter->tbl_rsform_forms} WHERE published=1 order by FormId LIMIT 1") or die(mysql_error());
		$rez = mysql_fetch_assoc($query);

		$formId = $rez['FormId'];
		if($formId)	$RSadapter->redirect(_RSFORM_BACKEND_SCRIPT_PATH.'?option='.$option.'&task=submissions.manage&formId='.$formId);
	}

	$data = new SManager($formId);
	$data->rows = $RSadapter->config['list_limit'];

	//load forms
	$forms = array();
	$query = mysql_query("SELECT FormId, FormName FROM {$RSadapter->tbl_rsform_forms} order by FormId");
	while($formRow = mysql_fetch_array($query)){
		$forms[$formRow['FormId']] = $formRow['FormName'];
	}
	rsform_HTML::submissionsManage($option, $data, $forms);
}
/**
 * Edits one submission
 *
 * @param str $option
 * @param int $formId
 */
function submissionsEdit($option, $formId)
{
	$RSadapter = $GLOBALS['RSadapter'];

	$data = new SManager($formId);
	$order = 0;
	if (isset($_GET['order']))
	{
		if ($_GET['order'] == "asc") { $order = 1; }
	}
	$id = 0;
	if (isset($_GET['id']))
	{
		if ($_GET['id'] > 0 ) { $id = $_GET['id']; }
	}
	$sort_id = 0;
	if (isset($_GET['sort_id']))
	{
		if ($_GET['sort_id'] > 0 ) { $sort_id = $_GET['sort_id']; }
	}
	$filter = "";
	if (isset($_GET['filter']))
	{
		if (strlen($_GET['filter']) > 0) { $filter = $_GET['filter']; }
	}
	$page = 1;
	if (isset($_GET['page']))
	{
		if ($_GET['page'] > 1) { $page = $_GET['page']; }
	}
	$data->rows = 5;
	if (isset($_GET['limit']))
	{
		$data->rows = $_GET['limit'];
	}

	$data->setFilter($filter);
	$data->current = $page;

	if(!isset($_GET['action'])) $_GET['action'] = '';
	switch($_GET['action']){
		case 'edit':
			$data->setValue($_GET['SubmissionId'], $_GET['SubmissionValueId'], $_POST['value'], $_GET['fieldName']);
			exit();
		break;
		case 'remove':
			$data->setOrder($sort_id, $order);
			$data->deleteRow($id);
			rsform_HTML::submissionsTable($option, $data);
			exit();
		break;
		case 'sort':
			$data->setOrder($sort_id, $order);
			rsform_HTML::submissionsTable($option, $data);
			exit();
		break;
		case 'filter':
			$data->setOrder($sort_id, $order);
			rsform_HTML::submissionsTable($option, $data);
			exit();
		break;
		case 'page':
			$data->setOrder($sort_id, $order);
			rsform_HTML::submissionsTable($option, $data);
			exit();
		break;
		case 'pager':
			$data->setOrder($sort_id, $order);
			$data->pager($page, $filter);
			exit();
		break;
		case 'exportall':
			$data->setOrder($sort_id, $order);
			$data->exportAll($page, $filter);
			exit();
		break;
	}

}

/**
 * Deletes submissions
 *
 * @param str $option
 * @param int $all
 */
function submissionsDelete($option, $all=1)
{
	$RSadapter = $GLOBALS['RSadapter'];

	$formId 		= $RSadapter->getParam($_REQUEST, 'formId');
	$submissionIds 	= $RSadapter->getParam($_POST, 'checks');

	//delete submissionIds
	if($all!=-1)
	{
		if(!empty($submissionIds))
		{
			$query = mysql_query("DELETE FROM {$RSadapter->tbl_rsform_submissions} WHERE `SubmissionId` IN (".implode(',',$submissionIds).")");
			$query = mysql_query("DELETE FROM {$RSadapter->tbl_rsform_submission_values} WHERE `SubmissionId` IN (".implode(',',$submissionIds).")");
		}
	}
	else
	{
		$query = mysql_query("SELECT SubmissionId FROM {$RSadapter->tbl_rsform_submissions} WHERE `FormId` = '$formId'");
		while($SubmissionId = mysql_fetch_array($query)){
			$querySV = mysql_query("DELETE FROM {$RSadapter->tbl_rsform_submission_values} WHERE `SubmissionId` = '{$SubmissionId['SubmissionId']}'");
		}
		$query = mysql_query("DELETE FROM {$RSadapter->tbl_rsform_submissions} WHERE `FormId` = '$formId'");
	}
	$RSadapter->redirect(_RSFORM_BACKEND_SCRIPT_PATH.'?option='.$option.'&task=submissions.manage&formId='.$formId);
}

/**
 * Export Submissions Screen
 *
 * @param str $option
 */
function submissionsExport($option)
{
	$RSadapter = $GLOBALS['RSadapter'];

	$formId 		= $RSadapter->getParam($_REQUEST, 'formId');
	$submissionIds 	= $RSadapter->getParam($_POST, 'checks');

	//load form Name
	$query = mysql_query("SELECT FormName FROM {$RSadapter->tbl_rsform_forms} WHERE FormId = '$formId'");
	$rez = mysql_fetch_assoc($query);
	$formName = $rez['FormName'];

	//load components
	$formComponents = array();
	$query = mysql_query("SELECT `ComponentId`, `Order` FROM `{$RSadapter->tbl_rsform_components}` WHERE `FormId` = '$formId' AND `Published` = 1 ORDER BY `Order`");
	while($componentRow = mysql_fetch_array($query))
	{
		$componentProperties=RSgetComponentProperties($componentRow['ComponentId']);

		$formComponents[$componentRow['ComponentId']] = array
		(
			'ComponentName'=>$componentProperties['NAME'],
			'Order'=>$componentRow['Order']
		);
	}
	rsform_HTML::submissionsExport($option, $formId, $submissionIds, $formName, $formComponents);
}

/**
 * Submissions Export Process
 *
 * @param str $option
 */
function submissionsExportProcess($option)
{
	$RSadapter = $GLOBALS['RSadapter'];

	//get export parameters
	$formId 				= $RSadapter->getParam($_POST,'formId');
	$data = new SManager($formId);
	$order = 0;
	if (isset($_GET['order']))
	{
		if ($_GET['order'] == "asc") { $order = 1; }
	}
	$id = 0;
	if (isset($_GET['id']))
	{
		if ($_GET['id'] > 0 ) { $id = $_GET['id']; }
	}
	$sort_id = 0;
	if (isset($_GET['sort_id']))
	{
		if ($_GET['sort_id'] > 0 ) { $sort_id = $_GET['sort_id']; }
	}
	$filter = "";
	if (isset($_POST['filter']))
	{
		if (strlen($_POST['filter']) > 0) { $filter = $_POST['filter']; }
	}
	$page = 1;
	if (isset($_GET['page']))
	{
		if ($_GET['page'] > 1) { $page = $_GET['page']; }
	}



	$data->setFilter($filter);


	$data->submissionIds 		= $RSadapter->getParam($_POST,'ExportRows', 0);
	$data->exportHeaders		= $RSadapter->getParam($_POST,'ExportHeaders',0);
	$data->exportDelimiter		= (isset($_POST['ExportDelimiter']) ? stripslashes($_POST['ExportDelimiter']):'');
	$data->exportDelimiter		= str_replace(array('\t','\n','\r'),array("\t","\n","\r"),$data->exportDelimiter);
	$data->exportFieldEnclosure	= (isset($_POST['ExportFieldEnclosure']) ? stripslashes($_POST['ExportFieldEnclosure']):'');
	$data->exportSubmission		= $RSadapter->getParam($_POST,'ExportSubmission');
	$data->exportOrder			= $RSadapter->getParam($_POST,'ExportOrder');
	$data->exportRowsOrder		= $RSadapter->getParam($_POST,'ExportRowsOrder');
	$data->exportRowsOrderDirection= $RSadapter->getParam($_POST,'ExportRowsOrderDirection');
	$data->exportComponent		= $RSadapter->getParam($_POST,'ExportComponent');

	if(strtolower($data->exportRowsOrderDirection) == 'asc') $data->exportRowsOrderDirection=1;
	else $data->exportRowsOrderDirection=0;

	$data->setOrder($data->exportRowsOrder,$data->exportRowsOrderDirection);
	
	$output = $data->createExportFile();

}

//////////////////////////////////////// CONFIGURATION ////////////////////////////////////////
/**
 * Saves registration form
 *
 * @param str $option
 */
function saveRegistration($option){

	$RSadapter = $GLOBALS['RSadapter'];

	$rsformConfigPost = $RSadapter->getParam($_POST,'rsformConfig');

	if(!isset($rsformConfigPost['global.register.code']))$rsformConfigPost['global.register.code']='';

	if($rsformConfigPost['global.register.code']=='') $RSadapter->redirect(_RSFORM_BACKEND_SCRIPT_PATH.'?option='.$option,_RSFORM_BACKEND_SAVEREG_CODE);

	$query = mysql_query("UPDATE `{$RSadapter->tbl_rsform_config}` SET SettingValue = '".trim($rsformConfigPost['global.register.code'])."' WHERE SettingName = 'global.register.code'") or die(mysql_error());

	$RSadapter->redirect(_RSFORM_BACKEND_SCRIPT_PATH.'?option='.$option.'&task=updates.manage',_RSFORM_BACKEND_SAVEREG_SAVED);
}
/**
 * Configuration Edit Screen
 *
 * @param str $option
 */
function configurationEdit($option){
	$RSadapter = $GLOBALS['RSadapter'];
	rsform_HTML::configurationEdit($option);
}
/**
 * Configuration Save process
 *
 * @param str $option
 */
function configurationSave($option){
	$RSadapter = $GLOBALS['RSadapter'];

	$rsformConfig = $RSadapter->getParam($_POST,'rsformConfig',array());
	$languageFile = $RSadapter->getParam($_POST,'languageFile',array());

	foreach($rsformConfig as $setting_name=>$setting_value){
		$query = mysql_query("UPDATE `{$RSadapter->tbl_rsform_config}` SET SettingValue = '$setting_value' WHERE SettingName = '$setting_name'") or die(mysql_error());
	}

	if(!empty($languageFile)){
		foreach($languageFile as $file=>$content){

			$filename = _RSFORM_FRONTEND_ABS_PATH.'/languages/'.$file;
			if ( $fp = fopen ($filename, 'wb') ) {
				fputs( $fp, stripslashes( $content ) );
				fclose( $fp );
			}
		}
	}
	$RSadapter->redirect(_RSFORM_BACKEND_SCRIPT_PATH.'?option='.$option.'&task=configuration.edit',_RSFORM_BACKEND_CONFIGURATION_SAVED);
}


//////////////////////////////////////// MIGRATION ////////////////////////////////////////

function migrationProcess($option)
{
	$RSadapter = $GLOBALS['RSadapter'];
	
	//cicle forms
	$formCount = 0;
	$query = mysql_query("SELECT * FROM ".$RSadapter->config['dbprefix']."forme_forms");
	while ($form = mysql_fetch_array($query))
	{
		$formCount ++;
		
		foreach($form as $key=>$value){
			$form[$key] = mysql_real_escape_string(stripslashes($value));
		}
		
		//TODO 
		
		$formQuery = mysql_query("INSERT INTO `{$RSadapter->tbl_rsform_forms}` 
		(`FormId`, `FormName`, `FormLayout`, `FormLayoutName`, `FormLayoutAutogenerate`, `FormTitle`, `Published`, `Lang`, `ReturnUrl`, `Thankyou`, `UserEmailText`, `UserEmailTo`, `UserEmailFrom`, `UserEmailFromName`, `UserEmailSubject`, `UserEmailMode`) VALUES		
		('', '{$form['name']}', '".$formLayout."', 'inline', 0, '{$form['title']}', '{$form['published']}', '', '{$form['return_url']}', '{$form['thankyou']}', '{$form['email']}', '{$form['emailto']}', '{$form['emailfrom']}', '{$form['emailfromname']}', '{$form['emailsubject']}', '{$form['emailmode']}')") or die(mysql_error());
		
		$formId = mysql_insert_id();
		$newform = $form;
		$formLayout = '';
		$enctype = '';
		
		$fieldsQuery = mysql_query("SELECT * FROM ".$RSadapter->config['dbprefix']."forme_fields WHERE form_id = '".$form['id']."' order by ordering") or die(mysql_error());
		while($field = mysql_fetch_assoc($fieldsQuery))
		{
			foreach($field as $key=>$value){
				$field[$key] = mysql_real_escape_string(stripslashes($value));
			}
			
			if($field['fieldstyle']=='') $field['fieldstyle'] = $form['fieldstyle'];
			
			$inputtype = RSmigrationGetComponentTypeId($field['inputtype']);
			if($inputtype == 9) $enctype = ' enctype="multipart/form-data"';
			
			$componentsQuery = mysql_query("INSERT INTO `{$RSadapter->tbl_rsform_components}` 
			(`ComponentId`, `FormId`, `ComponentTypeId`, `Order`, `Published`) VALUES
			('', $formId, ".$inputtype.", '{$field['ordering']}', '{$field['published']}')") or die(mysql_error());
						
			$componentId = mysql_insert_id();
			
			$propertiesQuery = mysql_query("INSERT INTO `{$RSadapter->tbl_rsform_properties}` 
			(`PropertyId`, `ComponentId`, `PropertyName`, `PropertyValue`) VALUES 
			('', '$componentId', 'NAME', '".$field['name']."'),
			('', '$componentId', 'CAPTION', '".$field['title']."'),
			('', '$componentId', 'DESCRIPTION', '".$field['description']."'),
			".RSmigrationParseAdditionalAttributes($inputtype, $field['params'], $componentId)."
			".RSmigrationParseDefaultValue($inputtype, $field['default_value'], $componentId)."
			".(($field['validation_rule']!='mandatory') ? 
			"('', '$componentId', 'VALIDATIONRULE', '".$field['validation_rule']."'),
			('', '$componentId', 'REQUIRED', 'YES'),
			" : "('', '$componentId', 'REQUIRED', 'YES'),").
			
			"('', '$componentId', 'VALIDATIONMESSAGE', '".$field['validation_message']."')") or die(mysql_error());
			
			
			$field['fieldstyle'] = str_replace('{fieldtitle}','{'.$field['name'].':caption}',$field['fieldstyle']);
			$field['fieldstyle'] = str_replace('{field}','{'.$field['name'].':body}'.'<br/>{'.$field['name'].':validation}',$field['fieldstyle']);
			$field['fieldstyle'] = str_replace('{fielddesc}','{'.$field['name'].':description}',$field['fieldstyle']);
			$field['fieldstyle'] = str_replace('{validationsign}',' * ',$field['fieldstyle']);
			
			if($field['published'])  $formLayout .= $field['fieldstyle'];
		
			$newform = RSmigrationChangePlaceholdersValues($field['name'],$newform);
			
			
			
				
		}
		
		$newFormQuery = mysql_query("UPDATE `{$RSadapter->tbl_rsform_forms}` SET
			
			`ReturnUrl` = '{$newform['return_url']}', 
			`Thankyou` = '{$newform['thankyou']}',
			`UserEmailText` = '{$newform['email']}',
			`UserEmailTo` = '{$newform['emailto']}', 
			`UserEmailFrom` = '{$newform['emailfrom']}', 
			`UserEmailFromName` = '{$newform['emailfromname']}', 
			`UserEmailSubject` = '{$form['emailsubject']}'
			
			WHERE FormId = 	$formId");	
		
		
		
		
		//update form layout
		
		$explodes = explode('<form',$form['formstyle']);
		
		$afterForm = $explodes[1];
		$afterForm = explode('>',$afterForm);
		array_shift($afterForm);
		$afterForm = implode('>',$afterForm);
		$form['formstyle'] = $explodes[0].$afterForm;
		
		
		
		//$form['formstyle'] = preg_replace('<form(.*)>','',$form['formstyle']);
		$form['formstyle'] = str_replace('</form>','',$form['formstyle']);
		$form['formstyle'] = str_replace('{formtitle}','{global:formtitle}',$form['formstyle']);
		$form['formstyle'] = str_replace('{formfields}',$formLayout,$form['formstyle']);
		
		$layoutQuery = mysql_query("UPDATE `{$RSadapter->tbl_rsform_forms}` SET FormLayout = '".$form['formstyle']."' WHERE FormId = $formId");
		
		
		//submissions
		$submissionsQuery = mysql_query("SELECT * FROM ".$RSadapter->config['dbprefix']."forme_data WHERE form_id = '".$form['id']."'") or die(mysql_error());
		while($submission = mysql_fetch_array($submissionsQuery))
		{
			$sQuery = mysql_query("INSERT INTO `{$RSadapter->tbl_rsform_submissions}` 
			(`SubmissionId`, `FormId`, `DateSubmitted`, `UserIp`, `Username`, `UserId`) VALUES 
			('','{$formId}','{$submission['date_added']}','{$submission['uip']}','','{$submission['UserId']}')");
			
			$SubmissionId = mysql_insert_id();
			
			$result_explode = explode("||\n",$submission['params']);
			foreach($result_explode as $param_row)
			{
				$param_row = explode('=',$param_row,2);
				if(isset($param_row[1])){
					$result[$param_row[0]] = $param_row[1];
				}else{
					$result[$param_row[0]] = '';
				}
			}
			
			if(!empty($result))
			{
				foreach($result as $key=>$value)
				{
					$svQuery = mysql_query("INSERT INTO `{$RSadapter->tbl_rsform_submission_values}` (`SubmissionId`,`FieldName`,`FieldValue`) VALUES ('$SubmissionId','$key','".addslashes($value)."')") or die(mysql_error());
				}
			}
			
		}
		
	}
	$msg = $formCount . _RSFORM_BACKEND_MIGRATION_MSG;
	$RSadapter->redirect(_RSFORM_BACKEND_SCRIPT_PATH.'?option='.$option.'&task=forms.manage',$msg);
	
}


//////////////////////////////////////// BACKUP / RESTORE ////////////////////////////////////////
/**
 * Backup / Restore Screen
 *
 * @param str $option
 */
function backupRestore($option)
{
	$RSadapter = $GLOBALS['RSadapter'];
	
	//check if RSform! exists
	$tables = mysql_list_tables($RSadapter->config['db']);
	$num_tables = @mysql_numrows($tables);
	
	$i = 0;
	$exist = 0;
	while($i < $num_tables)
	{
	$tablename = mysql_tablename($tables, $i);
	if ($tablename==$RSadapter->config['dbprefix'].'forme_forms') $exist=1;
	$i++;
	}
	
	rsform_HTML::backupRestore( _RSFORM_BACKEND_BACKUPRESTORE_TITLE_HEAD, $option, 'component', '', dirname(__FILE__), "", $exist );
}

/**
 * Backup Generate Process
 *
 * @param str $option
 */
function backupDownload($option)
{
    $RSadapter = $GLOBALS['RSadapter'];
/*
    //first let's clean the directory
    if ($handle = opendir($RSadapter->config['absolute_path'].'/media')) {
        while (false !== ($file = readdir($handle))) {
            if(stristr($file,'.zip')){
                if(!is_dir($file)) unlink($RSadapter->config['absolute_path'].'/media/'.$file);
            }
        }
        closedir($handle);
    }
*/
    $tmpdir = uniqid('rsformbkp');
    $pathtotmpdir = $RSadapter->config['absolute_path'].'/media/'.$tmpdir.'/';
    mkdir($pathtotmpdir);
    chmod($pathtotmpdir,0777);

    require_once( $RSadapter->config['absolute_path'] . '/administrator/includes/pcl/pclzip.lib.php' );
    require_once( $RSadapter->config['absolute_path'] . '/administrator/includes/pcl/pclerror.lib.php' );

    $name = 'rsform_backup_' . date('Y-m-d_His') . '.zip';

    $files4XML = array();
    RSbackupCreateXMLfile($option, $files4XML, $pathtotmpdir . '/install.xml' );

    chdir($pathtotmpdir);
    $zipfile = new PclZip( $pathtotmpdir . $name );

    $zipfile->add($pathtotmpdir.'/install.xml',
                        PCLZIP_OPT_REMOVE_PATH, $pathtotmpdir);
    /*$zipfile->add(implode(',',$files),
                        PCLZIP_OPT_ADD_PATH, 'rsads',
                        PCLZIP_OPT_REMOVE_PATH, $mosConfig_absolute_path);*/
    @$zipfile->create();

    $RSadapter->redirect( $RSadapter->config['live_site'] .'/media/'. $tmpdir .'/'. $name );
}


//////////////////////////////////////// UPDATES ////////////////////////////////////////


function updateUploadProcess( $option ) {
	$RSadapter = $GLOBALS['RSadapter'];

    // Check that the zlib is available
    if(!extension_loaded('zlib')) {
        echo "The installer can't continue before zlib is installed";
        exit() ;
    }

    $userfile = $RSadapter->getParam( $_FILES, 'userfile' );
    $filetype = $RSadapter->getParam( $_POST, 'filetype');

    if (!$userfile) {
        echo "No file selected";
        exit();
    }

    $userfile_name = $userfile['name'];

    $msg = @constant('_RSFORM_BACKEND_UPDATECHECK_STATUS_'.strtoupper($filetype));

    $resultdir = RSuploadFile( $userfile['tmp_name'], $userfile['name'], $msg );

    $has_errors = 0;
    //check if file is a valid plugin
    if ($resultdir !== false) {
        $baseDir = $RSadapter->config['absolute_path'] . '/media/' ;

        require_once( _RSFORM_JOOMLA_XML_PATH );
        $installer = new RSinstaller();
        $installer->archivename = $userfile['name'];
        if($installer->upload($userfile['name']))
        {
        	if($installer->readInstall())
        	{
        	 	$RSinstall = $installer->xmldoc->documentElement;
        	 	if($installer->installType!=$filetype)
        	 	{
                    $msg = constant('_RSFORM_BACKEND_UPDATECHECK_'.strtoupper($filetype));
                }
                else
                {

                    $tasks_node = &$RSinstall->getElementsByPath('tasks', 1);
                    if (!is_null($tasks_node)) {
                        $tasks = $tasks_node->childNodes;
                        $has_errors = false;
                        foreach($tasks as $task){
                            if(RSprocessTask($option, $task, $installer->installDir)===FALSE)$has_errors = true;
                        }
                        //if($has_errors) die();
                    }

                    //clean up
                    @unlink($baseDir.$userfile['name']);
                    $installer->cleanup($userfile['name'], $installer->installDir);
                	$msg = _RSFORM_BACKEND_UPDATECHECK_OK;
                }

        	}else{
                $msg = _RSFORM_BACKEND_UPDATECHECK_NOINSTALL;
			}
        }else{
            $msg = _RSFORM_BACKEND_UPDATECHECK_BADFILE;
            @unlink($baseDir.$userfile['name']);
        }
    }

    
    if(!$has_errors)
    switch($filetype){
        case 'rsformbackup':
            $RSadapter->redirect(_RSFORM_BACKEND_SCRIPT_PATH.'?option='.$option.'&task=backup.restore',$msg);
        break;
        case 'rsformupdate':
            $RSadapter->redirect(_RSFORM_BACKEND_SCRIPT_PATH.'?option='.$option.'&task=updates.manage',$msg);
        break;
        case 'rsformplugin':
            $RSadapter->redirect(_RSFORM_BACKEND_SCRIPT_PATH.'?option='.$option.'&task=plugins.manage',$msg);
        break;

    }
}

function updatesManage($option){
	rsform_HTML::updatesManage($option);
}

?>