<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Gmap
{
	protected $_config = NULL;
	protected $_options = array(
		'lat',
		'lng',
		'zoom',
		'sensor',
		'maptype',
		'view',
		'gmap_size_x',
		'gmap_size_y',
		'gmap_controls',
	);
	protected $marker = array();
	protected $view = NULL;
	protected static $maptypes = array(
		'road'      => 'google.maps.MapTypeId.ROADMAP',
		'satellite' => 'google.maps.MapTypeId.SATELLITE',
		'hybrid'    => 'google.maps.MapTypeId.HYBRID',
		'terrain'   => 'google.maps.MapTypeId.TERRAIN',
	);
	protected static $control_maptypes = array(
		'horizontal_bar' => 'google.maps.MapTypeControlStyle.HORIZONTAL_BAR',
		'dropdown_menu' => 'google.maps.MapTypeControlStyle.DROPDOWN_MENU',
		'default' => 'google.maps.MapTypeControlStyle.DEFAULT',
	);
	protected static $control_navigation = array(
		'small' => 'google.maps.NavigationControlStyle.SMALL',
		'zoom_pan' => 'google.maps.NavigationControlStyle.ZOOM_PAN',
		'android' => 'google.maps.NavigationControlStyle.ANDROID',
		'default' => 'google.maps.NavigationControlStyle.DEFAULT',
	);
	protected static $control_positions = array(
		'top' => 'google.maps.ControlPosition.TOP',
		'top_left' => 'google.maps.ControlPosition.TOP_LEFT',
		'top_right' => 'google.maps.ControlPosition.TOP_RIGHT',
		'bottom' => 'google.maps.ControlPosition.BOTTOM',
		'bottom_left' => 'google.maps.ControlPosition.BOTTOM_LEFT',
		'bottom_right' => 'google.maps.ControlPosition.BOTTOM_RIGHT',
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
		$available_keys = $this->_options;
		$this->_options = array();
		
		// Check if each available key is set. Using Arr::extract filled everything up with NULL.
		foreach ($available_keys as $key)
		{
			if (isset($options[$key]))
			{
				$this->_options[$key] = $options[$key];
			} // if
		} // foreach
		
		unset($available_keys);
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
	 * @param string $view Defines a view for rendering.
	 * @return string
	 */
	public function render($view = '')
	{
		$temp = array();
		
		foreach ((Array) $this->_config as $key => $value)
		{
			$temp[str_replace('default_', '', $key)] = $value;
		} // foreach
		
		// Override the config-defaults with your setted options.
		$this->_options = Arr::merge($temp, $this->_options);
		unset($temp);
		
		// Set the map-type.
		$this->set_maptype($this->_options['maptype']);
		
		// Set the latitude.
		$this->set_pos($this->_options['lat'], NULL);
		
		// Set the longitude.
		$this->set_pos(NULL, $this->_options['lng']);
		
		// Set the Google Map size.
		$this->set_gmap_size($this->_options['gmap_size_x'], $this->_options['gmap_size_y']);
		
		// Set the styles for the Google Map controls.
		$this->_options['gmap_controls']['maptype']['style'] = Gmap::validate_control_maptype($this->_options['gmap_controls']['maptype']['style']);
		$this->_options['gmap_controls']['navigation']['style'] = Gmap::validate_control_navigation($this->_options['gmap_controls']['navigation']['style']);
		
		// Set the positions for the Google Map controls.
		$this->_options['gmap_controls']['maptype']['position'] = Gmap::validate_control_position($this->_options['gmap_controls']['maptype']['position']);
		$this->_options['gmap_controls']['navigation']['position'] = Gmap::validate_control_position($this->_options['gmap_controls']['navigation']['position']);
		$this->_options['gmap_controls']['scale']['position'] = Gmap::validate_control_position($this->_options['gmap_controls']['scale']['position']);
		
		// If we set the view parameter in this method, use it!
		if (! empty($view))
		{
			$this->_options['view'] = $view;
		}		
		elseif ($this->_options['view'] === NULL)
		{
			$this->_options['view'] = $this->_config->default_view;
		} // if
		
		// Bind the necessary variables.
		$this->view = View::factory($this->_options['view'])
			->bind('options', $this->_options)
			->bind('marker', $this->marker);

		// Render the view. 
		return $this->view->render();
	} // function

	/**
	 * Set some controls for your gmap.
	 * You can specify how to display the map-type and navigation control.
	 * For more information visit https://github.com/solidsnake/Kohana-Google-Maps-Module/wiki
	 * 
	 * @param array $options Set the options for the gmap-controls here.
	 * @return Gmap
	 */
	public function set_gmap_controls(array $options)
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
	 * @param array $options Set the options for the controls here.
	 * @return Gmap
	 */
	public function set_gmap_controls_maptype(array $options)
	{
		if (isset($options['display']))
		{
			$this->_options['gmap_controls']['maptype']['display'] = (Bool) $options['display'];
		} // if
		
		if (isset($options['style']))
		{
			$this->_options['gmap_controls']['maptype']['style'] = Gmap::validate_control_maptype($options['style']);
		} // if
		
		if (isset($options['position']))
		{
			$this->_options['gmap_controls']['maptype']['position'] = Gmap::validate_control_position($options['position']);
		} // if
		
		return $this;
	} // function

	/**
	 * Set the navigation controls for your gmap.
	 * For more information visit https://github.com/solidsnake/Kohana-Google-Maps-Module/wiki
	 * 
	 * @param array $options Set the options for the controls here.
	 * @return Gmap
	 */
	public function set_gmap_controls_navigation(array $options)
	{
		if (isset($options['display']))
		{
			$this->_options['gmap_controls']['navigation']['display'] = (Bool) $options['display'];
		} // if
		
		if (isset($options['style']))
		{
			$this->_options['gmap_controls']['navigation']['style'] = Gmap::validate_control_navigation($options['style']);
		} // if
		
		if (isset($options['position']))
		{
			$this->_options['gmap_controls']['navigation']['position'] = Gmap::validate_control_position($options['position']);
		} // if
		
		return $this;
	} // function

	/**
	 * Set the scale controls for your gmap.
	 * For more information visit https://github.com/solidsnake/Kohana-Google-Maps-Module/wiki
	 * 
	 * @param array $options Set the options for the controls here.
	 * @return Gmap
	 */
	public function set_gmap_controls_scale(array $options)
	{
		if (isset($options['display']))
		{
			$this->_options['gmap_controls']['scale']['display'] = (Bool) $options['display'];
		} // if
		
		if (isset($options['position']))
		{
			$this->_options['gmap_controls']['scale']['position'] = Gmap::validate_control_position($options['position']);
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
		if ($x != NULL)
		{
			$this->_options['gmap_size_x'] = (is_numeric($x)) ? $x . 'px' : $x;
		} // if
		
		if ($y != NULL)
		{
			$this->_options['gmap_size_y'] = (is_numeric($y)) ? $y . 'px' : $y;
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
		$this->_options['maptype'] = Gmap::validate_maptype($maptype);
		
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
			$this->_options['lat'] = Gmap::validate_latitude($lat);
		} // if
		
		if ($lng != NULL)
		{
			$this->_options['lng'] = Gmap::validate_longitude($lng);
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
	 * @return string
	 */
	protected static function validate_control_maptype($maptype)
	{
		if (! array_key_exists($maptype, Gmap::$control_maptypes) AND ! in_array($maptype, Gmap::$control_maptypes))
		{
			throw new Kohana_Exception('Given Map-Type ":maptype" control is not valid.',
				array(':maptype' => $maptype));
		} // if
				
		if (array_key_exists($maptype, Gmap::$control_maptypes))
		{ 
			return Gmap::$control_maptypes[$maptype];
		} // if
		elseif (in_array($maptype, Gmap::$control_maptypes))
		{
			return $maptype;
		} // if
	} // function
	
	/**
	 * Validate, if the given maptype-control is valid.
	 * 
	 * @param float $lat
	 * @return string
	 */
	protected static function validate_control_navigation($navigation)
	{
		if (! array_key_exists($navigation, Gmap::$control_navigation) AND ! in_array($navigation, Gmap::$control_navigation))
		{
			throw new Kohana_Exception('Given Navigation-control ":navigation" is not valid.',
				array(':navigation' => $navigation));
		} // if
				
		if (array_key_exists($navigation, Gmap::$control_navigation))
		{ 
			return Gmap::$control_navigation[$navigation];
		} // if
		elseif (in_array($navigation, Gmap::$control_navigation))
		{
			return $navigation;
		} // if
	} // function
	
	/**
	 * Validate, if the given position is supported.
	 * 
	 * @param string $position
	 * @return string
	 */
	protected static function validate_control_position($position)
	{
		if ($position === NULL)
		{
			return NULL;
		} // if
		
		if (! array_key_exists($position, Gmap::$control_positions) AND ! in_array($position, Gmap::$control_positions))
		{
			throw new Kohana_Exception('":position" is no supported position.',
				array(':position' => $position));
		} // if
				
		if (array_key_exists($position, Gmap::$control_positions))
		{ 
			return Gmap::$control_positions[$position];
		} // if
		elseif (in_array($position, Gmap::$control_positions))
		{
			return $position;
		} // if
	} // function
	
	/**
	 * Validate, if the latitude is in bounds.
	 * 
	 * @param float $lat
	 * @return float
	 */
	protected static function validate_latitude($lat)
	{
		if ($lat > 180 OR $lat < -180)
		{
			throw new Kohana_Exception('Latitude has to lie between -180.0 and 180.0! Set to ":lat"',
				array(':lat' => $lat));
		} // if
		
		return $lat;
	} // function
	
	/**
	 * Validate, if the longitude is in bounds.
	 * 
	 * @param float $lng
	 * @return float
	 */
	protected static function validate_longitude($lng)
	{
		if ($lng > 90 OR $lng < -90)
		{
			throw new Kohana_Exception('Longitude has to lie between -90.0 and 90.0! Set to ":lng"',
				array(':lng' => $lng));
		} // if
		
		return $lng;
	} // function
	
	/**
	 * Validate, if the given map-type is supported.
	 * 
	 * @param string $maptype
	 * @return string
	 */
	protected static function validate_maptype($maptype)
	{
		if (! array_key_exists($maptype, Gmap::$maptypes) AND ! in_array($maptype, Gmap::$maptypes))
		{
			throw new Kohana_Exception('":maptype" is no supported map-type.',
				array(':maptype' => $maptype));
		} // if
		
		if (array_key_exists($maptype, Gmap::$maptypes))
		{ 
			return Gmap::$maptypes[$maptype];
		} // if
		elseif (in_array($maptype, Gmap::$maptypes))
		{
			return $maptype;
		} // if
	} // function
} // class