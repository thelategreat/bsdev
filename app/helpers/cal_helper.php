<?php
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