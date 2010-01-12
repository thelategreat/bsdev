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
	}


	function index()
	{		
		$pg_data = $this->get_page_data('Bookshelf - Profile', 'home');
		$data = array('title' => 'Page Not Found', 'body' => '');
		$data['title'] = '';
		$data['body'] = '';
  	$pg_data['content'] = $this->load->view('profile/profile_view', $data, true);
		$this->load->view('layouts/standard_page', $pg_data );
	}

	function login()
	{
		$pg_data = $this->get_page_data('Bookshelf - Login', 'home');
		$data = array('title' => 'Page Not Found', 'body' => '');
		$data['title'] = '';
		$data['body'] = '';
  	$pg_data['content'] = $this->load->view('profile/profile_login', $data, true);
		$this->load->view('layouts/standard_page', $pg_data );			
	}

	function logout()
	{
		redirect('/');
	}

}