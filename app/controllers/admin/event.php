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
		redirect('/admin/calendar');			
	}

	function add()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		if( $this->input->post('cancel')) {
			redirect('/admin/calendar');			
		}
		
		$this->form_validation->set_error_delimiters('','');
		$this->form_validation->set_rules('title', 'title', 'trim|required');
		$this->form_validation->set_rules('event_date_start', 'Start Date', 'trim');
		$this->form_validation->set_rules('event_time_start', 'Start Time', 'trim');
		
		if( $this->form_validation->run()) {
			$data = array();
			$data['submitter_id'] = 1;
			$data['title'] = $this->input->post('title');
			$data['venue'] = $this->input->post('venue');
			$data['category'] = $this->input->post('category');
			$data['body'] = $this->input->post('body');
			$data['dt_start'] = $this->input->post('event_date_start') . " " . $this->input->post('event_time_start');
			$data['dt_end'] = $this->input->post('event_date_end') . " " . $this->input->post('event_time_end');
			$this->event_model->add_event( $data );
			redirect('/admin/calendar');
		}
		
		
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
		$event_id = $this->uri->segment(4);
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		if( $this->input->post('cancel')) {
			redirect('/admin/calendar');			
		}
		if( $this->input->post('rm')) {
			$this->db->where('id',$event_id);
			$this->db->delete('events');
			redirect('/admin/calendar');			
		}
		
		$this->form_validation->set_error_delimiters('','');
		$this->form_validation->set_rules('title', 'title', 'trim|required');
		$this->form_validation->set_rules('event_date_start', 'Start Date', 'trim|callback_check_date');
		$this->form_validation->set_rules('event_time_start', 'Start Time', 'trim|callback_check_time');
		
		if( $this->form_validation->run()) {
			$data = array();
			$data['submitter_id'] = 1;
			$data['title'] = $this->input->post('title');
			$data['venue'] = $this->input->post('venue');
			$data['category'] = $this->input->post('category');
			$data['body'] = $this->input->post('body');
			$data['dt_start'] = $this->input->post('event_date_start') . " " . $this->input->post('event_time_start');
			$data['dt_end'] = $this->input->post('event_date_end') . " " . $this->input->post('event_time_end');
			$this->event_model->update_event( $event_id, $data );
			redirect('/admin/calendar');
		}
		
		$this->db->where('id', $event_id);
		$event = $this->db->get('events')->row();
		
		$pg_data = array(
			'title' => 'Admin - Event',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/calendar/event_edit', array('event' => $event), true ),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );				
	}

	function lookup()
	{
		$xml = '';
		$query = $this->input->post("query");
		$cat = $this->input->post("cat");
		
		$xml .= '<?xml version="1.0" encoding="UTF-8"?>';
		$xml .= '<results>';
		
		if( $query && $cat ) {
			$res = $this->db->query("select title from films where title like '" . $query . "%'");
			foreach( $res->result() as $row ) {
				//$xml .= '<li onclick="fill(\'' . addslashes($row->title) . '\')">'. $row->title . '</li>';				
				$xml .= '<item type = "'.$cat.'" name="'. htmlentities($row->title) . '" />' ;
			}
		}
		$xml .= '</results>';
		
//		$html .= '<li onclick="fill(\'foo\')">foo</li>';
//		$html .= '<li onclick="fill(\'bar\')">bar</li>';
		
		header('content-type: text/xml');
		echo $xml;
	}

	function check_date( $dt )
	{
		if( strlen($dt) != 10 ) {
			$this->form_validation->set_message('check_date','The %s field must be a date');
			return false;
		}
		return true;
	}

	function check_time( $ti )
	{
		if( strlen($ti) != 5 ) {
			$this->form_validation->set_message('check_time','The %s field must be a time');
			return false;
		}
		return true;
	}

}