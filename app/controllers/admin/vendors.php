<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Vendors extends Admin_Controller 
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
		$this->gen_page('Admin - Vendors', '<h3>Vendors</h3>' );		
	}
}
