<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Bugs extends Admin_Controller 
{

	function __construct()
	{
		parent::__construct();

		//
		$this->page_title = 'Admin - Issues';
		$this->page_root = '/admin/bugs';

		$this->load->model('users_model');
		$this->load->model('bugs_model');
		
    $this->page_tabs = array(
			'Details', 'Media'
			);

	}
	
	function index()
	{
		$this->bugs_model->init_tables();
		
		$page_size = $this->config->item('list_page_size');
		$page = 1;

		if( $this->uri->segment(4) && is_numeric($this->uri->segment(4))) {
			$page = $this->uri->segment(4);
			if( $page < 1 ) {
				$page = 1;
			}
		}
		
		$query = 'search...';
		$filter = array();

		$bugs = $this->bugs_model->get_bugs( $filter, $page, $page_size );

		// pagination
		$next_page = '';
		$prev_page = '';
		if( $page > 1 ) {
			$prev_page = "<a class='small' href='/admin/bugs/index/".($page-1)."'>⇐ prev</a>";
		}
		if( $bugs->num_rows() == $page_size ) {
			$next_page = "<a class='small' href='/admin/bugs/index/".($page+1)."'>next ⇒</a>";
		}

	
		$pg_data = array(
			'next_page' => $next_page,
			'prev_page' => $prev_page,
			'query' => $query,
			'bugs' => $bugs
			);
	
		$this->gen_page($this->page_title, 'admin/bugs/bug_list', $pg_data );
	}

	function add()
	{
		if( $this->input->post("save")) {
			$this->form_validation->set_error_delimiters('<span class="form_error">','</span>');
			$this->form_validation->set_rules('summary','Summary','trim|required');
			$this->form_validation->set_rules('description','Description','trim|required');
			if( $this->form_validation->run()) {
				$this->db->set('summary', $this->input->post('summary'));
				$this->db->set('description', $this->input->post('description'));
				$this->db->set('type', $this->input->post('type'));
				
				$this->db->set('submitted_by', $this->session->userdata('logged_user'));
				$this->db->set('created_on', "NOW()", false);
				$this->db->set('status', 'new');
				$this->db->set('assigned_to', 'nobody');
				$this->db->insert("bugs");
				redirect($this->page_root);
			}
		}	
			
		if( $this->input->post("cancel")) {
			redirect($this->page_root);
		}
		
		$view_data = array(
			);
		
		$this->gen_page($this->page_title, 'admin/bugs/bug_add', $view_data );		
	}

	function edit()
	{
		$bug_id = $this->uri->segment(4);

		if( !$bug_id ) {
			redirect($this->page_root);			
		}

		// ------------
		// U P D A T E
		if( $this->input->post("save")) {
			$this->form_validation->set_error_delimiters('<span class="form_error">','</span>');
			$this->form_validation->set_rules('summary','Summary','trim|required');
			$this->form_validation->set_rules('description','Description','trim|required');
			if( $this->form_validation->run()) {
				$this->db->where('id', $bug_id);
				$this->db->set('summary', $this->input->post('summary'));
				$this->db->set('description', $this->input->post('description'));
				$this->db->set('type', $this->input->post('type'));
				$this->db->set('status', $this->input->post('status'));
				$this->db->set('assigned_to', $this->input->post('assigned_to'));
				$this->db->update("bugs");
				redirect($this->page_root);
			}
		}	

		// ------------
		// D E L E T E
		if( $this->input->post("rm")) {
			$this->db->where('id', $bug_id);
			$this->db->delete('bugs');
			redirect($this->page_root);
		}
			
		// -----------
		// C A N C E L
		if( $this->input->post("cancel")) {
			redirect($this->page_root);
		}

		
		$this->db->where( 'id', $bug_id );
		$bug = $this->db->get('bugs')->row();
		if( !$bug ) {
			redirect($this->page_root);			
		}		
				
				
		$view_data = array( 
			'bug' => $bug,
			'assigned_to_select' => $this->get_assign_to_select( $bug->assigned_to )
		);
		
		$this->gen_page($this->page_title, 'admin/bugs/bug_edit', $view_data );
		
	}

	private function get_assign_to_select( $who = '' )
	{
		// grab a list of admin users
		$users = $this->users_model->get_users( NULL, 'admin' );
		
		$sel = '<select name="assigned_to">';
		$sel .= '<option value="nobody" '.($who == 'nobody' ? "selected" : '').'>nobody</option>';
		foreach( $users->result() as $user ) {
			$sel .= '<option value="' . $user->username.'" '. ($who == $user->username ? "selected" : '') . '>' . $user->username . '</option>';
		}
		$sel .= '</select>';
		return $sel;
	}

}