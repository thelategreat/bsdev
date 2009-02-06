<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Calendar extends Controller 
{

	protected $day_names = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
	protected $month_names = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");

	function Calender()
	{
		parent::Controller();
	}
	
	function index()
	{
		$this->view();
	}
	
	
	function view()
	{
	    $s = '';

	    $today = getdate(time());
	
		$filter = array(
			'year' => $today['year'],
			'month' => $today['mon'],
			'day' => $today['mday']
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
	    $s .= '<tbody><tr><th>' . $week_no . '</th>';  
	    while( $d <= $days_in_month ) {
	      for( $i = 0; $i < 7; $i++ ) {
	        if( $d < 1 ) {
	          $s .= '<td class="odd"><p class="day-num">' . ($days_in_last_month + $d) .'</p></td>';
	        }
	        elseif( $d <= $days_in_month ) {
	          $s .= '<td class="even ' . ($d == $today['mday'] ? 'today' : '') . '"><p class="day-num">' . $d . '</p></td>';
	        } elseif( $d > $days_in_month ) {
	          $s .= '<td class="odd"><p class="day-num">' . ($d - $days_in_month) . '</p></td>';
	        }
	        $d++;
	      }
	      $s .= '</tr>';
	      if( $d <= $days_in_month ) {
	        $week_no++;
	        $s .= '<tr><th>' . $week_no . '</th>';
	      }
	    }
	    $s .= '</tbody>';
	    $s .= '</table>';
	
	
		$tabs = '<div class="tabs">';
		$tabs .= '<ul>';
		$tabs .= '<li><a href="" class="selected">Month</a></li>';
		$tabs .= '<li><a href="" >Week</a></li>';
		$tabs .= '<li><a href="" >Day</a></li>';
		$tabs .= '</ul>';
		$tabs .= '</div>';
	
		$pg_data = array(
			'title' => 'Admin - Calendar',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'content' => $this->load->view('admin/calendar', array('cal' => $s, 'tabs' => $tabs ), true ),
			'footer' => $this->load->view('layouts/standard_footer', '', true)
		);
		$this->load->view('layouts/admin_page', $pg_data );		
	}
	
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

