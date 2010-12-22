<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Gmap
{
	protected $_config = NULL;
	protected $_options = array(
		'lat' => NULL,
		'lng' => NULL,
		'zoom' => NULL,
		'sensor' => FALSE,
		'maptype' => NULL,
		'view' => NULL,
		'gmap_size_x' => NULL,
		'gmap_size_y' => NULL,
		'gmap_controls' => array(
			'maptype' => NULL,
			'navigation' => NULL,
			'scale' => NULL,
		),
	);
	protected $marker = array();
	protected $template = NULL;
	protected static $maptypes = array(
		'road'      => 'google.maps.MapTypeId.ROADMAP',
		'satellite' => 'google.maps.MapTypeId.SATELLITE',
		'hybrid'    => 'google.maps.MapTypeId.HYBRID',
		'terrain'   => 'google.maps.MapTypeId.TERRAIN',
	);
	protected static $control_navigation = array(
		'small' => 'google.maps.NavigationControlStyle.SMALL',
		'zoom_pan' => 'google.maps.NavigationControlStyle.ZOOM_PAN',
		'android' => 'google.maps.NavigationControlStyle.ANDROID',
		'default' => 'google.maps.NavigationControlStyle.DEFAULT',
	);
	protected static $control_maptypes = array(
		'horizontal_bar' => 'google.maps.MapTypeControlStyle.HORIZONTAL_BAR',
		'dropdown_menu' => 'google.maps.MapTypeControlStyle.DROPDOWN_MENU',
		'default' => 'google.maps.MapTypeControlStyle.DEFAULT',
	);
	protected static $control_positions = array(
		'top' => 'google.maps.ControlPosition.TOP',
		'top_left' => 'google.maps.ControlPosition.TOP_LEFT',
		'top_right' => 'google.maps.ControlPosition.TOP_RIGHT',
		'bottom' => 'google.maps.ControlPosition.BOTTOM',
		'bottom_left' => 'google.maps.ControlPosition.BOTTOM_LEFT',
		'bottom_righ' => 'google.maps.ControlPosition.BOTTOM_RIGHT',
		'left' => 'google.maps.ControlPosition.LEFT',
		'right' => 'google.maps.ControlPosition.RIGHT',
	);
	
	/**
	 * Constructor for the Google-Map class.
	 * 
	 * @param array $options
	 */
	public function __construct(array $options = array())
	{
		$this->_config = Kohana::config('gmap');
		$this->_options = Arr::extract(Arr::merge($this->_options, $options), array_keys($this->_options));
		
		$this->set_pos($this->_options['lat'], $this->_options['lng']);
		$this->set_sensor($this->_options['sensor']);
		$this->set_gmap_size($this->_options['gmap_size_x'], $this->_options['gmap_size_y']);
	} // function
	
	/**
	 * Renders the google-map template.
	 * 
	 * @return string
	 */
	public function __toString()
	{
		return $this->render();
	} // function
	
	/**
	 * Set a marker somewhere on the map.
	 * 
	 * @param string $id
	 * @param float  $lat
	 * @param float  $lng
	 * @param array  $options
	 * @return Gmap
	 */
	public function add_marker($id, $lat, $lng, array $options = array())
	{
		Gmap::validate_latitude($lat);
		Gmap::validate_longitude($lng);
		
		$available_options = array(
			'title',
			'content',
			'icon',
		);
		
		if (! isset($options['title']) OR empty($options['title']))
		{
			$options['title'] = $id;
		} // if
		
		$this->marker[$id] = array(
			'id' => URL::title($id, '_'),
			'lat' => $lat,
			'lng' => $lng,
			'options' => Arr::extract($options, $available_options),
		);
		
		return $this;
	} // function
	
	/**
	 * Renders the google-map template.
	 * 
	 * @return string
	 */
	public function render($view = '')
	{
		// Set a default map-type.
		if (! isset($this->_options['maptype']))
		{
			$this->_options['maptype'] = Gmap::$maptypes[$this->_config->default_maptype];
		} // if
		
		if (! isset($this->_options['zoom']))
		{
			$this->_options['zoom'] = $this->_config->default_zoom;
		} // if
		
		if (! isset($this->_options['sensor']))
		{
			$this->_options['sensor'] = $this->_config->default_sensor;
		} // if
		
		if (! isset($this->_options['lat']))
		{
			$this->_options['lat'] = $this->_config->default_lat;
		} // if
		
		if (! isset($this->_options['lng']))
		{
			$this->_options['lng'] = $this->_config->default_lng;
		} // if
		
		if (! isset($this->_options['gmap_size_x']))
		{
			$this->_options['gmap_size_x'] = $this->_config->default_gmap_size_x;
		} // if
		
		if (! isset($this->_options['gmap_size_y']))
		{
			$this->_options['gmap_size_y'] = $this->_config->default_gmap_size_y;
		} // if
		
		if ($this->_options['gmap_controls']['maptype'] !== FALSE)
		{
			if (! isset($this->_options['gmap_controls']['maptype']['control-type']))
			{
				$this->_options['gmap_controls']['maptype']['control-type'] = Gmap::$control_maptypes[$this->_config->default_gmap_controls['maptype']['control-type']];
			} // if
			
			if (! isset($this->_options['gmap_controls']['maptype']['position']))
			{
				$this->_options['gmap_controls']['maptype']['position'] = NULL;
				if ($this->_config->default_gmap_controls['maptype']['position'] !== NULL)
				{
					$this->_options['gmap_controls']['maptype']['position'] = Gmap::$control_positions[$this->_config->default_gmap_controls['maptype']['position']];
				} // if
			} // if
		} // If
		
		if ($this->_options['gmap_controls']['navigation'] !== FALSE)
		{
			if (! isset($this->_options['gmap_controls']['navigation']['control-type']))
			{
				$this->_options['gmap_controls']['navigation']['control-type'] = Gmap::$control_maptypes[$this->_config->default_gmap_controls['navigation']['control-type']];
			} // if
			
			if (! isset($this->_options['gmap_controls']['navigation']['position']))
			{
				$this->_options['gmap_controls']['navigation']['position'] = NULL;
				if ($this->_config->default_gmap_controls['navigation']['position'] !== NULL)
				{
					$this->_options['gmap_controls']['navigation']['position'] = Gmap::$control_positions[$this->_config->default_gmap_controls['navigation']['position']];
				} // if
			} // if
		} // if
		
		if ($this->_options['gmap_controls']['scale'] !== FALSE)
		{
			if (! isset($this->_options['gmap_controls']['scale']))
			{
				$this->_options['gmap_controls']['scale'] = array('position' => Gmap::$control_positions[$this->_config->default_gmap_controls['scale']['position']]);
			} // if
		} // if
		
		if (! empty($view))
		{
			$this->_options['view'] = $view;
		}		
		elseif (! isset($this->_options['view']))
		{
			$this->_options['view'] = $this->_config->default_view;
		} // if
		
		$this->template = View::factory($this->_options['view']);
		
		$this->template
			->bind('options', $this->_options)
			->bind('marker', $this->marker);
		
		return $this->template->render();
	} // function

	/**
	 * Set some controls for your gmap.
	 * You can specify how to display the map-type and navigation control.
	 * For more information visit https://github.com/solidsnake/Kohana-Google-Maps-Module/wiki
	 * 
	 * @param array $options Set the options for the gmap-controls here.
	 * @return Gmap
	 */
	public function set_gmap_controls($options)
	{
		if (isset($options['maptype']))
		{
			$this->set_gmap_controls_maptype($options['maptype']);
		} // if
		
		if (isset($options['navigation']))
		{
			$this->set_gmap_controls_navigation($options['navigation']);
		} // if
		
		if (isset($options['scale']))
		{
			$this->set_gmap_controls_scale($options['scale']);
		} // if
		
		return $this;
	} // function

	/**
	 * Set the maptype controls for your gmap.
	 * For more information visit https://github.com/solidsnake/Kohana-Google-Maps-Module/wiki
	 * 
	 * @param mixed $options Set the options for the controls here.
	 * @return Gmap
	 */
	public function set_gmap_controls_maptype($options)
	{
		if ($options === FALSE)
		{
			$this->_options['gmap_controls']['maptype'] = FALSE;
		}
		elseif (is_array($options))
		{
			if (isset($options['control-type']))
			{
				Gmap::validate_control_maptype($options['control-type']);
				$this->_options['gmap_controls']['maptype']['control-type'] = Gmap::$control_maptypes[$options['control-type']];
			} // if
			
			if (isset($options['position']))
			{
				Gmap::validate_control_position($options['position']);
				$this->_options['gmap_controls']['maptype']['position'] = Gmap::$control_positions[$options['position']];
			} // if
		} // if
		
		return $this;
	} // function

	/**
	 * Set the navigation controls for your gmap.
	 * For more information visit https://github.com/solidsnake/Kohana-Google-Maps-Module/wiki
	 * 
	 * @param mixed $options Set the options for the controls here.
	 * @return Gmap
	 */
	public function set_gmap_controls_navigation($options)
	{
		if ($options === FALSE)
		{
			$this->_options['gmap_controls']['navigation'] = FALSE;
		}
		elseif (is_array($options))
		{
			if (isset($options['control-type']))
			{
				Gmap::validate_control_navigation($options['control-type']);
				$this->_options['gmap_controls']['navigation']['control-type'] = Gmap::$control_navigation[$options['control-type']];
			} // if
			
			if (isset($options['position']))
			{
				Gmap::validate_control_position($options['position']);
				$this->_options['gmap_controls']['navigation']['position'] = Gmap::$control_positions[$options['position']];
			} // if
		} // if
		
		return $this;
	} // function

	/**
	 * Set the scale controls for your gmap.
	 * For more information visit https://github.com/solidsnake/Kohana-Google-Maps-Module/wiki
	 * 
	 * @param mixed $options Set the options for the controls here.
	 * @return Gmap
	 */
	public function set_gmap_controls_scale($options)
	{
		if ($options === FALSE)
		{
			$this->_options['gmap_controls']['scale'] = FALSE;
		}
		elseif (is_array($options))
		{
			if (isset($options['position']))
			{
				Gmap::validate_control_position($options['position']);
				$this->_options['gmap_controls']['scale']['position'] = Gmap::$control_positions[$options['position']];
			} // if
		} // if
		
		return $this;
	} // function

	/**
	 * Set a size for the rendered Google-Map.
	 * You may set a CSS attribute like for example "500px", "50%" or "10em".
	 * If you just set an integer, "px" will be used.
	 * 
	 * @param mixed $x May be a CSS attribute ("500px", "50%", "10em") or an int
	 * @param mixed $y May be a CSS attribute ("500px", "50%", "10em") or an int
	 * @return Gmap
	 */
	public function set_gmap_size($x = NULL, $y = NULL)
	{
		if (is_numeric($x))
		{
			$x = $x . 'px';
		} // if
		
		if (is_numeric($y))
		{
			$y = $y . 'px';
		} // if
		
		if ($x != NULL)
		{
			$this->_options['gmap_size_x'] = $x;
		} // if
		
		if ($y != NULL)
		{
			$this->_options['gmap_size_y'] = $y;
		} // if
		
		return $this;
	} // function
	
	/**
	 * Set another map-type. Possible types are 'road', 'satellite', 'hybrid' and 'terrain'.
	 * 
	 * @param string $maptype
	 */
	public function set_maptype($maptype)
	{
		Gmap::validate_maptype($maptype);
		$this->_options['maptype'] = Gmap::$maptypes[$maptype];
		
		return $this;
	} // function
	
	/**
	 * Set a new position to show, when starting up the map.
	 * 
	 * @param float $lat
	 * @param float $lng
	 * @return Gmap
	 */
	public function set_pos($lat = NULL, $lng = NULL)
	{
		if ($lat != NULL)
		{
			Gmap::validate_latitude($lat);
			$this->_options['lat'] = $lat;
		} // if
		
		if ($lng != NULL)
		{
			Gmap::validate_longitude($lng);
			$this->_options['lng'] = $lng;
		} // if
		
		return $this;
	} // function
	
	/**
	 * Set the sensor-parameter for the google-api.
	 * 
	 * @param boolean $sensor
	 * @return Gmap
	 */
	public function set_sensor($sensor)
	{
		if (! is_bool($sensor))
		{
			throw new Kohana_Exception('The parameter must be boolean. ":sensor" given',
				array(':sensor' => $sensor));
		} // if
		
		$this->_options['sensor'] = $sensor;
		
		return $this;
	} // function
	
	/**
	 * Set the view for displaying the Google-map.
	 * 
	 * @param string $view
	 * @return Gmap
	 */
	public function set_view($view)
	{
		$this->_options['view'] = $view;
		
		return $this;
	} // function
	
	/**
	 * Validate, if the given maptype-control is valid.
	 * 
	 * @param float $lat
	 * @return boolean
	 */
	protected static function validate_control_maptype($maptype)
	{
		if (! array_key_exists($maptype, Gmap::$control_maptypes))
		{
			throw new Kohana_Exception('Given Map-Type ":maptype" control is not valid.',
				array(':maptype' => $maptype));
		} // if
	} // function
	
	/**
	 * Validate, if the given maptype-control is valid.
	 * 
	 * @param float $lat
	 * @return boolean
	 */
	protected static function validate_control_navigation($navigation)
	{
		if (! array_key_exists($navigation, Gmap::$control_navigation))
		{
			throw new Kohana_Exception('Given Navigation-control ":navigation" is not valid.',
				array(':navigation' => $navigation));
		} // if
	} // function
	
	/**
	 * Validate, if the given position is supported.
	 * 
	 * @param string $position
	 */
	protected static function validate_control_position($position)
	{
		if (! array_key_exists($position, Gmap::$control_positions))
		{
			throw new Kohana_Exception('":position" is no supported position.',
				array(':position' => $position));
		} // if
	} // function
	
	/**
	 * Validate, if the latitude is in bounds.
	 * 
	 * @param float $lat
	 * @return boolean
	 */
	protected static function validate_latitude($lat)
	{
		if ($lat > 180 OR $lat < -180)
		{
			throw new Kohana_Exception('Latitude has to lie between -180.0 and 180.0! Set to ":lat"',
				array(':lat' => $lat));
		} // if
	} // function
	
	/**
	 * Validate, if the longitude is in bounds.
	 * 
	 * @param float $lng
	 * @return boolean
	 */
	protected static function validate_longitude($lng)
	{
		if ($lng > 90 OR $lng < -90)
		{
			throw new Kohana_Exception('Longitude has to lie between -90.0 and 90.0! Set to ":lng"',
				array(':lng' => $lng));
		} // if
	} // function
	
	/**
	 * Validate, if the given map-type is supported.
	 * 
	 * @param string $maptype
	 */
	protected static function validate_maptype($maptype)
	{
		if (! array_key_exists($maptype, Gmap::$maptypes))
		{
			throw new Kohana_Exception('":maptype" is no supported map-type.',
				array(':maptype' => $maptype));
		} // if
	} // function
} // class