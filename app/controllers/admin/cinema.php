<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Cinema extends Controller {

	function Cinema()
	{
		parent::Controller();
		$this->load->helper('url');
		$this->load->database();
	}
	
	function index()
	{
		$this->view();
	}

	function view()
	{
		$filter = array(
			'page' => 0,
			'limit' => 20
			);
			
		$segs = $this->uri->uri_to_assoc(4);
		foreach( $segs as $k => $v ) {
			$filter[$k] = $v;
		}
		
		$this->db->order_by('title');
		$this->db->limit( $filter['limit'], $filter['page'] * $filter['limit'] );  // limit, offset
		$films = $this->db->get('films');
		
		$data = array('films' => $films );
		
		$pg_data = array(
			'title' => 'Admin - Cinema',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/films/films_list', $data, true ),
			'footer' => $this->load->view('layouts/standard_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
		
	}

	function add()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		if( $this->input->post('cancel')) {
			redirect('/admin/cinema');			
		}
		
		$this->form_validation->set_error_delimiters('','');
		$this->form_validation->set_rules('title', 'title', 'trim|required');
		$this->form_validation->set_rules('year', 'year', 'trim|integer');
		$this->form_validation->set_rules('running_time', 'running time', 'trim|integer');
		
		if( $this->form_validation->run()) {
			$data = array();
			$data['title'] = $this->input->post('title');
			$data['director'] = $this->input->post('director');
			$data['country'] = $this->input->post('country');
			$data['year'] = $this->input->post('year');
			$data['running_time'] = $this->input->post('running_time');
			$data['rating'] = $this->input->post('rating');
			$data['description'] = $this->input->post('description');
			$data['imdb_link'] = $this->input->post('link');
			$this->db->insert('films', $data );
			redirect('/admin/cinema');
		}
		
		$pg_data = array(
			'title' => 'Admin - Cinema',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/films/films_add', '', true ),
			'footer' => $this->load->view('layouts/standard_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );		
	}
	
	function edit()
	{
		$id = $this->uri->segment(4);
		if( ! $id ) {
			redirect("/admin/cinema");
		}

		if( $this->input->post('cancel')) {
			redirect('/admin/cinema');			
		}
				
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->form_validation->set_error_delimiters('','');
		$this->form_validation->set_rules('title', 'title', 'trim|required');
		$this->form_validation->set_rules('year', 'year', 'trim|integer');
		$this->form_validation->set_rules('running_time', 'running time', 'trim|integer');
		
		if( $this->form_validation->run()) {
			$data = array();
			$data['title'] = $this->input->post('title');
			$data['director'] = $this->input->post('director');
			$data['country'] = $this->input->post('country');
			$data['year'] = $this->input->post('year');
			$data['running_time'] = $this->input->post('running_time');
			$data['rating'] = $this->input->post('rating');
			$data['description'] = $this->input->post('description');
			$data['imdb_link'] = $this->input->post('link');
			
			$this->db->where( 'id', $id );
			$this->db->update('films', $data );
			redirect('/admin/cinema');
		}
		
		$this->db->where('id', $id );
		$film = $this->db->get('films')->row();
		
		$pg_data = array(
			'title' => 'Admin - Cinema',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/films/films_edit', array('film' => $film), true ),
			'footer' => $this->load->view('layouts/standard_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );		
	}
	
	//http://www.trynt.com/trynt-movie-imdb-api/
	//http://www.imdb.com/interfaces.html
	// this is kinda fiked up
	function imdb_lookup()
	{
		if( !$this->input->post('t')) {
			echo json_encode(array());
			return;
		}
		$title = $this->input->post('t');
		
		$this->load->library('spider');
		
		$url = "http://www.trynt.com/movie-imdb-api/v2/";
		$opts = array('t' => $title, "fo" => "json", "f" => "0");
		$this->spider->fetch( $url, $opts, 'post' );
		$data = json_decode($this->spider->html, true);
		if( isset($data['trynt']) && isset($data['trynt']['movie-imdb']) && isset($data['trynt']['movie-imdb']['matched-url'])) {
			$ra = $this->imdb_scrape($data['trynt']['movie-imdb']['matched-url']);
		} else {
			$ra = $data;
		}
		echo json_encode( $ra );
	}
	
	function imdb_search()
	{
		if( !$this->input->post('u')) {
			echo json_encode(array());
			return;
		}
		$ra = $this->imdb_scrape($this->input->post('u'));
		echo json_encode( $ra );
	}
	
	private function imdb_scrape( $url )
	{
		$this->load->helper('simple_html_dom');
		$data = array();
		$data['title'] = '';
		$data['director'] = '';
		$data['country'] = '';
		$data['year'] = '';
		$data['running_time'] = '';
		$data['description'] = '';
		$data['link'] = $url;
		
		$html = file_get_html( $url );
		if( preg_match('/(.*)\(\d+\)/', $html->find('div[id=tn15title] h1', 0)->plaintext, $matches)) {
			$data['title'] = trim($matches[1]);
		}
		foreach( $html->find('div[class=info]') as $info ) {
			$tag = $info->find('h5', 0);
			if( $tag ) {
				switch( trim($tag->innertext) ) {
					case 'Plot:':
					$tmp = explode("\n", "$info->plaintext");
					$data['description'] = $tmp[2];
					break;
					
					case 'Release Date:':
					$tmp = explode("\n", "$info->plaintext");
					if( preg_match('/\((.*)\)/', $tmp[2], $matches)) {
						$data['country'] = $matches[1];
					}
					if( preg_match('/(\d{4})/', $tmp[2], $matches)) {
						$data['year'] = $matches[1];
					}
					break;

					case 'Runtime:':
					$tmp = explode("\n", "$info->plaintext");
					if( preg_match('/(\d+)/', $tmp[2], $matches)) {
						$data['running_time'] = $matches[1];
					}
					break;
					
					case 'Country:':
					$tmp = explode(":", "$info->plaintext");
					$data['country'] = trim($tmp[1]);
					break;
					
					case 'Certification:':
					if( preg_match('/Canada:(\w+)\s/', $info->plaintext, $matches)) {
						$data['rating'] = $matches[1];
					}
					break;
					
					case 'Director:':
					$tmp = explode("\n", "$info->plaintext");
					$data['director'] = $tmp[2];
					break;
				}
			}
		}
		
		return $data;
	}
}

