<?php defined('SYSPATH') or die('No direct access allowed.');

return array (
	'lat' => 51.1731,
	'lng' => 6.8328,
	'zoom' => 12,
	'sensor' => FALSE, // Used for mobile devices.
	'map_type' => array(
		'road' => 'google.maps.MapTypeId.ROADMAP',
		'satellite' => 'google.maps.MapTypeId.SATELLITE',
		'hybrid' => 'google.maps.MapTypeId.HYBRID',
		'terrain' => 'google.maps.MapTypeId.TERRAIN',
	),
);