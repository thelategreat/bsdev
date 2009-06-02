<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Media extends Controller 
{

	function Media()
	{
		parent::Controller();
		$this->auth->restrict_role(array('admin'));
		$this->load->library('tabs');
		$this->load->library('form_validation');
		$this->load->model('media_model');
		
		$this->media_tabs = array('Front Page','Top Feature', 'Left Feature', 'Mid Feature', 'Right Feature');
		
	}

	function index()
	{
		redirect('/admin/media/front_page');
	}
	
	
	/**
	 * main feature on front page
	 *
	 * @return void
	 */
	function front_page()
	{
		// upload configuration
		$conf['allowed_types'] = 'jpg|png';
		//$conf['overwrite'] = FALSE;
		// kb
		//$conf['max_size']	= '0'; 
		//$conf['max_width']  = '596';
		//$conf['max_height']  = '296';
		//$conf['max_filename'] = 0;
		//$conf['encrypt_name'] = FALSE;
		//$conf['remove_spaces'] = TRUE;
		
		$this->gen_page('Front Page', $conf );		
	}


	/**
	 * events stuff
	 *
	 * @return void
	 */
	function top_feature()
	{
		// upload configuration
		$conf['allowed_types'] = 'jpg|png';		
		$this->gen_page('Top Feature', $conf );		
	}

	/**
	 * events stuff
	 *
	 * @return void
	 */
	function left_feature()
	{
		// upload configuration
		$conf['allowed_types'] = 'jpg|png';
		$this->gen_page('Left Feature', $conf);		
	}

	/**
	 * events stuff
	 *
	 * @return void
	 */
	function right_feature()
	{
		// upload configuration
		$conf['allowed_types'] = 'jpg|png';
		$this->gen_page('Right Feature', $conf);		
	}

	/**
	 * events stuff
	 *
	 * @return void
	 */
	function mid_feature()
	{
		// upload configuration
		$conf['allowed_types'] = 'jpg|png';
		$this->gen_page('Mid Feature', $conf);		
	}


	/**
	 * events stuff
	 *
	 * @return void
	 */
	function library()
	{
		// upload configuration
		$conf['allowed_types'] = 'jpg|png';
		$this->gen_page('Library', $conf);		
	}


	function browser()
	{
		$view_data = array(
			'media_path' => '',
			'errors' => '',
			'files' => array(),);
		
		$this->load->view('admin/media/media_browser', $view_data );						
	}

	function edit( $fname )
	{
		$view_data = array(
			'fname' => $fname 
			);
		
		$pg_data = array(
			'title' => 'Admin - Media',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view("admin/media/media_edit", $view_data, true ),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );						
	}


	/* -----------------
	 * P R I V A T E
	 * ----------------- */
	
	private function gen_page( $section, $conf )
	{
		$lc_section = strtolower(str_replace(' ', '_', $section));
		$my_root = './pubmedia/' . $lc_section;
		$errors = '';
				
		if( $this->input->post("upload")) {
			$conf['upload_path'] = $my_root;
			$errors = $this->up($my_root, $conf, $lc_section);
		}
		
		// check for an operation
		$segs = $this->uri->uri_to_assoc(4);
		if( isset($segs['rm'])) {
			$this->rm( $my_root, $segs['rm'], $lc_section );
			redirect('/admin/media/' . $lc_section );
		}
		else if( isset($segs['edit'])) {
			$this->edit( '/pubmedia/' . $this->uri->segment(3) . "/" . $segs['edit'] );
			return;
		}
		
		// generate the tabs
		if( $section == 'Library') {
			$tabbar = '';
		} else {
			$tabbar = $this->tabs->gen_tabs($this->media_tabs, $section, '/admin/media');
		}
		// load file manager
		$this->load->library('fm', array('root' => $my_root ));
		
		$file_list = $this->fm->file_list();
		$db_files = $this->media_model->files_for_section($lc_section);
		foreach( $db_files->result() as $row ) {
			for( $i = 0; $i < count($file_list); $i++) {
				if( $row->filepath == $file_list[$i]['fname']) {
					$file_list[$i]['author'] = $row->user;
					$file_list[$i]['attached_to'] = '-';
				}
			}
		}
		
		// set data for the fm view
		$file_data = array( 'files' => $file_list, 
												'media_path' => $my_root, 
												'errors' => $errors
												 );
		// load fm view
		$file_view = $this->load->view('admin/media/media_list', $file_data, true );
		// set data for the main view
		$view_data = array(	'tabs' => $tabbar, 
												'section' => $section,
												'content' => $file_view
												);
																								
		// load the page data
		$pg_data = array(
			'title' => 'Admin - Media',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view("admin/media/media", $view_data, true ),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );				
	}
	
	
	private function rm( $path, $fname, $section )
	{
		$thepath = $path . '/' . $fname;
		if(file_exists($thepath) && !is_dir($thepath)) {
			unlink( $thepath );
			$this->media_model->remove_upload( $fname, $section );
		}
	}
	
	private function up( $path, $conf, $section )
	{
		$this->load->library('upload', $conf);
		
		if( !$this->upload->do_upload('userfile')) {
			return $this->upload->display_errors('<p class="error">', '</p>');
		} else {
			$data = $this->upload->data();
			$this->media_model->add_upload($data['file_name'], $section, $this->session->userdata('logged_user'));
			return '';
		}
	}
	
}