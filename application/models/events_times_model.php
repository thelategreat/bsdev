<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 */
class events_times_model extends CI_Model
{
	/**
	 *
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	   Get a times for an event
	   @param event ID 
	   @return array of start and end times or false
	*/
	function get_event_times( $id ) {
		if (!is_numeric($id)) return false;

		$sql = "SELECT * from events_times where events_id = '$id'
				ORDER BY start_time";
		$result = $this->db->query($sql)->result();

		return $result;
	}

	/** 
		Get an array of times for a film
		@param Film ID
		@return array of start and end times or false
	*/
	function get_film_times( $id ) {
		if (!is_numeric($id)) return false;

		$sql = "SELECT * from events_times where films_id = '$id'
				ORDER BY start_time";
		$result = $this->db->query($sql)->result();

		return $result;

	}
	

}
