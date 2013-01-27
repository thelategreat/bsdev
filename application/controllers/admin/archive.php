<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");


class Archive extends Admin_Controller 
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
		$this->gen_page('Admin - Archive', '<h3>Archive</h3>');		
	}
}
