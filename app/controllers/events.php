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
		/*
		$pg_data = array(
			'page_title' => 'Bookshelf - Calendar',
			'page_name' => 'events-calendar',
			'main_nav_arrows' => '<a id="left_arrow" href="#">&laquo; Previous</a><a id="right_arrow" href="#">Next &raquo;</a>',
			'main_content_nav' => '<ul id="main_content_nav"><li></li></ul>',
			'content' => $this->load->view('events/calendar', '', true),
			'sidebar_nav' => $this->load->view('events/sidebar_nav', '', true ),
			'sidebar' => $this->load->view('home/sidebar', '', true ),
			'footer' => $this->load->view('layouts/standard_footer', '', true )
		);
		*/
		$pg_data = $this->get_page_data('Bookshelf - Calendar', 'events-calendar');
		$pg_data['content'] = $this->load->view('events/calendar', '', true);
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
