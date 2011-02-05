<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Admin_Controller extends Controller 
{

	function Admin_Controller()
	{
		parent::Controller();
		
		// check the permissions
		//if( !$this->auth->restrict_role('admin')) 
		if( !$this->auth->restrict_role_db( true, $this->uri->uri_string() )) 
			$this->no_permission();
		
		// load up
		$this->load->helper('url','form');
		$this->load->library('tabs');
		$this->load->library('form_validation');
			
	}
	
	function index()
	{
		$this->no_permission();
	}
	
	protected function no_permission()
	{
		$this->gen_page('Admin - Error', '<p class="error">You do not have permission to view this resource</p>');
	}
	
	/**
	 * generate the final page
	 *
	 * $title - the page title
	 * $content - either the path to the view or actual html
	 * $view_data - if an array then $content is path to view, else NULL
	 * $sidebar - optional sidebar content
	 */
	protected function gen_page( $title, $content, $view_data = NULL, $sidebar = false  )
	{
		// if we have an array then we try and load the $content as a path
		// to a view with $view_data
		if( is_array($view_data) ) {
			$content = $this->load->view($content, $view_data, true);
		}
		
		// load the navigation
		$nav = $this->load->view('layouts/admin_nav', '', true);
		
		// we can have role specific menus if the role is tagged onto the end
		// eventually it would be cool to gen this dynamically, based on the
		// perms but I don't want the db hit so need to build a data gen first
		// perhaps. another project.
		$role = $this->session->userdata('logged_user_role');
		if( file_exists(APPPATH . "/views/layouts/admin_nav_$role.php")) {
			$nav = $this->load->view("layouts/admin_nav_$role", '', true);			
		}
		
		$pg_data = array(
			'title' => $title,
			'nav' => $nav,
			'sidebar' => $sidebar,
			'content' => $content,
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );				
	}
	
}