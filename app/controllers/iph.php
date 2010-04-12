<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class iph extends MY_Controller 
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
	 * AT the top, we have the home page. This points at
	 * the other links and content os automatically added.
	 *
	 * @return void
	 **/
	function index()
	{
		$this->load->view('layouts/iphone');
	}

	function cal()
	{
		$this->load->model('event_model');
		$monday = strtotime('-' . (date('w') == 0 ? "6" : date("w") -1) . " days");	
		
		
		$events = array();
		$today = $monday;
		for( $i = 0; $i < 7; $i++ ) {
			$dt = date('D M d, Y', $today);
			$events[$dt] = array();
			$filter = array(
				'year' => date('Y',$today),
				'month' => date('m',$today),
				'day' => date('d',$today),
				'view' => 'day'
			);
			$res = $this->event_model->get_events( $filter );
			foreach( $res->result() as $row ) {
				$item = '<a class="event-time">' . date('g:i a',strtotime($row->dt_start)) . '</a>';
				$item .= "<a href='/iph/event/$row->id'>$row->title</a>";
				$events[$dt][] = $item;
			}
			$today = strtotime( "+1 day",  $today );
		}
		
		$page_data = array(
			'title' => 'Cinema',
			'events' => $events
			);
			
		$this->load->view('iphone/list', $page_data );
	}


	function event()
	{
		$id = $this->uri->segment(3);		
		$this->load->model('event_model');
		$res = $this->event_model->get_event( $id );
		if( $res->num_rows() ) {
			$event = $res->row();
			$media = $this->media_model->get_media_for_path('/event/' . $event->id );			
			echo "<div title='Film'>";
			if( count($media) ) {
				$media = $media[0];
				echo '<img style="float: right; width: 150px; padding: 5px" src="/media/' . $media['uuid'] . '" />';
			}
			echo "<h3>" . $event->title . "</h3>";
			echo "<p class='event-date'>" . date('D M d, Y @ g:i a', strtotime($event->dt_start)) . "</p>";
			echo $event->body; 
			echo '</div>';
		} else {			
			echo "<h3>Event not found :/</h3>";				
		}
	}

	function ebar()
	{
		echo "<h3>The eBar</h3>";		
	}
	
	function books()
	{
		echo "<h3>The Book Store</h3>";		
	}
	
	function about()
	{
		echo "<h3>The Bookshelf</h3>";
	}
	
	function search()
	{
		$page_data = array(
			'title' => ''
			);
			
		$this->load->view('iphone/event', $page_data );
	}
	
	
	
}
