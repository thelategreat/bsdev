<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

/**
 *
 */
class Media extends Controller 
{

	/**
	 *
	 */
	function Media()
	{
		parent::Controller();
		$this->auth->restrict_role(array('admin'));
		$this->load->helper('media');
		$this->load->library('tabs');
		$this->load->library('form_validation');
		$this->load->model('media_model');
	}

	/**
	 *
	 */
	function index()
	{
		if( $this->input->post("q") !== FALSE ) {
			$url = '/admin/media/index';
			// if we have a query			
			if( trim($this->input->post("q")) != '') {
				$params = explode(' ', $this->input->post("q"));
				foreach( $params as $p ) {
					$url .= '/' . urlencode($p);
				}
			}			
			redirect( $url );
		}
		
		$errors = '';
		$my_root = './media/';
		$page_size = 10;
		$stags = array();
		$page = 1;
				
		// the first segment might be a page number
		$offs = 4;
		if( $this->uri->segment(4) && is_numeric($this->uri->segment(4))) {
			$page = $this->uri->segment(4);
			$offs = 5;
			if( $page < 1 ) {
				$page = 1;
			}
		}
		// the rest of the segments are search tags
		for( $i = $offs; $i <= $this->uri->total_segments(); $i++ ) {
			$stags[] = $this->uri->segment($i);
		}
		
		// this is sent by other controller pages so we can get
		// back there once we are done here
		$next = $this->input->post('next');
		$slot = 'general';
		if( $next ) {
			if( $this->input->post('slot')) {
				$slot = $this->input->post('slot');
				$next .= "/$slot";
			}
		}
		
		// ------------
		// U P L O A D
		// ------------
		if( $this->input->post("upload")) {
			$conf['allowed_types'] = 'jpg|png';
			$conf['upload_path'] = $my_root;
			$uuid = $this->up($my_root, $conf );
			if( !$uuid ) {
				$errors = '<p class="error">';
				$errors .= 'There was trouble uploading the image. Perhaps it is too big?<br/>';
				$errors .= 'Images must be less than '.$this->config->item('max_image_size').'kbytes ';
				$errors .= 'and smaller than '.$this->config->item('max_image_width').'x'.$this->config->item('max_image_height');
				if( $this->input->post('next') ) {
					$next = $this->input->post('next');
					$errors .= " [<a href='$next'>return</a>]";
				}
				$errors .= '</p>';
			} else {
				if( $next ) {
					$path = $this->input->post('path');
					$this->media_model->add_media_for_path( $path, $uuid, $slot );
					redirect( $next );					
				}
				redirect('/admin/media/edit/' . $uuid );
			}
		}
		// ------------
		// L I N K
		// ------------
		if( $this->input->post("link")) {
			$uuid = gen_uuid();
			$this->media_model->add_link( $uuid, $this->input->post('url'), $this->session->userdata('logged_user'));
			if( $next ) {
				$path = $this->input->post('path');
				$this->media_model->add_media_for_path( $path, $uuid, $slot );
				redirect( $next );					
			}
			redirect('/admin/media/edit/' . $uuid );
		}
		
		$results = $this->media_model->get_media( null, $stags, $page, $page_size );
		
		$data = array(
			'items' => $results,
			'page' => $page,
			'stags' => $stags,
			'errors' => $errors
			);
		
		if( $this->input->post('ajax')) {
			$this->load->view('admin/media/media_index', $data );			
		} else {
			$pg_data = array(
				'title' => 'Admin - Media',
				'nav' => $this->load->view('layouts/admin_nav', '', true),
				'content' => $this->load->view('admin/media/media_index', $data, true),
				'footer' => $this->load->view('layouts/admin_footer', '', true)
			);
			$this->load->view('layouts/admin_page', $pg_data );				
		}
	}

	/**
	 *
	 */
	function search()
	{
		$errors = '';
		$my_root = './media/';
		$page_size = 10;
		$stags = array();
		$page = 1;
		
		if( $this->input->post("pg")) {
			$page = $this->input->post("pg");
		}
		
		if( $this->input->post("q")) {
			$stags = explode(' ', $this->input->post("q"));
		}
																
		$results = $this->media_model->get_media( null, $stags, $page, $page_size );
		
		$data = array(
			'items' => $results,
			'page' => $page,
			'stags' => $stags,
			'errors' => $errors
			);
		
		$this->load->view('admin/media/media_search', $data );			
	}

	/**
	 *
	 */
	function edit()
	{
		$errors = '';
		$this->load->library('form_validation');
		$uuid = $this->uri->segment(4);

		$page = NULL;
		// the page number, maybe
		if( $this->uri->segment(6) && is_numeric($this->uri->segment(6))) {
			$page = $this->uri->segment(6);
			if( $page < 1 ) {
				$page = 1;
			}
		}
		
		$this->form_validation->set_rules('title','Title','required');
		$this->form_validation->set_rules('caption','Caption','required');
		$this->form_validation->set_rules('tt_isbn','tt#/isbn','callback_tt_isbn_check');
		
		// -----------
		// S A V E
		// -----------
		if( $this->input->post('save') && $this->form_validation->run()) {
			$meta['title'] = $this->input->post('title');
			$meta['caption'] = $this->input->post('caption');
			$meta['tt_isbn'] = trim(str_replace('-','',$this->input->post('tt_isbn')));
			$meta['description'] = $this->input->post('description');
			$meta['license'] = $this->input->post('license');
			$this->media_model->update_media( $uuid, $meta, $this->input->post('tags') );			
			redirect('/admin/media');
		}
		// -----------
		// D E L E T E
		// -----------
		if( $this->input->post('delete')) {
			$this->media_model->remove_upload( $uuid );
			if( file_exists( './media/' . $uuid )) {
				unlink( './media/' . $uuid );
			}
			if( $this->input->post('page')) {
				redirect('/admin/media/index/' . $this->input->post('page'));				
			}
			redirect('/admin/media');
		}
		// -----------
		// C A N C E L
		// -----------
		if( $this->input->post('cancel')) {
			if( $this->input->post('page')) {
				redirect('/admin/media/index/' . $this->input->post('page'));				
			}
			redirect('/admin/media');
		}
		
		$item = $this->media_model->get_media( $uuid );
		$used = $this->media_model->get_media_usage( $uuid );
				
		$data = array(
			'item' => $item[0],
			'used' => $used,
			'page' => $page,
			'errors' => $errors
			);
		
		$pg_data = array(
			'title' => 'Admin - Media',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/media/media_edit', $data, true),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );						
	}

	/**
	 *
	 */
	function browser()
	{
		$is_ajax = true;
		$path = $this->input->post('path');
		$slot = $this->input->post('slot') ? $this->input->post('slot') : 'general' ;
		
		if( !$path ) {
			$is_ajax = false;
			$path = '/' . implode( '/', array_slice($this->uri->segment_array(), 3));
		}
		//$path = '/articles/1';
		
		$view_data = array(
			'media_path' => $path,
			'slot' => $slot,
			'errors' => '',
			'files' => $this->media_model->get_media_for_path( $path, $slot ),
			);
		
		if( !$is_ajax ) {
			$pg_data = array(
				'title' => 'Admin - Media',
				'nav' => $this->load->view('layouts/admin_nav', '', true),
				'content' => $this->load->view('admin/media/media_browser', $view_data, true),
				'footer' => $this->load->view('layouts/admin_footer', '', true)
			);
			$this->load->view('layouts/admin_page', $pg_data );									
		} else {
			$this->load->view('admin/media/media_browser', $view_data );						
		}
	}

	/**
	 * TinyMCE popup thing
	 */
	function mce()
	{
		if( $this->input->post("q") !== FALSE ) {
			$url = '/admin/media/mce';
			// if we have a query			
			if( trim($this->input->post("q")) != '') {
				$params = explode(' ', $this->input->post("q"));
				foreach( $params as $p ) {
					$url .= '/' . urlencode($p);
				}
			}			
			redirect( $url );
		}
		
		$errors = '';
		$my_root = './media/';
		$page_size = 10;
		$stags = array();
		$page = 1;
				
		// the first segment might be a page number
		$offs = 4;
		if( $this->uri->segment(4) && is_numeric($this->uri->segment(4))) {
			$page = $this->uri->segment(4);
			$offs = 5;
			if( $page < 1 ) {
				$page = 1;
			}
		}
		// the rest of the segments are search tags
		for( $i = $offs; $i <= $this->uri->total_segments(); $i++ ) {
			$stags[] = $this->uri->segment($i);
		}
		
		$results = $this->media_model->get_media( null, $stags, $page, $page_size );
		
		// pagination
		$next_page = '';
		$prev_page = '';
		if( $page > 1 ) {
			$prev_page = "<a class='small' href='/admin/media/mce/".($page-1)."'>⇐ prev</a>";
		}
		if( count($results) == $page_size ) {
			$next_page = "<a class='small' href='/admin/media/mce/".($page+1)."'>next ⇒</a>";
		}
		
		$view_data = array(
			'items' => $results,
			'page' => $page,
			'stags' => $stags,
			'errors' => $errors,
			'prev_page' => $prev_page,
			'next_page' => $next_page
			);
		$this->load->view('admin/media/media_mce', $view_data );						
	}


	/**
	 * Add media
	 */
	function add()
	{
		if( $this->input->post('path') && $this->input->post('uuid')) {
			$path = $this->input->post('path');
			$slot = $this->input->post('slot');
			$uuid = $this->input->post('uuid');
			$this->media_model->add_media_for_path( $path, $uuid, $slot );
		}
	}

	/**
	 * Remove a link
	 */
	function rmlink()
	{
		$uuid = $this->uri->segment(4);
		$slot = $this->uri->segment(5);
		$path = '/' . implode( '/', array_slice($this->uri->segment_array(), 5));
		
		$this->db->where('uuid', $uuid);
		$item = $this->db->get('media')->row();
		
		$this->db->where( 'media_id', $item->id );
		$this->db->where( 'slot', $slot );
		$this->db->where( 'path', $path );
		$this->db->delete( 'media_map' );
		
		// now this is lame
		$tmp = explode('/', $path );
		redirect('/admin/' . $tmp[1] . '/edit/' . $tmp[2] . '/media/' . $slot );
	}


	/**
	 * Change the sort order
	 */
	function move()
	{
		$dir = $this->uri->segment(4);
		$uuid = $this->uri->segment(5);
		$slot = $this->uri->segment(6);
		$path = '/' . implode( '/', array_slice($this->uri->segment_array(), 6));
		
		$this->db->where('uuid', $uuid);
		$item = $this->db->get('media')->row();
				
		$this->media_model->move( $dir, $path, $slot, $uuid );
		
		// lame, the sequel
		$tmp = explode('/', $path );
		redirect('/admin/' . $tmp[1] . '/edit/' . $tmp[2] . '/media/' . $slot );
	}


	/**
	 * Remove a file
	 */
	private function rm( $path, $uuid )
	{
		$thepath = $path . '/' . $fname;
		if(file_exists($thepath) && !is_dir($thepath)) {
			unlink( $thepath );
			$this->media_model->remove_upload( $fname, $section );
		}
	}
	
	/**
	 * Handle an upload 
	 */
	private function up( $path, $conf )
	{
		$uuid = gen_uuid();
		$conf['file_name'] = $uuid;
		$conf['max_size'] = $this->config->item('max_image_size');
		$conf['max_width'] = $this->config->item('max_image_width');
		$conf['max_height'] = $this->config->item('max_image_height');
		$this->load->library('upload', $conf );
		$this->upload->initialize($conf);
		
		if( !$this->upload->do_upload('userfile')) {
			return NULL;
		} else {
			$data = $this->upload->data();
			rename( $data['full_path'], $data['file_path'] . '/' . $uuid );
			$this->media_model->add_upload($uuid, $data, $this->session->userdata('logged_user'));
			return $uuid;
		}
	}
	
	public function tt_isbn_check( $str ) 
	{
		// tt number
		if( preg_match('/tt[0-9]{7}/', $str )) {
			return TRUE;
		}
		
		// isbn
		$str = trim(str_replace('-','',$str));
		if(strlen($str) == 13) && preg_match('/\d+/', $str) ) {
			return TRUE;
		} 
		
		$this->form_validation->set_message('tt_isbn_check','The %s field must be either tt# or an isbn');
		return FALSE;
	}
	
}