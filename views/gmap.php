<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=<?php echo ($options['sensor']) ? 'true' : 'false'; ?>"></script>
<script type="text/javascript">
var gmaps_mod = gmaps_mod || {};

gmaps_mod.initialize = function() {
	var map = new google.maps.Map(document.getElementById("map_canvas"), {
			zoom: <?php echo $options['zoom']; ?>,
			center: new google.maps.LatLng(<?php echo str_replace(',', '.', $options['lat']); ?>, <?php echo str_replace(',', '.', $options['lng']); ?>),
			mapTypeId: <?php echo $options['maptype']; ?>
		});

	<?php if (isset($marker)): ?>
		<?php foreach ($marker as $mark): ?>
			var marker_<?php echo $mark['id']; ?> = new google.maps.Marker({
					position: new google.maps.LatLng(<?php echo str_replace(',', '.', $mark['lat']); ?>, <?php echo str_replace(',', '.', $mark['lng']); ?>),
					map: map,
					title: "<?php echo $mark['title']; ?>",
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
	<?php endif; ?>
};


window.onload = (function(){
	gmaps_mod.initialize();
});
</script>

<div id="map_canvas" style="width:<?php echo $options['gmap_size_x']; ?>; height:<?php echo $options['gmap_size_y']; ?>"></div>
