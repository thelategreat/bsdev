<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Venues extends Admin_Controller 
{

	function __construct()
	{
		parent::__construct();

    // page title
    $this->root_title = 'Venues';
    // template names
    $this->list_template = 'venue_list';
    $this->add_template = 'venue_add';
    $this->edit_template = 'venue_edit';
    // path for media table
    $this->media_path = '/venues';
    // path for this controller
    $this->root_path = '/admin/venues';
    // db table name
    $this->table_name = 'venues';

    $this->page_tabs = array(
			'Details', 'Media'
			);

	}
	
	function index()
	{
		$data = $this->db->get($this->table_name)->result();
		
		$pg_data = array(
			'data' => $data 
			);
		
		$this->gen_page('Admin - ' . $this->root_title, $this->root_path . '/' . $this->list_template, $pg_data );
	}


	function add()
	{
		$error_msg = "";
		
		if( $this->input->post("save")) {
			if( trim($this->input->post("venue")) != "" ) {
				unset($_POST["save"]);
				$this->db->insert( $this->table_name, $_POST );
				redirect( $this->root_path );
			} else {
				$error_msg = '<p class="error">You must know the venue name, at least. Sheesh!</p>';
			}
		}
		else if( $this->input->post("cancel")) {
			redirect( $this->root_path );			
		}


		$data = $this->db->get( $this->table_name )->result();
		
		$pg_data = array(
			'data' => $data,
			'error_msg' => $error_msg,
			);
		
		
		$this->gen_page('Admin - ' . $this->root_title, $this->root_path . '/' . $this->add_template, $pg_data );
	}


	function edit()
	{
		$venue_id = (int)$this->uri->segment(4);
		if( !$venue_id ) {
			redirect( $this->root_path );						
		}
				
		if( $this->input->post("save")) {
			$this->db->where('id', $this->input->post('id'));
			unset($_POST["save"]);
			$this->db->update( $this->table_name, $_POST );
			redirect( $this->root_path );
		}
		else if( $this->input->post("cancel")) {
			redirect( $this->root_path );			
		}
		

		$cur_tab = 'details';
		if( $this->uri->segment(5)) {
			$cur_tab = strtolower($this->uri->segment(5));
		}

		$this->db->where( 'id', $venue_id );
		$data = $this->db->get( $this->table_name )->row();

		if( !$data ) {
			redirect( $this->root_path );						
		}

		$content = array(
			'data' => $data,
			'tabs' => $this->tabs->gen_tabs($this->page_tabs, $cur_tab, $this->root_path . '/edit/' . $data->id),
			);

		switch( $cur_tab ) {
			case 'media':
			$content['title'] = "Media for location: $venue->name";
			$content['path'] = $this->media_path . '/' . $venue->id;
			$content['next'] =  $this->root_path . "/edit/$venue->id/media";
			$page = $this->load->view('admin/media/media_tab', $content, true );
			break;
			default:
			$page = $this->load->view( $this->root_path . '/' . $this->edit_template, $content, true );
		}
		
		$this->gen_page('Admin - ' . $this->root_title, $page );
	}
	
}

