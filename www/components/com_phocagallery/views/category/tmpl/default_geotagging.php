<?php defined('_JEXEC') or die('Restricted access');

if ($this->tmpl['googlemapsapikey'] == '') {
	echo '<p>' . JText::_('Google Maps API Key Error Front') . '</p>';
} else if ($this->map['longitude'] == '' || $this->map['latitude'] == '') {
	echo '<p>' . JText::_('Google Maps Error Front') . '</p>';
} else {


	?><script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $this->tmpl['googlemapsapikey'];?>" type="text/javascript"></script>

<noscript><?php echo JText::_('GOOGLE MAP ENABLE JAVASCRIPT');?></noscript>
<div style="font-size:1px;height:1px;margin:0px;padding:0px;">&nbsp;</div>
<div align="center" style="margin:0;padding:0;margin-top:10px;">
	<div id="phoca_geo_map" style="margin:0;padding:0;width:<?php echo $this->tmpl['categorymapwidth'];?>px;height:<?php echo $this->tmpl['categorymapheight'];?>px"></div>
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
		
		map_phoca_geo.setCenter(new GLatLng(<?php echo $this->map['latitude'];?>, <?php echo $this->map['longitude'];?>), <?php echo $this->map['zoom'];?>);
		map_phoca_geo.setMapType(G_NORMAL_MAP);
		map_phoca_geo.enableContinuousZoom();
		map_phoca_geo.enableDoubleClickZoom();
		map_phoca_geo.enableScrollWheelZoom();
		
		var point = new GPoint( <?php echo $this->map['longitude'];?>, <?php echo $this->map['latitude'];?>);
		var marker_phoca_geo = new GMarker(point, {title:"<?php echo $this->map['geotitle'];?>"});
		map_phoca_geo.addOverlay(marker_phoca_geo);
		
		GEvent.addListener(marker_phoca_geo, 'click', function() {
			marker_phoca_geo.openInfoWindowHtml('<?php echo $this->map['geotitle'];?>');
			});
			
		GEvent.addDomListener(tst_phoca_geo, 'DOMMouseScroll', CancelEventPhocaGeoMap);
		GEvent.addDomListener(tst_phoca_geo, 'mousewheel', CancelEventPhocaGeoMap);
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
