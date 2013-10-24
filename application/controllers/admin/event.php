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
	 Add an event
	 */
	function add_event()
	{
		$widgets = array(
			"start_time_widget" => $this->get_time_widget('time_start', mktime( 12, 0, 0 )), //time()),
			"end_time_widget" => $this->get_time_widget('time_end', mktime( 13, 0, 0 )),//time() + 60*60),
			"audience_select" => $this->get_audience_select(),
			"category_select" => $this->get_category_select(),
			"venue_select" => $this->get_venues_select()
			);
		$this->gen_page('Admin - Event', 'admin/calendar/event_add', $widgets );
	}


	/**
	 Edit a calendar event 
	 */
	function edit_event($id)
	{
		if (!$id) redirect('/admin/calendar');
		$event = $this->event_model->get_event_by_event_time_id($id);

		$widgets = array(
			"start_time_widget" => $this->get_time_widget('time_start', strtotime($event->start_time)),
			"end_time_widget" => $this->get_time_widget('time_end', strtotime($event->end_time)),
			"audience_select" => $this->get_audience_select($event->audience),
			"category_select" => $this->get_category_select($event->category),
			"venue_select" => $this->get_venues_select($event->venues_id),
			"event"	=> $event
			);
		$this->gen_page('Admin - Event', 'admin/calendar/event_add', $widgets );
	}

	/**
	 Add a film
	 */
	function add_film($event_id = false)
	{
		if( $this->input->post('cancel')) {
			redirect('/admin/calendar');
		}

		$film = $film_id = false;
		if ($event_id) {
			$film_id = $this->event_model->get_film_id_by_event_time_id($event_id);
			$film = $this->event_model->get_film($film_id);
		}


		$widgets = array(
			"start_time_widget" => $this->get_time_widget('event_time_start', mktime( 12, 0, 0 )), //time()),
			"end_time_widget" => $this->get_time_widget('event_time_end', mktime( 13, 0, 0 )),//time() + 60*60),
			"audience_select" => $this->get_audience_select(),
			"category_select" => $this->get_category_select(1),
			"venue_select"		=> $this->get_venue_select(2),
			"film_id"			=> $film_id,
			"title"				=> $film != false ? $film->title : ''
			);

		$this->gen_page('Admin - Event', 'admin/calendar/film_add', $widgets );
	}

	/**
	 AJAX call to remove a calendar event 
	 */
	 public function ajax_remove_event() {
	 	$id = $this->input->post('event_id');

	 	$this->db->trans_start();
	 	$sql = "DELETE FROM events_times WHERE events_id = '{$id}'";
	 	$this->db->query($sql);
	 	$sql = "DELETE FROM events WHERE id = '{$id}'";
	 	$this->db->query($sql);
	 	$this->db->trans_complete();

	 	if ($this->db->trans_status() === false) {
	 		$output = array('success'=>'false');
	 		log_message('debug', "AJAX remove event {$id} FAIL");
	 	} else {
	 		$output = array('success'=>'true');
	 		log_message('debug', "AJAX remove event {$id} OK");
	 	}

	 	echo json_encode($output);
	 }
	/**
		AJAX call to add an event
	*/
	public function ajax_add_event() {
		log_message('debug', 'AJAX add event');

		$category = $this->input->post('category');
		$audience = $this->input->post('audience');
		$title 	= $this->input->post('title');
		$body	= $this->input->post('body');
		$venue 	= $this->input->post('venue');
		$start 	= $this->input->post('start');
		$end   	= $this->input->post('end');
		$start_time_hour  = $this->input->post('time_start_hour');
		$start_time_min   = $this->input->post('time_start_min');
		if ($start_time_min == '0') $start_time_min = '00';
		$start_time_am_pm = $this->input->post('time_start_am_pm');
		$end_time_hour    = $this->input->post('time_end_hour');
		$end_time_min     = $this->input->post('time_end_min');
		if ($end_time_min == '0') $end_time_min = '00';
		$end_time_am_pm   = $this->input->post('time_end_am_pm');

		if (!($body && $venue && $start && $end  && $start_time_hour && $start_time_min && $start_time_am_pm
				&& $end_time_hour && $end_time_min && $end_time_am_pm)) {
			echo json_encode(array('status'=>false, 'message'=>'Fields are missing'));
			exit;
		}

		$start_time = date('Y-m-d H:i', strtotime($start . ' ' . $start_time_hour . ':' . $start_time_min . ' ' . $start_time_am_pm));
		$end_time = date('Y-m-d H:i', strtotime($end . ' ' . $end_time_hour . ':' . $end_time_min . ' ' . $end_time_am_pm));

		// If there's an event id on the page it's an update, otherwise it's a new event request
		if ($this->input->post('event_id') === false) {
			log_message('debug', 'AJAX add event - new event');
			$sql = "INSERT INTO events (title, venues_id, body, category, audience)
				VALUES ("
					. $this->db->escape($title) . ", " 
					. $this->db->escape($venue) . ", "
					. $this->db->escape($body) . ", "
					. $this->db->escape($category) . ", "
					. $this->db->escape($audience) . ")";
			$query = $this->db->query($sql);
			$id = mysql_insert_id();

			$sql = "INSERT INTO events_times (events_id, start_time, end_time) 
					VALUES (" 
						. $this->db->escape($id) . ", " 
						. $this->db->escape($start_time) . ", " 
						. $this->db->escape($end_time) . ")";
			$this->db->query($sql);

			if ($this->db->affected_rows() > 0) {
				echo json_encode(array('status'=>true, 'message'=>'OK'));
			}
		} else {
			log_message('debug', 'AJAX add event - update existing event ' . $this->input->post('event_id'));
			$sql = "UPDATE events SET 
				title = " . $this->db->escape($title) . ", 
				venues_id = " . $this->db->escape($venue) . ",
				body = " . $this->db->escape($body) . ",
				category = " . $this->db->escape($category) . ",
				audience = " . $this->db->escape($audience) . "
				WHERE id = " . $this->db->escape($this->input->post('event_id'));
			$query = $this->db->query($sql);

			$sql = "UPDATE events_times SET 
				start_time = " . $this->db->escape($start_time) . ",
				end_time = " . $this->db->escape($end_time) . "
				WHERE events_id = " . $this->db->escape($this->input->post('event_id'));;

			$query = $this->db->query($sql);

			if ($query != false) {
				echo json_encode(array('status'=>true, 'message'=>'OK'));
			}
		}
		
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
			// handled by media controller
		} else {
			$this->form_validation->set_error_delimiters('','');
			$this->form_validation->set_rules('title', 'title', 'trim|required');
			$this->form_validation->set_rules('event_date_start', 'Start Date', 'trim|callback_check_date');
			$this->form_validation->set_rules('event_date_end', 'End Date', 'trim|callback_check_date');
		  $this->form_validation->set_rules('venue_ref', 'Venue', 'trim');

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

	function lookup_json()
	{
		$query = $this->input->post("query");
		$cat = $this->input->post("cat");
		$id = $this->input->post("id");
		$data = array();

		if( $id && $cat ) {
			// F I L M
			if( $cat == "1" ) {
				$res = $this->db->query("select id, title, description, running_time from films where id = $id");
				foreach( $res->result() as $row ) {
					//$data[] = array('id'=>$row->id,'title'=>$row->title,'time'=>$row->running_time,'description'=>$row->description);
					$data[] = array('label'=>$row->title, 'value'=>$row->id);
				}
			}
		} elseif( $query && $cat ) {
			// F I L M
			if( $cat == "1" ) {
				$res = $this->db->query("select id, title, running_time from films where lower(title) like '%" . strtolower($query) . "%'");
				foreach( $res->result() as $row ) {
					//$data[] = array('cat'=>$cat,'id'=>$row->id,'name'=>$row->title,'time'=>$row->running_time);
					$data[] = array('label'=>$row->title, 'value'=>$row->id);
				}
			}
		}

		echo json_encode( $data );
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
				$res = $this->db->query("select id, title, description, running_time from films where id = " . $this->db->escape($id));
				foreach( $res->result() as $row ) {
					$xml .= '<item ';
					$xml .= "id='" . htmlspecialchars($row->id) . "' ";
					$xml .= "title='" . htmlspecialchars($row->title, ENT_QUOTES) . "' ";
					$xml .= "time='" . htmlspecialchars($row->running_time) . "' >";
					$xml .= '<description>' . htmlspecialchars($row->description, ENT_QUOTES) . '</description>';
					$xml .= "</item>";
				}
			}
		} elseif( $query && $cat ) {
			// F I L M
			if( $cat == "1" ) {
				$res = $this->db->query("select id, title, running_time from films where lower(title) like '%" . $this->db->escape_like_str(strtolower($query)) . "%'");
				foreach( $res->result() as $row ) {
					$xml .= '<item cat="'.$cat.'" id="'.$row->id.'" name="'. htmlspecialchars($row->title) . '" time="' . $row->running_time . '" />' ;
				}
			}
		}

		$xml .= '</results>';
		header('content-type: text/xml');
		echo $xml;
	}

	function lookup_venue()
	{
		$query = $this->input->post("query");
		$data = array();

		if( $query ) {
				$sql = "SELECT id, venue 
						FROM venues
						WHERE LOWER(venue)
						LIKE '%" . $this->db->escape_like_str(strtolower($query)) . "%'";						
				$res = $this->db->query($sql);
				foreach( $res->result() as $row ) {					
					$data[] = array('label'=>htmlspecialchars($row->venue), 'value'=>$row->id);
				}
		}

		echo json_encode( $data );

	 /*$xml = '';
		$query = $this->input->post("query");
		$id = $this->input->post("id");

		$xml .= '<?xml version="1.0" encoding="UTF-8"?>';
		$xml .= '<results>';

		if( $query ) {
			$res = $this->db->query("select id, venue from venues where lower(venue) like '%" . $this->db->escape_like_str(strtolower($query)) . "%'");
			foreach( $res->result() as $row ) {
				$xml .= '<item id="'.$row->id.'" name="'. htmlspecialchars($row->venue) . '" />' ;
			}
		}

		$xml .= '</results>';

		header('content-type: text/xml');
		echo $xml; */ 
	}


	/**
	 *
	 */
	private function get_time_post( $name )
	{
		$hour = $this->input->post($name . "_hour");
		$min = $this->input->post($name . "_min");
		$ampm = $this->input->post($name . "_am_pm");
		if( $ampm == 'pm' && $hour != 12 ) {
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
			if( $hr >= 12 ) {
				$ampm = 'pm';
			}
			if ($hr > 12) {
				$hr -= 12;
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
			} elseif( $min > 7 ) {
				$min = 15;
			} else {
				$min = 0;
			}
		}

		$s = "";
		$s .= '<select name="'.$name.'_hour" id="fld_'.$name.'_hour" >';
		for( $i = 1; $i < 13; $i++ ) {
			$s .= "<option";
			if( $hr && $i == $hr ) {
				$s .= ' selected="selected"';
			}
			$s .= ">$i</option>";
		}
		$s .= '</select>';

		$s .= '<select name="'.$name.'_min" id="fld_'.$name.'_min" >';
		//foreach(array("00","15","30","45") as $mint ) {
		for( $mint = 0; $mint < 60; $mint += 5 ) {
			$s .= "<option value='$mint'";
			if( $min && $mint == $min ) {
				$s .= ' selected="selected"';
			}
			$s .= ">".sprintf("%02d",$mint)."</option>";
		}
		$s .= '</select>';

		$s .= '<select name="'.$name.'_am_pm" id="fld_'.$name.'_am_pm" >';
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

	protected function get_venues_select( $filter_id = NULL )
	{
		$sel = '<select name="venue" id="fld_venue" >';
		$results = $this->event_model->get_venues( $filter_id );
		foreach( $results->result() as $it) {
			$sel .= "<option value='{$it->id}' ";
			if( $filter_id && $filter_id == $it->id ) {
				$sel .= 'selected="selected"';
			}
			$sel .= ">" . ucwords($it->name) . "</option>";
		}
		$sel .= '</select>';
		return $sel;
	}

	protected function get_category_select( $filter_id = NULL )
	{
		$sel = '<select name="category" id="fld_category">';
		$results  = $this->event_model->get_categories( $filter_id );
		foreach( $results->result() as $it) {
			$sel .= "<option value='{$it->id}' ";
			if( $filter_id && $filter_id == $it->id ) {
				$sel .= 'selected="selected"';
			}
			$sel .= ">" . ucwords($it->name) . "</option>";
		}
		$sel .= '</select>';
		return $sel;
	}

	protected function get_venue_select( $filter_id = NULL )
	{
		$sel = '<select name="venue" id="fld_venue">';
		$results  = $this->event_model->get_venues( $filter_id );
		foreach( $results->result() as $it) {
			$sel .= "<option value='{$it->id}' ";
			if( $filter_id && $filter_id == $it->id ) {
				$sel .= 'selected="selected"';
			}
			$sel .= ">" . ucwords($it->name) . "</option>";
		}
		$sel .= '</select>';
		return $sel;
	}

	protected function get_audience_select( $filter_id = NULL )
	{
		$sel = '<select name="audience" id="fld_audience">';
		$results  = $this->event_model->get_audiences( $filter_id );
		foreach( $results->result() as $it) {
			$sel .= "<option value='{$it->id}' ";
			if( $filter_id && $filter_id == $it->id ) {
				$sel .= 'selected="selected"';
			}
			$sel .= ">" . ucwords($it->name) . "</option>";
		}
		$sel .= '</select>';
		return $sel;
    }

    

}
