<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class iph extends MY_Controller 
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
	
	/**
	 * Home page
	 *
	 * @return void
	 **/
	function index()
	{
		$this->load->view('layouts/iphone');
	}

	function cal()
	{
		$this->load->model('event_model');
		$monday = strtotime('-' . (date('w') == 0 ? "6" : date("w") -1) . " days");	
		
		$events = array();
		$today = $monday;
		for( $i = 0; $i < 7; $i++ ) {
			$dt = date('D M d, Y', $today);
			$events[$dt] = array();
			$evt = new stdClass();
			$evt->title = "Dummy";
			$events[$dt][] = $evt;
			$today = strtotime( "+1 day",  $today );
		}
		
		$page_data = array(
			'title' => 'Cinema',
			'events' => $events
			);
			
		$this->load->view('iphone/list', $page_data );
	}

	function ebar()
	{
		echo "<h3>The eBar</h3>";		
	}
	
	function books()
	{
		echo "<h3>The Book Store</h3>";		
	}
	
	function about()
	{
		echo "<h3>The Bookshelf</h3>";
	}
	
	function search()
	{
		$page_data = array(
			'title' => ''
			);
			
		$this->load->view('iphone/event', $page_data );
	}
	
	
	
}
