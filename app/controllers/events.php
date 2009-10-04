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
		$cal_info = array();
		
		$ts = mktime( 0, 0, 0, date('n'), 1, date('Y'));
		$maxday = date('t', $ts );
		$today = getdate($ts);
		$startday = $today['wday'];
		
		$filter = array('day' => 1, 'month' => $today['mon'], 'year' => $today['year'], 'view' => 'month');
		$items = $this->event_model->get_events( $filter );
		
		// fill with empty
		for( $i = 0; $i < 7*6; $i++ ) {
			$event_info = array();
			if( $i < $startday ) {
  			$event_info["day_number"] = $i - $startday;
  			$event_info["description"] = "";
  			$event_info["url"] = "";
  			$event_info["image"] = "/i/calendar/cal_no_image.jpg";
			} else {
  			$event_info["day_number"] = $i - $startday + 1;
  			$event_info["description"] = "";
  			$event_info["url"] = "";
  			$event_info["image"] = "/i/calendar/cal_no_image.jpg";			  
			}
			$cal_info[] = $event_info;
		}
		
		foreach( $items->result() as $event ) {
			$dt = date_parse($event->dt_start);
			$cal_info[$dt['day']+1]['url'] = '/events/details/' . $event->id;
			$cal_info[$dt['day']+1]['description'] = $event->title;
			$image_path = 'pubmedia/films/' . strtolower($event->title) . '.jpg';
			$image_path = str_replace( ' ', '-', $image_path );
			if( file_exists( $image_path )) {	
				$cal_info[$dt['day']+1]["image"] = '/' . $image_path;
			}
		}
		
		$main_content_nav = '
			<ul id="main_content_nav">
				<li class="calendar_month selected"><a class="cufon" href="page-event-calendar.html">August 2009</a></li>
				<li class="calendar_meta">Click arrows to view previous or next month.</li>
			</ul>';
			
		$pg_data = $this->get_page_data('Bookshelf - Calendar', 'events-calendar');
		$pg_data['content'] = $this->load->view('events/calendar', array("calinfo" => $cal_info), true);
		$pg_data['main_content_nav'] = $main_content_nav;
		$pg_data['main_nav_arrows'] = '<a id="left_arrow" href="#">&laquo; Previous</a><a id="right_arrow" href="#">Next &raquo;</a>';
		$this->load->view('layouts/standard_page', $pg_data );	  
	}


	/**
	 *
	 */
	function details()
	{
	  $main_content_nav = '<ul id="main_content_nav">
  		<li class="details selected"><a class="cufon" href="/events/details">Details</a></li>
  		<li class="location"><a class="cufon" href="/events/location">Location</a></li>
  		<li class="media_gallery"><a class="cufon" href="/events/media">Media Gallery</a></li>	
  	</ul>';
		
	  /*
		$pg_data = array(
			'title' => 'Welcome',
			'page_title' => 'Bookshelf - Event',
			'page_name' => 'event',
			'main_content_nav' => $main_content_nav,
			'content' => $this->load->view('events/details', '', true),
			'sidebar_nav' => $this->load->view('events/sidebar_nav', '', true ),
			'sidebar' => $this->load->view('home/sidebar', '', true ),
			'footer' => $this->load->view('layouts/standard_footer', '', true )
		);
		*/
		$pg_data = $this->get_page_data('Bookshelf - Event', 'event');
		$pg_data['content'] = $this->load->view('events/details', '', true);
		$pg_data['main_content_nav'] = $main_content_nav;
		$this->load->view('layouts/standard_page', $pg_data );	  
	}


	/**
	 *
	 */
	function location()
	{
	  $main_content_nav = '<ul id="main_content_nav">
  		<li class="details"><a class="cufon" href="/events/details">Details</a></li>
  		<li class="location selected"><a class="cufon" href="/events/location">Location</a></li>
  		<li class="media_gallery"><a class="cufon" href="/events/media">Media Gallery</a></li>	
  	</ul>';
		
	  /*
		$pg_data = array(
			'title' => 'Welcome',
			'page_title' => 'Bookshelf - Event Location',
			'page_name' => 'event',
			'main_content_nav' => $main_content_nav,
			'content' => $this->load->view('events/location', '', true),
			'sidebar_nav' => $this->load->view('events/sidebar_nav', '', true ),
			'sidebar' => $this->load->view('home/sidebar', '', true ),
			'footer' => $this->load->view('layouts/standard_footer', '', true )
		);
		*/
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
	  $main_content_nav = '<ul id="main_content_nav">
  		<li class="details"><a class="cufon" href="/events/details">Details</a></li>
  		<li class="location"><a class="cufon" href="/events/location">Location</a></li>
  		<li class="media_gallery selected"><a class="cufon" href="/events/media">Media Gallery</a></li>	
  	</ul>';
		
	  /*
		$pg_data = array(
			'title' => 'Welcome',
			'page_title' => 'Bookshelf - Event Media',
			'page_name' => 'event-media',
			'main_content_nav' => $main_content_nav,
			'content' => $this->load->view('events/media', '', true),
			'sidebar_nav' => $this->load->view('events/sidebar_nav', '', true ),
			'sidebar' => $this->load->view('home/sidebar', '', true ),
			'footer' => $this->load->view('layouts/standard_footer', '', true )
		);
		*/
		
		$pg_data = $this->get_page_data('Bookshelf - Event Media', 'event-media');
		$pg_data['content'] = $this->load->view('events/media', '', true);
		$pg_data['main_content_nav'] = $main_content_nav;
		$this->load->view('layouts/standard_page', $pg_data );	  
	}


}
