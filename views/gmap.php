<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=<?php echo ($options['sensor']) ? 'true' : 'false'; ?>"></script>
<script type="text/javascript">
var gmaps_instance_<?php echo $options['instance']; ?> = gmaps_instance_<?php echo $options['instance']; ?> || {};

gmaps_instance_<?php echo $options['instance']; ?>.initialize = function() {
	var options = {
		zoom: <?php echo $options['zoom']; ?>,
		center: new google.maps.LatLng(<?php echo str_replace(',', '.', $options['lat']); ?>, <?php echo str_replace(',', '.', $options['lng']); ?>),
		mapTypeId: <?php echo $options['maptype']; ?>,
		<?php if ($options['gmap_controls']['maptype']['display']): ?>
		mapTypeControl: true,
		mapTypeControlOptions: <?php echo Gmap::clean_json_string(json_encode(Arr::extract($options['gmap_controls']['maptype'], array('style', 'position')))); ?>,
		<?php else: ?>
		mapTypeControl: false,
		<?php endif; ?>

		<?php if ($options['gmap_controls']['navigation']['display']): ?>
		navigationControl: true,
		navigationControlOptions: <?php echo Gmap::clean_json_string(json_encode(Arr::extract($options['gmap_controls']['navigation'], array('style', 'position')))); ?>,
		<?php else: ?>
		navigationControl: false,
		<?php endif; ?>

		<?php if ($options['gmap_controls']['scale']['display']): ?>
		scaleControl: true,
		<?php if ($options['gmap_controls']['scale']['position'] !== NULL): ?>
		scaleControlOptions: {
			position: <?php echo $options['gmap_controls']['scale']['position']; ?>
		}
		<?php endif; ?>
		<?php else: ?>
		scaleControl: false
		<?php endif; ?>
	};

	var map = new google.maps.Map(document.getElementById("gmap_<?php echo $options['instance']; ?>"), options);

	<?php foreach ($polylines as $polyline): ?>
		var polyline_coords_<?php echo $polyline['id']; ?> = [
		<?php foreach ($polyline['coords'] as $coodinates): ?>
			new google.maps.LatLng(<?php echo $coodinates[0] . ',' . $coodinates[1]; ?>),
		<?php endforeach; ?>
		];

		var polyline_<?php echo $polyline['id']; ?> = new google.maps.Polyline({
			path: polyline_coords_<?php echo $polyline['id']; ?>,
			strokeColor: "<?php echo $polyline['options']['strokeColor']; ?>",
			strokeOpacity: <?php echo $polyline['options']['strokeOpacity']; ?>,
			strokeWeight: <?php echo $polyline['options']['strokeWeight']; ?>,
		});

		polyline_<?php echo $polyline['id']; ?>.setMap(map);
	<?php endforeach; ?>


	<?php foreach ($polygons as $polygon): ?>
		var polygon_coords_<?php echo $polygon['id']; ?> = [
		<?php foreach ($polygon['coords'] as $coodinates): ?>
			new google.maps.LatLng(<?php echo $coodinates[0] . ',' . $coodinates[1]; ?>),
		<?php endforeach; ?>
		];

		var polygon_<?php echo $polygon['id']; ?> = new google.maps.Polygon({
			paths: polygon_coords_<?php echo $polygon['id']; ?>,
			strokeColor: "<?php echo $polygon['options']['strokeColor']; ?>",
			strokeOpacity: <?php echo $polygon['options']['strokeOpacity']; ?>,
			strokeWeight: <?php echo $polygon['options']['strokeWeight']; ?>,
			fillColor: "<?php echo $polygon['options']['fillColor']; ?>",
			fillOpacity: <?php echo $polygon['options']['fillOpacity']; ?>,
		});

		polygon_<?php echo $polygon['id']; ?>.setMap(map);
	<?php endforeach; ?>

	<?php foreach ($marker as $mark): ?>
		var marker_<?php echo $mark['id']; ?> = new google.maps.Marker({
			position: new google.maps.LatLng(<?php echo str_replace(',', '.', $mark['lat']); ?>, <?php echo str_replace(',', '.', $mark['lng']); ?>),
			map: map,
			title: "<?php echo $mark['options']['title']; ?>",
			<?php echo (isset($mark['options']['icon'])) ? 'icon: "'.$mark['options']['icon'].'",' : ''; ?>
		});

		<?php if (isset($mark['options']['content'])): ?>
		var infowin_<?php echo $mark['id']; ?> = new google.maps.InfoWindow({
			content: "<?php echo addslashes($mark['options']['content']); ?>"
		});

		google.maps.event.addListener(marker_<?php echo $mark['id']; ?>, 'click', function() {
			infowin_<?php echo $mark['id']; ?>.open(map, marker_<?php echo $mark['id']; ?>);
		});
		<?php endif; ?>
	<?php endforeach; ?>
};

window.onload = (function(){
	<?php foreach ($instances as $instance): ?>
	gmaps_instance_<?php echo $instance; ?>.initialize();
	<?php endforeach; ?>
});
</script>
<div id="gmap_<?php echo $options['instance']; ?>" style="width:<?php echo $options['gmap_size_x']; ?>; height:<?php echo $options['gmap_size_y']; ?>"></div>