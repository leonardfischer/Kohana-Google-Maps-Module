<?php
/**
 * Tests the Arr lib that's shipped with kohana
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
	 * @covers Gmap::validate_longitude
	 * @expectedException Kohana_Exception
	 */
    function test_validate_longitude_exception_if_lower_than_minus90()
    {
    	$map = new Gmap();
    	$map->set_pos(NULL, -90.1);
    } // function
    
	/**
	 * Tests the exception, thrown by the validate_maptype method.
	 * 
	 * @test
	 * @covers Gmap::validate_maptype
	 * @dataProvider provider_maptype
	 */
    function test_validate_maptype($maptype)
    {
    	$map = new Gmap();
    	$map->set_maptype($maptype);
    } // function
    
	/**
	 * Tests the exception, thrown by the validate_maptype method.
	 * 
	 * @test
	 * @covers Gmap::validate_maptype
	 * @expectedException Kohana_Exception
	 */
    function test_validate_maptype_exception()
    {
    	$map = new Gmap();
    	$map->set_maptype('NotExisting');
    } // function
} // class