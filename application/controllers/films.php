<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   


class Films extends MY_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::__construct();
		
		$this->config->load('site_config');
		
		$this->load->model('lists_model');
		$this->load->model('tweets_model');
		$this->load->model('films_model');
	}


	function index()
	{
		redirect(base_url('cinema/view/month'));	
	}

	/* View the details about a specific film */	
	function view( $id = false )
	{
		$film = $this->films_model->get_film($id);

		if (!$film) redirect(base_url('cinema/view/month'));

		// Get the tweets for the @bookshelfnews account
	    $tweets = $this->load->view('widgets/tweets',
	      array('tweets' => $this->tweets_model->load('bookshelfnews')),
	      true );
		// Navigation links
		$nav = $this->groups_model->get_group_tree();
		// Upcoming events
		$events = $this->event_model->get_upcoming_events( 4, date('Y-m-d', strtotime('+2 weeks')) );

		$view_data = array(
			'item' 	=> $film,
		    'tweets' 	=> $tweets,
		    'events'	=> $events,
			'nav' => $nav[0]->children
		);
		
		$pg_data = $this->get_page_data('Bookshelf - films', 'films', null, array('cinema', 'films') );		
		
		$pg_data['content'] = $this->load->view('films/details', $view_data, true);			
		$this->load->view('layouts/standard_page', $pg_data );				
	}
	
	function do_view( $which )
	{
      
		$upcoming_films = $this->event_model->get_month_films($month);
		$films_this_month = $this->event_model->get_month_films_by_name($month);

		$view_data = array(
			'month' 	=> $month,
			'month_name'=> $month_name,
			'year' 		=> $year,
		    'tweets' 	=> $tweets,
		    'lists' 	=> $lists,
		    'events'	=> $events,
		    'cal_events'=> $cal_events,
		    'films_this_month' => $films_this_month,
			'next_month_url' => $next_month_url,
			'prev_month_url' => $prev_month_url,
			'nav' => $nav[0]->children
		);

		$pg_data = $this->get_page_data('Bookshelf - calendar', 'calendar', null, array('cinema', 'films') );		
		
		if( $which == 'list') {
			$pg_data['content'] = $this->load->view('calendar/list_view', $view_data, true);
		} else if( $which == 'month' ) {
			$pg_data['content'] = $this->load->view('calendar/month_view', $view_data, true);
		} else if( $which == 'week' ) {
			$pg_data['content'] = $this->load->view('calendar/week_view', $view_data, true);
		} else {
			$pg_data['content'] = $this->load->view('calendar/poster_view', $view_data, true);			
		}
		$this->load->view('layouts/standard_page', $pg_data );				
	}	
}
