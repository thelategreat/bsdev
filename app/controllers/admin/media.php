<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

/*
vimeo
<object width="400" height="225"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id=7874398&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1" /><embed src="http://vimeo.com/moogaloop.swf?clip_id=7874398&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="400" height="225"></embed></object><p><a href="http://vimeo.com/7874398">Drive (2009)</a> from <a href="http://vimeo.com/mikecelona">Mike Celona</a> on <a href="http://vimeo.com">Vimeo</a>.</p>
youtube
<object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/ta7q15OZg4c&hl=en_US&fs=1&rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/ta7q15OZg4c&hl=en_US&fs=1&rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed></object>
*/

class Media extends Controller 
{

	function Media()
	{
		parent::Controller();
		$this->auth->restrict_role(array('admin'));
		$this->load->helper('media');
		$this->load->library('tabs');
		$this->load->library('form_validation');
		$this->load->model('media_model');
		
		//$this->output->enable_profiler(TRUE);		
	}

	function index()
	{
		if( $this->input->post("q")) {
			$url = '/admin/media/index';
			$params = explode(' ', $this->input->post("q"));
			foreach( $params as $p ) {
				$url .= '/' . $p;
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
		
		
		if( $this->input->post("upload")) {
			$conf['allowed_types'] = 'jpg|png';
			$conf['upload_path'] = $my_root;
			$uuid = $this->up($my_root, $conf );
			if( $this->input->post('next')) {
				$slot = $this->input->post('slot');
				if( empty($slot))
					$slot = 'general';
				$path = $this->input->post('path');
				$this->media_model->add_media_for_path( $path, $uuid, $slot );
				redirect( $this->input->post('next') );
			}
			redirect('/admin/media/edit/' . $uuid );
		}
		if( $this->input->post("link")) {
			$uuid = gen_uuid();
			$this->media_model->add_link( $uuid, $this->input->post('url'), $this->session->userdata('logged_user'));
			if( $this->input->post('next')) {
				$slot = $this->input->post('slot');
				if( empty($slot))
					$slot = 'general';
				$path = $this->input->post('path');
				$this->media_model->add_media_for_path( $path, $uuid, $slot );
				redirect( $this->input->post('next') );
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

	function edit()
	{
		$errors = '';
		$this->load->library('form_validation');
		$uuid = $this->uri->segment(4);
		
		$this->form_validation->set_rules('title','Title','required');
		
		if( $this->input->post('save') && $this->form_validation->run()) {
			$meta['title'] = $this->input->post('title');
			$meta['caption'] = $this->input->post('caption');
			$meta['description'] = $this->input->post('description');
			$meta['license'] = $this->input->post('license');
			$this->media_model->update_media( $uuid, $meta, $this->input->post('tags') );
			redirect('/admin/media');
		}
		if( $this->input->post('delete')) {
			$this->media_model->remove_upload( $uuid );
			if( file_exists( './media/' . $uuid )) {
				unlink( './media/' . $uuid );
			}
			redirect('/admin/media');
		}
		if( $this->input->post('cancel')) {
			redirect('/admin/media');
		}
		$item = $this->media_model->get_media( $uuid );
				
		$data = array(
			'item' => $item[0],
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

	function browser()
	{
		$is_ajax = true;
		$path = $this->input->post('path');
		$slot = $this->input->post('slot') ?  $this->input->post('slot') : 'general' ;
		
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

	function add()
	{
		if( $this->input->post('path') && $this->input->post('uuid')) {
			$path = $this->input->post('path');
			$slot = $this->input->post('slot');
			$uuid = $this->input->post('uuid');
			$this->media_model->add_media_for_path( $path, $uuid, $slot );
		}
	}

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
		redirect('/admin/' . $tmp[1] . '/edit/' . $tmp[2] . '/media' );
	}


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
		redirect('/admin/' . $tmp[1] . '/edit/' . $tmp[2] . '/media' );
	}


	private function rm( $path, $uuid )
	{
		$thepath = $path . '/' . $fname;
		if(file_exists($thepath) && !is_dir($thepath)) {
			unlink( $thepath );
			$this->media_model->remove_upload( $fname, $section );
		}
	}
	
	private function up( $path, $conf )
	{
		$uuid = gen_uuid();
		$conf['file_name'] = $uuid;
		$this->load->library('upload', $conf );
		$this->upload->initialize($conf);
		
		if( !$this->upload->do_upload('userfile')) {
			return $uuid;
		} else {
			$data = $this->upload->data();
			rename( $data['full_path'], $data['file_path'] . '/' . $uuid );
			$this->media_model->add_upload($uuid, $data, $this->session->userdata('logged_user'));
			return $uuid;
		}
	}

}