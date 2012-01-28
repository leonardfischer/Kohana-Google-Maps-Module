<?php defined('SYSPATH') or die('No direct access allowed.');

// Don't delete or rename any keys unless you know what you're doing!

	// Default map-center.
	$config['default_lat'] = 51.1731;
	

	$config['default_lng'] =  6.8328;

	// Default zoom-level.
	$config['default_zoom'] =  12;

	// Default "sensor" setting - Used for mobile devices.
	$config['default_sensor'] =  FALSE;

	// Default map-type.
	$config['default_maptype'] =  'road';

	// The instance will be set in the render method to a random string (if this value is empty).
	$config['instance'] =  '';

	// Default view-options.
	$config['default_view'] =  'gmap';
	$config['default_gmap_size_x'] =  '100%';
	$config['default_gmap_size_y'] =  '100%';

	// Default Google Maps controls.
	$config['default_gmap_controls'] =  array(
		'maptype' =>   array(
			'display' =>   TRUE,
			'style' =>   'default',
			'position' =>   NULL,
		),
		'navigation' =>   array(
			'display' =>   TRUE,
			'style' =>   'default',
			'position' =>   NULL,
		),
		'scale' =>   array(
			'display' =>   TRUE,
			'position' =>   NULL,
		),
	);
	
	// Default options for polylines.
	$config['default_polyline_options'] =  array(
		'strokeColor' =>  '#000',
		'strokeOpacity' =>  1,
		'strokeWeight' =>  3,
	);
	
	// Default options for polygons.
	$config['default_polygon_options'] =  array(
		'strokeColor' =>  '#000',
		'strokeOpacity' =>  1,
		'strokeWeight' =>  3,
		'fillColor' =>  '#000',
		'fillOpacity' >=  .5,
	);
