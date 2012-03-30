<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

/**
 * Frontend poll handler
 */

class Poll extends MY_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::__construct();
		$this->load->model('polls_model');
		$this->load->helper('url');
	}
	
	function index()
  {
    $data = array();

		$pg_data = $this->get_page_data('Bookshelf - Polls', 'home' );
  	$pg_data['content'] = $this->load->view('poll/poll_index', $data, true);
		$this->load->view('layouts/standard_page', $pg_data );
	}

}

