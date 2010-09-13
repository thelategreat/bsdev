<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   


class Cart extends MY_Controller 
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
		$data = array();
		$pg_data = $this->get_page_data('Bookshelf - cart', 'home' );
  	$pg_data['content'] = $this->load->view('cart/cart_list', $data, true);
		$this->load->view('layouts/standard_page', $pg_data );
	}
}