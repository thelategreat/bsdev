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
		$this->gen_page('Admin', 'admin/index', array());
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */