<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

/**
 *
 */
class Event extends Admin_Controller 
{

	protected $day_names = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
	protected $month_names = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");

	/**
	 *
	 */
	function Event()
	{
		parent::__construct();
		
		$this->load->model('event_model');
	}
	
	/**
	 *
	 */
	function index()
	{
		redirect('/admin/calendar');			
	}

	/**
	 *
	 */
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
			$data = array();
			$data['submitter_id'] = 1;
			$data['title'] = $this->input->post('title');
			$data['venue'] = $this->input->post('venue');
			$data['category'] = $this->input->post('category');
			$data['audience'] = $this->input->post('audience');
			$data['body'] = $this->input->post('body');
			$data['event_ref'] = $this->input->post('event_ref');
			$data['venue_ref'] = $this->input->post('venue_ref');
			$data['dt_start'] = $this->input->post('event_date_start') . " " . $this->get_time_post('event_time_start');
			$data['dt_end'] = $this->input->post('event_date_end') . " " . $this->get_time_post('event_time_end');
			$id = $this->event_model->add_event( $data );
			
			// make link to existing media, if film
			if( $data['category'] == 'film' ) {
				$film = $this->db->query("SELECT * FROM films WHERE title = " . $this->db->escape($data['title']));
				if( $film->num_rows() == 1 ) {
					$film = $film->row();
					$mmid = $this->db->query("SELECT * FROM media_map WHERE path = '/films/" . $film->id . "' ORDER BY sort_order" );
					if( $mmid->num_rows()) {
						$mmid = $mmid->row();
						$this->db->query('INSERT INTO media_map (media_id,path,sort_order,slot) VALUES ('.$mmid->media_id.",'/event/$id',0,'general')");
					}
				}
			}
			
			if( $this->input->post('addedit')) {
				redirect('/admin/event/edit/' . $id . '/media' );
			} else {
				redirect('/admin/calendar');
			}
		}
		
		$widgets = array(
			"start_time_widget" => $this->get_time_widget('event_time_start', time()),
			"end_time_widget" => $this->get_time_widget('event_time_end', time() + 60*60),
			"audience_select" => $this->get_audience_select(),
			"category_select" => $this->get_category_select()
			);
		
		$this->gen_page('Admin - Event', 'admin/calendar/event_add', $widgets );
	}
	
	/**
	 *
	 */
	function edit()
	{
		$event_id = (int)$this->uri->segment(4);
		
		if( !$event_id ) {
			redirect('/admin/calendar');						
		}
		
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		if( $this->input->post('cancel')) {
			redirect('/admin/calendar');			
		}
		if( $this->input->post('rm')) {
			$this->db->where('id',$event_id);
			$this->db->delete('events');
			// delete media links
			$this->db->where('path','/event/'.$event_id);
			$this->db->delete('media_map');
			redirect('/admin/calendar');			
		}

		$cur_tab = 'details';
		if( $this->uri->segment(5)) {
			$cur_tab = strtolower($this->uri->segment(5));
		}
		
		if( $cur_tab == 'media' ) {
			// handled by media contraller
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
				$data['event_ref'] = $this->input->post('event_ref');
				$data['venue_ref'] = $this->input->post('venue_ref');
				$data['body'] = $this->input->post('body');
				$data['dt_start'] = $this->input->post('event_date_start') . " " . $this->get_time_post('event_time_start');
				$data['dt_end'] = $this->input->post('event_date_end') . " " . $this->get_time_post('event_time_end');
				$this->event_model->update_event( $event_id, $data );
				redirect('/admin/calendar');
			}
		}
	
		$this->db->where('id', $event_id);
		$event = $this->db->get('events')->row();
		if( !$event ) {
			redirect('/admin/calendar');			
		}
		
		
		$tabs = $this->tabs->gen_tabs(array('Details','Media'), $cur_tab, '/admin/event/edit/' . $event_id);
		
		$data = array(
			'event' => $event,
			'tabs' => $tabs,
			"start_time_widget" => $this->get_time_widget('event_time_start', strtotime($event->dt_start)),
			"end_time_widget" => $this->get_time_widget('event_time_end', strtotime($event->dt_end)),
			"audience_select" => $this->get_audience_select($event->audience),
			"category_select" => $this->get_category_select($event->category)
			
			);
		
		if( $cur_tab == 'media' ) {
			$data['title'] = "Media for: $event->title";
			$data['path'] = '/event/' . $event->id;
			$data['next'] = "/admin/event/edit/$event->id/media";
			$content = $this->load->view('admin/media/media_tab', $data, true );
		} else {
			$content = $this->load->view('admin/calendar/event_edit', $data, true );
		}
		
		$this->gen_page('Admin - Event', $content );
	}

	/**
	 *
	 */
	function lookup()
	{
		$xml = '';
		$query = $this->input->post("query");
		$cat = $this->input->post("cat");
		$id = $this->input->post("id");
		
		$xml .= '<?xml version="1.0" encoding="UTF-8"?>';
		$xml .= '<results>';
		
		if( $id && $cat ) {
			// F I L M
			if( $cat == "1" ) {
				$res = $this->db->query("select id, title, description, running_time from films where id = $id");
				foreach( $res->result() as $row ) {
					$xml .= '<item ';
					$xml .= "id='" . htmlspecialchars($row->id) . "' ";
					$xml .= "title='" . htmlspecialchars($row->title) . "' ";
					$xml .= "time='" . htmlspecialchars($row->running_time) . "' >";
					$xml .= '<description>' . htmlspecialchars($row->description) . '</description>';
					$xml .= "</item>";
				}
			}
		} elseif( $query && $cat ) {
			// F I L M
			if( $cat == "1" ) {
				$res = $this->db->query("select id, title from films where lower(title) like '%" . strtolower($query) . "%'");
				foreach( $res->result() as $row ) {
					$xml .= '<item cat="'.$cat.'" id="'.$row->id.'" name="'. htmlspecialchars($row->title) . '" />' ;
				}
			}
		}
		
		$xml .= '</results>';		
		header('content-type: text/xml');
		echo $xml;
	}

	function lookup_venue()
	{
		$xml = '';
		$query = $this->input->post("query");
		$id = $this->input->post("id");
		
		$xml .= '<?xml version="1.0" encoding="UTF-8"?>';
		$xml .= '<results>';
		
		if( $query ) {
			$res = $this->db->query("select id, name from venues where lower(name) like '%" . strtolower($query) . "%'");
			foreach( $res->result() as $row ) {
				$xml .= '<item id="'.$row->id.'" name="'. htmlspecialchars($row->name) . '" />' ;
			}
		}
		
		$xml .= '</results>';		
		
		header('content-type: text/xml');
		echo $xml;
	}


	/**
	 *
	 */
	private function get_time_post( $name )
	{
		$hour = $this->input->post($name . "_hour");
		$min = $this->input->post($name . "_min");
		$ampm = $this->input->post($name . "_am_pm");
		if( $ampm == 'pm' ) {
			$hour += 12;
		}
		return sprintf("%02d:%02d", $hour, $min );
	}

	/**
	 *
	 */
	private function get_time_widget($name, $time = NULL )
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
		$s .= '</select>';
		
		$s .= '<select name="'.$name.'_am_pm" id="fld_'.$name.'_am_pm" onchange="sel_'.$name.'();">';
		foreach( array("am","pm") as $zone ) {
			$s .= "<option";
			if( $ampm && $zone == $ampm ) {
				$s .= ' selected="selected"';
			}
			$s .= ">$zone</option>";
		}			
		$s .= '</select>';
		
		return $s;
	}


	/**
	 *
	 */
	public function check_date( $dt )
	{					
		if( strlen($dt) != 10 ) {
			$this->form_validation->set_message('check_date','The %s field must be a date');
			return false;
		}
		return true;
	}

	/**
	 *
	 */
	public function check_time( $ti )
	{			
		if( strlen($ti) != 5 ) {
			$this->form_validation->set_message('check_time','The %s field must be a time');
			return false;
		}
		return true;
	}

	protected function get_category_select( $category = NULL )
	{
		$sel = '<select name="category" id="fld_category" onchange="sel_category();" >';
		$cats = $this->event_model->get_categories();
		foreach( $cats->result() as $cat ) {
			$sel .= "<option value='$cat->id' ";
			if( $category && $category == $cat->id ) {
				$sel .= 'selected="selected"';
			}
			$sel .= ">$cat->category</option>";
		}
		$sel .= '</select>';		
		return $sel;
	}

	protected function get_audience_select( $audience = NULL )
	{
		$sel = '<select name="audience" id="fld_audience" onchange="sel_audience();" >';
		$auds = $this->event_model->get_audiences();
		foreach( $auds->result() as $aud ) {
			$sel .= "<option value='$aud->id' ";
			if( $audience && $audience == $aud->id ) {
				$sel .= 'selected="selected"';
			}
			$sel .= ">$aud->audience</option>";
			
		}
		$sel .= '</select>';		
		return $sel;
	}
}