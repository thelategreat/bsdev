<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Venues extends Controller {

	function Venues()
	{
		parent::Controller();
		$this->auth->restrict_role('admin');
		$this->load->helper('url');
		$this->load->library('tabs');

      $this->page_tabs = array(
        'Details', 'Media'
        );

	}
	
	function index()
	{
		$venues = $this->db->get("venues")->result();
		
		$content = array(
			'venues' => $venues 
			);
		
		$pg_data = array(
			'title' => 'Admin - Venues',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/venues/venue_list', $content, true ),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
	}


	function add()
	{
		$error_msg = "";
		
		if( $this->input->post("save")) {
			if( trim($this->input->post("name")) != "" ) {
				unset($_POST["save"]);
				$this->db->insert('venues', $_POST );
				redirect("/admin/venues");
			} else {
				$error_msg = '<p class="error">You must know the name, at least. Sheesh!</p>';
			}
		}
		else if( $this->input->post("cancel")) {
			redirect("/admin/venues");			
		}


		$venues = $this->db->get("venues")->result();
		
		$content = array(
			'venues' => $venues,
			'error_msg' => $error_msg,
			);
		
		$pg_data = array(
			'title' => 'Admin - Venues',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/venues/venue_add', $content, true ),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
	}


	function edit()
	{
		$venue_id = $this->uri->segment(4);
		
		if( $this->input->post("save")) {
			$this->db->where('id', $this->input->post('id'));
			unset($_POST["save"]);
			$this->db->update('venues', $_POST );
			redirect("/admin/venues");
		}
		else if( $this->input->post("cancel")) {
			redirect("/admin/venues");			
		}
		

		$cur_tab = 'details';
		if( $this->uri->segment(5)) {
			$cur_tab = strtolower($this->uri->segment(5));
		}

		$this->db->where( 'id', $venue_id );
		$venue = $this->db->get('venues')->row();

		$content = array(
			'venue' => $venue,
			'tabs' => $this->tabs->gen_tabs($this->page_tabs, $cur_tab, '/admin/venues/edit/' . $venue->id),
			);

		switch( $cur_tab ) {
			case 'media':
			$page = $this->load->view('admin/venues/venue_media', $content, true );
			break;
			default:
			$page = $this->load->view('admin/venues/venue_edit', $content, true );
		}
		
		
		$pg_data = array(
			'title' => 'Admin - Venues',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $page,
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
	}
	
}

