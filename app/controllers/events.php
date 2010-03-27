<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Events extends MY_Controller 
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
	  $this->calendar();
	}
	
	/**
	 *
	 */
	function calendar()
	{
		$today = $this->get_cal_date();
		
		$ts = mktime( 0, 0, 0, date('n',$today), 1, date('Y',$today));
		$manday = date('t', $ts );
		$first_day = getdate($ts);
		
		$startday = $first_day['wday'];
				
		$next_month = strtotime("+1 month", $ts);
		$last_month = strtotime("-1 month", $ts);
		$last_day_of_this_month = date('t',$ts);
		$last_day_of_last_month = date('t',$last_month);
		
		$lm = $last_day_of_last_month - $startday + 1 . ' ' .date('F',$last_month) . ' ' .  date('Y',$last_month);
		if( $startday == 0 ) {
			$lm =  date('d F Y', $ts);
		}
		$start_date = strtotime(  $lm  );
		$end_date = strtotime("+41 days", $start_date );
		/*
		echo '<span style="color: white;">';
		echo date('Y M d', $start_date ) . '<br/>';
		echo date('Y M d', $end_date ) . '<br/>';
		echo '</span>';
		*/
		$cal_info = array();
		// fill array with empty values
		// TODO: this could be way simpler
		for( $i = 0; $i < 7*6; $i++ ) {
			$event_info = array();
			$event_info["title"] = "";
			$event_info["description"] = "";
			$event_info['day_url'] = '/events/calendar/';
			$event_info["url"] = "";
			$event_info["image"] = "/i/calendar/cal_no_image.jpg";			  
			if( $i < $startday ) {
  			$event_info["day_number"] = $last_day_of_last_month + $i - $startday + 1;
				$event_info['day_url'] = '/events/calendar/' . date('M',$last_month) . ($last_day_of_last_month - $i - $startday + 1) ;
			} else if( $i - $startday + 1 > $last_day_of_this_month ) {
  			$event_info["day_number"] = $i - $startday - $last_day_of_this_month + 1;
				$event_info['day_url'] = '/events/calendar/' . date('M',$next_month) . ($i - $startday - $last_day_of_this_month + 1);
			} else {
  			$event_info["day_number"] = $i - $startday + 1;
				$event_info['day_url'] = '/events/calendar/' . date('M',$ts) . ($i - $startday + 1);
			}
			$cal_info[] = $event_info;
		}
		
		// put the events in the appropriate slot
		// for now we just count
		$events = $this->event_model->get_events_by_date_range( $start_date, $end_date );		
		foreach( $events->result() as $event ) {
			$dt = strtotime($event->dt_start);
			$offs = ($dt - $start_date) / (60*60*24);
			if( !isset($cal_info[$offs]['count'])) {
				$cal_info[$offs]['count'] = 0;
			}
			$cal_info[$offs]['count']++;
		}
				
		$main_content_nav = '
			<ul id="main_content_nav">
				<li class="calendar_month selected"><a class="cufon" href="page-event-calendar.html">'.$first_day['month'].' '.$first_day['year'].'</a></li>
				<li class="calendar_meta">Click arrows to view previous or next month.</li>
			</ul>';
			
		
		$pg_data = $this->get_page_data('Bookshelf - Calendar', 'events-calendar');
		$pg_data['content'] = $this->load->view('events/calendar', array("calinfo" => $cal_info), true);
		$pg_data['main_content_nav'] = $main_content_nav;
		$pg_data['main_nav_arrows'] = '<a id="left_arrow" href="/events/calendar/'.date('M',$last_month).'01">&laquo; Previous</a>';
		$pg_data['main_nav_arrows'] .= '<a id="right_arrow" href="/events/calendar/'.date('M',$next_month).'01">Next &raquo;</a>';
		$this->load->view('layouts/standard_page', $pg_data );	  
	}


	/**
	 *
	 */
	function details()
	{
		$id = $this->uri->segment(3);
		
		$event = $this->event_model->get_event( $id );
		if( $event->num_rows() > 0 ) {
			$event = $event->row();
		} else {
			$event = NULL;
		}
		
		$event_media = $this->event_model->get_event_media( $id );
		
	  $main_content_nav = '<ul id="main_content_nav">
  		<li class="details selected"><a class="cufon" href="/events/details/'.$id.'">Details</a></li>
<!--
  		<li class="location"><a class="cufon" href="/events/location/'.$id.'">Location</a></li>
  		<li class="media_gallery"><a class="cufon" href="/events/media/'.$id.'">Media Gallery</a></li>	
-->
  	</ul>';
		

		$pg_data = $this->get_page_data('Bookshelf - Event', 'event');
		$pg_data['content'] = $this->load->view('events/details', array('event'=>$event, 'media'=>$event_media), true);
		$pg_data['main_content_nav'] = $main_content_nav;
		$this->load->view('layouts/standard_page', $pg_data );	  
	}


	/**
	 *
	 */
	function location()
	{
		$id = $this->uri->segment(3);
		
		$event = $this->event_model->get_event( $id );
		if( $event->num_rows() > 0 ) {
			$event = $event->row();
		} else {
			$event = NULL;
		}


	  $main_content_nav = '<ul id="main_content_nav">
  		<li class="details"><a class="cufon" href="/events/details">Details</a></li>
  		<li class="location selected"><a class="cufon" href="/events/location">Location</a></li>
  		<li class="media_gallery"><a class="cufon" href="/events/media">Media Gallery</a></li>	
  	</ul>';
		
		$pg_data = $this->get_page_data('Bookshelf - Event Location', 'event');
		$pg_data['content'] = $this->load->view('events/location', '', true);
		$pg_data['main_content_nav'] = $main_content_nav;
		$this->load->view('layouts/standard_page', $pg_data );	  
	}

	/**
	 *
	 */
	function media()
	{
		$id = $this->uri->segment(3);
		
		$event = $this->event_model->get_event( $id );
		if( $event->num_rows() > 0 ) {
			$event = $event->row();
		} else {
			$event = NULL;
		}
		
	  $main_content_nav = '<ul id="main_content_nav">
  		<li class="details"><a class="cufon" href="/events/details">Details</a></li>
  		<li class="location"><a class="cufon" href="/events/location">Location</a></li>
  		<li class="media_gallery selected"><a class="cufon" href="/events/media">Media Gallery</a></li>	
  	</ul>';
				
		$pg_data = $this->get_page_data('Bookshelf - Event Media', 'event-media');
		$pg_data['content'] = $this->load->view('events/media', '', true);
		$pg_data['main_content_nav'] = $main_content_nav;
		$this->load->view('layouts/standard_page', $pg_data );	  
	}


}
