<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Gmap
{
	protected $config = NULL;
	protected $template = NULL;
	
	public function __construct()
	{
		$this->config = Kohana::config('gmap');
		$this->template = View::factory('gmap');
	} // function
	
	public function __tostring()
	{
		$this->template->bind('config', $this->config);
		return (String) $this->template;
	} // function
} // class