<?php defined('SYSPATH') or die('No direct access allowed.');

// Don't delete or rename any keys unless you know what you're doing!

return array (
	// Default map-center.
	'default_lat' => 51.1731,
	'default_lng' => 6.8328,

	// Default zoom-level.
	'default_zoom' => 12,

	 // Default "sensor" setting - Used for mobile devices.
	'default_sensor' => FALSE,

	// Default map-type.
	'default_maptype' => 'road',

	// View
	'default_view' => 'gmap',
	'default_gmap_size_x' => '100%',
	'default_gmap_size_y' => '100%',

	// Google Maps controls
	'default_gmap_controls' => array(
		'maptype' => array(
			'display' => TRUE,
			'style' => 'default',
			'position' => NULL,
		),
		'navigation' => array(
			'display' => TRUE,
			'style' => 'default',
			'position' => NULL,
		),
		'scale' => array(
			'display' => TRUE,
			'position' => NULL,
		),
	),
);