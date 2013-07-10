<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class films_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();
    $this->db->db_select();
  }

/**
	Get the media for a film 
 */
function get_film_media( $id )
{
	$sql = "SELECT m.uuid FROM 
			media_map as mm, 
			media as m 
			WHERE mm.path = '/films/" . intval($id) . 
			"' AND m.id = mm.media_id ORDER BY mm.sort_order";
	$result = $this->db->query($sql)->result();

	if ($result) {
		return ($result[0]);
	}

	return $result;
}

    
}
