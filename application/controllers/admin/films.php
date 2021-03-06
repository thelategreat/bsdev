<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Films extends Admin_Controller 
{

	function Films()
	{
		parent::__construct();
	}
	
	function index()
	{
		$this->view();
	}

	/**
		 Basic index. Datatables handles all sorting so there's nothing fancy here 
	*/
	function view()
	{
		$this->db->order_by('title');
		$films = $this->db->get('films');
		
		$data = array(
			'films' => $films, 
			);
		
		$this->gen_page('Admin - Films', 'admin/films/films_list', $data );
	}

	function add()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		if( $this->input->post('cancel')) {
			redirect('/admin/films');			
		}
		
		$this->form_validation->set_error_delimiters('','');
		$this->form_validation->set_rules('title', 'title', 'trim|required');
		$this->form_validation->set_rules('year', 'year', 'trim|integer');
		$this->form_validation->set_rules('running_time', 'running time', 'trim|integer');
		
		if( $this->form_validation->run()) {
			$data = array();
			$data['ttno'] = $this->input->post('ttno');
			$data['title'] = $this->input->post('title');
			$data['director'] = $this->input->post('director');
			$data['country'] = $this->input->post('country');
			$data['year'] = $this->input->post('year');
			$data['running_time'] = $this->input->post('running_time');
			$data['aspect_ratio'] = $this->input->post('aspect_ratio');
			$data['rating'] = $this->input->post('rating');
			$data['description'] = $this->input->post('description');
			$data['imdb_link'] = $this->input->post('link');
			$data['trailer_link'] = $this->input->post('trailer_link');
			$this->db->insert('films', $data );
			
			if( $this->input->post('addedit')) {
				redirect('/admin/films/edit/' . $this->db->insert_id() . '/media');
			} else {
				redirect('/admin/films');
			}
		}
		
		$this->gen_page('Admin - Films', 'admin/films/films_add', array());
	}
	
	function edit()
	{
		$id = (int)$this->uri->segment(4);
		
		if( ! $id ) {
			redirect("/admin/films");
		}

		if( $this->input->post('cancel')) {
			redirect('/admin/films');			
		}
		if( $this->input->post('rm')) {
			redirect('/admin/films/rm/' . $id );			
		}
						

		$cur_tab = 'details';
		if( $this->uri->segment(5)) {
			$cur_tab = strtolower($this->uri->segment(5));
		}
		
		switch ($cur_tab) {
			case 'media':
			break;

			case 'associated_items':
			case 'items':
			break;

			default: 
				$this->load->helper('form');
				$this->load->library('form_validation');

				$this->form_validation->set_error_delimiters('','');
				$this->form_validation->set_rules('title', 'title', 'trim|required');
				$this->form_validation->set_rules('year', 'year', 'trim|integer');
				$this->form_validation->set_rules('running_time', 'running time', 'trim|integer');

				if( $this->form_validation->run()) {
					$data = array();
					$data['ttno'] = $this->input->post('ttno');
					$data['title'] = $this->input->post('title');
					$data['director'] = $this->input->post('director');
					$data['country'] = $this->input->post('country');
					$data['year'] = $this->input->post('year');
					$data['running_time'] = $this->input->post('running_time');
					$data['aspect_ratio'] = $this->input->post('aspect_ratio');
					$data['rating'] = $this->input->post('rating');
					$data['description'] = $this->input->post('description');
					$data['trailer_link'] = $this->input->post('trailer_link');
					$data['imdb_link'] = $this->input->post('link');

					$this->db->where( 'id', $id );
					$this->db->update('films', $data );
					redirect('/admin/films');
				}			
			break;
		}
		
		// Can we delete this item - yes only if there are no events associated to it 
		$can_delete = false;
		$res = $this->db->query("SELECT * FROM events WHERE event_ref = $id");
		if( $res->num_rows() == 0 ) {
			$can_delete = true;
		}

		$this->db->where('id', $id );
		$film = $this->db->get('films')->row();
		if( !$film ) {
			redirect('/admin/films');			
		}
				
		$tabs = $tabs = $this->tabs->gen_tabs(array('Details','Media','Associated Items'), $cur_tab, '/admin/films/edit/' . $id);
		
		
		switch ($cur_tab) {
			case 'media':

				$data = array(
					'film' => $film,
					'tabs' => $tabs,
					'title' => "Media for $film->title",											// the page title
					'path' => '/films/' . $film->id,								// the page in the db for this
					'next' => "/admin/films/edit/$film->id/media",  // the web path to this tab		
					);
				//$content = $this->load->view('admin/films/films_media', array('film' => $film, 'tabs' => $tabs ), true );
				$content = $this->load->view('admin/media/media_tab', $data, true );
			break;

			case 'associated_items':
				$data = array(
					'film' => $film,
					'tabs' => $tabs,
					'item_type' => 'film', // This is used to define which association table will be used article_films/films_events, etc
					'title' => "Associated items for $film->title",
					'path' => '/films/' . $film->id,
					'next' => "/admin/films/edit/$film->id/associated_items",  // the web path to this tab		
				);

				$content = $this->load->view('admin/items/items_index', $data, true);

			break;

			default:
				$content = $this->load->view('admin/films/films_edit', array('film' => $film, 'can_delete' => $can_delete, 'tabs' => $tabs ), true );
			break;
		}

		$this->gen_page( 'Admin - Films', $content );
	}

	/**
	 * Delete this record from the database and any associated media
	 * @param none
	 * @return none
	 */
	function rm()
	{
		$id = (int)$this->uri->segment(4);
		if( $id ) {
			$this->db->where('id', $id );
			$film = $this->db->delete('films');

			// TODO: remove disk images as well!!
			$this->db->where('path', "/films/$id" );
			$film = $this->db->delete('media_map');
		
			$this->db->where('films_id', $id);
			$film = $this->db->delete('events_times');	
		}
		redirect('/admin/films');
	}
	
	
	/* NOTE: I think nothing below here is used anymore */
	
	function upload()
	{
		$id = $this->uri->segment(4);
		if( ! $id ) {
			return '';
		}
		
		$path = '../public/pubmedia/films/';
		if( !file_exists( $path . $id)) {
			mkdir( $path . $id, 0777, TRUE );
		}
		
		$fname = $path . $id . '/' . basename($_FILES['userfile']['name']);
		$bad_chars = array('`','"','\'','\\','/');
		$fname = str_replace(' ','_',$fname);
		$fname = str_replace($bad_chars,'',$fname);
		if( move_uploaded_file( $_FILES['userfile']['tmp_name'], $fname )) {
			// cool
		} else {
			// not cool
		}
		return '';
	}
	
	function media( )
	{
		$id = $this->uri->segment(4);
		if( ! $id ) {
			redirect("/admin/films");
		}

    $xml =<<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<images>

  <image>
    <caption>Tester image</caption>
    <source>/pubmedia/films/doubt.jpg</source>
    <type>image</type>
    <info>lorem freakin ipsum and all that stuff</info>
  </image>
  <image>
    <caption>Tester image</caption>
    <source>/pubmedia/films/milk.jpg</source>
    <type>image</type>
    <info>lorem freakin ipsum and all that stuff</info>
  </image>
  <image>
    <caption>Tester image</caption>
    <source>/pubmedia/films/the-wrestler.jpg</source>
    <type>image</type>
    <info>lorem freakin ipsum and all that stuff</info>
  </image>
  <image>
    <caption>Tester image</caption>
    <source>/pubmedia/films/the-wrestler.jpg</source>
    <type>image</type>
    <info>lorem freakin ipsum and all that stuff</info>
  </image>
  <image>
    <caption>Tester image</caption>
    <source>/pubmedia/films/the-wrestler.jpg</source>
    <type>image</type>
    <info>lorem freakin ipsum and all that stuff</info>
  </image>
  <image>
    <caption>Tester image</caption>
    <source>/pubmedia/films/the-wrestler.jpg</source>
    <type>image</type>
    <info>lorem freakin ipsum and all that stuff</info>
  </image>
</images>
EOF;
	  
		$xml = '<?xml version="1.0" encoding="UTF-8"?><images>';
		
		$dh = @opendir($path = '../public/pubmedia/films/' . $id );
		if( $dh ) {
			while(($file = readdir($dh)) !== false ) {
				if( $file[0] != '.') {
					$xml .= '<image>';
					$xml .= '<source>/pubmedia/films/' . $id . '/' . $file . '</source>';
					$xml .= '</image>';
				}
			}
		}
		$xml .= '</images>';
	
	  header("Content-Type: text/xml");
	  echo $xml;
	  
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

	/**
		Lookup Show Times
		Used as a callback to look up show times in the events events_times 
		@param Film ID (POST)
		@return JSON encoded list of film times
	*/
	function lookup_showtimes() {
		$film_id = $this->input->post('id');
		$result = array();

		if (!$film_id) return json_encode($result);

		$sql = "SELECT * FROM 
				events_times
				WHERE films_id = " . $this->db->escape($film_id)
				. " ORDER BY start_time";
		$query = $this->db->query($sql);
		
		$out = array();
		foreach ($query->result() as $row) {
			$row->date = date('Y-m-d', strtotime($row->start_time));
			$row->start = date('h:i a', strtotime($row->start_time));
			$row->end = date('h:i a', strtotime($row->end_time));
			$out[] = $row;
		}
		echo json_encode($out);
	}

	/** 
		Remove a show time
		@param Events_times table ID
	*/
	function remove_showtime() {
		$id = $this->input->post('id');

		if (!$id) {
			echo json_encode(array('status'=>false, 'message'=>'Missing ID'));
			exit;
		}

		$sql = "DELETE FROM events_times WHERE id = " . $this->db->escape($id);
		$this->db->query($sql);

		if ($this->db->affected_rows() > 0) {
			echo json_encode(array('status'=>true));
		} else {
			echo json_encode(array('status'=>false, 'message'=>'ID not found'));
		}		
	}

	/** 
		AJAX call to add a show time
		@param Film ID
		@param Venue
		@param Start and end dates and times
	*/
	function ajax_add_showtime() {
		$id 	= $this->input->post('id');
		$venue 	= $this->input->post('venue');
		$start 	= $this->input->post('start');
		$end   	= $this->input->post('end');
		$start_time_hour  = $this->input->post('start_time_hour');
		$start_time_min   = $this->input->post('start_time_min');
		if ($start_time_min == '0') $start_time_min = '00';
		$start_time_am_pm = $this->input->post('start_time_am_pm');
		$end_time_hour    = $this->input->post('end_time_hour');
		$end_time_min     = $this->input->post('end_time_min');
		if ($end_time_min == '0') $end_time_min = '00';
		$end_time_am_pm   = $this->input->post('end_time_am_pm');

		if (!($id && $venue && $start && $end  && $start_time_hour && $start_time_min && $start_time_am_pm
				&& $end_time_hour && $end_time_min && $end_time_am_pm)) {
			echo json_encode(array('status'=>false, 'message'=>'Fields are missing'));
			exit;
		}

		$start_time = date('Y-m-d H:i', strtotime($start . ' ' . $start_time_hour . ':' . $start_time_min . ' ' . $start_time_am_pm));
		$end_time = date('Y-m-d H:i', strtotime($end . ' ' . $end_time_hour . ':' . $end_time_min . ' ' . $end_time_am_pm));

		$sql = "SELECT COUNT(*) AS count
			FROM events_times 
			WHERE films_id = " . $this->db->escape($id) . "
			AND start_time = '{$start_time}'
			AND end_time = '{$end_time}'";
		$query = $this->db->query($sql);
		$result = $query->row();

		if ($result->count > 0) {
			echo json_encode(array('status'=>false, 'message'=>'Event already exists'));
			exit;
		}

		$sql = "INSERT INTO events_times (films_id, start_time, end_time) 
				VALUES (" 
					. $this->db->escape($id) . ", " 
					. $this->db->escape($start_time) . ", " 
					. $this->db->escape($end_time) . ")";
		//echo $sql;
		$values = array($this->db->escape($id), $this->db->escape($start_time), $this->db->escape($end_time));
		//var_dump($values);
		$this->db->query($sql);

		if ($this->db->affected_rows() > 0) {
			echo json_encode(array('status'=>true, 'message'=>'OK'));
		}
	}
}

