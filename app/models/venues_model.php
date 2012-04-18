<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * generic tag model.
 * this is meant to be inherited and added tagging support to a model.
 */
class Venues_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}


  function venues_list()
  {
    return $this->db->query("SELECT v.id, v.venue, locations.name as location FROM venues as v LEFT JOIN locations ON v.location_id = locations.id");
    
  }

  function venues_select_list( $default = 0 )
  {
    $res = $this->venues_list();
    $s = '<select name="venues" id="venues-select">';
    foreach( $res->result() as $row ) {
			$s .= '<option value="' . $row->id . '" ';
			if( $default == $row->id ) {
				$s .= " selected ";
			}
			$s .= '>' . $row->venue . '</option>';
    }
    $s .= '</select>';
    return $s;
  }

  function locations_select_list( $default = 0 )
  {
    $res = $this->db->query( "SELECT id, name FROM locations ORDER BY name" );
    $s = '<select name="locations" id="locations-select">';
    $s .= '<option value="0">None</option>';
    foreach( $res->result() as $row ) {
			$s .= '<option value="' . $row->id . '" ';
			if( $default == $row->id ) {
				$s .= " selected ";
			}
			$s .= '>' . $row->name . '</option>';
    }
    $s .= '</select>';
    return $s;
  }

}

