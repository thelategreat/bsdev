<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   


class Profile extends MY_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::Controller();
		
		$this->config->load('site_config');
		
		$this->load->library('email');
		
		$this->load->helper('email');
		$this->load->helper('media');
		$this->load->helper('misc');
		
		$this->load->model('maillist_model');
	}


	function index()
	{		
		if( !$this->auth->logged_in()) {
			redirect('/profile/login');
			exit();
		}
		$error = '';
		if( $this->input->post('update')) {			
			// handle user info
			$this->db->set('firstname', $this->input->post('firstname'));
			$this->db->set('lastname', $this->input->post('lastname'));
			$this->db->where('username', $this->auth->username());
			
			if( $this->input->post('password') && $this->input->post('password2')) {
				$p1 = $this->input->post('password');
				$p2 = $this->input->post('password2');
				if( $p1 === $p2 ) {
					$this->db->set('passwd', "PASSWORD(" . $this->db->escape($p1) . ")", false);
				}
				else {
					$error = '<p class="error">Passwords did not match.</p>';
				}
			}
			$this->db->update('users');
			
			// now mail lists
			// ---------------
			// this is fucked up :/
			$list_ids = array();
			for( $i = 0; $i < 10; $i++ ) {
				if( $this->input->post("list_" . $i )) {
					$list_ids[] = $i;
				}
			}
			if( count($list_ids)) {
				$this->maillist_model->update_subscriptions($this->auth->username(), $list_ids );
			}			
		}
		
		$pg_data = $this->get_page_data('Bookshelf - Profile', 'page');
		$data = array('title' => 'Page Not Found', 'body' => '');
		$data['title'] = '';
		$data['body'] = '';
		$data['error'] = $error;
		// get user info
		$data['username'] = $this->auth->username();
		$this->db->where('username',$this->auth->username());
		$res = $this->db->get('users');
		if( $res->num_rows() != 1 ) {
			redirect('/profile/logout');
		}
		$row = $res->row();
		$data['email'] = $row->email;
		$data['firstname'] = $row->firstname;
		$data['lastname'] = $row->lastname;
		$data['created_on'] = $row->created_on;
		$data['last_seen'] = $row->last_login;
		
		// mail list info
		$this->db->where('is_visible', 1);
		$this->db->where('is_enabled', 1);
		$this->db->where('is_open', 1);
		$res = $this->db->get('ml_list');
		
		$subs = $this->maillist_model->get_subscriptions($this->auth->username());
		$lists = array();
		foreach( $res->result() as $row ) {
			$list = array($row->id, $row->name, $row->descrip, false );
			foreach( $subs->result() as $srow ) {
				if( $srow->id == $row->id ) {
					$list[3] = true;
				}
			}
			$lists[] = $list;
		}
		$data['maillists'] = $lists;
		
  	$pg_data['content'] = $this->load->view('profile/profile_view', $data, true);
		$this->load->view('layouts/standard_page', $pg_data );
	}

	function login()
	{
		if( $this->input->post("user") && $this->input->post("password")) {
			$user = (string)$this->input->post("user");
			$passwd = (string)$this->input->post("password");
			if( $this->auth->process_login(array($user, $passwd))) {
				redirect("/profile");
			}
		}
		
		$pg_data = $this->get_page_data('Bookshelf - Login', 'login');
		$data = array('title' => 'Page Not Found', 'body' => '');
		$data['title'] = '';
		$data['body'] = '';
  	$pg_data['content'] = $this->load->view('profile/profile_login', $data, true);
		$this->load->view('layouts/standard_page', $pg_data );			
	}

	function logout()
	{
		$this->auth->logout();
		redirect('/');
	}

	function verified()
	{
		$pg_data = $this->get_page_data('Bookshelf - Welcome', 'login');
		$data = array('title' => 'Page Not Found', 'body' => '');
		$data['title'] = '';
		$data['body'] = '';
  	$pg_data['content'] = $this->load->view('profile/profile_register_verified', $data, true);
		$this->load->view('layouts/standard_page', $pg_data );					
	}

	function register()
	{
		$mail_sent = false;
		$error = '';

		if( $this->uri->segment(3) ) {
			$uuid = 'register_' . $this->uri->segment(3);
			$this->db->where('action_uuid', $uuid );
			$res = $this->db->get('users');
			if( $res->num_rows() == 1 ) {
				$this->db->where('id', $res->row()->id);
				$this->db->set('action_uuid', 'NULL', false );
				$this->db->set('updated_on', 'now()', false );
				$this->db->set('active', 1, false );
				$this->db->update('users');
				redirect('/profile/verified');
			} else {
				//$error = '<p class="error">That registration code is no longer valid.</p>';
			}
		}
		
		$data = array('title' => 'Page Not Found', 'body' => '');
		$ok = true;
		
		
		if( $this->input->post("email") && 
				$this->input->post('cap') && 
				$this->input->post('j') && 
				$this->input->post('b')) {
					
			
			$j = $this->input->post('j');
			$b = $this->input->post('b');
			$cap = $this->input->post('cap');
			$email = (string)$this->input->post("email",TRUE);
			$data['email'] = $email;
			
			$this->db->where('email', $email);
			$res = $this->db->get('users');
			if( $res->num_rows() != 0 ) {
				$error = "<p class='error'>That email is already registered. Did you <a href='/profile/forgot'>forget</a> your password?</p>";						
				$ok = false;
			}
			
			if( valid_email($email) && ($cap == $j + $b) && $cap > 0 && $ok ) {			
				$uuid = gen_uuid();
				$passwd = generatePassword(6);
				$url = $this->config->item('base_url') . 'profile/register/' . $uuid;
				$this->email->from($this->config->item('email_from'), $this->config->item('email_from_name'));
				$this->email->to($email);
				$this->email->subject('Bookshelf Registration');
				$this->email->message($this->load->view('profile/profile_register_email', array('reg_url' => $url, 'passwd' => $passwd), true));
				$mail_sent = $this->email->send();
				//echo $this->email->print_debugger();
				if( !$mail_sent ) {
					$error = "<p class='error'>There was a problem sending the mail. Perhaps try again later.</p>";
					$error .= '<pre>' . $this->email->print_debugger() . '</pre>';
				} else {
					$this->db->set('updated_on', 'now()', false );
					$this->db->set('created_on', 'now()', false );
					$this->db->set('passwd', $this->auth->hash_password($passwd));					
					//$this->db->set('passwd', "PASSWORD(".$this->db->escape($passwd).")", false );
					$this->db->set('username', $email );
					$this->db->set('email', $email );
					$this->db->set('action_uuid', 'register_' . $uuid );
					$this->db->set('active', 0 );
					$this->db->set('role_id', '(select id from user_roles where role = \'user\')', false );
					$this->db->insert('users' );
				}	
			} else {
				if( $ok )
					$error = "<p class='error'>That seems to be an invalid email. Did you type it in correctly?</p>";
			}
		}
		
		$pg_data = $this->get_page_data('Bookshelf - Register', 'login');
		$data['title'] = '';
		$data['body'] = '';
		$data['error'] = $error;
		$data['n1'] = rand(1,10);
		$data['n2'] = rand(1,10);
  	$pg_data['content'] = $this->load->view('profile/profile_register', $data, true);
		if( $mail_sent ) {
	  	$pg_data['content'] = $this->load->view('profile/profile_register_success', $data, true);			
		} 
		$this->load->view('layouts/standard_page', $pg_data );					
	}
	
	function forgot()
	{
		$passwd = NULL;
		
		if( $this->uri->segment(3) ) {
			$uuid = 'forgot_' . $this->uri->segment(3);
			$this->db->where('action_uuid', $uuid );
			$res = $this->db->get('users');
			if( $res->num_rows() == 1 ) {
				$passwd = generatePassword(6);
				$this->db->where('id', $res->row()->id);
				$this->db->set('action_uuid', 'NULL', false );
				$this->db->set('updated_on', 'now()', false );
				$this->db->set('passwd', $this->auth->hash_password($passwd));					
				//$this->db->set('passwd', "PASSWORD(".$this->db->escape($passwd).")", false );
				$this->db->update('users');
			}
		}
		
		
		$mail_sent = false;
		
		if( $this->input->post("reset")) {
			$j = $this->input->post('j');
			$b = $this->input->post('b');
			$cap = $this->input->post('cap');
			
			$email = (string)$this->input->post("email",TRUE);			
			$ok = true;
			$this->db->where('email', $email);
			$res = $this->db->get('users');
			// found them
			if( $res->num_rows() == 1 && ($j + $b == $cap) && $cap > 0 ) {
				
				$uuid = gen_uuid();											
				$url = $this->config->item('base_url') . 'profile/forgot/' . $uuid;
				$this->email->from($this->config->item('email_from'), $this->config->item('email_from_name'));
				$this->email->to($email);
				$this->email->subject('Bookshelf Password Reset');
				$this->email->message($this->load->view('profile/profile_forgot_email', array('forgot_url' => $url), true));
				$mail_sent = $this->email->send();				
				
				if( $mail_sent ) {
					$this->db->set('action_uuid', 'forgot_' . $uuid );
					$this->db->where('email', $email);
					$this->db->update('users'); 					
				}
			}
		}
		
		$pg_data = $this->get_page_data('Bookshelf - Forgot', 'login');
		$data = array('title' => 'Page Not Found', 'body' => '');
		$data['title'] = '';
		$data['body'] = '';
		$data['n1'] = rand(1,10);
		$data['n2'] = rand(1,10);
  	$pg_data['content'] = $this->load->view('profile/profile_forgot', $data, true);
		if( $mail_sent ) {
	  	$pg_data['content'] = $this->load->view('profile/profile_forgot_email_sent', NULL, true);			
		}
		if( $passwd ) {
	  	$pg_data['content'] = $this->load->view('profile/profile_forgot_new_passwd', array('passwd' => $passwd), true);						
		}
		$this->load->view('layouts/standard_page', $pg_data );							
	}
	
}