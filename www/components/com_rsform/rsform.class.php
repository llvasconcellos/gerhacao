<?php
/**
* @version 1.0.1
* @package RSform!Pro 1.0.1
* @copyright (C) 2007-2008 www.rsjoomla.com
* @license Commercial License, http://www.rsjoomla.com/license/rsformpro.html
*/

///////////////////////////////////////////////////// SUBMISSIONS /////////////////////////////////////////////////////


class SManager {
	var $fields = array();
	var $SubmissionsTable;
	var $SubmissionValuesTable;
	var $ComponentsTable;
	var $PropertiesTable;
	var $formId = null;
	var $order = array(0, 0);
	var $filter = "";
	var $current = 1;
	//$order = array( id of the element, order of the element)
	// order:
	// 0 - Desc
	// 1 - Asc
	// Default value set to 1 - "date_added"
	var $comp_pre = "comp_";
	var $subm_pre = "subm_";
	var $rows = 5; //number of rows shown per page

	function mySQLConnect()
	{
		$RSadapter = $GLOBALS['RSadapter'];
		/*
		$db_addr = $GLOBALS['mosConfig_host'];
		$db_user = $GLOBALS['mosConfig_user'];
		$db_pass = $GLOBALS['mosConfig_password'];
		$db_name = $GLOBALS['mosConfig_db'];

		mysql_connect($db_addr, $db_user, $db_pass) or die(mysql_error());
		mysql_select_db($db_name) or die(mysql_error());
		*/
	}//mySQLConnect()

	function mySQLClose()
	{
		mysql_close();
	}//mySQLClose()

	function setFields()
	{
		$RSadapter = $GLOBALS['RSadapter'];
		$i = 0;
		$query = mysql_query("SELECT p.* FROM `{$RSadapter->tbl_rsform_components}` c, `{$RSadapter->tbl_rsform_properties}` p WHERE c.ComponentId = p.ComponentId AND p.PropertyName='NAME' AND c.FormId = '{$this->formId}' AND c.Published = 1 ORDER BY c.`Order`");//WHERE `comp_view` = 1
		$this->fields[] = 'DateSubmitted';
		$this->fields[] = 'Username';
		while ($row = mysql_fetch_array($query))
		{
			$this->fields[] = $row['PropertyValue'];
			//Legend
			//$this->fields[]
		}
	}//setFields()

	function setTable()
	{
		$RSadapter = $GLOBALS['RSadapter'];
	}//setTable()

	function createHeaders()
	{
		for($i = 0; $i < sizeof($this->fields); $i++)
		{
			echo "<th id=\"field".$i."\" class=\"title\" style=\"white-space:nowrap;\">";
			echo $this->fields[$i]." <a href=\"javascript:void(0)\" onclick=\"sortRows($i, 'desc')\">";
			if ($i == $this->order[0] && $this->order[1] == 0) { echo "<img src=\"images/downarrow-1.png\" border=\"0\"/></a>"; } else { echo "<img src=\"images/downarrow.png\" border=\"0\"/></a>"; }
			echo "<a href=\"javascript:void(0)\" onclick=\"sortRows($i, 'asc')\">";
			if ($i == $this->order[0] && $this->order[1] == 1) { echo "<img src=\"images/uparrow-1.png\" border=\"0\"/></a>"; } else { echo "<img src=\"images/uparrow.png\" border=\"0\"/></a>"; }
			echo "</th>";
		}
	}//createHeaders()


	function getSubmissionIds(){
		$RSadapter = $GLOBALS['RSadapter'];
		$sqlFilter = "";
		$this->current--; //normalizes the page

		$order_type = $this->order[1];


		if ($order_type == 1) { $order_type = "ASC"; } else { $order_type = "DESC"; }

		$rows = $this->rows;
		$this->current *= $rows;

		$sqlFilter .= "(
		sv.`FieldValue` LIKE '%{$this->filter}%' OR
		s.`DateSubmitted` LIKE '%{$this->filter}%' OR
		s.`Username` LIKE '%{$this->filter}%' OR
		s.`UserIp` LIKE '%{$this->filter}%')" ;

		if($this->order[0]>1)
		{
			//$sqlFilter .= "AND sv.`FieldName`='{$this->fields[$this->order[0]]}'";
			$order_field = 'sv.`FieldValue`';
		}
		else
		{
			//$sqlFilter .= "AND s.`FieldName`='{$this->fields[$this->order[0]]}'";
			$order_field = 's.`'.$this->fields[$this->order[0]].'`';
		}




		$rowColor = 0;
		$query = mysql_query(
		"
		SELECT
			DISTINCT sv.`SubmissionId`
		FROM
			`{$RSadapter->tbl_rsform_submissions}` s
		LEFT JOIN
			`{$RSadapter->tbl_rsform_submission_values}` sv
		ON
			s.SubmissionId = sv.SubmissionId
		WHERE
			s.`FormId` = '{$this->formId}' AND
			{$sqlFilter}

		"
		);

		/*LIMIT {$this->current}, {$rows}
		ORDER BY
			$order_field $order_type
		*/
		$rowsOrder = array();

		//get results first
		while($row=mysql_fetch_array($query))
		{
			if($this->order[0]>1)
			{
				$queryResult = mysql_query("SELECT FieldValue FROM `{$RSadapter->tbl_rsform_submission_values}` WHERE `FieldName`='{$this->fields[$this->order[0]]}' AND `SubmissionId`='{$row['SubmissionId']}'");
				$fv = mysql_fetch_assoc($queryResult);
				if(!isset($fv['FieldValue'])) $fv['FieldValue']='';
				$rowsOrder[$row['SubmissionId']] = uniqid($fv['FieldValue']);
			}else
			{
				$queryResult = mysql_query("SELECT DateSubmitted FROM `{$RSadapter->tbl_rsform_submissions}` WHERE `SubmissionId`='{$row['SubmissionId']}'");
				$fv = mysql_fetch_assoc($queryResult);
				$rowsOrder[$row['SubmissionId']] = uniqid($fv['DateSubmitted']);
			}
		}


		$rowsOrder = array_flip($rowsOrder);
		if ($order_type == 'ASC')ksort($rowsOrder,SORT_STRING);
		else krsort($rowsOrder,SORT_STRING);
		reset($rowsOrder);
		$i = 0;
		foreach($rowsOrder as $key=>$value){
			if($i<$this->current || $i>=($this->current+$this->rows)) unset($rowsOrder[$key]);
			$i++;
		}
		return $rowsOrder;
	}


	function createRows( )
	{
		$RSadapter = $GLOBALS['RSadapter'];
		$selected = '';
		$rowsOrder = $this->getSubmissionIds();

		$selected = "z, ".$selected;
		$ids = explode(",", $selected);
		$i = 0;
		$rowColor=0;
		foreach($rowsOrder as $key=>$row)
		{
		//while($row=mysql_fetch_array($query))
		//{
			$rowColor = ($rowColor == 0 ? 1:0);
			echo "<tr class=\"row$rowColor\">";

				echo "<td>";
				if ( !(array_search($row, $ids) > 0) )
				{
					echo "<input name=\"checks[]\" value=\"$row\" type=\"checkbox\" id=\"cb$i\" onclick=\"checkOne(this);isChecked(this.checked)\" />";
				}
				else
				{
					echo "<input name=\"checks[]\" value=\"$row\" type=\"checkbox\" id=\"cb$i\" onclick=\"checkOne(this);isChecked(this.checked)\" checked=\"checked\"/>";
				}
				echo "</td>";

				//let's get the date and user from submissions
				$querySubmissions = mysql_query("SELECT Username, DateSubmitted FROM `{$RSadapter->tbl_rsform_submissions}` WHERE `SubmissionId` = '{$row}'");
				$Submission = mysql_fetch_assoc($querySubmissions);

				if(!isset($Submission['Username'])) $Submission['Username'] = '-';

				echo '<td>'.$Submission['DateSubmitted'].'</td>';
				echo '<td>'.$Submission['Username'].'</td>';

				$totalFields = sizeof($this->fields);

				//get components
				$j = 0;
				$queryComponents = mysql_query("SELECT `{$RSadapter->tbl_rsform_properties}`.PropertyValue, `{$RSadapter->tbl_rsform_components}`.ComponentTypeId FROM `{$RSadapter->tbl_rsform_properties}` LEFT JOIN `{$RSadapter->tbl_rsform_components}` ON `{$RSadapter->tbl_rsform_properties}`.ComponentId = `{$RSadapter->tbl_rsform_components}`.ComponentId WHERE `{$RSadapter->tbl_rsform_properties}`.PropertyName = 'NAME' AND `{$RSadapter->tbl_rsform_components}`.`FormId`='{$this->formId}' AND `{$RSadapter->tbl_rsform_components}`.`Published` = 1 ORDER BY {$RSadapter->tbl_rsform_components}.`Order`");
				
				while($Component = mysql_fetch_array($queryComponents))
				{
					
					$queryFields = mysql_query("SELECT * FROM `{$RSadapter->tbl_rsform_submission_values}` WHERE `SubmissionId` = '{$row}' AND FieldName='{$Component['PropertyValue']}'");
					$field = mysql_fetch_assoc($queryFields);
					
					//if it's a file upload, prepare the link instead of value..
					if($Component['ComponentTypeId']==9)
					{
						//get file
						if(stristr($field['FieldValue'],'/')) $filename = explode('/',$field['FieldValue']);	
						else $filename = explode("\\",$field['FieldValue']);
						$filename = $filename[count($filename)-1];
						$label = '<a href="'.$field['FieldValue'].'">'.$filename.'</a>'; 
						$label = str_replace($RSadapter->config['absolute_path'],$RSadapter->config['live_site'],$label);
						$label = str_replace(array('//','\\\\','http:/'),array('/','\\','http://'),$label);
					}
					else 
					{
						$label = $field['FieldValue'];
					}
					
					
					echo "<td><div id=\"row-".$row."-$j\">";
						echo $label;
					echo "</div>";

					echo "<textarea id=\"textarea-".$row."-{$j}\" name=\"textarea-{$row}\" class=\"hidden\">";
						echo $field['FieldValue'];
					echo "</textarea>";
					echo "
					<input type=\"hidden\" name=\"SubmissionValueId-{$row}\" value=\"{$field['SubmissionValueId']}\"/>".
					"<input type=\"hidden\" name=\"fieldName-{$row}\" value=\"{$Component['PropertyValue']}\"/>";

					echo "</td>";
					$j++;
				}
				echo "<td width='195'><div id=\"act-".$row."\">";
					echo "<input type=\"button\" name=\"edit\" onclick=\"editRow($row, ".($totalFields-2).")\" value=\"Edit\" /><input type=\"button\" name=\"remove\" onclick=\"removeRow('$row')\" value=\"Remove\" />";
				echo "</div></td>";
			echo "</tr>"."\n";
			$i++;
		}
	}//createRows()

	function createExportFile()
	{
		$RSadapter = $GLOBALS['RSadapter'];

	//1. make exportOrder the reference array for what data needs to be exported
	//2. load selected submission details (DateSubmitted, Userip, Username)
	//3. load submission values (for the selected components) Array
	//4. merge 1,2 ordered by Order
	//5. Use headers? if so, first line of output contains the component names
	//6. build output with delimiters and field enclosures
	//7. output

	//1. make exportOrder the reference array for what data needs to be exported
	if(!empty($this->exportOrder))
	{
		foreach($this->exportOrder as $key=>$value)
		{
			if(!isset($this->exportSubmission[$key])&&!isset($this->exportComponent[$key])) unset($this->exportOrder[$key]);
		}

		//resolve duplicates
		foreach($this->exportOrder as $key=>$value){
			$this->exportOrder[$key] = uniqid($value.'_');
		}
		$this->exportOrder = array_flip($this->exportOrder);
	}

	if(empty($this->submissionIds)) $this->rows = 100000;

	$rowsOrder = $this->getSubmissionIds();
	

	//while($srow=mysql_fetch_array($squery))
	foreach($rowsOrder as $key=>$srow)
	{
		//2. load selected submission details (DateSubmitted, Userip, Username)

		$query = mysql_query("SELECT s.* FROM {$RSadapter->tbl_rsform_submissions} s WHERE s.SubmissionId='{$srow}'");
		while($row = mysql_fetch_array($query))
		{
			$submissions[$row['SubmissionId']] = array();
			if(!empty($this->exportSubmission))
			{
				foreach($this->exportSubmission as $key=>$value)
				{
					$submissions[$row['SubmissionId']] = array_merge($submissions[$row['SubmissionId']],array($key=>$row[$key]));
				}
			}
		}

		//3. load submission values (for the selected components) Array
		foreach($submissions as $submissionId=>$submissionArray)
		{
			$query = mysql_query("SELECT s.FieldValue, s.FieldName FROM {$RSadapter->tbl_rsform_submission_values} s WHERE s.SubmissionId='$submissionId'");
			while($row = mysql_fetch_array($query))
			{
				foreach($this->exportComponent as $ComponentName=>$ComponentId)
				{
					if($row['FieldName'] == $ComponentName)
					{
						$submissions[$submissionId] = array_merge($submissions[$submissionId],array($row['FieldName']=>$row['FieldValue']));
					}
				}
			}
		}
	}


	//4. merge 1,2 ordered by Order
	ksort($this->exportOrder);
	reset($this->exportOrder);

	//5. Use headers? if so, first line of output contains the component names
	$output = '';
	if ($this->exportHeaders)
	{
		$outputRow = array();
		foreach($this->exportOrder as $orderId=>$submissionName)
		{
			$submissionName = str_replace($this->exportFieldEnclosure, $this->exportFieldEnclosure. $this->exportFieldEnclosure, $submissionName);
			$outputRow[] = $this->exportFieldEnclosure . $submissionName . $this->exportFieldEnclosure;
		}
		$output .= implode($this->exportDelimiter,$outputRow)."\n";
	}


	foreach($submissions as $submissionId=>$submissionRow)
	{
		$outputRow = array();
		foreach($this->exportOrder as $orderId=>$submissionName)
		{
			if(!isset($submissionRow[$submissionName])) $submissionRow[$submissionName] = '';
			$submissionRow[$submissionName] = str_replace($this->exportFieldEnclosure, $this->exportFieldEnclosure. $this->exportFieldEnclosure, $submissionRow[$submissionName]);
			$submissionRow[$submissionName] = str_replace("\r\n", "\n", $submissionRow[$submissionName]);
			$outputRow[] = $this->exportFieldEnclosure . $submissionRow[$submissionName] . $this->exportFieldEnclosure;
		}
		$output .= implode($this->exportDelimiter,$outputRow)."\n";
	}

	header ("Expires: Mon, 01 Jan 1999 01:00:00 GMT");
	header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");
	header ("Content-type: application/csv");
	header ("Content-Disposition: attachment; filename=\"".date('Y-m-d_')."rsform.csv\"" );

	if(function_exists('mb_check_encoding'))
	if(mb_check_encoding($output,'UTF-16LE')){
		$output = chr(255).chr(254).mb_convert_encoding( $output, 'UTF-16LE', 'UTF-8');
    	echo $output;
	}else{
    	echo $output;
	}
	else echo $output;

	exit;
	}

	function deleteRow($id)
	{
		$RSadapter = $GLOBALS['RSadapter'];
		mysql_query("DELETE FROM `{$RSadapter->tbl_rsform_submission_values}` WHERE `SubmissionId` = '$id'");
		mysql_query("DELETE FROM `{$RSadapter->tbl_rsform_submissions}` WHERE `SubmissionId` = '$id'");
	}//deleteRow($id)


	function setFilter($filter)
	{
		$this->filter = $filter;
	}//setOrder($field, $type)

	function setOrder($field, $type)
	{
		$this->order[0] = $field;
		$this->order[1] = $type;
	}//setOrder($field, $type)

	function pager($current = 1, $filter = "")
	{
		$RSadapter = $GLOBALS['RSadapter'];
		$sqlFilter = "";
		$this->current--; //normalizes the page
		$current--; //normalizes the page
		$page = $current + 1;
		$order_type = $this->order[1];


		if ($order_type == 1) { $order_type = "ASC"; } else { $order_type = "DESC"; }

		$rows = $this->rows;
		$this->current *= $rows;

		$sqlFilter .= "(
		sv.`FieldValue` LIKE '%{$this->filter}%' OR
		s.`DateSubmitted` LIKE '%{$this->filter}%' OR
		s.`Username` LIKE '%{$this->filter}%' OR
		s.`UserIp` LIKE '%{$this->filter}%')" ;

		if($this->order[0]>1)
		{
			//$sqlFilter .= "AND sv.`FieldName`='{$this->fields[$this->order[0]]}'";
			$order_field = 'sv.`FieldValue`';
		}
		else
		{
			//$sqlFilter .= "AND s.`FieldName`='{$this->fields[$this->order[0]]}'";
			$order_field = 's.`'.$this->fields[$this->order[0]].'`';
		}




		$rowColor = 0;
		$query = mysql_query(
		"
		SELECT
			DISTINCT sv.`SubmissionId`
		FROM
			`{$RSadapter->tbl_rsform_submissions}` s
		LEFT JOIN
			`{$RSadapter->tbl_rsform_submission_values}` sv
		ON
			s.SubmissionId = sv.SubmissionId
		WHERE
			s.`FormId` = '{$this->formId}' AND
			{$sqlFilter}

		"
		);

		$totalRows = mysql_num_rows($query);

		echo "\n".'<div class="pagenav">';
		if($page!=1){
			echo "<span><a href='javascript:void(0)' onclick='changePage(1)'>&lt;&lt;Start</a></span>\n";
		}else{
			echo "<span>&lt;&lt;Start</span>\n";
		}
		if ($page != 1)
		{
			$previous = $page - 1;
			echo "<span><a href='javascript:void(0)' onclick='changePage($previous)'>&lt;Previous</a></span>\n";
		}else{
			echo "<span>&lt;Previous</span>\n";
		}
		for ($i = 1; $i <= roundup($totalRows/$this->rows); $i++)
		{
			if ($i == $page)
			{
				echo "<span class='selected'>$i</span>\n";
			}
			else
			{
				echo "<span><a href='javascript:void(0)' onclick='changePage($i)'>$i</a></span>\n";
			}
		}
		if ($page != roundup($totalRows / $this->rows))
		{
			$next = $page + 1;
			echo "<span><a href='javascript:void(0)' onclick='changePage($next)'>Next&gt;</a></span>\n";
		}else{
			echo "<span>Next&gt;</span>\n";
		}
		$last = roundup($totalRows/$this->rows);
		if($page!=$last){
			echo "<span><a href='javascript:void(0)' onclick='changePage($last)'>End&gt;&gt;</a></span>\n";
		}else{
			echo "<span>End&gt;&gt;</span>\n";
		}

		echo "<div class='statistics'>\n";
		$rows = $current * $this->rows+1;
		$shown = $rows + $this->rows-1;
		if ($shown > $totalRows) { $shown = $totalRows; }
		echo "Results $rows - $shown of $totalRows\n";
		echo "</div>\n";
		echo "</div>\n";
		//echo "</td>\n";
	}//pager($current = 1, $filter = "")

	function setValue($SubmissionId, $SubmissionValueId, $value, $fieldName=null)
	{
		$RSadapter = $GLOBALS['RSadapter'];
		if(empty($SubmissionValueId))
			mysql_query("INSERT INTO `{$RSadapter->tbl_rsform_submission_values}` (`SubmissionId`,`FieldName`, `FieldValue`) VALUES ('$SubmissionId','$fieldName','$value')");
		else
			mysql_query("UPDATE `{$RSadapter->tbl_rsform_submission_values}` SET `FieldValue` = '$value' WHERE `SubmissionValueId` = '$SubmissionValueId' LIMIT 1");
	}//setValue($id, $field, $value)
	function SManager($formId)
	{

		$this->formId = $formId;
		$this->mySQLConnect();
		$this->setFields();
		$this->setTable();
	}//CManager()

}


function roundup($num)
{
	if (intval($num) < $num)
	{
		return intval($num) + 1;
	}
	else
	{
		return $num;
	}
}//roundup($num)


///////////////////////////////////////////////////// CAPTCHA /////////////////////////////////////////////////////

class captcha{

    var $Length;
    var $Type;
    var $CaptchaString;
    var $fontpath;
    var $fonts;
    var $data;


    function captcha ( $componentId = 0){

	$this->data=RSgetComponentProperties($componentId);
    $this->Length = $this->data['LENGTH'];

    if(!function_exists('imagecreate')) header('Location:'._RSFORM_FRONTEND_REL_PATH.'/images/nogd.gif');//die('GD Library not found!');

      header('Content-type: image/png');

      $this->fontpath = dirname(__FILE__).'/fonts/';
      $this->fonts    = $this->getFonts();
      $errormgr       = new error;

      if ($this->fonts == FALSE)
      {

          //$errormgr = new error;
          $errormgr->addError('No fonts available!');
          $errormgr->displayError();
          die();

      }

      if (function_exists('imagettftext') == FALSE)
      {

        $errormgr->addError('the function imagettftext does not exist.');
        $errormgr->displayError();
        die();

      }


      $this->stringGenerate();

      $this->makeCaptcha($componentId);

    } //captcha

    function getFonts (){
      $fonts = array();
      if ($handle = @opendir($this->fontpath)){
        while (($file = readdir($handle)) !== FALSE){
          $extension = strtolower(substr($file, strlen($file) - 3, 3));
          if ($extension == 'ttf'){
            $fonts[] = $file;
          }
        }
        closedir($handle);
      }else{
          return FALSE;
      }

      if (count($fonts) == 0){
          return FALSE;
      }else{
          return $fonts;
      }
    }
    function getRandomFont (){
      return $this->fontpath . $this->fonts[mt_rand(0, count($this->fonts) - 1)];
    }
    function stringGenerate(){

    	switch($this->data['TYPE']){
    		case 'ALPHA':
    			$CharPool = range('a','z');
    		break;
    		case 'NUMERIC':
    			$CharPool = range('0','9');
    		break;
    		case 'ALPHANUMERIC':
    		default:
    			$CharPool = array_merge(range('0','9'),range('a','z'));
    		break;
    	}


      //$CharPool   = range('a', 'z');
      $PoolLength = count($CharPool) - 1;

      for ($i = 0; $i < $this->Length; $i++){
        $this->CaptchaString .= $CharPool[mt_rand(0, $PoolLength)];
      }
    } //stringGenerate

    function makeCaptcha ($componentId=0){



      $imagelength = $this->Length * 15 + 16;
      $imageheight = 40;
		$image       = imagecreate($imagelength, $imageheight);




		  $usebgrcolor = sscanf($this->data['BACKGROUNDCOLOR'], '#%2x%2x%2x');
		  $usestrcolor = sscanf($this->data['TEXTCOLOR'], '#%2x%2x%2x');

      $bgcolor     = imagecolorallocate($image, $usebgrcolor[0], $usebgrcolor[1], $usebgrcolor[2]);
      $stringcolor = imagecolorallocate($image, $usestrcolor[0], $usestrcolor[1], $usestrcolor[2]);

      $filter      = new filters;

      //$filter->signs($image, $this->getRandomFont(),1);

      for ($i = 0; $i < strlen($this->CaptchaString); $i++){
        imagettftext($image,15, mt_rand(-15, 15), $i * 15 + 10,
                     mt_rand(20, 30),
                     $stringcolor,
                     $this->getRandomFont(),
                     $this->CaptchaString{$i});
      }

      $filter->noise($image, 2);
      //$filter->blur($image, 0);

      imagepng($image);

      imagedestroy($image);

    } //MakeCaptcha

    function getCaptcha ()
    {

      return $this->CaptchaString;

    } //getCaptcha

  } //class: captcha

    class error
  {

      var $errors;

      function error ()
      {

        $this->errors = array();

      } //error

      function addError ($errormsg)
      {

        $this->errors[] = $errormsg;

      } //addError

      function displayError ()
      {

      $iheight     = count($this->errors) * 20 + 10;
      $iheight     = ($iheight < 130) ? 130 : $iheight;

      $image       = imagecreate(600, $iheight);

//      $errorsign   = imagecreatefromjpeg('./gfx/errorsign.jpg');
//      imagecopy($image, $errorsign, 1, 1, 1, 1, 180, 120);

      $bgcolor     = imagecolorallocate($image, 255, 255, 255);

      $stringcolor = imagecolorallocate($image, 0, 0, 0);

      for ($i = 0; $i < count($this->errors); $i++)
      {

        $imx = ($i == 0) ? $i * 20 + 5 : $i * 20;


        $msg = 'Error[' . $i . ']: ' . $this->errors[$i];

        imagestring($image, 5, 190, $imx, $msg, $stringcolor);

        }

      imagepng($image);

      imagedestroy($image);

      } //displayError

      function isError ()
      {

        if (count($this->errors) == 0)
        {

            return FALSE;

        }
        else
        {

            return TRUE;

        }

      } //isError

  } //class: error



  class filters
  {

    function noise (&$image, $runs = 30){

      $w = imagesx($image);
      $h = imagesy($image);

      for ($n = 0; $n < $runs; $n++)
      {

        for ($i = 1; $i <= $h; $i++)
        {

          $randcolor = imagecolorallocate($image,
                                          mt_rand(0, 255),
                                          mt_rand(0, 255),
                                          mt_rand(0, 255));

          imagesetpixel($image,
                        mt_rand(1, $w),
                        mt_rand(1, $h),
                        $randcolor);

        }

      }

    } //noise

    function signs (&$image, $font, $cells = 3){

      $w = imagesx($image);
      $h = imagesy($image);

         for ($i = 0; $i < $cells; $i++)
         {

             $centerX     = mt_rand(5, $w);
             $centerY     = mt_rand(1, $h);
             $amount      = mt_rand(5, 10);
        $stringcolor = imagecolorallocate($image, 150, 150, 150);

             for ($n = 0; $n < $amount; $n++)
             {

          $signs = range('A', 'Z');
          $sign  = $signs[mt_rand(0, count($signs) - 1)];

               imagettftext($image, 15,
                            mt_rand(-15, 15),
                            $n * 15,//mt_rand(0, 15),
                            30 + mt_rand(-5, 5),
                            $stringcolor, $font, $sign);

             }

         }

    } //signs


  } //class: filters
  
///////////////////////////////////////////////////// RSINSTALLER /////////////////////////////////////////////////////  
  
class RSinstaller {
	var $archivename	= "";
	var $installDir		= "";
	var $installFile	= "";
	var $installType	= "";
	var $unpackDir		= "";
	var $xmldoc			= null;
	var $msg			= null;
	
	var $elementName	= null;
	var $elementDir		= null;
	
	
	function upload($filename = null) 
	{
		$this->archivename = $filename;

		if ($this->extract()) {
			if(file_exists($this->installDir . '/install.xml')) 
			{
				$this->setInstallFile($this->installDir . '/install.xml');
				return true;
			}
			else return false;
		} else {
			return false;
		}
	}
	
	
	function extract() 
	{
		$RSadapter = $GLOBALS['RSadapter'];

		$base_Dir 		= $RSadapter->processPath( $RSadapter->config['absolute_path'] . '/media' );

		$archivename 	= $base_Dir . $this->archivename;
		$tmpdir 		= uniqid( 'rsinstall_' );

		$extractdir 	= $RSadapter->processPath( $base_Dir . $tmpdir );
		$archivename 	= $RSadapter->processPath( $archivename, false );

		$this->unpackDir = $extractdir ;

		if (eregi( '.zip$', $archivename )) {
			// Extract functions
			require_once( $RSadapter->config['absolute_path'] . '/administrator/includes/pcl/pclzip.lib.php' );
			require_once( $RSadapter->config['absolute_path'] . '/administrator/includes/pcl/pclerror.lib.php' );
			
			$zipfile = new PclZip( $archivename );
			if((substr(PHP_OS, 0, 3) == 'WIN')) {
				define('OS_WINDOWS',1);
			} else {
				define('OS_WINDOWS',0);
			}

			$ret = $zipfile->extract( PCLZIP_OPT_PATH, $extractdir );
			if($ret == 0) {
				$this->msg = 'Unrecoverable error "'.$zipfile->errorName(true).'"' ;
				return false;
			}
		} else {
			require_once( $RSadapter->config['absolute_path'] . '/includes/Archive/Tar.php' );
			$archive = new Archive_Tar( $archivename );
			$archive->setErrorHandling( PEAR_ERROR_PRINT );

			if (!$archive->extractModify( $extractdir, '' )) {
				$this->msg = 'Extract Error' ;
				return false;
			}
		}

		$this->installDir = $extractdir;

		// Try to find the correct install dir. in case that the package have subdirs
		// Save the install dir for later cleanup
		$filesindir = $RSadapter->readDirectory( $this->installDir, '' );

		if (count( $filesindir ) == 1) {
			if (is_dir( $extractdir . $filesindir[0] )) {
				$this->installDir = $RSadapter->processPath( $extractdir . $filesindir[0] ) ;
			}
		}
		return true;
	}
	
	function setInstallFile( $filename = null ) 
	{
		if(!is_null($filename)) {
			if((substr(PHP_OS, 0, 3) == 'WIN')) {
				$this->installFile = str_replace('/','\\',$filename);
			} else {
				$this->installFile = str_replace('\\','/',$filename);
			}
		}
		return $this->installFile;
	}
	
	function readInstall()
	{
		$this->xmldoc = new DOMIT_Lite_Document();
		$this->xmldoc->resolveErrors( true );
		if (!$this->xmldoc->loadXML( $this->installFile, false, true )) {
			return false;
		}
		$root = &$this->xmldoc->documentElement;

		// Check that it's an installation file
		if ($root->getTagName() != 'RSinstall') {
			$this->msg = 'File :"' . $this->installFile . '" is not a valid RSform!Pro installation file' ;
			return false;
		}

		$this->installType = $root->getAttribute( 'type' ) ;
		return true;	
	}
	
	function cleanup( $userfile_name, $resultdir) {
		$RSadapter = $GLOBALS['RSadapter'];
		
		if (file_exists( $resultdir )) {
			$this->deldir( $resultdir );
			unlink( $RSadapter->processPath( $RSadapter->config['absolute_path'] . '/media/' . $userfile_name, false ) );
		}
	}

		
	function deldir( $dir ) {
		$RSadapter = $GLOBALS['RSadapter'];
		$current_dir = opendir( $dir );
		$old_umask = umask(0);
		while ($entryname = readdir( $current_dir )) {
			if ($entryname != '.' and $entryname != '..') {
				if (is_dir( $dir . $entryname )) {
					$this->deldir( $RSadapter->processPath( $dir . $entryname ) );
				} else {
	                @chmod($dir . $entryname, 0777);
					unlink( $dir . $entryname );
				}
			}
		}
		umask($old_umask);
		closedir( $current_dir );
		return rmdir( $dir );
	}
	
	
	
	
	
}
?>