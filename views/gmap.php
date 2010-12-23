<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=<?php echo ($options['sensor']) ? 'true' : 'false'; ?>"></script>
<script type="text/javascript">
var gmaps_mod = gmaps_mod || {};

gmaps_mod.initialize = function() {
	var options = {
		<?php if (is_bool($options['gmap_controls']['maptype'])): ?>
			mapTypeControl: <?php echo $options['gmap_controls']['maptype']; ?>,
		<?php elseif(is_array($options['gmap_controls']['maptype'])): ?>
			mapTypeControl: true,
			mapTypeControlOptions: {
				<?php if(isset($options['gmap_controls']['maptype']['style'])): ?>
					style: <?php echo $options['gmap_controls']['maptype']['style']; ?>,
				<?php endif; ?>
				
				<?php if(isset($options['gmap_controls']['maptype']['position'])): ?>
					position: <?php echo $options['gmap_controls']['maptype']['position']; ?>,
				<?php endif; ?>
			},
		<?php endif; ?>
		
		<?php if (is_bool($options['gmap_controls']['navigation'])): ?>
			navigationControl: <?php echo $options['gmap_controls']['navigation']; ?>,
		<?php elseif(is_array($options['gmap_controls']['navigation'])): ?>
			navigationControl: true,
			navigationControlOptions: {
				<?php if(isset($options['gmap_controls']['navigation']['style'])): ?>
					style: <?php echo $options['gmap_controls']['navigation']['style']; ?>,
				<?php endif; ?>
				
				<?php if(isset($options['gmap_controls']['navigation']['position'])): ?>
					position: <?php echo $options['gmap_controls']['navigation']['position']; ?>,
				<?php endif; ?>
			},
		<?php endif; ?>
		zoom: <?php echo $options['zoom']; ?>,
		center: new google.maps.LatLng(<?php echo str_replace(',', '.', $options['lat']); ?>, <?php echo str_replace(',', '.', $options['lng']); ?>),
		mapTypeId: <?php echo $options['maptype']; ?>
	};
	
	var map = new google.maps.Map(document.getElementById("map_canvas"), options);

	<?php if (isset($marker)): ?>
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
	<?php endif; ?>
};


window.onload = (function(){
	gmaps_mod.initialize();
});
</script>
<div id="map_canvas" style="width:<?php echo $options['gmap_size_x']; ?>; height:<?php echo $options['gmap_size_y']; ?>"></div>
