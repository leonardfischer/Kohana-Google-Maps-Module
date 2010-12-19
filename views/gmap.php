<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=<?php echo $config->sensor; ?>"></script>
<script type="text/javascript">
function initialize() {
	var latlng = new google.maps.LatLng(<?php echo $config->lat; ?>, <?php echo $config->lng; ?>);
	var myOptions = {
		zoom: <?php echo $config->zoom; ?>,
		center: latlng,
		mapTypeId: <?php echo $config->map_type['road']; ?>
	};
	var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
}

window.onload = (function(){
	initialize();
});
</script>

<div id="map_canvas" style="width:100%; height:100%"></div>
