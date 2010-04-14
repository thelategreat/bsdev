<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Logs extends Admin_Controller 
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
	 *
	 */
	function index()
	{		
		$this->gen_page('Admin - Logs', '<h3>Logs</h3>' );		
	}
}
