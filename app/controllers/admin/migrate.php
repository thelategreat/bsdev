<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

/**
 * Front page
 *
 * @package default
 * @author J Knight
 **/
class Migrate extends Controller 
{
  /**
   * CTOR
   *
   * @return void
   **/
	function Migrate()
	{
		parent::Controller();
		$this->auth->restrict_role('admin');
		$this->load->helper('url');  
    
	}
	
	function index()
	{	
		require( APPPATH . '/config/database.php');
		$keys = array_keys($db);

		$this->load->view('layouts/admin_nav', '', true);
		
		$pg_data = array(
			'title' => 'Admin',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'footer' => $this->load->view('layouts/admin_footer', '', true),
			'content' => $this->load->view('admin/admin_migrate', array('keys' => $keys), true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
		
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