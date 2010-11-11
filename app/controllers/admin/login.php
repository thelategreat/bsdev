<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Login extends Controller 
{

	function Login()
	{
		parent::Controller();
	} 

	function index()
	{	
		$redir = $this->input->post('redir');
		if( !$redir ) {
			$redir = $this->session->flashdata('login_redir');
		}
		
					
		if ($this->input->post('submLogin') != FALSE)  
		{  
			$login = array($this->input->post('username'), $this->input->post('password'));  
			if($this->auth->process_login($login))  
			{  
				// success
				if( $this->input->post('redir') && strlen($this->input->post('redir'))) {
					redirect($this->input->post('redir'));
				}				
				redirect("/admin");  
			}  
			else  
			{  
				$data['error'] = 'Login failed, please try again'; 
				$this->load->vars($data); 
			} 
		} 

		$view_data = array(
			'login_redir' => $redir
			);


		$pg_data = array(
			'title' => 'Admin - Login',
			'nav' => '',
			'content' => $this->load->view('admin/login', $view_data, true ),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		
		$this->load->view('layouts/admin_page', $pg_data );		
	}

	function logout()
	{
		$this->auth->logout();
		redirect('/admin/login');		
	}

}