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
					
		$this->gen_page('Admin', 'admin/index', $pg_data );
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */