<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Calendar extends Admin_Controller 
{

	protected $day_names = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
	protected $month_names = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");

	function Calendar()
	{
		parent::__construct();
		
		$this->load->model('event_model');
    //$this->output->enable_profiler( TRUE );
	}
	
	function index()
	{
		$this->gen_page('Admin - Calendar', 'admin/calendar/index', array());
	}

	function get_events() {
		$start 	= $this->input->post('start');
		$end 	= $this->input->post('end');
		
		$events = $this->event_model->get_calendar_events($start, $end);

		echo json_encode($events);
	}
	
	
	/** TODO
	 * - year transition with week number is broken
	 */
	/*
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
		
		// ---------
		// T A B L E
		$s .= '<table width="100%">';
		
		// -------
		// H E A D
		$s .= '<thead>';
		$s .= '<tr>';
    $s .= '<td align="left"><h3>' . $this->month_names[$month-1] . ' ' . $year . '</h3></td>';
		$s .= '<td align="right">';
		$s .= '<button title="Last Month" onclick="window.location=\'/admin/calendar/month/year/'.$prev[1].'/month/'.$prev[0].'\'"><img src="/img/admin/32-arrow-right.png" width="12px"/></button>';
		$s .= '<button title="Next Month" onclick="window.location=\'/admin/calendar/month/year/'.$next[1].'/month/'.$next[0].'\'"><img src="/img/admin/32-arrow-left.png" width="12px"/></button>';
		$s .= '</td>';
		$s .= '</tr>';
		$s .= '</table>';

    $s .= '<table class="cal">';
    $s .= '<thead>';
    $s .= '<tr>';
    $s .= '<td class="nav">';
    $s .= '</td>';
    foreach( $this->day_names as $day ) {
			$s .= "<th style='text-align: center;'>$day</th>";    
		}
		$s .= '</tr>';
    $s .= '</thead>';

		// -------
		// B O D Y
    $s .= '<tbody><tr><th><a href="/admin/calendar/week/'.$week_no.'">' . $week_no . '</a></th>';  
    while( $d <= $days_in_month ) {
      for( $i = 0; $i < 7; $i++ ) {
				$thisdate = sprintf("%04d-%02d-%02d", $year, $month, $d);
        if( $d < 1 ) {
					// before month start
          $s .= '<td class="' . (($i == 0 || $i == 6) ? 'weekend' : '') . '"><p class="day-num">' . ($days_in_last_month + $d) . '</p>';
		  		$thisdate = sprintf("%04d-%02d-%02d",$year, ($month - 1), $days_in_last_month + $d);
        }
        elseif( $d <= $days_in_month ) {
					// in month
		  		$href = "/admin/calendar/day/year/$year/month/$month/day/$d";
          $s .= '<td width="14%" class="even ' . ($d == $today['mday'] ? 'today' : (($i == 0 || $i == 6) ? 'weekend' : '')) . '"><p class="day-num"><a href="'.$href.'">' . $d . '</a></p>';
        } elseif( $d > $days_in_month ) {
					// after month end
          $s .= '<td class="'.(($i == 0 || $i == 6) ? 'weekend' : '').'"><p class="day-num">' . ($d - $days_in_month) . '</p>';
		  		$thisdate = sprintf("%04d-%02d-%02d",$year, ($month + 1),($d - $days_in_month));
        }

				//$s .= $thisdate;
				
				$res = $this->db->query("SELECT count(*) as cnt, ec.category FROM events AS e, event_categories AS ec WHERE e.category = ec.id AND DATE(dt_start) = '$thisdate' GROUP BY category");
				foreach( $res->result() as $row ) {
		  		$s .= '<img class="icon" style="background-color: #88f" src="/img/icons/icon_'.$row->category.'.gif" /> (' . $row->cnt . ')<br/>';
				}
				$s .= '</td>';
        $d++;
      }
      $s .= '</tr>';
      if( $d <= $days_in_month ) {
        $week_no++;
				if( $week_no > 52 ) {
					$week_no =  1;
				}
        $s .= '<tr><th><a href="/admin/calendar/week/'.$week_no.'">' . $week_no . '</th>';
      }
    }
    $s .= '</tbody>';
    $s .= '</table>';
	
		// -------
		// T A B S
		// -------
		$tabs = '<div class="tabs">';
		$tabs .= '<ul>';
		$tabs .= '<li><a href="/admin/calendar/month" class="selected">Month</a></li>';
		$tabs .= '<li><a href="/admin/calendar/week" >Week</a></li>';
		//$tabs .= '<li><a href="/admin/calendar/day" >Day</a></li>';
		$tabs .= '</ul>';
		$tabs .= '</div>';

		$this->load->model('event_model');

		$data = array(
			'cal' => $s,
			'tabs' => $tabs,
			'events' => $this->event_model->get_events( $filter )
		);
		
		$this->gen_page('Admin - Calendar', 'admin/calendar/calendar', $data );
	}
	*/

	/** TODO
	 */
	/*
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
			$filter['week'] = strftime('%W', time());
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
		
		//var_dump( $filter );
		//exit;
		
		$events = $this->event_model->get_events( $filter );

		$pyear = $filter['year'];
		$nyear = $filter['year'];
		$pweek = $filter['week']-1;
		if( $pweek == 0 ) {
			$pweek = 52;
			$pyear--;
		}
		$nweek = $filter['week']+1;
		if( $nweek > 52 ) {
			$nweek = 1;
			$nyear++;
		}
			
    $s = '';
    $s .= '<h3>Week starting: ' . date('l, F d, Y', $monday) . " <span class='small'>(week #". $filter['week'] . ')</span></h3>';
    $s .= '<div id="weekdiv" class="scrollable" style="position: relative; width: 700px">';
    
		$s .= '<table class="cal cal_week" >';
		// header
    $head = '<thead class="fixed-table-header">';
    $head .= '<tr>';
    $head .= '<td class="nav">';
    $head .= '<a href="/admin/calendar/week/'.$pweek.'/year/'.$pyear.'" title="Prev"><img src="/img/admin/16-arrow-left.png"/></a>&nbsp;';
    $head .= '<a href="/admin/calendar/week/'.$nweek.'/year/'.$nyear.'" title="Next"><img src="/img/admin/16-arrow-right.png" /></a>';
    $head .= '</td>';
    for( $i = 0; $i < 7; $i++ ) {
			if( $filter['day'] + $i > $days_in_month ) {
				$head .= "<th>" . $this->day_names[$i] . " ". ($filter['day']+$i-$days_in_month) . "</th>";				
			} else {
				$head .= "<th>" . $this->day_names[$i] . " " . ($filter['day'] + $i) . "</th>";				
			}
			$d++;
		}
    $head .= '</tr>';
    $head .= '</thead>';

		$s .= $head;
		// body
    $s .= '<tbody class="scroll-table-body">';
    for( $i = 0; $i < 24; $i++ ) {
      $s .= '<tr>';
      $s .= '<th>' . ($i > 12 ? $i - 12 : ($i == 0 ? 12 : $i)) . '&nbsp;'. ($i > 11 ? "pm" : "am") . '</th>';
      for( $d = 0; $d < 7; $d++ ) {
        $s .= '<td>';
				if( $d == 1 && $i == 5 ) {
				}
				$s .= '</td>';
      }
      $s .= '</tr>';
    }
    $s .= '</tbody>';
		$s .= $head;
    $s .= '</table>';
	
		$lastevent = null;
		foreach( $events->result() as $event ) {
			$st = getdate(strtotime($event->dt_start));
			$stime = $st['hours'] + ($st['minutes'] / 60);
			$en = getdate(strtotime($event->dt_end));
			$etime = $en['hours'] + ($en['minutes'] / 60);
			// top: 45, left: 60, width: 65, height variable on duration (1 hr = 25)

			$left = 60 + ($st['wday'] * 91);
			$top = 45 + ($stime * 40);
			$height = 40 + (($etime - $stime - 1) * 40);
			$width = 65;

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
			// try and indicate stacked events if the times are the same
			if( $lastevent && strtotime($lastevent->dt_start) == strtotime($event->dt_start)) {
				$top += 5;
				$left += 5;
			}
			$s .= "<div class='rounded' style='font-size: 0.8em; line-height: 1.0em; opacity: 0.6; position: absolute; left: ${left}px; top: ${top}px; z-index: 100; width: ${width}px; height: ${height}px; background-color: ${color}; border: 1px solid #f00'>";
			$s .= '<a href="#" onclick="edit_event('.$event->id.')" style="font-weight: bold; color: #000;">';
			$s .= date('g:ia',strtotime($event->dt_start)) . '</a><br/>' . $event->title . '</div>';		
			$lastevent = $event;	
		}
		
	  $s .= '</div>';
		
		$tabs = '<div class="tabs">';
		$tabs .= '<ul>';
		$tabs .= '<li><a href="/admin/calendar/month" >Month</a></li>';
		$tabs .= '<li><a href="/admin/calendar/week" class="selected">Week</a></li>';
		//$tabs .= '<li><a href="/admin/calendar/day" >Day</a></li>';
		$tabs .= '</ul>';
		$tabs .= '</div>';

	
		$data['cal'] = $s;
		$data['tabs'] = $tabs;
		$data['events'] = $events;
	
		$this->gen_page('Admin - Calendar', 'admin/calendar/calendar', $data );
	}*/
	
	// TODO
	/*
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
		$pday = getdate($thisday - (24*60*60));
		$nday = getdate($thisday + (24*60*60));
		$purl = "/year/" . $pday['year'] . "/month/" . $pday['mon'] . '/day/' . $pday['mday'];
		$nurl = "/year/" . $nday['year'] . "/month/" . $nday['mon'] . '/day/' . $nday['mday'];
	
		$s = '';

    $s = '';
    $s .= '<div class="scrollable">';
    $s .= '<table class="cal cal_day">';
    $s .= '<thead>';
    $s .= '<tr>';
    $s .= '<td class="nav">';
    $s .= '<a href="/admin/calendar/day'.$purl.'" title="Prev"><img src="/img/16-arrow-left.png"/></a>&nbsp;';
    $s .= '<a href="/admin/calendar/day'.$nurl.'" title="Next"><img src="/img/16-arrow-right.png" /></a>';
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
	
		$this->gen_page('Admin - Calendar', 'admin/calendar/calendar', $data );
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
	
	function add_event()
	{
		redirect("/admin/calendar");
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
			$data['audience'] = $this->input->post('audience');
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
			$data['audience'] = $this->input->post('audience');
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
	
	// ------------------------
	// P R I V A T E  F U N C S
	// ------------------------
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
	 */
}

