<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   


class Calendar extends MY_Controller 
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
		$view_data = array();
		$pg_data = $this->get_page_data('Bookshelf - calendar', 'home' );
		$pg_data['content'] = $this->load->view('calendar/month_view', $view_data, true);
		$this->load->view('layouts/standard_page', $pg_data );		
	}
}