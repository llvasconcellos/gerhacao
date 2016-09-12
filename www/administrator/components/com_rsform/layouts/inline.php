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

	where {$RSadapter->tbl_rsform_components}.FormId=$formId and {$RSadapter->tbl_rsform_properties}.PropertyName='NAME'  and 
	
	{$RSadapter->tbl_rsform_components}.Published=1
	
	order by {$RSadapter->tbl_rsform_components}.Order";
	$rez=mysql_query($q) or die(mysql_error());
	$out='<div class="componentheading">{global:formtitle}</div>'."\n";
	$out.="<table border=\"0\">\n";
	while($r=mysql_fetch_assoc($rez))
	{

		//build validation message

		$componentProperties=RSgetComponentProperties($r['ComponentId']);
		$out.= "\t<tr>\n";
		$out.= "\t\t<td>{".$r['PropertyValue'].":caption}".((array_key_exists('REQUIRED',$componentProperties) && $componentProperties['REQUIRED']=='YES') ? " (*)" : "" )."</td>\n";
		$out.= "\t\t<td>{".$r['PropertyValue'].":body}<div class=\"clr\"/>{".$r['PropertyValue'].":validation}</td>\n";
		$out.= "\t\t<td>{".$r['PropertyValue'].":description}</td>\n";
		$out.= "\t</tr>\n";
	}
	$out.= "</table>\n";
	$q="update {$RSadapter->tbl_rsform_forms} set FormLayout='$out' where FormId=$formId";
	mysql_query($q) or die(mysql_error());

	echo $out;
?>