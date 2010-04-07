<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Stats extends Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::Controller();
		$this->auth->restrict_role(array('admin','editor'));
		$this->load->helper('misc');
	}
	
	function index()
	{
		$this->about();
	}
	
	/**
	 *
	 */
	function about()
	{
		$s = '';
				
		$data = array();
		$section[] = array('PHP Version', phpversion());
		$section[] = array('CI Version', CI_VERSION );
		$data[] = array('General', $section );
	
		//$s .= html_table( $data, NULL, 'General' );
				
		$pg_data = array(
			'title' => 'Admin',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'footer' => $this->load->view('layouts/admin_footer', '', true),
			'content' => $this->load->view('admin/stats/stats_about', array('info' => $data), true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
	}
	
	
}
