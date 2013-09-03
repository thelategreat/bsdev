<?php
/* A collection of array helper functions */

if (!defined('BASEPATH')) exit('No direct script access allowed');   



/**
	oddElements
	Return on the odd elements of an array
	@param array 
	@return array or null
*/
function oddElements($array) {
	if (!is_array($array)) return null;
	if (count($array) < 1) return null;

	for ($i = 1; $i<count($array); $i+=2) {
		$return[] = $array[$i];
	}

	return $return;
}


/**
	evenElements
	Return on the even elements of an array
	@param array 
	@return array or null
*/
function evenElements($array) {
	if (!is_array($array)) return null;
	if (count($array) < 1) return null;

	for ($i = 0; $i<count($array); $i+=2) {
		$return[] = $array[$i];
	}

	return $return;
}
?>