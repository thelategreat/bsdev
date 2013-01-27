<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");


class Home extends Admin_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Home page
	 *
	 * @return void
	 **/
	function index()
	{	
		$pg_data = array();
		$pg_data['counts'] = array();
		$pg_data['counts']['Events'] = '<span style="color: green">This Week: 0</span> <span style="color: blue">This Month: 0</span> <span style="color: purple">This Year: 0</span>';
		$pg_data['counts']['Articles'] = '<span style="color: green">Active: 0<span> <span style="color: purple">Pending: 0</span> Total: 0';
		$pg_data['counts']['Comments'] = '<span style="color: purple">Pending: 0</span> Total: 0';
		$pg_data['counts']['Media'] = 'Images: 0 Links: 0';
		$pg_data['counts']['Ads'] = '<span style="color: green">Active: 0</span> <span style="color: red">Expiring: 0</span> Clicks: 0';
		$pg_data['counts']['Newletters'] = '<span style="color: purple">Pending 0</span>';
		$pg_data['counts']['Users'] = '<span style="color: green">This week: 0</span> <span style="color: blue">Last Week: 0</span> Total: 0';

		$pg_data['server'] = array();
		$pg_data['server']['Visits'] = 0;
		$pg_data['server']['Redirects'] = 0;
		$pg_data['server']['404'] = 0;
					
		$this->gen_page('Admin', 'admin/index', $pg_data, '<h3>Sidebar Test</h3>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Pellentesque a massa. Praesent facilisis viverra diam. Nulla auctor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Pellentesque ultricies neque vitae nisl. Pellentesque ullamcorper est vitae eros. Ut nisi eros, mattis nec, tempus quis, rutrum eu, enim. Vivamus pellentesque dui nec lectus facilisis luctus. Donec mi libero, viverra sed, commodo sit amet, tincidunt id, sem. Duis turpis. Phasellus tristique, felis et interdum luctus, magna purus porttitor dui, eu viverra pede lectus eget elit. Mauris ultricies pellentesque turpis. Praesent tortor ante, adipiscing vel, ultricies eget, ullamcorper non, leo. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vivamus tincidunt consectetuer ligula. Quisque nibh. Phasellus ac urna in mauris bibendum pharetra. Nulla faucibus, diam eget ornare commodo, leo erat laoreet turpis, scelerisque interdum ipsum sapien at nulla.<hr/>' );
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */