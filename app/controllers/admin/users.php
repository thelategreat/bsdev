<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Users extends Admin_Controller 
{

	function Users()
	{
		parent::__construct();
		$this->load->model('users_model');
	}
	
	/**
	 * Main
	 *
	 * @return void
	 */
	function index()
	{
		$query = 'search...';
		$filter = array();
		
		if($this->input->post('q')) {
			$query = $this->input->post('q');
			$filter = explode(' ', $query);
		}
		
		$data = $this->users_model->get_users($filter)->result();
		
		$pg_data = array(
			'users' => $data,
			'query' => $query
			);

		$this->gen_page('Admin - Users', 'admin/users/users_list', $pg_data);		
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
				$this->db->set('firstname', $this->input->post('firstname'));
				$this->db->set('lastname', $this->input->post('lastname'));
				$this->db->set('passwd', $this->auth->hash_password($this->input->post('passwd')));
				$this->db->set('role_id', $this->input->post('role_id'));
				$this->db->set('email', $this->input->post('email'));
				$this->db->set('created_on', 'NOW()', false);
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
		
		$this->gen_page('Admin - Users', 'admin/users/users_add', $content );
	}
	
	/**
	 * Edit a user
	 *
	 * @return void
	 */
	function edit()
	{

		$user_id = (int)$this->uri->segment(4);
		if( !$user_id ) {
			redirect('/admin/users');							
		}
		
		
		if( $this->input->post("save")) {
			$this->form_validation->set_error_delimiters('<span class="form_error">','</span>');
			$this->form_validation->set_rules('username','Username','trim|required|min_length[3]');
			$this->form_validation->set_rules('passwd','Password','trim|min_length[6]|matches[vpasswd]');
			$this->form_validation->set_rules('vpasswd','Verify Password','trim');
			$this->form_validation->set_rules('email','Email','trim|valid_email');
			if( $this->form_validation->run()) {
				$this->db->where('id', $user_id);
				if( $this->input->post('passwd') != '') {
					$this->db->set('passwd', $this->auth->hash_password($this->input->post('passwd')));
				}
				if( $this->input->post('active')) {
					$this->db->set('active', 1);
				} else {
					$this->db->set('active', 0);
				}
				$this->db->set('username', $this->input->post('username'));
				$this->db->set('firstname', $this->input->post('firstname'));
				$this->db->set('lastname', $this->input->post('lastname'));
				$this->db->set('role_id', $this->input->post('role_id'));
				$this->db->set('email', $this->input->post('email'));
				$this->db->update('users' );
				redirect('/admin/users');				
			}
		}
		// Delete
		if( $this->input->post("rm") && $user_id > 1 ) {
			$this->db->where('id', $user_id );
			$this->db->delete('users');
			redirect('/admin/users');				
		}
		// cancelled edit
		if( $this->input->post("cancel")) {
			redirect("/admin/users");			
		}

		
		$this->db->where( 'id', $user_id );
		$user = $this->db->get('users')->row();
				
		if( !$user ) {
			redirect('/admin/users');							
		}		
				
		$content = array(
			'user' => $user, 
			'role_select' => $this->users_model->role_select($user->role_id)
			);
		
		$this->gen_page('Admin - Users', 'admin/users/users_edit', $content );		
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

