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
		$this->load->model('event_model');
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
		$view_menu = <<<EOF
		View:
			<a href="/calendar/view/month">month</a> |
			<a href="/calendar/view/poster">poster</a> |
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
						array_push($day['events'], array('id' => $event->id, 
																						 'title' => $event->title,
																						 'category' => strtolower($event->category),
																						 'start' => date('g:i a',strtotime($event->dt_start)),
																						 'end' => date('g:i a',strtotime($event->dt_end)),
																						 'media' => $media																						
																						) );
					}
				}
			}
		}
		
		$next_year = (int)$year;
		$prev_year = (int)$year;
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
		
		$view_data = array(
			'view_menu' => $view_menu,
			'month' => $month,
			'year' => $year,
			'cal' => $cal,
			'next_month_url' => $next_month_url,
			'prev_month_url' => $prev_month_url
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