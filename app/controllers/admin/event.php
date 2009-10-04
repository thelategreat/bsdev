<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Event extends Controller 
{

	protected $day_names = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
	protected $month_names = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");

	function Event()
	{
		parent::Controller();
		
		$this->auth->restrict_role('admin');
		$this->load->model('event_model');
		
	}
	
	function index()
	{
	}

	function add()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		if( $this->input->post('cancel')) {
			redirect('/admin/calendar');			
		}
		/*
		$this->form_validation->set_error_delimiters('','');
		$this->form_validation->set_rules('title', 'title', 'trim|required');
		$this->form_validation->set_rules('year', 'year', 'trim|integer');
		$this->form_validation->set_rules('running_time', 'running time', 'trim|integer');
		
		if( $this->form_validation->run()) {
			$data = array();
			$data['ttno'] = $this->input->post('ttno');
			$data['title'] = $this->input->post('title');
			$data['director'] = $this->input->post('director');
			$data['country'] = $this->input->post('country');
			$data['year'] = $this->input->post('year');
			$data['running_time'] = $this->input->post('running_time');
			$data['rating'] = $this->input->post('rating');
			$data['description'] = $this->input->post('description');
			$data['imdb_link'] = $this->input->post('link');
			$this->db->insert('films', $data );
			redirect('/admin/cinema');
		}
		*/
		
		$pg_data = array(
			'title' => 'Admin - Event',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/calendar/event_add', '', true ),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );		
		
	}
	
	function edit()
	{
		
	}

}