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
		//$this->load->library('tabs');
    
		$this->page_tabs = array(
		  'Site', 'Users', 'Roles', 'Projects', 'Tickets','Archive'
		  );
    
		$this->main_menu = array(
		  '<a href="/">Home</a>',
		  '<a href="/project">Projects</a>',
		  '<a href="/search">Search</a>',
		  '<a href="/help">Help</a>',
		  '<a class="selected" href="/admin">Admin</a>'
		  );
		
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
			$m = new DBMigrator( APPPATH . 'migrations/', $this->input->post('key'));
			echo '<pre>';
			$m->migrate();
			echo '</pre>';					
		} else {
			redirect('/admin/migrate');
		}
	}
}