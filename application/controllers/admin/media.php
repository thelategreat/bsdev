<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

include("admin_controller.php");


/**
 *
 */
class Media extends Admin_Controller
{

	/**
	 *
	 */
	function Media()
	{
		parent::__construct();
		$this->load->helper('media');
		$this->load->model('media_model');
		$this->load->library('form_validation');
	}

	/**
	 * This is both the index and the form processor.  Based on the presence of various post fields.
	 */
	function index()
	{
		$errors = '';
		$my_root = './media/';
		$page_size = 25; //$this->config->item('image_browser_page_size');
		$stags = array();
		$page = 1;

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
			$conf['allowed_types'] = 'jpg|jpeg|png';
			$conf['upload_path'] = $my_root;
			$uuid = $this->up($my_root, $conf, null, $this->input->post('title') );
			
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
				redirect('/admin/media/edit/' . $uuid . '/add' );
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
			redirect('/admin/media/edit/' . $uuid . '/add' );
		}

		$results = $this->media_model->get_media( null, $stags, $page, $page_size );

		$data = array(
			'items' => $results,
			'page' => $page,
			'stags' => $stags,
			'errors' => $errors
			);

		$this->gen_page('Admin - Media', 'admin/media/media_index', $data );
	}

	function gallery( $page = 1, $page_size = 15 )
	{
		//$page_size = 25; //$this->config->item('image_browser_page_size');
		$stags = array();
		$errors = '';

		if( $this->input->post('q') && strlen(trim($this->input->post('q')))) {
			$stags = explode(' ', $this->input->post('q'));
			//log_message( 'error', 'query: ' . $this->input->post('q'));
		}

		$results = $this->media_model->get_media( null, $stags, $page, $page_size );

		$data = array(
			'items' => $results,
			'page' => $page,
			'stags' => $stags,
			'errors' => $errors
			);

		echo json_encode( $data );
	}

	function get_media_info( $uuid )
	{
		$resp = array('error' => true, 'error_msg' => 'Unknown', 'value' => '' );
		if( $uuid ) {
			$item = $this->media_model->get_media( $uuid );
			if( $item ) {
				$resp['value'] = array();
				$resp['value']['uuid'] = $uuid;
				$resp['value']['type'] = $item[0]->type;
				$resp['value']['title'] = $item[0]->title;
				$resp['value']['caption'] = $item[0]->caption;

				$resp['error'] = false;
				$resp['error_msg'] = ' ';
			}
		}
		echo json_encode( $resp );
	}


	/**
	 * This processes edits, based on post variables it may or may not take an upload.
	 */
	function edit()
	{
		$errors = '';
		$is_adding = false;
		$redirect = $this->session->userdata('sourcepage');
		if ($redirect) {
			$redirect = base_url('/admin/media');
		} 

		$uuid = $this->uri->segment(4);
		if( !$uuid ) {
			redirect('/admin/media/' );
		}
		if( $this->uri->segment(5) == 'add') {
			$is_adding = true;
		}

		$page = NULL;
		// the page number, maybe
		if( $this->uri->segment(6) && is_numeric($this->uri->segment(6))) {
			$page = $this->uri->segment(6);
			if( $page < 1 ) {
				$page = 1;
			}
		}

		//$this->form_validation->set_rules('title','Title','required');
		$this->form_validation->set_rules('caption','Caption','required');
		$this->form_validation->set_rules('tt_isbn','tt#/isbn','callback_tt_isbn_check');

		// -----------
		// R E P L A C E (upload)
		// -----------
		if( isset($_FILES['userfile']) && $_FILES['userfile']['size'] > 0) {
			$my_root = './media/';
			$conf['allowed_types'] = 'jpg|png';
			$conf['upload_path'] = $my_root;
			$uuid = $this->up($my_root, $conf, $uuid );
			if( !$uuid ) {
				// TODO: give some feedback here
			} else {
				$redirect = $this->session->userdata('sourcepage');
				redirect($redirect);	
			}
		}

		// -----------
		// S A V E
		// -----------
		if( $this->input->post('save') && $this->form_validation->run()) {
			$meta['title'] = $this->input->post('title');
			$meta['caption'] = $this->input->post('caption');
			$meta['tt_isbn'] = trim(str_replace('-','',$this->input->post('tt_isbn')));
			$meta['description'] = $this->input->post('description');
			$meta['license'] = $this->input->post('license');
			$this->media_model->update_media( $uuid, $meta, explode(',', $this->input->post('tags')) );
			redirect(current_url());
		}

		// -----------
		// C A N C E L
		// -----------
		if( $this->input->post('cancel')) {
			redirect($redirect);
		}

		$item = $this->media_model->get_media( $uuid );		

		if( !$item ) {
			redirect($redirect);
		}

		$used = $this->media_model->get_media_usage( $uuid );

		$data = array(
			'item' => $item[0],
			'used' => $used,
			'page' => $page,
			'errors' => $errors,
			'is_adding' => $is_adding,
			'redirect' => $redirect
			);

		if( $this->input->post('ajax')) {
			$this->load->view('admin/media/media_edit', $data );
		} else {
			$this->gen_page('Admin - Media', 'admin/media/media_edit', $data );
		}
	}

	function delete($uuid)
	{
		$success = false;
		$message = '';
		$used = $this->media_model->get_media_usage( $uuid );

		if (count($used) > 0) {
			$success = false;
			$message = 'Media is assigned and cannot be deleted';
		} else {
			$this->media_model->remove_upload( $uuid );
			if( file_exists( './media/' . $uuid )) {
				$success = unlink( './media/' . $uuid );
				if ($success == false) {
					$message = 'Cannot delete file';
				}
			}
		}

		$output = array('success'=>$success, 'message'=>$message);
		echo json_encode($output);
		exit;


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
			$redirect = $this->session->userdata('sourcepage');
			if ($redirect) {
				$this->session->unset_userdata('sourcepage');
				redirect($redirect);
			} else {
				redirect('/admin/media');
			}
			redirect('/admin/media');
		}

		// --------------------
		// D E L E T E  R E F S
		// ---------------------
		if( $this->input->post('deleterefs')) {
			$this->media_model->remove_media( $uuid, true );
			if( file_exists( './media/' . $uuid )) {
				unlink( './media/' . $uuid );
			}
			if( $this->input->post('page')) {
				redirect('/admin/media/index/' . $this->input->post('page'));
			}
			redirect('/admin/media');
		}
	}

	/** 
	 * Browser that generates the actual list of images in the media map tab as well as links and 
	 * other stuff.
	 */
	function browser()
	{
		$is_ajax = true;
		$path = $this->input->post('path');
		$slot = $this->input->post('slot');

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
	 * Update a media map entry slot setting.  
	 * This is used from the media browser when changing the slots.
	 */
	function update_media_map( ) {
		$slot = $this->input->post('slot');
		$map_id = $this->input->post('map_id');

		if (!$slot || !$map_id) return false;

		$this->db->set('slot', $slot);
		$this->db->where('id', $map_id);
		$this->db->update('media_map');

		$result = array('status'=>'success');
		echo json_encode($result);
	}

  function mce()
  {
    $stags = array();
    for( $i = 4; $i <= $this->uri->total_segments(); $i++ ) {
      $stags[] = $this->uri->segment($i);
    }

    if( count($stags) > 0  ) {
      $path = '/' . $stags[0] . '/' . $stags[1];
      $results = $this->media_model->get_media_for_path( $path );
      $view_data = array(
        'items' => $results,
        'page' => 1,
        'stags' => $stags,
        'errors' => $path,
        'prev_page' => '',
        'next_page' => ''
        );
      $this->load->view('admin/media/media_mce', $view_data );

    } else {
      echo 'Invalid path';
    }
  }

  /**
   * TinyMCE popup thing
   */
  function mceold()
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
    $page_size = $this->config->item('image_browser_page_size');;
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
	 * Add media to path. Called by the popup when the user selected an image
	 */
	function add()
	{
		$path = $this->input->post('path');
		$slot = $this->input->post('slot') ? $this->input->post('slot') : 'general';
		$uuid = $this->input->post('uuid');

		$resp = array('error' => false, 'error_msg' => "[$path] [$uuid]" );

		if( $path && $uuid ) {
			$this->media_model->add_media_for_path( $path, $uuid, $slot );
		}
		echo json_encode( $resp );
	}

	/**
	 * New media browser add function - takes an array of media ids and adds them to the subject
	 */
	function add_ajax()
	{
		$path = $this->input->post('path');
		$slot = $this->input->post('slot') ? $this->input->post('slot') : 'general';
		$uuid = $this->input->post('uuid');

		if (!$path && !$uuid) {
			$resp = array('success' => false, 'message' => "Missing path or UUID" );
			echo json_encode($resp);
			return;
		}

		$return = $this->media_model->add_media_for_path( $path, $uuid, $slot );

		echo json_encode($return);
	}



	/**
	 * AJAX search function
	 */
	function search_json() {
		$page_size = $this->config->item('image_browser_page_size');;
		$page = 1;
		$stags = array();
		$results = array();
			
		$query = $this->input->post('q');
		if ($query === false) echo json_encode($results);

		$stags = explode(' ', $query);
		$page = $this->input->post("pg");

		$results = $this->media_model->get_media( null, $stags, $page, $page_size );

		echo json_encode($results);
	}
	/**
	 * Called by the popup media browser for selecting
	 */
	function search()
	{
		$errors = '';
		$my_root = './media/';
		$page_size = $this->config->item('image_browser_page_size');;
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
			'errors' => $errors,
			'path' => $this->input->post('path')
			);

		$this->load->view('admin/media/media_search', $data );
	}

	/**
	 * Remove a link. Called by media/browser from various controllers
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
	 * Handle an upload
	 */
	private function up( $path, $conf, $uuid = NULL, $title = null )
	{
		$is_replace = false;
		# give a uuid will replace if exists
		if( $uuid == NULL ) {
			$uuid = gen_uuid();
		} else {
			$is_replace = true;
		}
		$conf['file_name'] 	= $uuid;
		$conf['allowed_types'] = 'jpg|jpeg|png';
		$conf['max_size'] 	= $this->config->item('max_image_size');
		$conf['max_width'] 	= $this->config->item('max_image_width');
		$conf['max_height'] = $this->config->item('max_image_height');

		$this->load->library('upload', $conf );
		$this->upload->initialize($conf);

		if( !$this->upload->do_upload('userfile')) {
			/*new dBug($_POST);
			new dBug($_FILES);
			new dBug($this->upload->data());*/
			echo 'Error in processing upload: ';
			$errors = $this->upload->display_errors();
			new dBug($errors);
			die;
			return null;
		} else {
			$data = $this->upload->data();
			rename( $data['full_path'], $data['file_path'] . '/' . $uuid );
			# if a uuid is given we are simply replacing the disk file
			if( !$is_replace ) {
				$this->media_model->add_upload($uuid, $data, $this->session->userdata('logged_user'), $title );
			}
			return $uuid;
		}
	}

	/**
	 * Change the sort order
	 */
	function move($direction, $uuid, $slot)
	{
		// $dir = $this->uri->segment(4);
		// $uuid = $this->uri->segment(5);
		// $slot = $this->uri->segment(6);
		$path = '/' . implode( '/', array_slice($this->uri->segment_array(), 5));

		$this->db->where('uuid', $uuid);
		$item = $this->db->get('media')->row();

		$this->media_model->move( $direction, $path, $slot, $uuid );

		// lame, the sequel
		$tmp = explode('/', $path );

		redirect('/admin/' . $tmp[1] . '/edit/' . $tmp[2] . '/media/' . $slot );
	}


	// check the input looks like one of, blank, tt number or isbn
	public function tt_isbn_check( $str )
	{
		if( strlen(trim($str)) == 0 ) {
			return TRUE;
		}

		// tt number
		if( preg_match('/tt[0-9]{7}/', $str )) {
			return TRUE;
		}

		// isbn
		$str = trim(str_replace('-','',$str));
		if(strlen($str) == 13 && preg_match('/\d+/', $str) ) {
			return TRUE;
		}

		$this->form_validation->set_message('tt_isbn_check','The %s field must be either tt# or an isbn');
		return FALSE;
	}

}
