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
	$sql = "SELECT media.uuid, slot FROM 
			media_map LEFT JOIN media ON media_map.media_id = media.id
			WHERE media_map.path = '/films/{$id}'";
	$result = $this->db->query($sql)->result();

	if ($result) {
		return ($result);
	}

	return $result;
}

/**
	Get an individual film by ID
	@param ID
*/
function get_film( $id ) 
{
	if (!is_numeric($id)) return false;
	$sql = "SELECT *, title AS name FROM films WHERE id = '$id'";
	$query = $this->db->query($sql);
	$result = $query->result();

	if ($result) {
		$return = $result[0];
		$return->type = 'film';
		$return->object_type = 'film';
		$return->media = self::get_film_media( $id );
		$return->showtimes = self::get_upcoming_showtimes( $id );
		return $return;
	}

	return false;
}

function get_upcoming_showtimes( $id ) 
{
	$sql = "SELECT * FROM events_times WHERE films_id = '{$id}' 
			AND start_time > date_sub(NOW(), INTERVAL 2 WEEK)
			ORDER BY start_time 
			";
	$query = $this->db->query($sql);
	$results = $query->result();

	return $results;
}

    
}
