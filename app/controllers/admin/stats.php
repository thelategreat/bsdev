<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Stats extends Admin_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::__construct();
		$this->load->helper('misc');
	}
	
	function index()
	{
		$this->about();
	}
	
	/**
	 *
	 */
	function about()
	{
		$s = '';
				
		$data = array();
		$section[] = array('PHP Version', phpversion());
		$section[] = array('CI Version', CI_VERSION );
		$data[] = array('General', $section );
	
		$this->gen_page('Admin - Stats', 'admin/stats/stats_about', array('info' => $data));				
	}
	
	
}
