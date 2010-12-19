# Kohana-Google Maps Module
With this module you can easily add a "google map" to your Kohana installation

The gallery will make use of the following modules:

* None

## Features to come
The following features shall be implemented during development:

* Addind your own points to the map
* Adding your own control-elements to the map

## Usage
The usage of this module is as easy as it could be! Simply activate the module in your bootstrap.php

    Kohana::modules(array(
       // ...
       'gmap'       => MODPATH.'gmap',       // A simple google-maps module
    ));

Then you'll just echo the gmap-class in your action... For example:

    <?php defined('SYSPATH') or die('No direct script access.');
    
    class Controller_Welcome extends Controller_Template
    {
       public function action_index()
       {
          $this->template->map = new Gmap();
       } // function
    } // class