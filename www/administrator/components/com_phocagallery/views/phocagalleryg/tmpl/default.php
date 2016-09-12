<?php defined('_JEXEC') or die('Restricted access');

if ($this->googlemapsapikey == '') {
echo '<p>' . JText::_('Google Maps API Key Error') . '</p>';
} else {
?>

<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $this->googlemapsapikey;?>" type="text/javascript"></script>

<noscript><?php echo JText::_('GOOGLE MAP ENABLE JAVASCRIPT');?></noscript>

<div align="center" style="margin:0;padding:0">
	<div id="phoca_geo_map" style="margin:0;padding:0;width:620px;height:540px"></div>
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
	
		map_phoca_geo.setCenter(new GLatLng(<?php echo $this->latitude;?>, <?php echo $this->longitude;?>), <?php echo $this->zoom;?>);
		map_phoca_geo.setMapType(G_NORMAL_MAP);
		map_phoca_geo.enableContinuousZoom();
		map_phoca_geo.enableDoubleClickZoom();
		map_phoca_geo.enableScrollWheelZoom();
		
		var startzoom = <?php echo $this->zoom;?>;
		var zoom = null;
		GEvent.addListener(map_phoca_geo, "zoomend", function(startzoom,zoom) {
			window.top.document.forms.adminForm.elements.zoom.value = zoom;
		}); 
		 
		var marker = null;
		marker = new GMarker(new GLatLng(<?php echo $this->latitude;?>, <?php echo $this->longitude;?>), {draggable: true});
		map_phoca_geo.addOverlay(marker);
		GEvent.addListener(map_phoca_geo, 'click', function(overlay,point) {
			if (overlay) {
			} else {
				marker.setPoint(point);
				addPoint(point);
			}
		});

		GEvent.addListener(marker, "click", function() {
			var point = marker.getLatLng();
			marker.openInfoWindowHtml(marker.getLatLng().toUrlValue(6));
			addPoint(point);
		});

		function addPoint(point) {
			window.top.document.forms.adminForm.elements.latitude.value = point.y;
			window.top.document.forms.adminForm.elements.longitude.value = point.x;
		}
		
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
