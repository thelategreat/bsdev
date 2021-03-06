<?php

/**
 * Return an array that represents the given month and year. The array is
 * two dimensional with each row representing a week of days. Each day
 * is an assoc array with the following keys:
 * num -> the day number
 * date -> the date as a string like 'dd/mm/yyyy'
 * The grid itself overlaps the previous and next months so it is always
 * 5 rows * 7 days. 
 * Other data can be added to the day assoc as needed.
 *
 */
function cal_gen_old( $month, $year )
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
					$cal[$week][$i]['date'] = $adt[1] . '-' . $adt[0] . '-' . ($d + $days_in_last_month);
					$cal[$week][$i]['day'] = ($days_in_last_month + $d);
	      }
	      elseif( $d <= $days_in_month ) {
					$cal[$week][$i]['num'] = $d;
					$cal[$week][$i]['date'] = $year . '-' . $month . '-' . $d;
					$cal[$week][$i]['day'] = $d;
	    	} 
				elseif( $d > $days_in_month ) {
					$cal[$week][$i]['num'] = ($d - $days_in_month);
					$adt = cal_adjust_date( $month + 1, $year );
					$cal[$week][$i]['date'] = $adt[1] . '-' . $adt[0] . '-' . ($d - $days_in_month);
					$cal[$week][$i]['day'] = ($d - $days_in_month);
	  		}
				$cal[$week][$i]['events'] = array();
	      $d++;
		}
		$week++;
	}

	return $cal;
}

/**
	Generate a one month calendar
	@param Start day
	@param Start month
	@param Start year
	@return Array of 5 week consisting of 7 days
*/
function cal_gen( $month, $year )
{    
	
	$cal = array( 5 );
	for ($i = 0; $i<5; $i++) {
		$cal[$i] = array();
	}

	$days_in_month = cal_days_in_month( CAL_GREGORIAN, $month, $year );
 	$date = getdate(mktime(12,0,0,$month,1,$year));

	$first = $date['wday'];

	$prev = cal_adjust_date($month-1,$year);
	$days_in_last_month = cal_days_in_month( CAL_GREGORIAN, $prev[0], $prev[1]);
	$next = cal_adjust_date($month+1,$year);
	$week_no = (int)date("W", mktime(12,0,0,$month,1,$year));

	$d = $date['mday'];		

    $week = 0;

    $day_of_week = $first;
    for ($j = 0; $j < 5; $j++) {
	    for( $i = 0; $i < 7; $i++ ) {
			// last month
			if ($d > $days_in_month) {
				$d = 0;
				$month++;
				if ($month > 12) {
					$month = 0;
					$year++;
				}
			}
			$date_string = date('Y-m-d', strtotime("{$year}-{$month}-{$d}"));;

			$cal[$week][$date_string]['num'] = $d;
			$cal[$week][$date_string]['date'] = $year . '-' . $month . '-' . $d;
			$cal[$week][$date_string]['day'] = $day_of_week++ % 7; 
			$cal[$week][$date_string]['events'] = array();
			$d++;
		}

		$week++;
	}

	return $cal;
}


/**
	Generate a one week calendar
	@param Start day
	@param Start month
	@param Start year
	@return Array of 1 week consisting of 7 days
*/
function week_cal_gen( $day, $month, $year )
{    
	
	$cal = array( 1 );
	$cal[0] = array();

	$days_in_month = cal_days_in_month( CAL_GREGORIAN, $month, $year );
 	$date = getdate(mktime(12,0,0,$month,$day,$year));

	$first = $date['wday'];

	$prev = cal_adjust_date($month-1,$year);
	$days_in_last_month = cal_days_in_month( CAL_GREGORIAN, $prev[0], $prev[1]);
	$next = cal_adjust_date($month+1,$year);
	$week_no = (int)date("W", mktime(12,0,0,$month,1,$year));

	$d = $date['mday'];		

    $week = 0;

    $day_of_week = $first;
    for( $i = 0; $i < 7; $i++ ) {
		// last month
		if ($d > $days_in_month) {
			$d = 0;
			$month++;
			if ($month > 12) {
				$month = 0;
				$year++;
			}
		}
		$date_string = date('Y-m-d', strtotime("{$year}-{$month}-{$d}"));;

		$cal[$week][$date_string]['num'] = $d;
		$cal[$week][$date_string]['date'] = $year . '-' . $month . '-' . $d;
		$cal[$week][$date_string]['day'] = $day_of_week++ % 7; 
		$cal[$week][$date_string]['events'] = array();
		$d++;
	}

	return $cal;
}

/**
 * Adjust month year to accommodate negative and oversize values.
 * Returns an array [month,year]
 */
function cal_adjust_date( $month, $year )
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

// not sure :/
function cal_days_in_week ($weekNumber, $year) 
{
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

// er... umm... i am kinda drunk and on a train right now
function cal_get_iso_monday($year, $week) 
{
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

function draw_calendar($month,$year,$events){

	/* draw table */
	$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

	/* table headings */
	$headings = array('SUN','MON','TUE','WED','THU','FRI','SAT');
	$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

	/* days and weeks vars now ... */
	$running_day = date('w',mktime(0,0,0,$month,1,$year));
	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	/* row for week one */
	$calendar.= '<tr class="calendar-row">';

	/* print "blank" days until the first of the current week */
	for($x = 0; $x < $running_day; $x++):
		$calendar.= '<td class="calendar-day-np"> </td>';
		$days_in_this_week++;
	endfor;

	/* keep going with days.... */
	for($list_day = 1; $list_day <= $days_in_month; $list_day++):
		$calendar.= '<td class="calendar-day"><div class="day">';
			/* add in the day number */
			$calendar.= '<div class="day-number">'.$list_day.'</div>';

			/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
			$d = date('Y-m-d', mktime(0,0,0,$month,$list_day,$year));
			if (isset($events[$d])) {
				foreach ($events[date('Y-m-d', mktime(0,0,0,$month,$list_day,$year))] as $event) {
					$calendar.= "<div class='event'><time>" . date('h:i a', strtotime($event->start_time)) . "</time><a href='/films/view/{$event->films_id}'><h2>{$event->title}</h2></a></div>"; 
				}
			}
			
		$calendar.= '</div></td>';
		if($running_day == 6):
			$calendar.= '</tr>';
			if(($day_counter+1) != $days_in_month):
				$calendar.= '<tr class="calendar-row">';
			endif;
			$running_day = -1;
			$days_in_this_week = 0;
		endif;
		$days_in_this_week++; $running_day++; $day_counter++;
	endfor;

	/* finish the rest of the days in the week */
	if($days_in_this_week < 8):
		for($x = 1; $x <= (8 - $days_in_this_week); $x++):
			$calendar.= '<td class="calendar-day-np"> </td>';
		endfor;
	endif;

	/* final row */
	$calendar.= '</tr>';

	/* end the table */
	$calendar.= '</table>';
	
	/* all done, return result */
	return $calendar;
}