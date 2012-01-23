<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Lists extends Admin_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
  }

  function index()
	{
			$view_data = array( 
				);

			$this->gen_page('Admin - Lists', 'admin/lists/lists', $view_data );
	}
	
} //  
