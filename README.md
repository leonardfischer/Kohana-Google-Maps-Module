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

    <?php defined('SYSPATH') or die('No direct script access.');
    
    class Controller_Welcome extends Controller_Template
    {
       public function action_index()
       {
          $this->template->map = new Gmap();
       } // function
    } // class

If you want to display some markers, just take a closer look at the following example.

    public function action_index()
    {
       $map = new Gmap();
       $map->add_marker('Marker A', 51.15, 6.83)
          ->add_marker('Marker B', 51.15, 6.93, array( // Marker with Content and custom icon.
             'content' => '<p>Put HTML here. "Quotes" and \'singlequotes\'</p>',
             'icon' => Kohana::find_file('assets/images', 'red_pin', 'png')));
       
       $this->template->map = $map;
    }

Yes, it's that easy ;)