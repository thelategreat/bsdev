<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

/**
 * Front page
 *
 * @package default
 * @author J Knight
 **/
class Migrate extends Admin_Controller 
{
  /**
   * CTOR
   *
   * @return void
   **/
	function Migrate()
	{
		parent::__construct();    
	}
	
	function index()
	{	
		require( APPPATH . '/config/database.php');
		$keys = array_keys($db);

		$this->gen_page('Admin - Migrate', 'admin/admin_migrate', array('keys' => $keys));
	}
	
	function run()
	{
		if( $this->input->post('run')) {
			echo '<a href="/admin/migrate">back</a>';
			$this->load->helper('migrator');	
			$ci =& get_instance();
			$db = $ci->load->database( $this->input->post('key'), TRUE ); 
			$m = new DBMigrator( APPPATH . 'migrations/', $db );
			echo '<pre>';
			$m->migrate();
			echo '</pre>';					
		} else {
			redirect('/admin/migrate');
		}
	}
}