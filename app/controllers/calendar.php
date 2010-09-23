<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   


class Calendar extends MY_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::Controller();
		
		$this->config->load('site_config');
		
		$this->load->library('email');
		
		$this->load->helper('email');
		$this->load->helper('media');
		$this->load->helper('misc');
		$this->load->helper('cal');
		
		$this->load->model('maillist_model');
	}


	function index()
	{
		$this->do_view('poster');
	}
	
	function view()
	{
		$this->do_view($this->uri->segment(3));
	}
	
	function do_view( $which )
	{
		$view_menu = <<<EOF
		View:
		<a href="/calendar/view/poster">poster</a> |
		<a href="/calendar/view/month">month</a> |
		<a href="/calendar/view/list">list</a> 
		<p/>
EOF;
		
		$today = getdate(time());
		$month = $today['mon'];
		$year = $today['year'];
		
		$view_data = array(
			'view_menu' => $view_menu,
			'month' => $month,
			'year' => $year,
			'cal' => $this->gen_cal($month,$year) 
		);
			
		$pg_data = $this->get_page_data('Bookshelf - calendar', 'calendar' );
		$pg_data['sidebar_left'] = NULL;
		
		if( $which == 'list') {
			$pg_data['content'] = $this->load->view('calendar/list_view', $view_data, true);
		} else if( $which == 'month' ) {
			$pg_data['content'] = $this->load->view('calendar/month_view', $view_data, true);
		} else {
			$pg_data['content'] = $this->load->view('calendar/poster_view', $view_data, true);			
		}
		$this->load->view('layouts/standard_page', $pg_data );				
	}
	
	protected function gen_cal( $month, $year )
	{    
		
		$cal = array( 5 );
		for( $i = 0; $i < 5; $i++ ) {
			$cal[$i] = array( 7 );
			for( $j = 0; $j < 7; $j++ ) {
				$cal[$i][$j] = array( 'num' => '' . (($i + 1) * $j));
			}
		}
		
		$days_in_month = cal_days_in_month( CAL_GREGORIAN, $month, $year );
    $date = getdate(mktime(12,0,0,$month,1,$year));

    $first = $date['wday'];
    $prev = cal_adjust_date($month-1,$year);
    $days_in_last_month = cal_days_in_month( CAL_GREGORIAN, $prev[0], $prev[1]);
    $next = cal_adjust_date($month+1,$year);
    $week_no = (int)date("W", mktime(12,0,0,$month,1,$year));
    $d = -$first + 1;		
		
		$week = 0;
		while( $d <= $days_in_month ) {
      for( $i = 0; $i < 7; $i++ ) {
				// last month
				if( $d < 1 ) {
					$cal[$week][$i]['num'] = ($days_in_last_month + $d);
					$adt = cal_adjust_date( $month - 1, $year );
					$cal[$week][$i]['date'] = ($days_in_last_month + $d) . "/$adt[0]/$adt[1]" ;
        }
        elseif( $d <= $days_in_month ) {
					$cal[$week][$i]['num'] = $d;
					$cal[$week][$i]['date'] = "$d/$month/$year";
      	} 
				elseif( $d > $days_in_month ) {
					$cal[$week][$i]['num'] = ($d - $days_in_month);
					$adt = cal_adjust_date( $month + 1, $year );
					$cal[$week][$i]['date'] = ($d - $days_in_month) . "/$adt[0]/$adt[1]" ;
    		}
	      $d++;
			}
			$week++;
		}
		return $cal;
	}
	
}