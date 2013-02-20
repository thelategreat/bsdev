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
		parent::__construct();
		
		$this->config->load('site_config');
		
		$this->load->library('email');
		
		$this->load->helper('email');
		$this->load->helper('media');
		$this->load->helper('misc');
		$this->load->helper('cal');
		
		$this->load->model('maillist_model');
		$this->load->model('event_model');
		$this->load->model('lists_model');
	}


	function index()
	{
		$this->do_view('month');
	}
	
	function view()
	{
		$this->do_view($this->uri->segment(3));
	}
	
	function do_view( $which )
	{
	    $view_menu = "<ul class='tabbed'>";
	    $cal_views = array("month","poster","list");
	    // keep the date we are on for different views
	    $curr_view_date = $this->uri->segment(4) ? "/" . $this->uri->segment(4) : "";
	    if( $this->uri->segment(4) ) {
		    $curr_view_date .= $this->uri->segment(5) ? "/" . $this->uri->segment(5) : "";
	    }
	    foreach( $cal_views as $v ) {
			$view_menu .= '<li ' . ($which == $v ? " class='selected'" : '') . '>';
			$view_menu .= "<a href='/calendar/view/${v}${curr_view_date}'>$v view</a>";
			$view_menu .= '</li>';
	    }
	    $view_menu .= "</ul>";
		
		$today 	= getdate(time());
		$month 	= (int)$this->uri->segment(4) ? (int)$this->uri->segment(4) : $today['mon'];
		$year 	= (int)$this->uri->segment(5) ? (int)$this->uri->segment(5) : $today['year'];
		
		// adjust
		$atd = cal_adjust_date( $month, $year );
		$month = $atd[0];
		$year = $atd[1];
		
		// gen the calendar grid		
		$cal = cal_gen( $month, $year );
		// TODO: fill $cal with events
		$filter = array('view' => 'month', 'year' => $year, 'month' => $month);
		$events = $this->event_model->get_events( $filter );

		foreach( $events->result() as $event ) {
			foreach( $cal as &$week ) {
				foreach( $week as &$day ) {
					if( !isset($day['events'])) {
						$day['events'] = array();
					}
					// dd/mm/yyyy
					$edate = date('j/n/Y', strtotime($event->dt_start));
					if( $edate == $day['date'] ) {
						// grab media
						$media = $this->event_model->get_event_media( $event->id );
						if( $media && $media->num_rows() > 0 ) {
							$media = '/media/' . $media->row()->uuid;
						} else {
							// default image
							if( file_exists( 'img/defaults/' . $event->category . '.jpg'  )) {
								$media = '/img/defaults/' . $event->category . '.jpg';								
							} else {
								$media = '/img/image_not_found.jpg';
							}
						}
						$rating = $event->rating;
						// film
						if( $event->category == 'film' ) {
							$r = $this->db->query('SELECT rating FROM films WHERE id=' . $event->event_ref);
							if( $r->num_rows() > 0 ) {
								$row = $r->row();
								$rating = $row->rating;
							}
						}
						array_push($day['events'], array('id' => $event->id, 
														 'title' => $event->title,
														 'category' => strtolower($event->category),
														 'rating' => $rating,
														 'start' => date('g:i a',strtotime($event->dt_start)),
														 'end' => date('g:i a',strtotime($event->dt_end)),
														 'media' => $media	) );
					}
				}
			}
		}
		
		$next_year 	= (int)$year;
		$prev_year 	= (int)$year;
		$next_month = (((int)$month)+1);
		$prev_month = (((int)$month)-1);
		
		if( $next_month > 12 ) {
			$next_month = 1;
			$next_year++;
		}
		if( $prev_month < 1 ) {
			$prev_month = 12;
			$prev_year--;
		}
		$next_month_url = "/calendar/view/$which/$next_month/$next_year";
		$prev_month_url = "/calendar/view/$which/$prev_month/$prev_year";

/*
    $list_meta = array('Serendipity');
    $lists = array();
    foreach( $list_meta as $list_name ) {
      $lists[$list_name] = $this->lists_model->get_list_items_by_name( $list_name );
    }

*/
	$lists = array();
    	$nav['main'] = 'Home';
		$nav['sub'] = '';
		
		$view_data = array(
			'view_menu' => $view_menu,
			'month' => $month,
			'year' => $year,
		      'cal' => $cal,
		      'lists' => $lists,
			'next_month_url' => $next_month_url,
			'prev_month_url' => $prev_month_url,
			'nav' => $nav
		);
			
		$pg_data = $this->get_page_data('Bookshelf - calendar', 'calendar' );		
		
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
