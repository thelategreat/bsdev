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
		
	}

	function index()
	{
		$this->issues();
	}
	
	function issues()
	{		
		$page_size = 15; //$this->config->item('list_page_size');
		$page = 1;

		if( $this->uri->segment(4) && is_numeric($this->uri->segment(4))) {
			$page = $this->uri->segment(4);
			if( $page < 1 ) {
				$page = 1;
			}
		}

		if( $this->input->post('q') !== false ) {
			if( strlen(trim($this->input->post('q'))) == 0 ) {
				redirect("/admin/bugs/index/$page");
			}
			redirect("/admin/bugs/index/$page/" . urlencode($this->input->post('q')));
		} 
		
		$query = 'search...';
		$filter = null;
		if( $this->uri->segment(5) ) {
			$query = str_replace( '_', ' ', $this->uri->segment(5));
			$filter = $query;
		}	 
		
		$bugs = $this->bugs_model->get_bugs( $filter, $page, $page_size );
	
		$pg_data = array(
			'pager' => mk_pager( $page, $page_size, $bugs->num_rows(), '/admin/bugs/index'),
			'query' => $query,
			'bugs' => $bugs,
			'tabs' => $this->tabs->gen_tabs(array('Issues','Activity','People','Project'), 'Issues', '/admin/bugs')	
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
				$this->bugs_model->add_bug($this->input->post('summary'),
																		$this->input->post('description'),
																		$this->input->post('type'),
																		$this->session->userdata('logged_user'));
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

		if( $this->input->post('comment-text') && strlen(trim($this->input->post('comment-text'))) > 0 ) {
			/*
			$this->db->set('bug_id', $bug_id );
			$this->db->set('comment', $this->input->post('comment-text') );
			$this->db->set('submitted_by', $this->session->userdata('logged_user'));
			$this->db->set('created_on', "NOW()", false);
			$this->db->insert('bugs_comments');
			*/
			$this->bugs_model->add_comment( $bug_id, 
																			$this->input->post('comment-text'), 
																			$this->session->userdata('logged_user') );
		}
		
		// ------------
		// U P D A T E
		if( $this->input->post("save")) {
			$this->form_validation->set_error_delimiters('<span class="form_error">','</span>');
			$this->form_validation->set_rules('summary','Summary','trim|required');
			$this->form_validation->set_rules('description','Description','trim|required');
			if( $this->form_validation->run()) {
				/*
				$this->db->where('id', $bug_id);
				$this->db->set('summary', $this->input->post('summary'));
				$this->db->set('description', $this->input->post('description'));
				$this->db->set('type', $this->input->post('type'));
				$this->db->set('status', $this->input->post('status'));
				$this->db->set('assigned_to', $this->input->post('assigned_to'));
				$this->db->update("bugs");
				*/
				$this->bugs_model->update_bug( $bug_id, 
																				$this->input->post('summary'),
																				$this->input->post('description'),
																				$this->input->post('type'),
																				$this->input->post('status'),
																				$this->input->post('assigned_to'),
																				$this->session->userdata('logged_user'));
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
			'status_select' => $this->get_status_select( $bug->status ),
			'comments' => $this->bugs_model->get_comments( $bug_id ),
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

	private function get_status_select( $status = '' )
	{
		$res = $this->bugs_model->get_statuses();
		$s = '<select name="status">';
		foreach( $res->result() as $row ) {
			$s .= '<option value="' . $row->status . '" ';
			if( $row->status == $status ) {
				$s .= ' selected="selected" ';
			}
			$s .= '>' . $row->status . '</option>';
		}
		return $s . '</select>';
	}

	// A C T I V I T Y
	function activity()
	{
		$page = 1;
		$page_size = 25;
		
		$view_data = array( 
			'activity' => $this->bugs_model->get_activity( $page, $page_size ),
			'tabs' => $this->tabs->gen_tabs(array('Issues','Activity','People','Project'), 'Activity', '/admin/bugs')	
			);
		$this->gen_page($this->page_title, 'admin/bugs/bug_activity', $view_data );		
	}

	// P E O P L E
	function people()
	{
		$view_data = array( 
			'tabs' => $this->tabs->gen_tabs(array('Issues','Activity','People','Project'), 'People', '/admin/bugs')	
			);
		$this->gen_page($this->page_title, 'admin/bugs/bug_people', $view_data );		
	}

	// P R O J E C T
	function project()
	{
		$view_data = array( 
			'tabs' => $this->tabs->gen_tabs(array('Issues','Activity','People','Project'), 'Project', '/admin/bugs')	
			);
		$this->gen_page($this->page_title, 'admin/bugs/bug_project', $view_data );		
	}

}