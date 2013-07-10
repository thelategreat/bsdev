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

  /**
    Lookup a venue name by ID
    @param Venue ID
    @return Venue name
    */
  function get_venue( $id ) {
    $sql = "SELECT name FROM venues WHERE id = '$id'";
    $query = $this->db->query($sql);
    $result = $query->row();

    return $result;
  }


  /**
    Venue List
    Get a simple list of venues
  */
  function venues_list()
  {
    $sql = "
      SELECT v.*, l.name as location_name
      FROM venues v
      LEFT JOIN locations l ON v.locations_id = l.id
    ";
    $query = $this->db->query($sql);
    $result = $query->result();

    return $result;
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

  function locations_list()
  {
    $sql = "SELECT id, name FROM locations 
            ORDER BY name";
    $query = $this->db->query($sql);
    $result = $query->result();

    return $result;
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

