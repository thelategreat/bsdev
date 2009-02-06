<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Imdb extends Controller {

	function Imdb()
	{
		parent::Controller();
		$this->load->database();
	}
	
	function index()
	{
		$pg_data = array(
			'title' => 'Admin - eBar',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/imdb', '', true ),
			'footer' => $this->load->view('layouts/standard_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
	}


	function ajax_search()
	{
		$this->load->library('table');
		
		$dsn = 'mysql://jim:fugu@localhost/jmdb';
	
		$results = array('error' => 1, 'msg' => 'not found');
		
		$tt = array(
			'row_alt_start' => '<tr style="background-color: #dedede;">'
			);
		
		$this->table->set_template( $tt );
		
		if( $this->input->post('q')) {
			$q = $this->input->post('q');
			$DBO = $this->load->database($dsn, true);
			
			switch($this->input->post('t')) {
				case 'director':
					$res = $DBO->query("SELECT * FROM directors WHERE name LIKE " . $DBO->escape( $q .'%', false) . " LIMIT 100");
					break;
				case 'actor':
					$res = $DBO->query("SELECT * FROM actors WHERE name LIKE " . $DBO->escape( $q .'%', false) . " LIMIT 100");
					break;
				case 'year':
					$res = $DBO->query("SELECT * FROM movies WHERE year = " . $DBO->escape( $q, false) . " AND tv = 0 LIMIT 100");
					break;
				default:
					$res = $DBO->query("SELECT * FROM movies WHERE title LIKE " . $DBO->escape( $q .'%', false) . " AND tv = 0 LIMIT 100");
					break;
			}
			
			$results['num_rows'] = $res->num_rows();
			$results['err'] = 0;
			$results['msg'] = 'OK';
			$results['html'] = $this->table->generate($res);
		}
		echo json_encode($results);
	}
	
}

