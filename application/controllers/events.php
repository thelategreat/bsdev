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
    parent::__construct();
    $this->load->model('lists_model');
	}

	/**
	 * Home page
	 *
	 * @return void
	 **/
	function index()
	{
	  redirect('/calendar');
	}

	/**
	 *
	 */
	function details()
	{
		$id = (int)$this->uri->segment(3);

		$item = new stdClass();

		$event = $this->event_model->get_event( $id );

		if( $event != false ) {
			$event_media = $this->event_model->get_event_media( $id );			
			$event_extra = $this->event_model->get_extra_info( $event );
			$event_future = $this->event_model->get_future_dates( $event->category, $event->title );
		} else {
			$event = NULL;
			$event_media = NULL;
			$event_extra = NULL;
			$event_future = NULL;
		}
new dBug($event);

		$item->title 	= $event->title;
		$item->director = $event->director;
		$item->country 	= $event->country;
		$item->year 	= $event->year;
		$item->running_time = $event->running_time;
		$item->rating 	= $event->rating;
		$item->description = $event->description;
		$item->imdb_link = $event->imdb_link;
		$item->venue 	= $event->venue;
		$item->audience = $event->audience;
		$item->associated_essays = $event->associated_essays;


		new dBug($event_future);
		new dBug($item);die;

    $list_meta = array('Serendipity');
    $lists = array();
    foreach( $list_meta as $list_name ) {
      $lists[$list_name] = $this->lists_model->get_list_items_by_name( $list_name );
    }

    		$nav['main'] = 'Home';
			$nav['sub'] = '';
			
		$view_data = array(
			'event' => $event,
			'media' => $event_media,
			'extra' => $event_extra,
			'future' => $event_future,
			'lists' => $lists,
			'nav'	=> $nav
			);

		$pg_data = $this->get_page_data('Bookshelf - Event', 'event');
		$pg_data['content'] = $this->load->view('events/details', $view_data, true);
		$this->load->view('layouts/standard_page', $pg_data );
	}


	/**
	 *
	 */
	function venue()
	{
		$id = (int)$this->uri->segment(3);
		$ajax = $this->uri->segment(4);

		$venue = $this->db->query('SELECT * FROM venues WHERE id = ' . intval($id) );

		if( $venue->num_rows() > 0 ) {
			$venue = $venue->row();
		} else {
			$venue = NULL;
		}

		$view_data = array(
			'venue' => $venue,
			'id' => $id
			);

		if( $ajax ) {
			$this->load->view('events/venue', $view_data );
		} else {
			$pg_data = $this->get_page_data('Bookshelf - Event Location', 'event');
			$pg_data['content'] = $this->load->view('events/venue', $view_data, true);
			$this->load->view('layouts/standard_page', $pg_data );
		}
	}

	/**
	 *
	 */
	function media()
	{
		$id = (int)$this->uri->segment(3);

		$event = $this->event_model->get_event( $id );
		if( $event->num_rows() > 0 ) {
			$event = $event->row();
			$event_media = $this->event_model->get_event_media( $id );
		} else {
			$event = NULL;
			$event_media = NULL;
		}

		$view_data = array(
			'event' => $event,
			'media' => $event_media
			);

		$pg_data = $this->get_page_data('Bookshelf - Event Media', 'event-media');
		$pg_data['content'] = $this->load->view('events/media', $view_data, true);
		$this->load->view('layouts/standard_page', $pg_data );
	}

}
