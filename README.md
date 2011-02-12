# Kohana Google Maps Module V1.3
With this module you can easily add a Google Map to your Kohana installation!

The Google Map module has NO dependency to other modules!

## Features

* Display a Google-Map by just echo'ing an instance of the "Gmap" class
* Setting several map-types (road, satellite, hybrid and terrain)
* Setting sensor-parameter (for mobile devices)
* Adding markers to the map (including custom icon and Google Maps popups)
* Adding polylines to the map
* Adding polygons to the map
* Setting the positions and types for the map-controls

## Features to come
The following features shall be implemented during development:

* Cleaning up the Google Map-view (Maybe moving the javascript to a sub-views?)

## Usage
The usage of this module is as easy as it could be! Simply activate the module in your bootstrap.php

	/**
	 * Enable modules. Modules are referenced by a relative or absolute path.
	 */
	Kohana::modules(array(
		// ...
		'gmap'       => MODPATH.'gmap',       // A simple google-maps module
	));

Then you'll just echo an instance of the gmap class in your action... For example:

	public function action_index()
	{
		$this->template->map = Gmap::factory();
	} // function

This is a more advanced example with usage of various options...

	public function action_index()
	{
		$gmap = Gmap::factory(
				array(
					'zoom' => 4,
					'sensor' => FALSE,
				))
			->add_marker('Marker A', 51.15, 6.83)
			->add_marker('Marker B', 51.15, 6.93,
				array(
					'content' => '<p>Put HTML here. "Quotes" and \'singlequotes\'</p>',
					'icon' => '/path/to/your.icon'
				))
			->set_gmap_size('100%', 500); // Will output "width: 100%; height: 500px"

		// This will render the Google Map.
		$this->template->map = $gmap;

		// Or...
		$this->template->map = $gmap->render();

		// Or rendering a own Google Map view.
		$this->template->map = $gmap->render('gmap_view_2');
	} // function

Yes, it's that easy ;)

## More!
For more information look up the wiki!