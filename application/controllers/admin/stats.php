<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Stats extends Admin_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::__construct();
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
		
		$build_text = 'missing';
		
		// this gets created automatically by git commit/pull process
		// see: git-rev.sh
		if( file_exists( '../build.txt')) {
			$build_text = file_get_contents('../build.txt');
		}
		
		$msg = "<p class='info'>Bookshelf web system. ($build_text)</p>";
		$msg = '';		
		$data = array();
		$section[] = array('OS', php_uname('s') . ' v' . php_uname('r'));
		$section[] = array('PHP', phpversion());
		$section[] = array('CI', CI_VERSION );
		$section[] = array('Database', $this->db->platform() . ' v' . $this->db->version() );
		$data[] = array('Versions', $section );
		$data[] = array('Build', array(array('Build', $build_text)));
	
		$this->gen_page('Admin - Stats', 'admin/stats/stats_about', array('info' => $data, 'msg' => $msg ));				
	}
	
	
}
