<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Database extends Admin_Controller 
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
		$db_data = array(
			'tables' => $this->db->list_tables()
		);
		
		$this->gen_page('Admin - Database', 'admin/database', $db_data );
	}
}
