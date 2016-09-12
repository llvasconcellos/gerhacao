<?php
/**
* @version 1.0.1
* @package RSform!Pro 1.0.1
* @copyright (C) 2007-2008 www.rsjoomla.com
* @license Commercial License, http://www.rsjoomla.com/license/rsformpro.html
*/
	$q="select
		{$RSadapter->tbl_rsform_properties}.PropertyValue,
		{$RSadapter->tbl_rsform_components}.ComponentId

	from {$RSadapter->tbl_rsform_components}
		left join {$RSadapter->tbl_rsform_properties} on {$RSadapter->tbl_rsform_properties}.ComponentId={$RSadapter->tbl_rsform_components}.ComponentId

	where {$RSadapter->tbl_rsform_components}.FormId=$_GET[formId] and {$RSadapter->tbl_rsform_properties}.PropertyName='NAME'  and 
	
	{$RSadapter->tbl_rsform_components}.Published=1
	
	order by {$RSadapter->tbl_rsform_components}.Order";
	$rez=mysql_query($q) or die(mysql_error());

	$outLeft='';
	$outLeft.="<table border=\"0\">\n";

	$outRight='';
	$outRight.="<table border=\"0\">\n";

	$out = '<div class="componentheading">{global:formtitle}</div>'."\n";
	$out .= "<table border=\"0\">\n";

	$i = 0;
	while($r=mysql_fetch_assoc($rez))
	{
		$componentProperties=RSgetComponentProperties($r['ComponentId']);
		$tmp = '';
		$tmp.= "\t\t\t\t<tr>\n";
		$tmp.= "\t\t\t\t{".$r['PropertyValue'].":caption}".((array_key_exists('REQUIRED',$componentProperties) && $componentProperties['REQUIRED']=='YES') ? " (*)" : "" )."<br/>\n";
		$tmp.= "\t\t\t\t\t<td>{".$r['PropertyValue'].":body}</td>\n";
		$tmp.= "\t\t\t\t\t<td>{".$r['PropertyValue'].":validation}</td>\n";
		$tmp.= "\t\t\t\t\t<td>{".$r['PropertyValue'].":description}</td>\n";
		$tmp.= "\t\t\t\t</tr>\n";


		if($i%2) $outRight .= $tmp;
		else $outLeft .= $tmp;

		$i ++;
	}


	$outLeft.="\t\t\t</table>\n";
	$outRight.="\t\t\t</table>\n";

	$out .= "\t<tr>\n";
	$out .= "\t\t<td valign=\"top\">\n";
	$out .= "\t\t\t".$outLeft."\n";
	$out .= "\t\t</td>\n";
	$out .= "\t\t<td valign=\"top\">\n";
	$out .= "\t\t\t".$outRight."\n";
	$out .= "\t\t</td>\n";
	$out .= "\t</tr>\n";
	$out .= "</table>\n";
	$q="update {$RSadapter->tbl_rsform_forms} set FormLayout='$out' where FormId=$_GET[formId]";
	mysql_query($q) or die(mysql_error());
	
	echo $out;
?>