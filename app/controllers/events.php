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
		} else {
			$event = NULL;
			$event_media = NULL;
		}
		
		
		$view_data = array(
			'event' => $event,
			'media' => $event_media
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
		
		$event = $this->event_model->get_event( $id );
		
		if( $event->num_rows() > 0 ) {
			$event = $event->row();
		} else {
			$event = NULL;
		}

		$view_data = array(
			'event' => $event
			);

		$pg_data = $this->get_page_data('Bookshelf - Event Location', 'event');
		$pg_data['content'] = $this->load->view('events/venue', $view_data, true);
		$this->load->view('layouts/standard_page', $pg_data );	  
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
