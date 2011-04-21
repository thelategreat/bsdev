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
		
		$event = $this->event_model->get_event( $id );
		
		if( $event->num_rows() > 0 ) {
			$event = $event->row();
			$event_media = $this->event_model->get_event_media( $id );
			$event_extra = $this->event_model->get_extra_info( $event );
			$event_future = $this->event_model->get_future_dates( $event->category, $event->title );
		} else {
			$event = NULL;
			$event_media = NULL;
			$event_extra = NULL;
			$event_future = NULL;
		}
				
		$view_data = array(
			'event' => $event,
			'media' => $event_media,
			'extra' => $event_extra,
			'future' => $event_future
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
