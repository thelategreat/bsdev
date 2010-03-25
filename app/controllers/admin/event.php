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
		$this->load->library('tabs');		
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
		$this->form_validation->set_rules('event_date_end', 'End Date', 'trim');
		
		if( $this->form_validation->run()) {
			$dts = 
			$data = array();
			$data['submitter_id'] = 1;
			$data['title'] = $this->input->post('title');
			$data['venue'] = $this->input->post('venue');
			$data['category'] = $this->input->post('category');
			$data['audience'] = $this->input->post('audience');
			$data['body'] = $this->input->post('body');
			$data['dt_start'] = $this->input->post('event_date_start') . " " . 
				($this->input->post('event_time_start_am_pm') == "am" ? $this->input->post('event_time_start_hour') : 12 + $this->input->post('event_time_start_hour') ) . 
				":" . $this->input->post('event_time_start_min');
			$data['dt_end'] = $this->input->post('event_date_end') . " " . 
				($this->input->post('event_time_end_am_pm') == "am" ? $this->input->post('event_time_end_hour') : 12 + $this->input->post('event_time_end_hour')) . 
				":" . $this->input->post('event_time_end_min');
			$id = $this->event_model->add_event( $data );
			if( $this->input->post('addedit')) {
				redirect('/admin/event/edit/' . $id . '/media' );
			} else {
				redirect('/admin/calendar');
			}
		}
		
		$widgets = array(
			"start_time_widget" => $this->get_time_widget('event_time_start', time()),
			"end_time_widget" => $this->get_time_widget('event_time_end', time() + 60*60),
			);
		
		$pg_data = array(
			'title' => 'Admin - Event',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/calendar/event_add', $widgets, true ),
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

		$cur_tab = 'details';
		if( $this->uri->segment(5)) {
			$cur_tab = strtolower($this->uri->segment(5));
		}
		
		if( $cur_tab == 'media' ) {
			
		} else {
			$this->form_validation->set_error_delimiters('','');
			$this->form_validation->set_rules('title', 'title', 'trim|required');
			$this->form_validation->set_rules('event_date_start', 'Start Date', 'trim|callback_check_date');
			$this->form_validation->set_rules('event_date_end', 'End Date', 'trim|callback_check_date');
		
			if( $this->form_validation->run()) {
				$data = array();
				$data['submitter_id'] = 1;
				$data['title'] = $this->input->post('title');
				$data['venue'] = $this->input->post('venue');
				$data['category'] = $this->input->post('category');
				$data['audience'] = $this->input->post('audience');
				$data['body'] = $this->input->post('body');
				//$data['dt_start'] = $this->input->post('event_date_start') . " " . $this->input->post('event_time_start');
				//$data['dt_end'] = $this->input->post('event_date_end') . " " . $this->input->post('event_time_end');
				$data['dt_start'] = $this->input->post('event_date_start') . " " . 
					($this->input->post('event_time_start_am_pm') == "am" ? $this->input->post('event_time_start_hour') : 12 + $this->input->post('event_time_start_hour') ) . 
					":" . $this->input->post('event_time_start_min');
				$data['dt_end'] = $this->input->post('event_date_end') . " " . 
					($this->input->post('event_time_end_am_pm') == "am" ? $this->input->post('event_time_end_hour') : 12 + $this->input->post('event_time_end_hour')) . 
					":" . $this->input->post('event_time_end_min');
				$this->event_model->update_event( $event_id, $data );
				redirect('/admin/calendar');
			}
		}
	
		$this->db->where('id', $event_id);
		$event = $this->db->get('events')->row();
		$tabs = $this->tabs->gen_tabs(array('Details','Media'), $cur_tab, '/admin/event/edit/' . $event_id);
		
		if( $cur_tab == 'media' ) {
			$content = $this->load->view('admin/calendar/event_media', array('event' => $event, 'tabs' => $tabs), true );
		} else {
			$content = $this->load->view('admin/calendar/event_edit', array('event' => $event, 'tabs' => $tabs), true );
		}
		
		$pg_data = array(
			'title' => 'Admin - Event',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $content,
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );				
	}

	function lookup()
	{
		$xml = '';
		$query = $this->input->post("query");
		$cat = $this->input->post("cat");
		$id = $this->input->post("id");
		
		$xml .= '<?xml version="1.0" encoding="UTF-8"?>';
		$xml .= '<results>';
		
		if( $id && $cat ) {
			$res = $this->db->query("select id, title, description, running_time from films where id = $id");
			foreach( $res->result() as $row ) {
				$xml .= '<item ';
				$xml .= "id='" . htmlentities($row->id) . "' ";
				$xml .= "title='" . htmlentities($row->title) . "' ";
				$xml .= "time='" . htmlentities($row->running_time) . "' >";
				$xml .= '<description>' . htmlentities($row->description) . '</description>';
				$xml .= "</item>";
			}
		} elseif( $query && $cat ) {
			$res = $this->db->query("select id, title from films where title like '" . $query . "%'");
			foreach( $res->result() as $row ) {
				$xml .= '<item cat="'.$cat.'" id="'.$row->id.'" name="'. htmlentities($row->title) . '" />' ;
			}
		}
		
		$xml .= '</results>';		
		header('content-type: text/xml');
		echo $xml;
	}

	function get_time_widget($name, $time = NULL )
	{
		$hr = NULL;
		$min = NULL;
		$ampm = 'am';
		if( $time != NULL ) {
			$hr = date('H',$time);
			$min = date('i',$time);
			if( $hr > 12 ) {
				$hr = $hr - 12;
				$ampm = 'pm';
			}
			if( $min > 45 ) {
				$min = "00";
				$hr += 1;
				if( $hr > 12 ) {
					$hr = 1;
				}
			} elseif ( $min > 30 ) {
				$min = 45;
			} elseif( $min > 15 ) {
				$min = 30;
			} else {
				$min = 15;
			}
		}
		
		$s = "";
		$s .= '<select name="'.$name.'_hour" id="fld_'.$name.'_hour" onchange="sel_'.$name.'();">';
		for( $i = 1; $i < 13; $i++ ) {
			$s .= "<option";
			if( $hr && $i == $hr ) {
				$s .= ' selected="selected"';
			}
			$s .= ">$i</option>";
		}
		$s .= '</select>';
		
		$s .= '<select name="'.$name.'_min" id="fld_'.$name.'_min" onchange="sel_'.$name.'();">';
		//foreach(array("00","15","30","45") as $mint ) {
		for( $mint = 0; $mint < 60; $mint += 5 ) {
			$s .= "<option";
			if( $min && $mint == $min ) {
				$s .= ' selected="selected"';
			}
			$s .= ">".sprintf("%02d",$mint)."</option>";
		}
		
		$s .= '<select name="'.$name.'_am_pm" id="fld_'.$name.'_am_pm" onchange="sel_'.$name.'();">';
		foreach( array("am","pm") as $zone ) {
			$s .= "<option";
			if( $ampm && $zone == $ampm ) {
				$s .= ' selected="selected"';
			}
			$s .= ">$zone</option>";
		}			
		return $s;
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