<?php
/**
 * Tests the Google Maps Module.
 *
 * @group Google Maps Module
 *
 * @package    Unittest
 * @author     Leonard Fischer <leonard.fischer@sn4ke.de>
 */
Class GmapTest extends Kohana_Unittest_TestCase
{
	/**
	 * Provides maptype test data()
	 *
	 * @return array
	 */
	public function provider_maptype()
	{
		return array(
			array('road'),
			array('satellite'),
			array('hybrid'),
			array('terrain'),
		);
	}

	/**
	 * Tests the exception, thrown by the validate_latitude method.
	 * 
	 * @test
	 * @covers Gmap::set_pos
	 * @covers Gmap::validate_latitude
	 * @expectedException Kohana_Exception
	 */
    function test_validate_latitude_exception_if_higher_than_180()
    {
    	$map = new Gmap();
    	$map->set_pos(180.1, NULL);
    } // function
    
	/**
	 * Tests the exception, thrown by the validate_latitude method.
	 * 
	 * @test
	 * @covers Gmap::set_pos
	 * @covers Gmap::validate_latitude
	 * @expectedException Kohana_Exception
	 */
    function test_validate_latitude_exception_if_lower_than_minus180()
    {
    	$map = new Gmap();
    	$map->set_pos(-180.1, NULL);
    } // function
    
	/**
	 * Tests the exception, thrown by the validate_longitude method.
	 * 
	 * @test
	 * @covers Gmap::set_pos
	 * @covers Gmap::validate_longitude
	 * @expectedException Kohana_Exception
	 */
    function test_validate_longitude_exception_if_higher_than_90()
    {
    	$map = new Gmap();
    	$map->set_pos(NULL, 90.1);
    } // function
    
	/**
	 * Tests the exception, thrown by the validate_longitude method.
	 * 
	 * @test
	 * @covers Gmap::set_pos
	 * @covers Gmap::validate_longitude
	 * @expectedException Kohana_Exception
	 */
    function test_validate_longitude_exception_if_lower_than_minus90()
    {
    	$map = new Gmap();
    	$map->set_pos(NULL, -90.1);
    } // function
    
	/**
	 * Tests the validate_maptype method.
	 * 
	 * @test
	 * @covers Gmap::set_maptype
	 * @covers Gmap::validate_maptype
	 * @dataProvider provider_maptype
	 */
    function test_validate_maptype($maptype)
    {
    	$map = new Gmap();
    	$this->assertSame($map ,$map->set_maptype($maptype));
    } // function
    
	/**
	 * Tests the exception, thrown by the validate_maptype method.
	 * 
	 * @test
	 * @covers Gmap::set_maptype
	 * @covers Gmap::validate_maptype
	 * @expectedException Kohana_Exception
	 */
    function test_validate_maptype_exception()
    {
    	$map = new Gmap();
    	$map->set_maptype('NotExisting');
    } // function
    
	/**
	 * Tests the setting options through the constructor.
	 * 
	 * @test
	 * @covers Gmap::__construct
	 * @covers Gmap::get_option
	 */
    function test_constructor_options_parameter()
    {
    	$options = array(
    		'abc' => 123,
    		'def' => TRUE,
    		'ghi' => FALSE,
    		'jkl' => array(),
			'lat' => 12.34,
			'lng' => 34.56,
			'zoom' => 10,
			'sensor' => TRUE,
			'maptype' => 'terrain',
			'view' => 'gmap_demo',
			'gmap_size_x' => 666,
			'gmap_size_y' => 333,
			'gmap_controls' => array(
				'maptype' => array('display' => FALSE),
				'navigation' => array('display' => FALSE),
				'scale' => array('display' => FALSE),
			),
		);
    	$map = new Gmap($options);
		
		$expected = array(
			'lat' => 12.34,
			'lng' => 34.56,
			'zoom' => 10,
			'sensor' => TRUE,
			'maptype' => 'terrain',
			'view' => 'gmap_demo',
			'gmap_size_x' => 666,
			'gmap_size_y' => 333,
			'gmap_controls' => array(
				'maptype' => array('display' => FALSE),
				'navigation' => array('display' => FALSE),
				'scale' => array('display' => FALSE),
			),
		);
		
		$this->assertEquals($expected, $map->get_option());
    } // function
    
	/**
	 * Tests the factory method.
	 * 
	 * @test
	 * @covers Gmap::__construct
	 * @covers Gmap::factory
	 */
    function test_factory_metod()
    {
    	$this->assertEquals(new Gmap(), Gmap::factory());
    } // function
} // class