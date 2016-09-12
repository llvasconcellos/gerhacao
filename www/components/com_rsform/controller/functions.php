<?php
/**
* @version 1.0.1
* @package RSform!Pro 1.0.1
* @copyright (C) 2007-2008 www.rsjoomla.com
* @license Commercial License, http://www.rsjoomla.com/license/rsformpro.html
*/
	function RSgetValidationRules()
	{
		$RSadapter=$GLOBALS['RSadapter'];
		$lines=file(_RSFORM_FRONTEND_ABS_PATH . '/controller/validation.php');
		$result='';
		foreach($lines as $line)
		{
			if(strpos($line,'function')!==false && strpos($line,'require')==false)
			{
				$line=preg_replace('/function/','',$line);
				$line=ltrim(rtrim($line));
				list($var1,$var2)=explode('(',$line);
				$result.=$var1."\n";
			}
		}
		$result=rtrim($result);
		return $result;
	}

	function RSisCode($value)
	{
		$RSadapter=$GLOBALS['RSadapter'];
		if(preg_match('/<code>/',$value))
			return eval($value);
		else return $value;
	}
	
	function RSisXMLCode($value){
		$RSadapter=$GLOBALS['RSadapter'];
		if(preg_match('/{RSadapter}/',$value))
			return ($RSadapter->$value);
		else return $value;
	}

	function RSinitForm($formId)
	{
		$RSadapter=$GLOBALS['RSadapter'];
		$q="select `ComponentId`,`Order`,`ComponentTypeId`,`Published` from $RSadapter->tbl_rsform_components where FormId=$formId order by `Order` ";
		$rez=mysql_query($q) or die(mysql_error());
		$i=1;
		$j = 0;
		$returnVal='';
		while($r=mysql_fetch_assoc($rez))
		{
			$j = ($j) ? 0:1;
			$returnVal.='<tr class="row'.$j.'" style="height: auto">';
			$returnVal.='<td><input type="hidden" name="previewComponentId" value="'.$r['ComponentId'].'"></td>';
			$returnVal.=RSshowSelectComponent($r['ComponentId']);
			$returnVal.=RSshowComponentName($r['ComponentId']);
			$returnVal.=RSpreviewComponent($formId,$r['ComponentId']);
			$returnVal.=RSshowEditComponentButton($r['ComponentTypeId'],$r['ComponentId']);
			$returnVal.=RSshowRemoveComponentButton($formId,$r['ComponentId']);
			$returnVal.=RSshowComponentOrdering($formId,$r['ComponentId'],$r['Order'],$i);
			$returnVal.=RSshowMoveUpComponent($formId,$r['ComponentId']);
			$returnVal.=RSshowMoveDownComponent($formId,$r['ComponentId']);
			$returnVal.=RSshowChangeStatusComponentButton($formId,$r['ComponentId'],$r['Published']);
			$returnVal.='</tr>';
			$i++;
		}
		echo $returnVal;
	}

	function RSshowSelectComponent($componentId)
	{
		return '<td><input type="checkbox" name="checks[]" value="'.$componentId.'"/></td>';
	}

	function RSshowComponentName($componentId)
	{
		$data=array();
		$data=RSgetComponentProperties($componentId);
		return '<td>'.$data['NAME'].'</td>';
	}

	function RSgetComponentProperties($componentId)
	{
		$RSadapter=$GLOBALS['RSadapter'];
		$q="select PropertyName, PropertyValue from `$RSadapter->tbl_rsform_properties` where ComponentId=$componentId";
		//echo $q;
		$rez=mysql_query($q) or die(mysql_error());
		$data=array();
		while($r=mysql_fetch_assoc($rez))
		{
			//$result=array_keys($r);
			$data[$r['PropertyName']]=stripslashes($r['PropertyValue']);
		}
		$data['componentId']=$componentId;
		return $data;
	}
	function RSpreviewComponent($formId,$componentId)
	{
		$RSadapter=$GLOBALS['RSadapter'];
		$q="
			select
				$RSadapter->tbl_rsform_component_types.ComponentTypeName,
				$RSadapter->tbl_rsform_properties.PropertyName,
				$RSadapter->tbl_rsform_properties.PropertyValue

				from $RSadapter->tbl_rsform_components
				left join $RSadapter->tbl_rsform_forms on $RSadapter->tbl_rsform_components.FormId=$RSadapter->tbl_rsform_forms.FormId
				left join $RSadapter->tbl_rsform_component_types on $RSadapter->tbl_rsform_components.ComponentTypeId=$RSadapter->tbl_rsform_component_types.ComponentTypeId
				left join $RSadapter->tbl_rsform_properties on $RSadapter->tbl_rsform_components.ComponentId=$RSadapter->tbl_rsform_components.ComponentId

				where $RSadapter->tbl_rsform_forms.FormId=$formId and $RSadapter->tbl_rsform_components.ComponentId=$componentId
		";
		$rez=mysql_query($q) or die(mysql_error());
		$r=mysql_fetch_assoc($rez);
		$out='';
		switch($r['ComponentTypeName'])
		{
			case 'textBox':
			{
				$data=RSgetComponentProperties($componentId);
				$out.="<td>$data[CAPTION]</td>";
				$data['DEFAULTVALUE']=RSisCode($data['DEFAULTVALUE']);
				$out.="<td><input type=\"text\" value=\"$data[DEFAULTVALUE]\" size=\"$data[SIZE]\"</td>";
			}break;
			case 'textArea':
			{
				$data=RSgetComponentProperties($componentId);
				$out.="<td>$data[CAPTION]</td>";
				$out.="<td><textarea cols=\"$data[COLS]\" rows=\"$data[ROWS]\">$data[DEFAULTVALUE]</textarea></td>";
			}break;
			case 'selectList':
			{
				$data=RSgetComponentProperties($componentId);
				$out.="<td>$data[CAPTION]</td>";
				if($data['MULTIPLE']=='YES') $out.="<td><select multiple size=\"$data[SIZE]\">";
				else $out.="<td><select size=\"$data[SIZE]\">";
				$data['ITEMS']=str_replace("\r","",$data['ITEMS']);
				$items=explode("\n",$data['ITEMS']);
				foreach($items as $item)
				{
					$buf=explode("|",$item);
					if(count($buf)==1)
					{
						if(preg_match('/\[c\]/',$buf[0])) $out.="<option selected>".str_replace('[c]','',$buf[0])."</option>";
						else $out.="<option>$buf[0]</option>";
					}
					if(count($buf)==2)
					{
						if(preg_match('/\[c\]/',$buf[1])) $out.="<option selected value=\"$buf[0]\">".str_replace('[c]','',$buf[1])."</option>";
						else $out.="<option value=\"$buf[0]\">$buf[1]</option>";
					}
				}
				$out.='</select></td>';
			}break;
			case 'checkboxGroup':
			{
				$i=0;
				$data=RSgetComponentProperties($componentId);
				$out.="<td>$data[CAPTION]</td>";
				$data['ITEMS']=str_replace("\r","",$data['ITEMS']);
				$items=explode("\n",$data['ITEMS']);
				$out.='<td>';
				foreach($items as $item)
				{
					$buf=explode("|",$item);
					if(count($buf)==1)
					{
						if(preg_match('/\[c\]/',$buf[0]))
						{
							$v=str_replace('[c]','',$buf[0]);
							$out.="<input checked type=\"checkbox\" value=\"$v\" name=\"$data[NAME]\" id=\"$data[NAME]$i\"><label for=\"$data[NAME]$i\">$v</label>";
						}
						else
							$out.="<input type=\"checkbox\" value=\"$buf[0]\" name=\"$data[NAME]\" id=\"$data[NAME]$i\"><label for=\"$data[NAME]$i\">$buf[0]</label>";
					}
					if(count($buf)==2)
					{
						if(preg_match('/\[c\]/',$buf[1]))
						{
							$v=str_replace('[c]','',$buf[1]);
							$out.="<input checked type=\"checkbox\" value=\"$buf[0]\" name=\"$data[NAME]\" id=\"$data[NAME]$i\"><label for=\"$data[NAME]$i\">$v</label>";
						}
						else
							$out.="<input type=\"checkbox\" value=\"$buf[0]\" name=\"$data[NAME]\" id=\"$data[NAME]$i\"><label for=\"$data[NAME]$i\">$buf[1]</label>";

					}
					if($data['FLOW']=='VERTICAL') $out.='<br>';
					$i++;
				}
				$out.='</td>';

			}break;
			case 'radioGroup':
			{
				$i=0;
				$data=RSgetComponentProperties($componentId);
				$out.="<td>$data[CAPTION]</td>";
				$data['ITEMS']=str_replace("\r","",$data['ITEMS']);
				$items=explode("\n",$data['ITEMS']);
				$out.='<td>';
				foreach($items as $item)
				{
					$buf=explode("|",$item);
					if(count($buf)==1)
					{
						if(preg_match('/\[c\]/',$buf[0]))
						{
							$v=str_replace('[c]','',$buf[0]);
							$out.="<input checked type=\"radio\" value=\"$v\" name=\"$data[NAME]\" id=\"$data[NAME]$i\"><label for=\"$data[NAME]$i\">$v</label>";
						}
						else
							$out.="<input type=\"radio\" value=\"$buf[0]\" name=\"$data[NAME]\" id=\"$data[NAME]$i\"><label for=\"$data[NAME]$i\">$buf[0]</label>";
					}
					if(count($buf)==2)
					{
						if(preg_match('/\[c\]/',$buf[1]))
						{
							$v=str_replace('[c]','',$buf[1]);
							$out.="<input checked type=\"radio\" value=\"$buf[0]\" name=\"$data[NAME]\" id=\"$data[NAME]$i\"><label for=\"$data[NAME]$i\">$v</label>";
						}
						else
							$out.="<input type=\"radio\" value=\"$buf[0]\" name=\"$data[NAME]\" id=\"$data[NAME]$i\"><label for=\"$data[NAME]$i\">$buf[1]</label>";

					}
					if($data['FLOW']=='VERTICAL') $out.='<br>';
					$i++;
				}
				$out.='</td>';

			}break;
			case 'calendar':
			{
				$data=RSgetComponentProperties($componentId);
				$out.="<td>$data[CAPTION]</td>";
				$out.='<td><img src="'.$RSadapter->config['live_site'].'/administrator/components/com_rsform/images/icons/calendar.gif" /> '.constant('_RSFORM_BACKEND_COMP_FVALUE_'.$data['CALENDARLAYOUT']).'</td>';
			}break;
			case 'button':
			{
				$data=RSgetComponentProperties($componentId);
				$out.="<td>$data[CAPTION]</td>";
				$out.="<td><input type=\"button\" value=\"$data[LABEL]\">";
				if ($data['RESET']=='YES') $out.="&nbsp;&nbsp;<input type=\"reset\" value=\"$data[RESETLABEL]\">";
				$out.="</td>";
			}break;
			case 'captcha':
			{
				$data=RSgetComponentProperties($componentId);
				$out.="<td>$data[CAPTION]</td>";

				$out .="<td>";
				$out.='<img src="'._RSFORM_FRONTEND_SCRIPT_PATH.'?option=com_rsform&amp;task=captcha&amp;componentId='.$componentId.'" id="captcha'.$componentId.'"/>';
				$out.=($data['FLOW']=='HORIZONTAL') ? '':'<br/>';
				$out.="<input type=\"text\" name=\"form[".$data['NAME']."]\" value=\"\" id=\"captchaTxt$componentId\" $data[ADDITIONALATTRIBUTES] />";
				$out.=($data['SHOWREFRESH']=='YES') ? '<a href="" onclick="refreshCaptcha('.$componentId.',\''._RSFORM_FRONTEND_SCRIPT_PATH.'?option=com_rsform&amp;task=captcha&amp;componentId='.$componentId.'\');return false;">'.$data['REFRESHTEXT'].'</a>':'';


				$out.='</td>';
			}break;
			case 'fileUpload':
			{
				$data=RSgetComponentProperties($componentId);
				$out.="<td>$data[CAPTION]</td>";
				$out.="<td><input type=\"file\" name=\"$data[NAME]\"></td>";
			}break;
			case 'freeText':
			{
				$data=RSgetComponentProperties($componentId);
				$out.="<td>&nbsp;</td>";
				$out.="<td>".str_replace("\n",'<br>',$data['TEXT'])."</td>";
			}break;
			case 'hidden':
			{
				$data=RSgetComponentProperties($componentId);
				$out.="<td>&nbsp;</td>";
				$out.="<td>{hidden field}</td>";
			}break;
			case 'imageButton':
			{
				$data=RSgetComponentProperties($componentId);
				$out.="<td>$data[CAPTION]</td>";
				$out.="<td>";
					$out.="<input type=\"image\" src=\"$data[IMAGEBUTTON]\">";
					if($data['RESET']=='YES') $out.="&nbsp;&nbsp;<input type=\"image\" src=\"$data[IMAGERESET]\">";
				$out.="</td>";
			}break;
			case 'submitButton':
			{
				$data=RSgetComponentProperties($componentId);
				$out.="<td>$data[CAPTION]</td>";
				$out.="<td>";
					$out.="<input type=\"button\" value=\"$data[LABEL]\">";
					if($data['RESET']=='YES') $out.="&nbsp;&nbsp;<input type=\"reset\" value=\"$data[RESETLABEL]\">";
				$out.="</td>";
			}break;
			case 'password':
			{
				$data=RSgetComponentProperties($componentId);
				$out.="<td>$data[CAPTION]</td>";
				$out.="<td><input type=\"password\" value=\"$data[DEFAULTVALUE]\" size=\"$data[SIZE]\"</td>";
			}break;
			case 'ticket':
			{
				$data=RSgetComponentProperties($componentId);
				$out.="<td>&nbsp;</td>";
				$out.="<td>".RSgenerateString($data['LENGTH'],$data['CHARACTERS'])."</td>";
			}break;
		}
		return $out;
	}

	function RSshowEditComponentButton($formId,$componentId)
	{
		return "<td><a href=\"#\" onclick=\"displayTemplate('".$formId."','".$componentId."');\"><img src=\"components/com_rsform/images/icons/edit.png\" border=\"0\" width=\"16\" height=\"16\" alt=\"Edit Component\" /></a></td>";
	}
	function RSshowRemoveComponentButton($formId,$componentId)
	{
		return "<td><a href=\"#\" onclick=\"removeComponent('".$formId."','".$componentId."');\"><img src=\"components/com_rsform/images/icons/remove.png\" border=\"0\" width=\"12\" height=\"12\" alt=\"Remove Component\"  style=\"padding-left:20px;\"/></a></td>";
	}
	function RSshowChangeStatusComponentButton($formId, $componentId, $published){
		return "<td><a href=\"#\" onclick=\"changeStatusComponent('".$formId."','".$componentId."');\"><img src=\"components/com_rsform/images/icons/".($published ? 'publish':'unpublish').".png\" border=\"0\" width=\"12\" height=\"12\" alt=\"".($published ? 'Unpublish Component':'Publish Component')."\" style=\"padding-left:20px;\" id=\"currentStatus$componentId\" /></a></td>";
	}
	function RSshowComponentOrdering($formId,$componentId,$order,$tabIndex)
	{
		return "<td><input type=\"text\" value=\"$order\" size=\"2\" name=\"ordering[$componentId]\" tabindex=\"$tabIndex\"></td>";
	}
	function RSshowMoveUpComponent($formId,$componentId)
	{
		return "<td><a href=\"#\" onclick=\"moveComponentUp('".$formId."','".$componentId."');\"><img src=\"components/com_rsform/images/icons/uparrow.png\" border=\"0\" width=\"12\" height=\"12\" alt=\"Move Up\" /></a></td>";
	}
	function RSshowMoveDownComponent($formId,$componentId)
	{
		return "<td><a href=\"#\" onclick=\"moveComponentDown('".$formId."','".$componentId."');\"><img src=\"components/com_rsform/images/icons/downarrow.png\" border=\"0\" width=\"12\" height=\"12\" alt=\"Move Down\" /></a></td>";
	}
	function RSgetFormLayout($formId)
	{
		$RSadapter=$GLOBALS['RSadapter'];
		$q="select FormLayoutAutogenerate,FormLayoutName from $RSadapter->tbl_rsform_forms where FormId='$formId'";

		$rez=mysql_query($q) or die(mysql_error());
		$r=mysql_fetch_assoc($rez);
		if($r['FormLayoutAutogenerate']==1)
		{
			$layout=@include(_RSFORM_BACKEND_ABS_PATH.'/layouts/'.stripslashes($r['FormLayoutName']).'.php');
			$layout=preg_replace('/1/','',$layout);
			return $layout;
		}
		else
		{
			$q="select FormLayout from $RSadapter->tbl_rsform_forms where FormId=$formId";
			$rez=mysql_query($q) or die(mysql_error());
			$r=mysql_fetch_assoc($rez);
			return stripslashes($r['FormLayout']);
		}
	}
	function RSresolveComponentName($componentName,$formId)
	{
		$RSadapter=$GLOBALS['RSadapter'];
		$q="

		select $RSadapter->tbl_rsform_properties.ComponentId


		from $RSadapter->tbl_rsform_properties
		join $RSadapter->tbl_rsform_components on $RSadapter->tbl_rsform_components.ComponentId=$RSadapter->tbl_rsform_properties.ComponentId
		where $RSadapter->tbl_rsform_properties.PropertyValue='$componentName' and $RSadapter->tbl_rsform_properties.PropertyName='NAME' and $RSadapter->tbl_rsform_components.FormId='$formId'";
		$rez=mysql_query($q) or die(mysql_error());
		$r=mysql_fetch_assoc($rez);
		return $r['ComponentId'];
	}
	function RSfrontComponentCaption($componentId)
	{
		$RSadapter=$GLOBALS['RSadapter'];
		$q="select PropertyValue from $RSadapter->tbl_rsform_properties where ComponentId='$componentId' and PropertyName='CAPTION'";
		$rez=mysql_query($q) or die(mysql_error());
		$r=mysql_fetch_assoc($rez);
		return stripslashes($r['PropertyValue']);
	}
	function RSfrontComponentDescription($componentId)
	{
		$RSadapter=$GLOBALS['RSadapter'];

		$q="select PropertyValue from $RSadapter->tbl_rsform_properties where ComponentId='$componentId' and PropertyName='DESCRIPTION'";
		$rez=mysql_query($q) or die(mysql_error());
		$r=mysql_fetch_assoc($rez);
		return $r['PropertyValue'];
	}
	function RSfrontComponentValidationMessage($componentId,$value='')
	{
		$RSadapter=$GLOBALS['RSadapter'];

		$q="select PropertyValue from $RSadapter->tbl_rsform_properties where ComponentId='$componentId' and PropertyName='VALIDATIONMESSAGE'";
		$rez=mysql_query($q) or die(mysql_error());
		$r=mysql_fetch_assoc($rez);
		if(!empty($value) && in_array($componentId,$value,false)==true)
			return "<span id=\"$componentId\" class=\"formError\">".$r['PropertyValue']."</span>";
		else
			return "<span id=\"$componentId\" class=\"formNoError\">".$r['PropertyValue']."</span>";
	}
	function RSfrontLayout($formId, $formLayout)
	{
		$RSadapter=$GLOBALS['RSadapter'];
		
		
		//get form title
		$query = mysql_query("SELECT FormTitle FROM $RSadapter->tbl_rsform_forms WHERE FormId='$formId'") or die(mysql_error());
		$rezFormTitle = mysql_fetch_assoc($query);
		$formTitle = $rezFormTitle['FormTitle'];
		
		$result = str_replace('{global:formtitle}',$formTitle, $formLayout);
		
		return $result;
	}
	function RSfrontComponentBody($formId,$componentId,$value='')
	{
		$RSadapter=$GLOBALS['RSadapter'];
		$q="
			select

				$RSadapter->tbl_rsform_properties.PropertyName,
				$RSadapter->tbl_rsform_properties.PropertyValue,
				$RSadapter->tbl_rsform_components.ComponentTypeId,
				$RSadapter->tbl_rsform_components.Order

				from $RSadapter->tbl_rsform_components

				left join $RSadapter->tbl_rsform_properties on $RSadapter->tbl_rsform_properties.ComponentId=$RSadapter->tbl_rsform_components.ComponentId

				where $RSadapter->tbl_rsform_components.FormId=$formId and $RSadapter->tbl_rsform_components.ComponentId=$componentId
		";

		$rez=mysql_query($q) or die(mysql_error());
		$r=mysql_fetch_assoc($rez);
		$out='';
		switch(RSresolveComponentTypeId($r['ComponentTypeId']))
		{
			case 'textBox':
			{
				//echo 'text box found';
				$data=RSgetComponentProperties($componentId);
				/*echo '<pre>';
				var_dump($data);
				echo '</pre>';*/
				if(!empty($value))
					$out.="<input type=\"text\" value=\"".$value[$data['NAME']]."\" size=\"$data[SIZE]\" name=\"form[$data[NAME]]\" id=\"$data[NAME]\" $data[ADDITIONALATTRIBUTES]>";
				else {
					$data['DEFAULTVALUE']=RSisCode($data['DEFAULTVALUE']);
					$out.="<input type=\"text\" value=\"$data[DEFAULTVALUE]\" size=\"$data[SIZE]\" name=\"form[$data[NAME]]\" id=\"$data[NAME]\" $data[ADDITIONALATTRIBUTES]>";
				}
			}break;

			case 'textArea':
			{
				$data=RSgetComponentProperties($componentId);
				if(!empty($value))
					$out.="<textarea cols=\"$data[COLS]\" rows=\"$data[ROWS]\" name=\"form[$data[NAME]]\" id=\"$data[NAME]\" $data[ADDITIONALATTRIBUTES]>".$value[$data['NAME']]."</textarea>";
				else
				$out.="<textarea cols=\"$data[COLS]\" rows=\"$data[ROWS]\" name=\"form[$data[NAME]]\" id=\"$data[NAME]\" $data[ADDITIONALATTRIBUTES]>$data[DEFAULTVALUE]</textarea>";
			}break;

			case 'selectList':
			{
				$data=RSgetComponentProperties($componentId);
				if($data['MULTIPLE']=='YES') $out.="<select multiple name=\"form[$data[NAME]][]\" size=\"$data[SIZE]\" id=\"$data[NAME]\" $data[ADDITIONALATTRIBUTES]>";
				else $out.="<select size=\"$data[SIZE]\" name =\"form[$data[NAME]][]\" id=\"$data[NAME]\" $data[ADDITIONALATTRIBUTES]>";
				$data['ITEMS']=str_replace("\r","",$data['ITEMS']);
				$items=explode("\n",$data['ITEMS']);
				foreach($items as $item)
				{
					$buf=explode("|",$item);
					if(count($buf)==1)
					{
						if(empty($value))
							if(preg_match('/\[c\]/',$buf[0]))
							{
								$out.='<option selected value="'.$buf[0].'">'.str_replace('[c]','',$buf[0])."</option>";
							}
							else
							{
								$out.='<option value="'.$buf[0].'">'.$buf[0].'</option>';
							}
						else if(!empty($value[$data['NAME']]))
							if(array_search(str_replace('[c]','',$buf[0]),$value[$data['NAME']])!==false)
								$out.='<option selected value="'.$buf[0].'">'.str_replace('[c]','',$buf[0])."</option>";
							else
								$out.='<option value="'.$buf[0].'">'.str_replace('[c]','',$buf[0])."</option>";
						else
							$out.='<option value="'.$buf[0].'">'.str_replace('[c]','',$buf[0])."</option>";
					}
					if(count($buf)==2)
					{
						if(empty($value))
							if(preg_match('/\[c\]/',$buf[1]))
								$out.="<option selected value=\"$buf[0]\">".str_replace('[c]','',$buf[1])."</option>";
							else
								$out.="<option value=\"$buf[0]\">$buf[1]</option>";
						else if(!empty($value[$data['NAME']]))
							if(array_search(str_replace('[c]','',$buf[0]),$value[$data['NAME']])!==false)
								$out.="<option selected value=\"$buf[0]\">".str_replace('[c]','',$buf[1])."</option>";
							else
								$out.="<option value=\"$buf[0]\">".str_replace('[c]','',$buf[1])."</option>";
						else
							$out.="<option value=\"$buf[0]\">".str_replace('[c]','',$buf[1])."</option>";
					}
				}
				$out.='</select>';
			}break;
			case 'checkboxGroup':
			{
				$i=0;
				$data=RSgetComponentProperties($componentId);
				$data['Items']=str_replace("\r","",$data['ITEMS']);
				$items=explode("\n",$data['ITEMS']);
				foreach($items as $item)
				{
					$buf=explode("|",$item);
					if(count($buf)==1)
					{
						if(empty($value))
							if(preg_match('/\[c\]/',$buf[0]))
							{
								$v=str_replace('[c]','',$buf[0]);
								$out.="<input checked name =\"form[$data[NAME]][]\" type=\"checkbox\" value=\"$v\" id=\"$data[NAME]$i\"><label for=\"$data[NAME]$i\" $data[ADDITIONALATTRIBUTES]>$v</label>";
							}
							else
								$out.="<input name =\"form[$data[NAME]][]\" type=\"checkbox\" value=\"$buf[0]\" id=\"$data[NAME]$i\" $data[ADDITIONALATTRIBUTES]><label for=\"$data[NAME]$i\">$buf[0]</label>";

						else if(!empty($value[$data['NAME']]))

							if(array_search(str_replace('[c]','',$buf[0]),$value[$data['NAME']])!==false)
							{
								$v=str_replace('[c]','',$buf[0]);
								$out.="<input checked name =\"form[$data[NAME]][]\" type=\"checkbox\" value=\"$v\" id=\"$data[NAME]$i\" $data[ADDITIONALATTRIBUTES]><label for=\"$data[NAME]$i\">$v</label>";
							}
							else
							{
								$v=str_replace('[c]','',$buf[0]);
								$out.="<input name =\"form[$data[NAME]][]\" type=\"checkbox\" value=\"$v\" id=\"$data[NAME]$i\" $data[ADDITIONALATTRIBUTES]><label for=\"$data[NAME]$i\">$v</label>";
							}
						else
						{
							$v=str_replace('[c]','',$buf[0]);
							$out.="<input name=\"form[$data[NAME]][]\" type=\"checkbox\" value=\"$v\" id=\"$data[NAME]$i\" $data[ADDITIONALATTRIBUTES]><label for=\"$data[NAME]$i\">$v</label>";
						}
					}
					if(count($buf)==2)
					{
						if(empty($value))
							if(preg_match('/\[c\]/',$buf[1]))
							{
								$v=str_replace('[c]','',$buf[1]);
								$out.="<input checked name =\"form[$data[NAME]][]\" type=\"checkbox\" value=\"$buf[0]\" id=\"$data[NAME]$i\" $data[ADDITIONALATTRIBUTES]><label for=\"$data[NAME]$i\">$v</label>";
							}
							else
								$out.="<input name =\"form[$data[NAME]][]\" type=\"checkbox\" value=\"$buf[0]\" id=\"$data[NAME]$i\" $data[ADDITIONALATTRIBUTES]><label for=\"$data[NAME]$i\">$buf[1]</label>";

						else if(!empty($value[$data['NAME']]))
							if(array_search(str_replace('[c]','',$buf[0]),$value[$data['NAME']])!==false)
							{
								$v=str_replace('[c]','',$buf[1]);
								$out.="<input checked name =\"form[$data[NAME]][]\" type=\"checkbox\" value=\"$buf[0]\" id=\"$data[NAME]$i\" $data[ADDITIONALATTRIBUTES]><label for=\"$data[NAME]$i\">$v</label>";
							}
							else
							{
								$v=str_replace('[c]','',$buf[1]);
								$out.="<input name =\"form[$data[NAME]][]\" type=\"checkbox\" value=\"$buf[0]\" id=\"$data[NAME]$i\" $data[ADDITIONALATTRIBUTES]><label for=\"$data[NAME]$i\">$v</label>";
							}
						else
						{
							$v=str_replace('[c]','',$buf[1]);
							$out.="<input name =\"form[$data[NAME]][]\" type=\"checkbox\" value=\"$buf[0]\" id=\"$data[NAME]$i\" $data[ADDITIONALATTRIBUTES]><label for=\"$data[NAME]$i\">$v</label>";
						}

					}
					if($data['FLOW']=='VERTICAL') $out.='<br>';
					$i++;
				}

			}break;
			case 'radioGroup':
			{
				$i=0;
				$data=RSgetComponentProperties($componentId);
				$data['ITEMS']=str_replace("\r","",$data['ITEMS']);
				$items=explode("\n",$data['ITEMS']);
				foreach($items as $item)
				{
					$buf=explode("|",$item);
					if(count($buf)==1)
					{
						if(empty($value))
							if(preg_match('/\[c\]/',$buf[0]))
							{
								$v=str_replace('[c]','',$buf[0]);
								$out.="<input checked type=\"radio\" value=\"$v\" name =\"form[$data[NAME]]\" id=\"$data[NAME]$i\"><label for=\"$data[NAME]$i\" $data[ADDITIONALATTRIBUTES]>$v</label>";
							}
							else
								$out.="<input type=\"radio\" value=\"$buf[0]\" name =\"form[$data[NAME]]\" id=\"$data[NAME]$i\" $data[ADDITIONALATTRIBUTES]><label for=\"$data[NAME]$i\">$buf[0]</label>";

						else if(!empty($value[$data['NAME']]))
							if(str_replace('[c]','',$buf[0])==$value[$data['NAME']])
							{
								$v=str_replace('[c]','',$buf[0]);
								$out.="<input checked type=\"radio\" value=\"$v\" name =\"form[$data[NAME]]\" id=\"$data[NAME]$i\" $data[ADDITIONALATTRIBUTES]><label for=\"$data[NAME]$i\">$v</label>";
							}
							else
							{
								$v=str_replace('[c]','',$buf[0]);
								$out.="<input type=\"radio\" value=\"$v\" name =\"form[$data[NAME]]\" id=\"$data[NAME]$i\" $data[ADDITIONALATTRIBUTES]><label for=\"$data[NAME]$i\">$v</label>";
							}
						else
						{
							$v=str_replace('[c]','',$buf[0]);
							$out.="<input type=\"radio\" value=\"$v\" name =\"form[$data[NAME]]\" id=\"$data[NAME]$i\" $data[ADDITIONALATTRIBUTES]><label for=\"$data[NAME]$i\">$v</label>";
						}
					}
					if(count($buf)==2)
					{
						if(empty($value))
							if(preg_match('/\[c\]/',$buf[1]))
							{
								$v=str_replace('[c]','',$buf[1]);
								$out.="<input checked type=\"radio\" value=\"$buf[0]\" name =\"form[$data[NAME]]\" id=\"$data[NAME]$i\" $data[ADDITIONALATTRIBUTES]><label for=\"$data[NAME]$i\">$v</label>";
							}
							else
								$out.="<input type=\"radio\" value=\"$buf[0]\" name =\"form[$data[NAME]]\" id=\"$data[NAME]$i\" $data[ADDITIONALATTRIBUTES]><label for=\"$data[NAME]$i\">$buf[1]</label>";

						else if (!empty($value[$data['NAME']]))
							if(str_replace('[c]','',$buf[0])==$value[$data['NAME']])
							{
								$v=str_replace('[c]','',$buf[1]);
								$out.="<input checked type=\"radio\" value=\"$buf[0]\" name =\"form[$data[NAME]]\" id=\"$data[NAME]$i\" $data[ADDITIONALATTRIBUTES]><label for=\"$data[NAME]$i\">$v</label>";
							}
							else
							{
								$v=str_replace('[c]','',$buf[1]);
								$out.="<input type=\"radio\" value=\"$buf[0]\" name =\"form[$data[NAME]]\" id=\"$data[NAME]$i\" $data[ADDITIONALATTRIBUTES]><label for=\"$data[NAME]$i\">$v</label>";
							}
						else
						{
							$v=str_replace('[c]','',$buf[1]);
							$out.="<input type=\"radio\" value=\"$buf[0]\" name =\"form[$data[NAME]]\" id=\"$data[NAME]$i\" $data[ADDITIONALATTRIBUTES]><label for=\"$data[NAME]$i\">$v</label>";
						}


					}
					if($data['FLOW']=='VERTICAL') $out.='<br>';
					$i++;
				}

			}break;
			case 'calendar':
			{

				$data=RSgetComponentProperties($componentId);
				$calendars = RScomponentExists($formId, 6);
				$calendars = array_flip($calendars);
				$def_cal_val = (empty($value) ? '':$value[$data['NAME']]);

				switch($data['CALENDARLAYOUT'])
				{
					case 'FLAT':
						$out.='<input id="txtcal'.$calendars[$componentId].'" name="form['.$data['NAME'].']" type="text" '.($data['READONLY'] == 'YES' ? 'readonly' : '').' class="txtCal" value="'.$def_cal_val.'" '.$data['ADDITIONALATTRIBUTES'].'/><br/>
							<div id="cal'.$calendars[$componentId].'Container" style="z-index:'.(9999-$r['Order']).'"></div>';
					break;

					case 'POPUP':
						$out .= '<input id="txtcal'.$calendars[$componentId].'" name="form['.$data['NAME'].']" type="text" '.($data['READONLY'] == 'YES' ? 'readonly' : '').'  value="'.$def_cal_val.'" '. $data['ADDITIONALATTRIBUTES'].'/>
							<input id="btn'.$calendars[$componentId].'" type="button" value="'.$data['POPUPLABEL'].'" onclick="showHideCalendar(\'cal'.$calendars[$componentId].'Container\');" class="btnCal" />

							<div id="cal'.$calendars[$componentId].'Container" style="clear:both;display:none;position:absolute;z-index:'.(9999-$r['Order']).'"></div>';
					break;
				}

			}break;
			case 'button':
			{
				$data=RSgetComponentProperties($componentId);
				$out.="<input type=\"button\" value=\"$data[LABEL]\" name =\"form[$data[NAME]]\" id=\"$data[NAME]\" $data[ADDITIONALATTRIBUTES]/>";
				if ($data['RESET']=='YES') $out.="&nbsp;&nbsp;<input type=\"reset\" value=\"$data[RESETLABEL]\" name =\"form[$data[NAME]]\" id =\"$data[NAME]\" $data[ADDITIONALATTRIBUTES]>";
			}break;
			case 'captcha':
			{
				$data=RSgetComponentProperties($componentId);

				$out.='<img src="'._RSFORM_FRONTEND_SCRIPT_PATH.'?option=com_rsform&amp;task=captcha&amp;componentId='.$componentId.'" id="captcha'.$componentId.'"/>';
				$out.=($data['FLOW']=='HORIZONTAL') ? '':'<br/>';
				$out.="<input type=\"text\" name=\"form[".$data['NAME']."]\" value=\"\" id=\"captchaTxt$componentId\" $data[ADDITIONALATTRIBUTES] />";
				$out.=($data['SHOWREFRESH']=='YES') ? '<a href="" onclick="refreshCaptcha('.$componentId.',\''._RSFORM_FRONTEND_SCRIPT_PATH.'?option=com_rsform&amp;task=captcha&amp;componentId='.$componentId.'\');return false;">'.$data['REFRESHTEXT'].'</a>':'';
				//$out.='{captcha component}';
			}break;
			case 'fileUpload':
			{
				$data=RSgetComponentProperties($componentId);
				$out.="<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$data[FILESIZE]000\">";
				$out.="<input type=\"file\" name =\"form[$data[NAME]]\" id=\"$data[NAME]\" $data[ADDITIONALATTRIBUTES]>";
			}break;
			case 'freeText':
			{
				$data=RSgetComponentProperties($componentId);
				$out.=str_replace("\n",'<br>',$data['TEXT']);
			}break;
			case 'hidden':
			{

				$data=RSgetComponentProperties($componentId);
				$defaultValue=RSisCode($data['DEFAULTVALUE']);
				//echo "<hr>Default value: $defaultValue<hr>";
				$out.="<input type=\"hidden\" name =\"form[$data[NAME]]\" id=\"$data[NAME]\" value=\"".$defaultValue."\" $data[ADDITIONALATTRIBUTES]>";
			}break;
			case 'imageButton':
			{
				$data=RSgetComponentProperties($componentId);
				$out.="<input type=\"image\" src=\"$data[IMAGEBUTTON]\" name=\"form[$data[NAME]]\" id=\"$data[NAME]\" $data[ADDITIONALATTRIBUTES]>";
				if($data['RESET']=='YES') $out.="&nbsp;&nbsp;<input type=\"image\" src=\"$data[IMAGERESET]\" name =\"form[$data[NAME]]\" name=\"$data[NAME]\">";
			}break;
			case 'submitButton':
			{
				$data=RSgetComponentProperties($componentId);
				$out.="<input type=\"submit\" value=\"$data[LABEL]\" name =\"form[$data[NAME]]\" id=\"$data[NAME]\" $data[ADDITIONALATTRIBUTES]>";
				if($data['RESET']=='YES') $out.="&nbsp;&nbsp;<input type=\"reset\" value=\"$data[RESETLABEL]\" name =\"form[$data[NAME]]\">";
			}break;
			case 'password':
			{
				$data=RSgetComponentProperties($componentId);
				$out.="<input type=\"password\" value=\"$data[DEFAULTVALUE]\" size=\"$data[SIZE]\" name =\"form[$data[NAME]]\" id=\"$data[NAME]\" $data[ADDITIONALATTRIBUTES]>";
			}break;
			case 'ticket':
			{
				$data=RSgetComponentProperties($componentId);
				$out.="<input type=\"hidden\" name =\"form[$data[NAME]]\" value=\"".RSgenerateString($data['LENGTH'],$data['CHARACTERS'])."\" $data[ADDITIONALATTRIBUTES]>";
			}break;
		}
		return $out;
	}

	function RSshowForm($formId,$val='',$validation='')
	{
		$RSadapter=$GLOBALS['RSadapter'];

		if(!isset($GLOBALS['ismodule'])) $GLOBALS['ismodule'] = 'head';

		$RSadapter->addHeadTag( _RSFORM_FRONTEND_REL_PATH . '/controller/functions.js','js', $GLOBALS['ismodule'] );
		$RSadapter->addHeadTag( _RSFORM_FRONTEND_REL_PATH . '/front.css','css', $GLOBALS['ismodule'] );

		//add the head tags for the calendar
		$calendars = RScomponentExists($formId, 6);//6 is the componentTypeId for calendar
		if(!empty($calendars))
		{
			foreach($calendars as $i=>$calendarComponentId)
			{
				$data = RSgetComponentProperties($calendarComponentId);
				$calendars['CALENDARLAYOUT'][$i] = $data['CALENDARLAYOUT'];
				$calendars['DATEFORMAT'][$i] = $data['DATEFORMAT'];
				if(!empty($_POST))
				{
					if($_POST['form'][$data['NAME']]!='')$calendars['VALUES'][$i] = $_POST['form'][$data['NAME']];// date('m/d/Y',strtotime($_POST['form'][$data['NAME']]));
					else $calendars['VALUES'][$i] = '';
				}else {
					$calendars['VALUES'][$i] = '';
				}
			}
			$calendarsLayout = "'".implode('\',\'', $calendars['CALENDARLAYOUT'])."'";
			$calendarsFormat = "'".implode('\',\'', $calendars['DATEFORMAT'])."'";
			$calendarsValues = "'".implode('\',\'', $calendars['VALUES'])."'";

			//check if it's a module

			$RSadapter->addHeadTag( _RSFORM_FRONTEND_REL_PATH . '/calendar/cal.js','js',$GLOBALS['ismodule'] );
			$RSadapter->addHeadTag( _RSFORM_FRONTEND_REL_PATH . "/calendar/calendar.css",'css',$GLOBALS['ismodule'] );
			$RSadapter->addHeadTag( _RSFORM_FRONTEND_SCRIPT_PATH.'?option=com_rsform&amp;task=showJs','js', $GLOBALS['ismodule'] );

			$calSetup = '';
		}

		$q="select FormLayout, ScriptDisplay from $RSadapter->tbl_rsform_forms where FormId='$formId'";
		$rez=mysql_query($q) or die($q.mysql_error());
		$r=mysql_fetch_assoc($rez);
		
		if(!isset($r['FormLayout'])) return 'No formId';
		
		$scriptDisplay = stripslashes($r['ScriptDisplay']);
		$formLayout=stripslashes($r['FormLayout']);

		$find=array();
		$replace=array();

		$q="select
			$RSadapter->tbl_rsform_properties.PropertyValue,
			$RSadapter->tbl_rsform_components.ComponentId
		 from $RSadapter->tbl_rsform_properties
		join $RSadapter->tbl_rsform_components on `$RSadapter->tbl_rsform_components`.ComponentId=`$RSadapter->tbl_rsform_properties`.ComponentId
		where $RSadapter->tbl_rsform_components.FormId='$formId' and $RSadapter->tbl_rsform_properties.PropertyName='NAME'
		";
		//echo $q;
		$rez=mysql_query($q) or die(mysql_error());
		//echo mysql_affected_rows();
		//Caption
		while($r=mysql_fetch_assoc($rez))
		{

			array_push($find,'{'.$r['PropertyValue'].':caption}');
			array_push($replace,RSfrontComponentCaption(RSresolveComponentName($r['PropertyValue'],$formId)));
		}
		$formLayout=str_replace($find,$replace,$formLayout);

		//Body
		mysql_data_seek($rez,0);
		while($r=mysql_fetch_assoc($rez))
		{
			array_push($find,'{'.$r['PropertyValue'].':body}');
			array_push($replace,RSfrontComponentBody($formId,RSresolveComponentName($r['PropertyValue'],$formId),$val));
		}
		$formLayout=str_replace($find,$replace,$formLayout);

		//Description
		mysql_data_seek($rez,0);
		while($r=mysql_fetch_assoc($rez))
		{
			array_push($find,'{'.$r['PropertyValue'].':description}');
			array_push($replace,RSfrontComponentDescription(RSresolveComponentName($r['PropertyValue'],$formId)));
		}
		$formLayout=str_replace($find,$replace,$formLayout);
		mysql_data_seek($rez,0);
		//Validation rules hidden
		while($r=mysql_fetch_assoc($rez))
		{
			array_push($find,'{'.$r['PropertyValue'].':validation}');
			array_push($replace,RSfrontComponentValidationMessage(RSresolveComponentName($r['PropertyValue'],$formId),$validation));
		}

		$formLayout = str_replace($find,$replace,$formLayout);
		$formLayout = RSfrontLayout($formId, $formLayout);
		$formLayout.="<input type=\"hidden\" name=\"form[formId]\" value=\"$formId\">";
		$formLayout = '<form method="post" id="userForm" enctype="multipart/form-data">'.$formLayout.'</form>';
		if(!empty($calendars)) $formLayout .= '<script language="javascript">YAHOO.util.Event.addListener(window, "load", init(Array('.$calendarsLayout.'),Array('.$calendarsFormat.'),Array('.$calendarsValues.')));</script>' ;
		
		
		eval(stripslashes($scriptDisplay));
		return ''.$formLayout;
	}

	function RSshowThankyouMessage($formId)
	{
		$RSadapter=$GLOBALS['RSadapter'];
		$output = '';

		//check return url
		$query = mysql_query("SELECT ReturnUrl FROM `{$RSadapter->tbl_rsform_forms}` WHERE `formId` = '$formId'");
		$returnUrl = mysql_fetch_assoc($query);
		
		if(!isset($_SESSION['form'][$formId]['submissionId']))$_SESSION['form'][$formId]['submissionId'] = '';
		$returnUrl['ReturnUrl'] = RSprocessField($returnUrl['ReturnUrl'],$_SESSION['form'][$formId]['submissionId']);
		
		if(!empty($returnUrl['ReturnUrl'])) $goto = "document.location='".stripslashes($returnUrl['ReturnUrl'])."';";
		else $goto = 'document.location.reload();';

		$output .= $_SESSION['form'][$formId]['thankYouMessage'].sprintf(_RSFORM_FRONTEND_THANKYOU_BUTTON,$goto);
		unset($_SESSION['form'][$formId]['thankYouMessage']);

		return $output;
	}

	function RSprocessForm($formId)
	{
		$RSadapter=$GLOBALS['RSadapter'];
		$user = $RSadapter->user();
		
		$q="select ScriptProcess from `{$RSadapter->tbl_rsform_forms}` where FormId={$_POST['form']['formId']}";
		$rez=mysql_query($q) or die(mysql_error().$q.'<br>');
		$r=mysql_fetch_assoc($rez);
		
		$invalid=array();
		$invalid=RSvalidateForm($_POST['form']['formId']);

		if(!empty($invalid)) return $invalid;//showForm($formId,$_POST['form'],$invalid);
		
		
		eval(stripslashes($r['ScriptProcess']));
		
		if(empty($invalid))
		{
			$userEmail=array(
				'to'=>'',
				'from'=>'',
				'fromName'=>'',
				'text'=>'',
				'subject'=>'',
				'files' =>array()
				);
			$adminEmail=array(
				'to'=>'',
				'from'=>'',
				'fromName'=>'',
				'text'=>'',
				'subject'=>'',
				'files'=>array()
				);

			$db='';
			$dest=array();
			$tmp_name=array();
			$name=array();
			$fieldName=array();
			$q="insert into `{$RSadapter->tbl_rsform_submissions}` (`FormId`, `DateSubmitted`, `UserIp`, `Username`, `UserId`) VALUES ('{$_POST['form']['formId']}',now(),'{$_SERVER['REMOTE_ADDR']}','{$user['username']}','{$user['id']}')";
			mysql_query($q) or die(mysql_error());
			$SubmissionId = mysql_insert_id();
			if(isset($_FILES['form']['tmp_name']))
			{
				if(is_array($_FILES['form']['tmp_name']))
				{
					foreach($_FILES['form']['name'] as $key=>$val)
					{
						if(!empty($_FILES['form']['name'][$key]))
						{
							array_push($dest,RSgetFileDestination($key,$_POST['form']['formId']));
							array_push($name,$val);
							array_push($fieldName,$key);
						}
					}
					foreach($_FILES['form']['tmp_name'] as $key=>$val)
					{
						if(!empty($_FILES['form']['name'][$key]))
						{
							array_push($tmp_name,$val);
						}
					}
					for($i=0;$i<count($dest);$i++)
					{
						if(isset($tmp_name[$i]))
						{
							$prop=RSgetComponentProperties(RSresolveComponentName($fieldName[$i],$formId));
							$timestamp=uniqid('');
							move_uploaded_file($tmp_name[$i],$dest[$i].$timestamp.'-'.$name[$i]);
							$db=$dest[$i].$timestamp.'-'.$name[$i];
							$q="insert into `{$RSadapter->tbl_rsform_submission_values}` (`SubmissionId`, `FieldName`, `FieldValue`) VALUES ('{$SubmissionId}','$fieldName[$i]','$db')";
							if($prop['ATTACHUSEREMAIL']=='YES') array_push($userEmail['files'],$db);
							if($prop['ATTACHADMINEMAIL']=='YES') array_push($adminEmail['files'],$db);

							mysql_query($q) or die(mysql_error());
						}
					}
				}
			}

			foreach ($_POST['form'] as $key=>$val)
			{
				$val = (is_array($val) ? implode('\n',$val) : $val);
				$key = RScleanVar($key);
				$val = RScleanVar($val);
				$q="insert into `{$RSadapter->tbl_rsform_submission_values}` (`SubmissionId`, `FieldName`, `FieldValue`) VALUES ('{$SubmissionId}','".$key."','".$val."')";
				mysql_query($q) or die(mysql_error());
				//echo $q.'<br>';
			}

			$q="select UserEmailSubject,  UserEmailText, UserEmailTo, UserEmailFrom, UserEmailFromName, UserEmailMode, AdminEmailText, AdminEmailSubject, AdminEmailTo, AdminEmailFrom, AdminEmailFromName, AdminEmailMode, Thankyou from `{$RSadapter->tbl_rsform_forms}` where FormId={$_POST['form']['formId']}";
			$rez=mysql_query($q) or die(mysql_error().$q.'<br>');
			$r=mysql_fetch_assoc($rez);
			$userEmail['to']=RSprocessField(stripslashes($r['UserEmailTo']),$SubmissionId);
			$userEmail['subject']=RSprocessField(stripslashes($r['UserEmailSubject']),$SubmissionId);
			$userEmail['from']=RSprocessField(stripslashes($r['UserEmailFrom']),$SubmissionId);
			$userEmail['fromName']=RSprocessField(stripslashes($r['UserEmailFromName']),$SubmissionId);
			$userEmail['text']=RSprocessField(stripslashes($r['UserEmailText']),$SubmissionId);
			$userEmail['mode']=$r['UserEmailMode'];

			$adminEmail['to']=RSprocessField(stripslashes($r['AdminEmailTo']),$SubmissionId);
			$adminEmail['subject']=RSprocessField(stripslashes($r['AdminEmailSubject']),$SubmissionId);
			$adminEmail['from']=RSprocessField(stripslashes($r['AdminEmailFrom']),$SubmissionId);
			$adminEmail['fromName']=RSprocessField(stripslashes($r['AdminEmailFromName']),$SubmissionId);
			$adminEmail['text']=RSprocessField(stripslashes($r['AdminEmailText']),$SubmissionId);
			$adminEmail['mode']=$r['AdminEmailMode'];

			//mail users
			$recipients = explode(',',$userEmail['to']);
			if(!empty($recipients))
			{
				foreach($recipients as $recipient)
				{
					if(!empty($recipient))$RSadapter->mail($userEmail['from'], $userEmail['fromName'], $recipient, $userEmail['subject'], $userEmail['text'], $userEmail['mode'], null, null, $userEmail['files']  );
				}
			}

			//mail admins
			$recipients = explode(',',$adminEmail['to']);
			if(!empty($recipients))
			{
				foreach($recipients as $recipient)
				{
					if(!empty($recipient))$RSadapter->mail($adminEmail['from'], $adminEmail['fromName'], $recipient, $adminEmail['subject'], $adminEmail['text'], $adminEmail['mode'], null, null, $adminEmail['files']  );
				}
			}

			
			$_SESSION['form'][$formId]['thankYouMessage']=RSprocessField($r['Thankyou'],$SubmissionId);
			$_SESSION['form'][$formId]['submissionId'] = $SubmissionId;
			$RSadapter->redirect($_SERVER['REQUEST_URI']);

		}

		return false;
	}

	
	function RScleanVar($var){
		return mysql_real_escape_string(stripslashes($var));
	}
	
	function RSgetValidationRule($value,$formId)
	{
		$RSadapter=$GLOBALS['RSadapter'];

		$componentId=RSresolveComponentName($value,$formId);
		$q="
		SELECT
			$RSadapter->tbl_rsform_properties.PropertyValue
		FROM $RSadapter->tbl_rsform_properties
		join $RSadapter->tbl_rsform_components on $RSadapter->tbl_rsform_properties.ComponentId=$RSadapter->tbl_rsform_components.ComponentId
		where $RSadapter->tbl_rsform_components.FormId='$formId' and $RSadapter->tbl_rsform_properties.PropertyName='VALIDATIONRULE' and $RSadapter->tbl_rsform_properties.ComponentId='$componentId';
		";
		$rez=mysql_query($q) or die(mysql_error()."$q<br>");
		$r=mysql_fetch_assoc($rez);
		if(!empty($r['PropertyValue'])) return $r['PropertyValue'];
	}

	function RSgetRequired($value,$formId)
	{
		$RSadapter=$GLOBALS['RSadapter'];

		$componentId=RSresolveComponentName($value,$formId);
		$q="
		SELECT
			$RSadapter->tbl_rsform_properties.PropertyValue
		FROM $RSadapter->tbl_rsform_properties
		join $RSadapter->tbl_rsform_components on $RSadapter->tbl_rsform_properties.ComponentId=$RSadapter->tbl_rsform_components.ComponentId
		where $RSadapter->tbl_rsform_components.FormId='$formId' and $RSadapter->tbl_rsform_properties.PropertyName='REQUIRED' and $RSadapter->tbl_rsform_properties.ComponentId='$componentId';
		";
		$rez=mysql_query($q) or die(mysql_error()."$q<br>");
		$r=mysql_fetch_assoc($rez);
		if(!empty($r['PropertyValue'])) return $r['PropertyValue'];
	}
	function RSvalidateForm($formId)
	{
		$RSadapter=$GLOBALS['RSadapter'];

		$invalid=array();
		$q="select ComponentId from $RSadapter->tbl_rsform_components where FormId='$formId' AND Published=1";
		$rez=mysql_query($q) or die(mysql_error());
		while($r=mysql_fetch_assoc($rez))
		{
			$data=RSgetComponentProperties($r['ComponentId']);
			$required=RSgetRequired($data['NAME'],$formId);
			$validationRule=RSgetValidationRule($data['NAME'],$formId);

			if(RSgetComponentTypeId($r['ComponentId'])==8)
			{
				if($_POST['form'][$data['NAME']]!=$_SESSION['CAPTCHA'.$r['ComponentId']])
					array_push($invalid,$data['componentId']);
			}
			if(RSgetComponentTypeId($r['ComponentId'])==9)
			{
				if(!empty($_FILES['form']['tmp_name'][$data['NAME']]))
				{
					if(!empty($_FILES['form']['error']) && $_FILES['form']['error'][$data['NAME']]==0)
					{
						$buf=explode('.',$_FILES['form']['name'][$data['NAME']]);
						$m='#'.$buf[count($buf)-1].'#';
						//echo $m;
						if(!empty($data['ACCEPTEDFILES']) && !preg_match(strtolower($m),strtolower($data['ACCEPTEDFILES'])) || (filesize($_FILES['form']['tmp_name'][$data['NAME']])>($data['FILESIZE']*1000)&&($data['FILESIZE']>0)))
							array_push($invalid,$data['componentId']);
					}
					else
						array_push($invalid,$data['componentId']);
				}else{
					if($data['REQUIRED']=='YES')
					{
						array_push($invalid,$data['componentId']);
					}
				}
			continue;
			}

			if(!isset($_POST['form'][$data['NAME']]) && $required=='YES')
			{
				array_push($invalid,$data['componentId']);
				continue;
			}
			if(isset($_POST['form'][$data['NAME']]) && empty($_POST['form'][$data['NAME']]) && $required=='YES')
			{
				array_push($invalid,$data['componentId']);
				continue;
			}

			if(isset($_POST['form'][$data['NAME']]) && is_array($_POST['form'][$data['NAME']]))
			{
				$valid=implode('',$_POST['form'][$data['NAME']]);
				if(empty($valid)) {
					array_push($invalid,$data['componentId']);
				}
				continue;
			}


			if(isset($_POST['form'][$data['NAME']]) && $validationRule!='none' && is_callable($validationRule) && !call_user_func($validationRule,$_POST['form'][$data['NAME']]))
			{
				array_push($invalid,$data['componentId']);
				continue;
			}
		}
		return $invalid;
	}

	function RSgetComponentTypeId($componentId)
	{
		$RSadapter=$GLOBALS['RSadapter'];

		$q="select ComponentTypeId from $RSadapter->tbl_rsform_components where ComponentId='$componentId'";
		$rez=mysql_query($q) or die(mysql_error());
		$r=mysql_fetch_assoc($rez);
		return $r['ComponentTypeId'];
	}
	function RSresolveComponentTypeId($componentTypeId)
	{
		$RSadapter=$GLOBALS['RSadapter'];

		$q="select ComponentTypeName from $RSadapter->tbl_rsform_component_types where ComponentTypeId='$componentTypeId'";
		$rez=mysql_query($q) or die(mysql_error());
		$r=mysql_fetch_assoc($rez);
		return $r['ComponentTypeName'];
	}
	function RSgetComponentTypeIdByName($componentName,$formId)
	{
		$RSadapter=$GLOBALS['RSadapter'];

		$q="
		select $RSadapter->tbl_rsform_components.ComponentTypeId
		from $RSadapter->tbl_rsform_components
		left join $RSadapter->tbl_rsform_properties on $RSadapter->tbl_rsform_properties.ComponentId=$RSadapter->tbl_rsform_components.ComponentId
		where $RSadapter->tbl_rsform_properties.PropertyName='NAME' and $RSadapter->tbl_rsform_properties.PropertyValue='$componentName' and $RSadapter->tbl_rsform_components.FormId='$formId';
		";
		$rez=mysql_query($q) or die(mysql_error());
		$r=mysql_fetch_assoc($rez);
		return $r['ComponentTypeId'];
	}

	function RSgetFileDestination($componentName,$formId)
	{
		$RSadapter=$GLOBALS['RSadapter'];

		$componentId=RSresolveComponentName($componentName,$formId);
		$q="select PropertyValue from $RSadapter->tbl_rsform_properties where PropertyName='DESTINATION' and ComponentId='$componentId'";
		$rez=mysql_query($q) or die(mysql_error());
		$r=mysql_fetch_assoc($rez);
		return $r['PropertyValue'];
	}
	function RScomponentExists($formId,$componentTypeId)
	{
		$RSadapter=$GLOBALS['RSadapter'];

		$q="select ComponentId from $RSadapter->tbl_rsform_components where ComponentTypeId='$componentTypeId' and FormId='$formId' AND Published='1'";
		$rez=mysql_query($q) or die(mysql_error());
		$output=array();
		while($r=mysql_fetch_assoc($rez))
		{
			array_push($output,$r['ComponentId']);
		}
		return $output;
	}

	function RSgenerateString($length, $characters, $type='Random')
	{
		if($type == 'Random')
		{

			switch($characters)
			{
				case 'ALPHANUMERIC':
				default:
			  		$possible = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
				break;
				case 'ALPHA':
					$possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
				break;
				case 'NUMERIC':
					$possible = "0123456789";
				break;
			}

			if($length<1||$length>255) $length = 8;
			  $key = "";
			  $i = 0;
			  while ($i < $length) {
			    $key .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			    $i++;
			  }
		}
		if($type == 'Sequential')
		{

		}
		return $key;
	}

	function RSprocessField($text,$submissionId)
	{
		$RSadapter=$GLOBALS['RSadapter'];

		$result=$text;

		//get form id
		$query = mysql_query("SELECT * FROM $RSadapter->tbl_rsform_submissions WHERE SubmissionId = '$submissionId'") or die(mysql_error());
		$Submission = mysql_fetch_assoc($query);
		$formId = $Submission['FormId'];

		//get components
		$query = mysql_query("SELECT ComponentId FROM $RSadapter->tbl_rsform_components WHERE FormId = '$formId' AND Published = 1");
		while ($Component = mysql_fetch_array($query))
		{
			$properties = RSgetComponentProperties($Component['ComponentId']);

			//{component:caption}
			$replace='{'.$properties['NAME'].':caption'.'}';
			if(!isset($properties['CAPTION'])) $properties['CAPTION'] = '';
			$result=str_replace($replace,$properties['CAPTION'],$result);

			//{component:name}
			$replace='{'.$properties['NAME'].':name'.'}';
			$result=str_replace($replace,$properties['NAME'],$result);

			//{component:value}
			$submission_query = mysql_query("SELECT FieldValue FROM $RSadapter->tbl_rsform_submission_values WHERE FieldName = '{$properties['NAME']}' AND SubmissionId = '$submissionId'") or die(mysql_error());
			$SubmissionValue = mysql_fetch_assoc($submission_query);
			$replace='{'.$properties['NAME'].':value'.'}';

			if(isset($SubmissionValue['FieldValue']))
			{
				if(RSgetComponentTypeId($Component['ComponentId'])==9)
				{
					//get file
					if(stristr($SubmissionValue['FieldValue'],'/')) $filename = explode('/',$SubmissionValue['FieldValue']);
					else $filename = explode("\\",$SubmissionValue['FieldValue']);
					$filename = $filename[count($filename)-1];
					$SubmissionValue['FieldValue'] = $filename;
				}
				$result=str_replace($replace,$SubmissionValue['FieldValue'],$result);
			}
			else
			{
				$result=str_replace($replace,'',$result);
			}
		}
		

		$user = $RSadapter->user($Submission['UserId']);
		$result = str_replace('{global:username}',$user['username'],$result);
		$result = str_replace('{global:userid}',$user['id'],$result);
		$result = str_replace('{global:useremail}',$user['email'],$result);
		$result = str_replace('{global:fullname}',$user['fullname'],$result);
		$result = str_replace('{global:userip}',$_SERVER['REMOTE_ADDR'],$result);
		$result = str_replace('{global:date_added}',$Submission['DateSubmitted'],$result);
		$result = str_replace('{global:sitename}',$RSadapter->config['sitename'],$result);
		$result = str_replace('{global:siteurl}',$RSadapter->config['live_site'],$result);

		return stripslashes($result);
	}

	function RSgetFormLayoutName($formId)
	{
		$RSadapter=$GLOBALS['RSadapter'];

		$q="select FormLayoutName from $RSadapter->tbl_rsform_forms where FormId='$formId'";
		$rez=mysql_query($q) or die(mysql_error());
		$r=mysql_fetch_assoc($rez);
		return stripslashes($r['FormLayoutName']);
	}

	function RSreturnCheckedLayoutName($formId,$layoutName)
	{
		$RSadapter=$GLOBALS['RSadapter'];

		$q="select FormLayoutName from $RSadapter->tbl_rsform_forms where FormId='$formId'";
		$rez=mysql_query($q) or die(mysql_error());
		$r=mysql_fetch_assoc($rez);
		if(stripslashes($r['FormLayoutName'])==$layoutName) echo 'checked';
	}

	function RSremoveForm($formId)
	{
		$RSadapter=$GLOBALS['RSadapter'];

		$q="delete $RSadapter->tbl_rsform_properties where ComponentId IN
			(
				select
					ComponentId
				from $RSadapter->tbl_rsform_components
				where FormId='$formId'
			)";
		$q="delete $RSadapter->tbl_rsform_components where FormId='$formId'";
		$q="delete $RSadapter->tbl_rsform_forms where FormId='$formId'";
	}

	function RScopyForm($formId)
	{
		$RSadapter=$GLOBALS['RSadapter'];

		$q="insert into $RSadapter->tbl_rsform_forms
		(`FormName`,`FormLayout`,`FormLayoutName`,`FormLayoutAutogenerate`,`FormTitle`,`Published`,`Lang`,`ReturnUrl`,`Thankyou`,`UserEmailText`,`UserEmailTo`,`UserEmailFrom`,`UserEmailFromName`,`UserEmailSubject`,`UserEmailMode`,`AdminEmailText`,`AdminEmailTo`,`AdminEmailFrom`,`AdminEmailFromName`,`AdminEmailSubject`,`AdminEmailMode`,`ScriptProcess`,`ScriptDisplay`)
		select
		`FormName`,`FormLayout`,`FormLayoutName`,`FormLayoutAutogenerate`,`FormTitle`,`Published`,`Lang`,`ReturnUrl`,`Thankyou`,`UserEmailText`,`UserEmailTo`,`UserEmailFrom`,`UserEmailFromName`,`UserEmailSubject`,`UserEmailMode`,`AdminEmailText`,`AdminEmailTo`,`AdminEmailFrom`,`AdminEmailFromName`,`AdminEmailSubject`,`AdminEmailMode`,`ScriptProcess`,`ScriptDisplay`

		from $RSadapter->tbl_rsform_forms where $RSadapter->tbl_rsform_forms.FormId='$formId'";
		mysql_query($q) or die(mysql_error()."<br>$q");
		$newFormId=mysql_insert_id();

		$q="update $RSadapter->tbl_rsform_forms set FormName=CONCAT(FormName,' copy'),FormTitle=CONCAT(FormTitle,' copy') where FormId='$newFormId'";
		mysql_query($q) or die(mysql_error());

		$q="select * from $RSadapter->tbl_rsform_components where FormId='$formId'";
		$rez=mysql_query($q) or die(mysql_error());
		while($r=mysql_fetch_assoc($rez))
		{
			$componentId=$r['ComponentId'];
			$q1="insert into $RSadapter->tbl_rsform_components (FormId,ComponentTypeId,`Order`) values ('$newFormId','{$r['ComponentTypeId']}','{$r['Order']}')";
			mysql_query($q1) or die(mysql_error());
			$newComponentId=mysql_insert_id();

			$q2="select * from $RSadapter->tbl_rsform_properties where ComponentId='$componentId'";
			$rez2=mysql_query($q2) or die(mysql_error());
			while($r2=mysql_fetch_assoc($rez2))
			{
				$q3="insert into $RSadapter->tbl_rsform_properties (PropertyName,PropertyValue,ComponentId) values ('$r2[PropertyName]','$r2[PropertyValue]','$newComponentId')";
				mysql_query($q3) or die(mysql_error());
			}

		}
	}

	function RScopyComponent($sourceComponentId,$destinationFormId)
	{
		$RSadapter=$GLOBALS['RSadapter'];

		$q="select * from $RSadapter->tbl_rsform_components where ComponentId='$sourceComponentId'";
		$rez=mysql_query($q) or die(mysql_error());
		$r=mysql_fetch_assoc($rez);
		$q="insert into $RSadapter->tbl_rsform_components (`FormId`,`ComponentTypeId`,`Order`,`Published`) values ('$destinationFormId','$r[ComponentTypeId]','$r[Order]','$r[Published]')";
		mysql_query($q) or die(mysql_error());
		$newComponentId=mysql_insert_id();

		$q="select * from $RSadapter->tbl_rsform_properties where ComponentId='$sourceComponentId'";
		$rez=mysql_query($q) or die(mysql_error());
		while($r=mysql_fetch_assoc($rez))
		{
			if($r['PropertyName'] == 'NAME') $r['PropertyValue'] .= ' copy';
			$q1="insert into $RSadapter->tbl_rsform_properties (ComponentId,PropertyName,PropertyValue) values ('$newComponentId','$r[PropertyName]','$r[PropertyValue]')";
			mysql_query($q1) or die(mysql_error());
		}
	}

	function RSlistComponents($formId)
	{
		$RSadapter=$GLOBALS['RSadapter'];

		$components=array();
		$q="select $RSadapter->tbl_rsform_properties.PropertyValue

		from $RSadapter->tbl_rsform_properties

		left join $RSadapter->tbl_rsform_components on $RSadapter->tbl_rsform_components.ComponentId=$RSadapter->tbl_rsform_properties.ComponentId

		where
			$RSadapter->tbl_rsform_components.FormId='$formId' and
			$RSadapter->tbl_rsform_components.Published='1' and
			$RSadapter->tbl_rsform_properties.PropertyName='NAME'
		order by
			$RSadapter->tbl_rsform_components.`Order`;
		";
		$rez=mysql_query($q) or die(mysql_error());
		while($r=mysql_fetch_assoc($rez))
		{
			array_push($components,$r['PropertyValue']);
		}
		return $components;
	}


function RSbackupCreateXMLfile($option, $files, $filename)
{
	$RSadapter=$GLOBALS['RSadapter'];
	$user = $RSadapter->user();

    //create the xml file
$xml =
'<?xml version="1.0" encoding="iso-8859-1"?>
<RSinstall type="rsformbackup">
<name>RSform backup</name>
<creationDate></creationDate>
<author></author>
<copyright></copyright>
<authorEmail></authorEmail>
<authorUrl></authorUrl>
<version>'._RSFORM_VERSION.'</version>
<description>RSform Backup</description>
<tasks></tasks>
</RSinstall>';
    $xml = str_replace('<creationDate></creationDate>','<creationDate>'.date('Y-m-d').'</creationDate>',$xml);
    $xml = str_replace('<author></author>','<author>'.$user['username'].'</author>',$xml);
    $xml = str_replace('<copyright></copyright>','<copyright> (C) '.date('Y').' '.$RSadapter->config['live_site'].'</copyright>',$xml);
    $xml = str_replace('<authorEmail></authorEmail>','<authorEmail>'.$RSadapter->config['mail_from'].'</authorEmail>',$xml);
    $xml = str_replace('<authorUrl></authorUrl>','<authorUrl>'.$RSadapter->config['live_site'].'</authorUrl>',$xml);

    $tasks = array();
    $tasks[] = "\t".'<task type="query">'."TRUNCATE TABLE `{$RSadapter->tbl_rsform_components}`".'</task>';
    $tasks[] = "\t".'<task type="query">'."TRUNCATE TABLE `{$RSadapter->tbl_rsform_component_types}`".'</task>';
    $tasks[] = "\t".'<task type="query">'."TRUNCATE TABLE `{$RSadapter->tbl_rsform_component_type_fields}`".'</task>';
    $tasks[] = "\t".'<task type="query">'."TRUNCATE TABLE `{$RSadapter->tbl_rsform_config}`".'</task>';
    $tasks[] = "\t".'<task type="query">'."TRUNCATE TABLE `{$RSadapter->tbl_rsform_forms}`".'</task>';
    $tasks[] = "\t".'<task type="query">'."TRUNCATE TABLE `{$RSadapter->tbl_rsform_properties}`".'</task>';
    $tasks[] = "\t".'<task type="query">'."TRUNCATE TABLE `{$RSadapter->tbl_rsform_submissions}`".'</task>';
    $tasks[] = "\t".'<task type="query">'."TRUNCATE TABLE `{$RSadapter->tbl_rsform_submission_values}`".'</task>';

    //LOAD COMPONENTS
    $query = mysql_query("SELECT * FROM `$RSadapter->tbl_rsform_components`");
    while($component_row = mysql_fetch_array($query,MYSQL_ASSOC))
    {
         $tasks[] = RSxmlReturnQuery($RSadapter->tbl_rsform_components,$component_row);
    }
    //LOAD COMPONENT_TYPES
    $query = mysql_query("SELECT * FROM `$RSadapter->tbl_rsform_component_types`");
    while($component_row = mysql_fetch_array($query,MYSQL_ASSOC))
    {
         $tasks[] = RSxmlReturnQuery($RSadapter->tbl_rsform_component_types,$component_row);
    }
    //LOAD COMPONENT_TYPE_FIELDS
    $query = mysql_query("SELECT * FROM `{$RSadapter->tbl_rsform_component_type_fields}`");
    while($component_row = mysql_fetch_array($query,MYSQL_ASSOC))
    {
         $tasks[] = RSxmlReturnQuery($RSadapter->tbl_rsform_component_type_fields,$component_row);
    }
    //LOAD CONFIG
    $query = mysql_query("SELECT * FROM `$RSadapter->tbl_rsform_config`");
    while($component_row = mysql_fetch_array($query,MYSQL_ASSOC))
    {
         $tasks[] = RSxmlReturnQuery($RSadapter->tbl_rsform_config,$component_row);
    }
    //LOAD FORMS
    $query = mysql_query("SELECT * FROM `{$RSadapter->tbl_rsform_forms}`");
    while($component_row = mysql_fetch_array($query,MYSQL_ASSOC))
    {
         $tasks[] = RSxmlReturnQuery($RSadapter->tbl_rsform_forms,$component_row);
    }
    //LOAD PROPERTIES
    $query = mysql_query("SELECT * FROM `{$RSadapter->tbl_rsform_properties}`");
    while($component_row = mysql_fetch_array($query,MYSQL_ASSOC))
    {
         $tasks[] = RSxmlReturnQuery($RSadapter->tbl_rsform_properties,$component_row);
    }
    //LOAD SUBMISSIONS
    $query = mysql_query("SELECT * FROM `{$RSadapter->tbl_rsform_submissions}`");
    while($component_row = mysql_fetch_array($query,MYSQL_ASSOC))
    {
         $tasks[] = RSxmlReturnQuery($RSadapter->tbl_rsform_submissions,$component_row);
    }
    //LOAD SUBMISSION_VALUES
    $query = mysql_query("SELECT * FROM `{$RSadapter->tbl_rsform_submission_values}`");
    while($component_row = mysql_fetch_array($query,MYSQL_ASSOC))
    {
         $tasks[] = RSxmlReturnQuery($RSadapter->tbl_rsform_submission_values,$component_row);
    }

    $task_html = implode("\r\n",$tasks);
    $xml = str_replace('<tasks></tasks>','<tasks>'."\r\n".$task_html."\r\n".'</tasks>',$xml);

    //write the file
    touch($filename);
    if (!$handle = fopen($filename, 'w')) exit;
    if (fwrite($handle, $xml) === FALSE) exit;
    fclose($handle);
}



function RSxmlReturnQuery($tb_name, $row)
{

    $fields = array();
    $values = array();

    foreach($row as $k=>$v) {
        $fields[] = '`' . $k . '`';
        $values[] = "'" . addslashes($v) . "'";
    }

    $xml = 'INSERT INTO `' . $tb_name . '` (' . implode(',',$fields) . ') VALUES (' . implode(',',$values) . ' )';
    $xml = str_replace("\r",'',$xml);
    $xml = str_replace("\n",'\\n',$xml);

    return "\t".'<task type="query">'.RSxmlentities($xml).'</task>';
}
function RSxmlentities($string, $quote_style=ENT_QUOTES)
{
    static $trans;
    if (!isset($trans)) {
        $trans = get_html_translation_table(HTML_ENTITIES, $quote_style);
        foreach ($trans as $key => $value)
            $trans[$key] = '&#'.ord($key).';';
        // dont translate the '&' in case it is part of &xxx;
        //$trans[chr(38)] = '&';
    }
    // after the initial translation, _do_ map standalone '&' into '&#38;'
    return preg_replace("/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,3};)/","&#38;" , strtr($string, $trans));
}/*
function RSxmlentities ( $string, $null )
{
    return str_replace ( array ( '&', '"', "'", '<', '>' ), array ( '&amp;' , '&quot;', '&apos;' , '&lt;' , '&gt;' ), $string );
}
*/
function RSRmkdir($path)
{
    $exp=explode("/",$path);
    $way='';
    foreach($exp as $n){
        $way.=$n.'/';
        if(!file_exists($way))
            @mkdir($way);
    }
}

function RSuploadFile( $filename, $userfile_name, &$msg )
{
    $RSadapter=$GLOBALS['RSadapter'];
    $baseDir = $RSadapter->processPath( $RSadapter->config['absolute_path'] . '/media' );

    if (file_exists( $baseDir )) {
        if (is_writable( $baseDir )) {
            if (move_uploaded_file( $filename, $baseDir . $userfile_name )) {
            	$RSadapter->chmod( $baseDir . $userfile_name );
            	return true;/*
                if () {

                } else {
                    $msg = 'Failed to change the permissions of the uploaded file.';
                }*/
            } else {
                $msg = 'Failed to move uploaded file to <code>/media</code> directory.';
            }
        } else {
            $msg = 'Upload failed as <code>/media</code> directory is not writable.';
        }
    } else {
        $msg = 'Upload failed as <code>/media</code> directory does not exist.'.$baseDir;
    }
    return false;
}


function RSprocessTask($option, $task, $uploaddir){
    //$type,$value,$dest
    $RSadapter=$GLOBALS['RSadapter'];

    $type    	= $task->getAttribute('type');
    $source    	= $task->getAttribute('source');
    $value   	= $task->getText();
    
    //$source 	= eval('return "'.$source.'";');
    //$value	= eval('return "'.$value.'";');
     
    switch ($type){
        case 'mkdir':
            RSRmkdir($RSadapter->config['absolute_path'].$value);
            //echo 'MKDIR OK '.$value;
            return true;
        break;
        case 'query':
        	$value = str_replace('{PREFIX}',$RSadapter->config['dbprefix'], $value);
        	if(mysql_query(html_entity_decode($value)))
        	{
                return true;
            }else{
                echo 'QUERY ERROR '.$value."<br/>";
                return false;
            }

        break;
        case 'copy':
            if($value!=''){

                $rfile = @fopen ($uploaddir.$source, "r");
                if (!$rfile) {
                    echo 'FOPEN ERROR '.$uploaddir.$source.". Make sure the file exists.<br/>";
                    return false;
                }else{
                    $filecontents = @fread($rfile, filesize($uploaddir.$source));
                    $filename = $RSadapter->config['absolute_path'].'/'.$value;

                    //check if folder exists, else mkdir it.
                    $path = str_replace('\\','/',$filename);
                    $path = explode('/',$path);
                    unset($path[count($path)-1]);
                    $path = implode('/',$path);
                    if(!is_dir($path)) RSRmkdir($path);
					@chmod($path,0777);
                    if (!$handle = @fopen($filename, 'w')) {
                        echo 'FWRITE OPEN ERROR '.$filename.". Make sure there are write permissions (777)<br/>";
                        return false;
                        // exit;
                    }

                    // Write $filecontents to our opened file.
                    if (fwrite($handle, $filecontents) === FALSE) {
                        echo 'FWRITE ERROR '.$filename.". Make sure there are write permissions (777)<br/>";
                        return false;
                    }
                    //echo 'COPY OK '.$value;
                    return true;

                    fclose($handle);
                }
            }
        break;
        case 'rename':
        	if($value!=''){
        		$oldfile = $uploaddir.$source;
        		$newfile = $RSadapter->config['absolute_path'].'/'.$value;
        		$rename = @rename($oldfile,$newfile);
        		if(!$rename){
        			 echo 'RENAME ERROR '.$newfile."<br/>";
                     return false;
        		}
        	}
        break;
        case 'eval':
        	eval($value);
        	return true;
        break;
        case 'delete':
            $filename = $RSadapter->config['absolute_path'].$value;
            if(file_exists($filename)){
                if(is_dir($filename)){
                    rmdir($filename);
                }else{
                    unlink($filename);
                }
                //echo 'DELETE OK '.$value;
                return true;
            }else{
                echo 'DELETE ERROR '.$value."<br/>";
                return false;
            }
        break;

    }
}


function RSparse_mysql_dump($file)
{
	$RSadapter=$GLOBALS['RSadapter'];
	$message = '';

	$file_content = file($file);
	foreach($file_content as $sql_line)
	{
		if(trim($sql_line) != "" && strpos($sql_line, "--") === false)
		{
			$sql_line = str_replace('{PREFIX}',$RSadapter->config['dbprefix'], $sql_line);
	   		mysql_query($sql_line) or $message .= '<pre>'.$sql_line.mysql_error().'</pre><br/>';
	 	}
	}

	if($message == '') return 'ok';
	else return $message;
}

function RSmigrationChangePlaceholdersValues($fieldname, $formArray)
{
	foreach($formArray as $key=>$value)
	{
		$formArray[$key] = str_replace('{'.$fieldname.'}','{'.$fieldname.':value}',$formArray[$key]);
	}
	return $formArray;
}

function RSmigrationParseDefaultValue($inputtype, $text, $componentId)
{
	switch($inputtype)
	{
		case 3:
		case 4:
		case 5:
			$text = str_replace(",","\n", $text);
			$text = str_replace('{checked}','[c]',$text);
			$text = "('', '$componentId', 'ITEMS', '".$text."'),";
		break;	
		case 6:
			$text = "('', '$componentId', 'CALENDARLAYOUT', 'FLAT'),";	
			$text .= "('', '$componentId', 'DATEFORMAT', 'dd.mm.yyyy'),";	
		break;
		case 8:
			$text = "('', '$componentId', 'LENGTH', '4'),";	
			$text .= "('', '$componentId', 'BACKGROUNDCOLOR', '#FFFFFF'),";	
			$text .= "('', '$componentId', 'TEXTCOLOR', '#000000'),";	
			$text .= "('', '$componentId', 'TYPE', 'ALPHA'),";	
		break;
		case 7:
		case 12:
		case 13:
			$text = "('', '$componentId', 'LABEL', '".$text."'),";
		break;
		case 10:
			$text = "('', '$componentId', 'TEXT', '".$text."'),";
		break;
		
		default:
			$text = "('', '$componentId', 'DEFAULTVALUE', '".addslashes($field['default_value'])."'),";
		break;
	}	
	return $text;
}


function RSmigrationParseAdditionalAttributes($inputtype, $text, $componentId)
{
	switch($inputtype)
	{
		case 2:
				$text = "('', '$componentId', 'COLS', '40'),";
				$text .= "('', '$componentId', 'ROWS', '4'),";
		break;
		
		default:
			$text = "('', '$componentId', 'ADDITIONALATTRIBUTES', '".addslashes($field['params'])."'),";
		break;
	}	
	return $text;
}

function RSmigrationGetComponentTypeId($type)
{	
	switch($type)
	{
		case 'text': 			return 1;
		case 'password': 		return 14;
		case 'radio': 			return 5;
		case 'checkbox':		return 4;
		case 'calendar':		return 6;
		case 'textarea':		return 2;
		case 'select':			return 3;
		case 'button':			return 7;
		case 'image button':	return 12;
		case 'submit button':	return 13;
		case 'reset button':	return 7;
		case 'file upload':		return 9;
		case 'hidden':			return 11;
		case 'free text':		return 10;
		case 'ticket number':	return 15;
		case 'captcha':			return 8;
	}
}

?>