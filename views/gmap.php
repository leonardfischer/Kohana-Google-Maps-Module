<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=<?php echo ($options['sensor']) ? 'true' : 'false'; ?>"></script>
<script type="text/javascript">
function initialize() {
	var map = new google.maps.Map(document.getElementById("map_canvas"), {
			zoom: <?php echo $options['zoom']; ?>,
			center: new google.maps.LatLng(<?php echo str_replace(',', '.', $options['lat']); ?>, <?php echo str_replace(',', '.', $options['lng']); ?>),
			mapTypeId: <?php echo $options['maptype']; ?>
		});

	<?php if (isset($marker)): ?>
		<?php foreach ($marker as $name => $mark): ?>
			var marker_<?php echo $mark['js_name']; ?> = new google.maps.Marker({
					position: new google.maps.LatLng(<?php echo str_replace(',', '.', $mark['lat']); ?>, <?php echo str_replace(',', '.', $mark['lng']); ?>),
					map: map,
					title: "<?php echo $mark['title']; ?>",
					<?php echo (isset($mark['options']['icon'])) ? 'icon: "'.$mark['options']['icon'].'",' : ''; ?>
				});
			
			<?php if (isset($mark['options']['content'])): ?>
			var infowin_<?php echo $mark['js_name']; ?> = new google.maps.InfoWindow({
					content: "<?php echo addslashes($mark['options']['content']); ?>"
				});	

			google.maps.event.addListener(marker_<?php echo $mark['js_name']; ?>, 'click', function() {
					infowin_<?php echo $mark['js_name']; ?>.open(map, marker_<?php echo $mark['js_name']; ?>);
				});
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>
}

window.onload = (function(){
	initialize();
});
</script>

<div id="map_canvas" style="width:100%; height:100%"></div>
