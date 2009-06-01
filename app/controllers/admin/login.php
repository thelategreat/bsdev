<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Login extends Controller {

	function Login()
	{
		parent::Controller();
		$this->load->helper('url');
		$this->load->library('tabs');

      $this->page_tabs = array(
        'Details', 'Media'
      );
	} 

	function index()
	{				
		if ($this->input->post('submLogin') != FALSE)  
		{  
			$login = array($this->input->post('username'), $this->input->post('password'));  
			if($this->auth->process_login($login))  
			{  
				// success
				redirect("/admin");  
			}  
			else  
			{  
				$data['error'] = 'Login failed, please try again'; 
				$this->load->vars($data); 
			} 
		} 


		$pg_data = array(
			'title' => 'Admin - Login',
			'nav' => '',
			'content' => $this->load->view('admin/login', '', true ),
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