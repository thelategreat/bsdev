<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Calendar extends Controller 
{

	protected $day_names = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
	protected $month_names = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");

	function Calendar()
	{
		parent::Controller();
		
		$this->auth->restrict_role('admin');
		$this->load->model('event_model');
		
	}
	
	function index()
	{
		$this->month();
	}
	
	function month()
	{
	    $s = '';

	    $today = getdate(time());
	
		$filter = array(
			'year' => $today['year'],
			'month' => $today['mon'],
			'day' => $today['mday'],
			'view' => 'month'
		);

		// handle segments
		$segs = $this->uri->uri_to_assoc(4);
		foreach( $segs as $k => $v ) {
			$filter[$k] = $v;
		}

	    $a = $this->_adjust_date( $filter['month'], $filter['year'] );
	    $month = $a[0];
	    $year = $a[1];

	    $days_in_month = $this->_days_in_month( $month, $year );
	    $date = getdate(mktime(12,0,0,$month,1,$year));

	    $first = $date['wday'];
	    $prev = $this->_adjust_date($month-1,$year);
	    $days_in_last_month = $this->_days_in_month( $prev[0], $prev[1]);
	    $next = $this->_adjust_date($month+1,$year);
	    $week_no = (int)date("W", mktime(12,0,0,$month,1,$year));
	    $d = -$first + 1;		

	    $s .= '<h3>' . $this->month_names[$month-1] . ' ' . $year . '</h3>';

	    $s .= '<table class="cal">';
	    $s .= '<thead>';
	    $s .= '<tr>';
	    $s .= '<td/>';
	    foreach( $this->day_names as $day ) $s .= "<th>$day</th>";    
	    $s .= '</tr>';
	    $s .= '</thead>';
	    $s .= '<tbody><tr><th><a href="/admin/calendar/week/'.$week_no.'">' . $week_no . '</a></th>';  
	    while( $d <= $days_in_month ) {
	      for( $i = 0; $i < 7; $i++ ) {
			$thisdate = sprintf("%04d-%02d-%02d", $year, $month, $d);
	        if( $d < 1 ) {
	          $s .= '<td class="odd"><p class="day-num">' . ($days_in_last_month + $d) .'</p>';
			  $thisdate = sprintf("%04d-%02d-%02d",$year, ($month - 1), $days_in_last_month + $d);
	        }
	        elseif( $d <= $days_in_month ) {
			  $href = "/admin/calendar/day/year/$year/month/$month/day/$d";
	          $s .= '<td class="even ' . ($d == $today['mday'] ? 'today' : '') . '"><p class="day-num"><a href="'.$href.'">' . $d . '</a></p>';
	        } elseif( $d > $days_in_month ) {
	          $s .= '<td class="odd"><p class="day-num">' . ($d - $days_in_month) . '</p>';
			  $thisdate = sprintf("%04d-%02d-%02d",$year, ($month + 1),($d - $days_in_month));
	        }
			//$s .= $thisdate;
			$res = $this->db->query("SELECT count(*) as cnt, venue FROM events WHERE DATE(dt_start) = '$thisdate' GROUP BY venue");
			foreach( $res->result() as $row ) {
			  $s .= '<img class="icon" src="/img/icons/icon_'.$row->venue.'.gif" /> (' . $row->cnt . ')<br/>';
			}
			$s .= '</td>';
	        $d++;
	      }
	      $s .= '</tr>';
	      if( $d <= $days_in_month ) {
	        $week_no++;
	        $s .= '<tr><th><a href="/admin/calendar/week/'.$week_no.'">' . $week_no . '</th>';
	      }
	    }
	    $s .= '</tbody>';
	    $s .= '</table>';
	
	
		$tabs = '<div class="tabs">';
		$tabs .= '<ul>';
		$tabs .= '<li><a href="/admin/calendar/month" class="selected">Month</a></li>';
		$tabs .= '<li><a href="/admin/calendar/week" >Week</a></li>';
		$tabs .= '<li><a href="/admin/calendar/day" >Day</a></li>';
		$tabs .= '</ul>';
		$tabs .= '</div>';

		$this->load->model('event_model');

		$data['cal'] = $s;
		$data['tabs'] = $tabs;
		$data['events'] = $this->event_model->get_events( $filter );
	
		$pg_data = array(
			'title' => 'Admin - Calendar',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/calendar/calendar', $data, true ),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );		
	}
	
	function week()
	{
		
	    $today = getdate(time());
	
		$filter = array(
			'year' => $today['year'],
			'month' => $today['mon'],
			'day' => $today['mday'],
			'view' => 'week'
		);
	
		$segs = $this->uri->uri_to_assoc(3);
		foreach( $segs as $k => $v ) {
			$filter[$k] = $v;
		}

		if( !isset($filter['week']) || $filter['week'] == '') {
			$filter['week'] = strftime('%V', time());
		}

		$monday = $this->_get_iso_monday( $filter['year'], $filter['week']);
        $monday -= 60*60*24;
		$today = getdate($monday);
		$filter['year'] = $today['year'];
		$filter['month'] = $today['mon'];
		$filter['day'] = $today['mday'];

	    $a = $this->_adjust_date( $filter['month'], $filter['year'] );
	    $month = $a[0];
	    $year = $a[1];

	    $days_in_month = $this->_days_in_month( $month, $year );
	    $date = getdate(mktime(12,0,0,$month,1,$year));

	    $first = $date['wday'];
	    $prev = $this->_adjust_date($month-1,$year);
	    $days_in_last_month = $this->_days_in_month( $prev[0], $prev[1]);
	    $next = $this->_adjust_date($month+1,$year);
	    $d = -$first + 1;		

		$this->load->model('event_model');
		$events = $this->event_model->get_events( $filter );


	    $s = '';
	    $s .= '<h3>Week starting: ' . date('l, F d, Y', $monday) . " <span class='small'>(week #". $filter['week'] . ')</span></h3>';
	    $s .= '<div class="scrollable" style="position: relative; width: 700px">';
	    $s .= '<table class="cal cal_week">';
	    $s .= '<thead>';
	    $s .= '<tr>';
	    $s .= '<td class="nav">';
	    $s .= '<a href="" title="Prev"><img src="/img/16-arrow-left.png"/></a>&nbsp;';
	    $s .= '<a href="" title="Next"><img src="/img/16-arrow-right.png" /></a>';
	    $s .= '</td>';
	    for( $i = 0; $i < 7; $i++ ) {
			if( $filter['day'] + $i > $days_in_month ) {
				$s .= "<th>" . $this->day_names[$i] . " " . ($i - $d - 1) . "</th>";				
			} else {
				$s .= "<th>" . $this->day_names[$i] . " " . ($filter['day'] + $i) . "</th>";				
			}
			$d++;
		}
	    $s .= '</tr>';
	    $s .= '</thead>';
	    $s .= '<tbody>';
	    for( $i = 0; $i < 24; $i++ ) {
	      $s .= '<tr>';
	      $s .= '<th>' . ($i > 12 ? $i - 12 : $i) . '&nbsp;'. ($i > 12 ? "pm" : "am") . '</th>';
	      for( $d = 0; $d < 7; $d++ ) {
	        $s .= '<td>';
			if( $d == 1 && $i == 5 ) {
			}
			$s .= '</td>';
	      }
	      $s .= '</tr>';
	    }
	    $s .= '</tbody>';
	    $s .= '</table>';
	
		foreach( $events->result() as $event ) {
			$st = getdate(strtotime($event->dt_start));
			$en = getdate(strtotime($event->dt_end));
			// top: 45, left: 60, width: 65, height variable on duration (1 hr = 25)
			$left = 60 + (($st['mday'] - $filter['day']) * 91);
			$top = 45 + ($st['hours'] * 40);
			$height = 40 + (($en['hours'] - $st['hours'] - 1) * 40);
			$color = '#f99';
			switch( $event->venue ) {
				case 'cinema':
				$color = '#FC6';
				break;
				case 'greenroom':
				$color = '#9f9';
				break;
				case 'ebar':
				$color = '#99f';
				break;
				case 'bookstore':
				$color = '#ff9';
				break;
			}
			$s .= "<div class='rounded' style='font-size: 0.8em; line-height: 1.0em; opacity: 0.6; position: absolute; left: ${left}px; top: ${top}px; z-index: 100; width: 65px; height: ${height}px; background-color: ${color}; border: 1px solid #f00'>";
			$s .= '<a href="#" onclick="edit_event('.$event->id.')" style="font-weight: bold; color: #000;">';
			$s .= date('g:ia',strtotime($event->dt_start)) . '</a><br/>' . $event->title . '</div>';			
		}
		
	
	    $s .= '</div>';
		
		$tabs = '<div class="tabs">';
		$tabs .= '<ul>';
		$tabs .= '<li><a href="/admin/calendar/month" >Month</a></li>';
		$tabs .= '<li><a href="/admin/calendar/week" class="selected">Week</a></li>';
		$tabs .= '<li><a href="/admin/calendar/day" >Day</a></li>';
		$tabs .= '</ul>';
		$tabs .= '</div>';

	
		$data['cal'] = $s;
		$data['tabs'] = $tabs;
		$data['events'] = $events;
	
		$pg_data = array(
			'title' => 'Admin - Calendar',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/calendar/calendar', $data, true ),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );				
	}
	
	function day()
	{
	    $today = getdate(time());
	
		$filter = array(
			'year' => $today['year'],
			'month' => $today['mon'],
			'day' => $today['mday'],
			'view' => 'day'
		);
	
		$segs = $this->uri->uri_to_assoc(4);
		foreach( $segs as $k => $v ) {
			$filter[$k] = $v;
		}

		
		$thisday = strtotime(sprintf("%04d-%02d-%02d",$filter['year'],$filter['month'],$filter['day']) . " 00:00:00");
		
		$s = '';

	    $s = '';
	    $s .= '<div class="scrollable">';
	    $s .= '<table class="cal cal_day">';
	    $s .= '<thead>';
	    $s .= '<tr>';
	    $s .= '<td class="nav">';
	    $s .= '<a href="" title="Prev"><img src="/img/16-arrow-left.png"/></a>&nbsp;';
	    $s .= '<a href="" title="Next"><img src="/img/16-arrow-right.png" /></a>';
	    $s .= '</td>';
	    $s .= '<th>' . date('l, M j, Y', $thisday). "</th>";
	    $s .= '</tr>';
	    $s .= '</thead>';
	    $s .= '<tbody>';
	    for( $i = 0; $i < 24; $i++ ) {
	      $s .= '<tr>';
	      $s .= '<th>' . $i . ':00</th>';
	      $s .= '<td>&nbsp;</td>';
	      $s .= '</tr>';
	    }
	    $s .= '</tbody>';
	    $s .= '</table>';
	    $s .= '</div>';
			
		$tabs = '<div class="tabs">';
		$tabs .= '<ul>';
		$tabs .= '<li><a href="/admin/calendar/month" >Month</a></li>';
		$tabs .= '<li><a href="/admin/calendar/week" >Week</a></li>';
		$tabs .= '<li><a href="/admin/calendar/day" class="selected">Day</a></li>';
		$tabs .= '</ul>';
		$tabs .= '</div>';


		$this->load->model('event_model');
	
		$data['cal'] = $s;
		$data['tabs'] = $tabs;
		$data['events'] = $this->event_model->get_events( $filter );
	
		$pg_data = array(
			'title' => 'Admin - Calendar',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/calendar/calendar', $data, true ),
			'footer' => $this->load->view('layouts/admin_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );						
	}
	
	
	function new_event()
	{
		$this->load->view('admin/calendar/event' );						
	}
	
	function new_media()
	{
		$this->load->view('admin/calendar/event_media' );								
	}
	
	function rm_event()
	{
		
	}
	
	function ajax_add_event()
	{
		$ra = array(
			'err' => true,
			'msg' => 'nothing happened',
			'data' => $_POST
			);
		// YYYY-MM-DD HH:MM:SS
		
		if( $this->input->post('title')) {
			if( $this->input->post('id') != -1 ) {
				$this->update_event();
				return;
			}
			$this->load->model('event_model');
			$data['submitter_id'] = 1;
			$data['title'] = $this->input->post('title');
			$data['venue'] = $this->input->post('venue');
			$data['body'] = $this->input->post('body');
			$data['dt_start'] = $this->input->post('event_date_start') . " " . $this->input->post('event_time_start');
			$data['dt_end'] = $this->input->post('event_date_end') . " " . $this->input->post('event_time_end');
			$this->event_model->add_event( $data );
			$ra['err']  = false;
		}
		
		echo json_encode( $ra );		
	}
	
	function ajax_update_event()
	{
		$ra = array(
			'err' => true,
			'msg' => 'nothing happened',
			'data' => $_POST
			);


		if( $this->input->post('title')) {
			$this->load->model('event_model');
			$id = $this->input->post('id');
			$data['submitter_id'] = 1;
			$data['title'] = $this->input->post('title');
			$data['venue'] = $this->input->post('venue');
			$data['body'] = $this->input->post('body');
			$data['dt_start'] = $this->input->post('event_date_start') . " " . $this->input->post('event_time_start');
			$data['dt_end'] = $this->input->post('event_date_end') . " " . $this->input->post('event_time_end');
			if( $id ) {
				$this->event_model->update_event( $id, $data );
				$ra['err']  = false;
			} else {
				
			}
		}

		echo json_encode( $ra );		
	}
		
	function ajax_get_event()
	{
		$ra = array(
			'err' => true,
			'msg' => 'nothing happened',
			'data' => $_POST
			);

		if( $this->input->post('id')) {
			$this->db->where('id', $this->input->post('id'));
			$res = $this->db->get('events')->result_array();
			$ra['err'] = false;
			$ra['msg'] = '';
			$ra['data'] = $res[0];
		}
		
		echo json_encode( $ra );
	}
	
	// --------------------------------
	// P R I V A T E  F U N C T I O N S
	// --------------------------------	
	private function _adjust_date( $month, $year )
	{
		$a = array();  
		$a[0] = $month;
		$a[1] = $year;

		while ($a[0] > 12) {
		    $a[0] -= 12;
		    $a[1]++;
		}

		while ($a[0] <= 0) {
		    $a[0] += 12;
		    $a[1]--;
		}

		return $a;		
	}

	private function _days_in_week ($weekNumber, $year) {
		// Count from '0104' because January 4th is always in week 1
		// (according to ISO 8601).
		$time = strtotime($year . '0104 +' . ($weekNumber - 1)
		                  . ' weeks');
		// Get the time of the first day of the week
		$mondayTime = strtotime('-' . (date('w', $time) - 1) . ' days',
		                        $time);
		// Get the times of days 0 -> 6
		$dayTimes = array ();
		for ($i = 0; $i < 7; ++$i) {
		  $dayTimes[] = strtotime('+' . $i . ' days', $mondayTime);
		}
		// Return timestamps for mon-sun.
		return $dayTimes;
	}

	private function _get_iso_monday($year, $week) {
		# check input
		$year = min ($year, 2038); $year = max ($year, 1970);
		$week = min ($week, 53); $week = max ($week, 1);
		# make a guess
		$monday = mktime (1,1,1,1,7*$week,$year);
		# count down to week
		//while (strftime('%V', $monday) != $week)
		//        $monday -= 60*60*24*7;
		# count down to monday
		while (strftime('%u', $monday) != 1)
		        $monday -= 60*60*24;
		# got it
		return $monday;
	}

	function _days_in_month( $month, $year )
	{
		$daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

		if ($month < 1 || $month > 12) {
		    return 0;
		}

		$d = $daysInMonth[$month - 1];

		if ($month == 2) {
		    if ($year%4 == 0) {
		        if ($year%100 == 0) {
		            if ($year%400 == 0) {
		                $d = 29;
		            }
		        }
		        else {
		            $d = 29;
		        }
		    }
		}
		return $d;
	 }
}

