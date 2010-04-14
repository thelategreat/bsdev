<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Imdb extends Admin_Controller {

	function Imdb()
	{
		parent::__construct();
		//$this->auth->restrict_role('admin');
		$this->load->database();
		$this->load->helper('markdown');
	}
	
	function index()
	{
		$pg_data = array(
			'title' => 'Admin - IMDB',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/imdb', '', true ),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
	}


	function ajax_search()
	{
		$this->load->library('table');
		
		$dsn = 'mysql://jim:fugu@localhost/jmdb';
	
		$results = array('error' => 1, 'msg' => 'not found');
		
		$tt = array(
			'row_start' => '<tr class="hilite" onclick="show_item(this);">',
			'row_alt_start' => '<tr class="odd hilite" onclick="show_item(this);" >'
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
					$res = $DBO->query("SELECT * FROM movies WHERE year = " . $DBO->escape( $q, false) . " AND type = 0 LIMIT 100");
					break;
				default:
					$res = $DBO->query("SELECT * FROM movies WHERE title LIKE " . $DBO->escape( $q .'%', false) . " AND type = 0 LIMIT 100");
					break;
			}
			
			$results['num_rows'] = $res->num_rows();
			$results['err'] = 0;
			$results['msg'] = 'OK';
			$results['html'] = $this->table->generate($res);
		}
		echo json_encode($results);
	}
	
	function ajax_details()
	{
		$dsn = 'mysql://jim:fugu@localhost/jmdb';
	
		$results = array('error' => 1, 'msg' => 'not found');
		
		if( $this->input->post('id')) {
			$id = $this->input->post('id');
			$DBO = $this->load->database($dsn, true);
			
			$pg_data = array();
			
			$type = $this->input->post('t');
			
			switch( $type ) {
				case 'director':
				$res = $DBO->query("SELECT * FROM directors WHERE directorid = $id");
				$row = $res->row();
				
				$pg_data['data'] = $row;
				$pg_data['bio'] = $DBO->query("SELECT * FROM biographies WHERE name = " . $DBO->escape($row->name))->row();
				break;
				
				case 'actor':
				$res = $DBO->query("SELECT * FROM actors WHERE actorid = $id");
				$row = $res->row();
				
				$pg_data['data'] = $row;
				$bio = $DBO->query("SELECT * FROM biographies WHERE name = " . $DBO->escape($row->name));
				$pg_data['bio'] = $bio->num_rows() ? $bio->row() : null;
				break;
				
				default:
				$type="movie";
				$res = $DBO->query("SELECT * FROM movies WHERE movieid = $id");
				$row = $res->row();
				
				$pg_data['data'] = $row;
				$pg_data['directors'] = $DBO->query("select * from movies2directors as md, directors as d where md.movieid = $row->movieid and d.directorid = md.directorid");
				$pg_data['plot'] = $DBO->query("SELECT * FROM plots WHERE movieid = $row->movieid")->row();
				break;
			}								
			$results['err'] = 0;
			$results['msg'] = 'OK';
			$results['html'] = $this->load->view("admin/films/imdb_$type", $pg_data, true);
		}
		echo json_encode($results);
	}
}

