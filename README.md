# Kohana-Google Maps Module
With this module you can easily add a "google map" to your Kohana installation

The Google Map Module will make use of the following modules:

* None

## Features

* Display a Google-Map by just echo'ing an instance of the "Gmap" class
* Setting several map-types (road, satellite, hybrid and terrain)
* Setting sensor-parameter for mobile devices
* Adding new markers to the map

## Features to come
The following features shall be implemented during development:

* Adding your own control-elements to the map
* Cleaning up the gmap-view

## Usage
The usage of this module is as easy as it could be! Simply activate the module in your bootstrap.php

    Kohana::modules(array(
        // ...
        'gmap'       => MODPATH.'gmap',       // A simple google-maps module
    ));

Then you'll just echo an instance of the gmap class in your action... For example:

    public function action_index()
    {
        $this->template->map = new Gmap();
    } // function

This is a more advanced example with usage of various methods...

    public function action_index()
    {
       $map = new Gmap();
       $map->add_marker('Marker A', 51.15, 6.83)
           ->add_marker('Marker B', 51.15, 6.93,
               array( // Marker with Content and custom icon.
                   'content' => '<p>Put HTML here. "Quotes" and \'singlequotes\'</p>',
                   'icon' => '/assets/images/red_pin.png'
               ))
           ->set_gmap_size('100%', 500); // Will output "width: 100%; height: 500px"
       
       // Will render the "gmap_view_2" instead of the default "gmap" view.
       $this->template->map = $map->render('gmap_view_2');
    }

Yes, it's that easy ;)

## More!
For more information look up the wiki!