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
		$this->load->helper('cal');
		
		$this->load->model('maillist_model');
	}


	function index()
	{
		$this->do_view('poster');
	}
	
	function view()
	{
		$this->do_view($this->uri->segment(3));
	}
	
	function do_view( $which )
	{
		$view_menu = <<<EOF
		View:
		<a href="/calendar/view/poster">poster</a> |
		<a href="/calendar/view/month">month</a> |
		<a href="/calendar/view/list">list</a> 
		<p/>
EOF;
		
		$today = getdate(time());
		$month = (int)$this->uri->segment(4) ? (int)$this->uri->segment(4) : $today['mon'];
		$year = (int)$this->uri->segment(5) ? (int)$this->uri->segment(5) : $today['year'];
		
		// adjust
		$atd = cal_adjust_date( $month, $year );
		$month = $atd[0];
		$year = $atd[1];
		
		// gen the calendar grid		
		$cal = cal_gen( $month, $year );
		// TODO: fill $cal with events
		
		$view_data = array(
			'view_menu' => $view_menu,
			'month' => $month,
			'year' => $year,
			'cal' => $cal
		);
			
		$pg_data = $this->get_page_data('Bookshelf - calendar', 'calendar' );
		$pg_data['sidebar_left'] = NULL;
		
		if( $which == 'list') {
			$pg_data['content'] = $this->load->view('calendar/list_view', $view_data, true);
		} else if( $which == 'month' ) {
			$pg_data['content'] = $this->load->view('calendar/month_view', $view_data, true);
		} else {
			$pg_data['content'] = $this->load->view('calendar/poster_view', $view_data, true);			
		}
		$this->load->view('layouts/standard_page', $pg_data );				
	}	
}