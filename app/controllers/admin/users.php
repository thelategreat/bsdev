<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Users extends Controller 
{

	function Users()
	{
		parent::Controller();
		$this->auth->restrict_role(array('admin'));
		$this->load->helper('url','form');
		$this->load->library('form_validation');
		$this->load->model('users_model');
	}
	
	/**
	 * Main
	 *
	 * @return void
	 */
	function index()
	{
		$data = $this->users_model->get_users('users')->result();
		
		$pg_data = array(
			'title' => 'Admin - Users',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/users/users_list', array('users' => $data), true ),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
	}
	
	/**
	 * Add a new user
	 *
	 * @return void
	 */
	function add()
	{		
		if( $this->input->post("save")) {
			$this->form_validation->set_error_delimiters('<span class="form_error">','</span>');
			$this->form_validation->set_rules('username','Username','trim|required|min_length[3]|callback_username_check');
			$this->form_validation->set_rules('passwd','Password','trim|required|min_length[6]|matches[vpasswd]');
			$this->form_validation->set_rules('vpasswd','Verify Password','trim');
			$this->form_validation->set_rules('email','Email','trim|valid_email');
			if( $this->form_validation->run()) {
				$this->db->set('username', $this->input->post('username'));
				$this->db->set('passwd', "PASSWORD(".$this->db->escape($this->input->post('passwd')).")", false);
				$this->db->set('role_id', $this->input->post('role_id'));
				$this->db->set('email', $this->input->post('email'));
				$this->db->insert("users");
				redirect("/admin/users");
			}
		}
		if( $this->input->post("cancel")) {
			redirect("/admin/users");			
		}
		
		$content = array(
			'role_select' => $this->users_model->role_select()
			);
		
		$pg_data = array(
			'title' => 'Admin - Users',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/users/users_add', $content, true ),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
		
	}
	
	/**
	 * Edit a user
	 *
	 * @return void
	 */
	function edit()
	{

		$user_id = $this->uri->segment(4);
		
		if( $this->input->post("save")) {
			$this->form_validation->set_error_delimiters('<span class="form_error">','</span>');
			$this->form_validation->set_rules('username','Username','trim|required|min_length[3]');
			$this->form_validation->set_rules('passwd','Password','trim|min_length[6]|matches[vpasswd]');
			$this->form_validation->set_rules('vpasswd','Verify Password','trim');
			$this->form_validation->set_rules('email','Email','trim|valid_email');
			if( $this->form_validation->run()) {
				$this->db->where('id', $user_id);
				if( $this->input->post('passwd') === '') {
				} else {
					if( $this->input->post('passwd') == $this->input->post('vpasswd')) {
						$this->db->set('passwd', "PASSWORD(".$this->db->escape($this->input->post('passwd')).")", false);				
					} else {
						$error_msg .= '<br/>passwords do not match';
					}
				}
				if( $this->input->post('active')) {
					$this->db->set('active', 1);
				} else {
					$this->db->set('active', 0);
				}
				$this->db->set('username', $this->input->post('username'));
				$this->db->set('role_id', $this->input->post('role_id'));
				$this->db->set('email', $this->input->post('email'));
				$this->db->update('users' );
				redirect('/admin/users');				
			}
		}
		
		// cancelled edit
		if( $this->input->post("cancel")) {
			redirect("/admin/users");			
		}

		
		$this->db->where( 'id', $user_id );
		$user = $this->db->get('users')->row();
				
		$content = array(
			'user' => $user, 
			'role_select' => $this->users_model->role_select($user->role_id)
			);
		
		$pg_data = array(
			'title' => 'Admin - Users',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/users/users_edit', $content, true ),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
		
	}
	
	/**
	 * Verify that the user does not already exist
	 *
	 * @param string $user the username to check
	 * @return false if the user exists
	 */
	function username_check( $user )
	{
		$this->db->where('username',$user);
		$result = $this->db->get('users');
		if( $result->num_rows() > 0 ) {
			$this->form_validation->set_message('username_check', 'This username is already in use');
			return false;
		}
		return true;
	}
	
}

