<?php defined('_JEXEC') or die('Restricted access');

if ( $this->params->def( 'show_page_title', 1 ) ) { ?>
   <div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
      <?php echo $this->params->get('page_title'); ?>
   </div>
<?php } 

if ($this->tmpl2['googlemapsapikey'] == '') {
	echo '<p>' . JText::_('Google Maps API Key Error Front') . '</p>';
} else if ($this->tmpl2['categorieslng'] == '' || $this->tmpl2['categorieslat'] == '') {
	echo '<p>' . JText::_('Google Maps Error Front') . '</p>';
} else {
	?>
	


<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $this->tmpl2['googlemapsapikey'];?>" type="text/javascript"></script>

<noscript><?php echo JText::_('GOOGLE MAP ENABLE JAVASCRIPT');?></noscript>

<div align="center" style="margin:0;padding:0;margin-top:10px;">
	<div id="phoca_geo_map" style="margin:0;padding:0;width:<?php echo $this->tmpl2['categoriesmapwidth'];?>px;height:<?php echo $this->tmpl2['categoriesmapheight'];?>px"></div>
</div>

<script type='text/javascript'>//<![CDATA[
var tst_phoca_geo=document.getElementById('phoca_geo_map');
var tstint_phoca_geo;
var map_phoca_geo;

function CancelEventPhocaGeoMap(event) { 
	var e = event; 
	if (typeof e.preventDefault == 'function') e.preventDefault(); 
	if (typeof e.stopPropagation == 'function') e.stopPropagation(); 
	
	if (window.event) { 
		window.event.cancelBubble = true; // for IE 
		window.event.returnValue = false; // for IE 
	} 
}

function CheckPhocaGeoMap()
{
	if (tst_phoca_geo) {
		if (tst_phoca_geo.offsetWidth != tst_phoca_geo.getAttribute("oldValue"))
		{
			tst_phoca_geo.setAttribute("oldValue",tst_phoca_geo.offsetWidth);

			if (tst_phoca_geo.getAttribute("refreshMap")==0)
				if (tst_phoca_geo.offsetWidth > 0) {
					clearInterval(tstint_phoca_geo);
					getPhocaGeoMap();
					tst_phoca_geo.setAttribute("refreshMap", 1);
				} 
		}
		//window.top.document.forms.adminForm.elements.zoom.value = tstint_phoca_geo;
	}
}

function getPhocaGeoMap(){
	if (tst_phoca_geo.offsetWidth > 0) {
		
	
		map_phoca_geo = new GMap2(document.getElementById('phoca_geo_map'));
		map_phoca_geo.addControl(new GMapTypeControl());
		map_phoca_geo.addControl(new GLargeMapControl());
		var overviewmap = new GOverviewMapControl();
		map_phoca_geo.addControl(overviewmap, new GControlPosition(G_ANCHOR_BOTTOM_RIGHT));
		
		map_phoca_geo.setCenter(new GLatLng(<?php echo $this->tmpl2['categorieslat'];?>, <?php echo $this->tmpl2['categorieslng'];?>), <?php echo $this->tmpl2['categorieszoom'];?>);
		map_phoca_geo.setMapType(G_NORMAL_MAP);
		map_phoca_geo.enableContinuousZoom();
		map_phoca_geo.enableDoubleClickZoom();
		map_phoca_geo.enableScrollWheelZoom();

<?php
$data = array();
foreach ($this->categories as $category) {
	if (isset($category->params)) {
		$longitude	= PhocaGalleryHelper::getParamsArray($category->params, 'longitude');
		$latitude	= PhocaGalleryHelper::getParamsArray($category->params, 'latitude');
		//$zoom		= PhocaGalleryHelper::getParamsArray($category->params, 'zoom');
		$geotitle	= PhocaGalleryHelper::getParamsArray($category->params, 'geotitle');
		
		if (!isset($longitude[0]) || (isset($longitude[0]) && ($longitude[0] == '' || $longitude[0] == 0))) {
			$data['longitude'] = '';
		} else {
			$data['longitude'] = $longitude[0];
		}
	
		if (!isset($latitude[0]) || (isset($latitude[0]) && ($latitude[0] == '' || $latitude[0] == 0))) {
			$data['latitude'] = '';
		} else {
			$data['latitude'] = $latitude[0];
		}
		
		if (!isset($geotitle[0]) || (isset($geotitle[0]) && $geotitle[0] == '')) {
			$data['geotitle'] = $category->title;
		} else {
			$data['geotitle'] = $geotitle[0];
		}
	} else {
		$data['longitude']	= '';
		$data['latitude']	= '';
		$data['geotitle'] 	= $category->title;
	}
					
	if ($data['longitude'] != '' && $data['latitude'] != '') {
		$text = '<div style="text-align:left"><table border="0" cellspacing="5" cellpadding="5"><tr><td align="left" colspan="2"><b><a href="'.$category->link.'">'. $data['geotitle'].'</a></b></td></tr>';
		$text .='<tr>';
		$text .='<td valign="top" align="left"><a href="'.$category->link.'">'.JHTML::_( 'image.site', $category->linkthumbnailpath, '', '', '', $data['geotitle'] ) . '</a></td>';
		$text .='<td valign="top" align="left">'. PhocaGalleryHelper::strTrimAll($category->description).'</td>';
		$text .='</tr></table></div>';
		?>
	
		var point<?php echo $category->id;?> = new GPoint( <?php echo $data['longitude'];?>, <?php echo $data['latitude'];?>);
		var marker_phoca_geo<?php echo $category->id;?> = new GMarker(point<?php echo $category->id;?>, {title:"<?php echo $data['geotitle'];?>"});
		map_phoca_geo.addOverlay(marker_phoca_geo<?php echo $category->id;?>);
		
		GEvent.addListener(marker_phoca_geo<?php echo $category->id;?>, 'click', function() {
			marker_phoca_geo<?php echo $category->id;?>.openInfoWindowHtml('<?php echo $text;?>');
			});
			
		GEvent.addDomListener(tst_phoca_geo, 'DOMMouseScroll', CancelEventPhocaGeoMap);
		GEvent.addDomListener(tst_phoca_geo, 'mousewheel', CancelEventPhocaGeoMap);
	<?php
	}
}
?>

	}
}
//]]></script>

<script type="text/javascript">//<![CDATA[
if (GBrowserIsCompatible()) {
	tst_phoca_geo.setAttribute("oldValue",0);
	tst_phoca_geo.setAttribute("refreshMap",0);
	tstint_phoca_geo=setInterval("CheckPhocaGeoMap()",500);
}
//]]></script>		


<?php
}
?>