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
function cal_gen( $month, $year )
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

/*
function cal_days_in_month( $month, $year )
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