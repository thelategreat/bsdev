<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

/**
 * 
 */
class event_model extends Model
{
	/**
	 * 
	 */
	function __constructor()
	{
		parent::Model();
	}
	
	/**
	 * 
	 */
	function get_event( $id )
	{
		$this->db->where('id', intval($id) );
		return $this->db->get('events');
	}
	
	/**
	 * 
	 */
	function get_event_media( $id )
	{
		$res = $this->db->query("SELECT m.uuid FROM media_map as mm, media as m WHERE mm.path = '/event/" . intval($id) . "' AND m.id = mm.media_id ORDER BY mm.sort_order");
		return $res;
	}
	
	/**
	 * 
	 */
	function get_events( $filter )
	{
		$start = sprintf('%04d-%02d-%02d', $filter['year'],$filter['month'],$filter['day']);
		$end = sprintf('%04d-%02d-%02d', $filter['year'],$filter['month'],$filter['day']);
		$view = $this->db->escape_str($filter['view']);
		
		$query =<<<EOF
SELECT * FROM events 
	WHERE dt_start BETWEEN date('$start') AND DATE_ADD(DATE('$end'), INTERVAL + 1 $view)
	ORDER BY dt_start ASC
EOF;
		return $this->db->query( $query );
	}
	
	/**
	 * date in long format 
	 */
	function get_events_by_date_range( $start, $end )
	{
		$start = date('Y-m-d', $start);
		$end = date('Y-m-d', $end);
		
		$query =<<<EOF
SELECT * FROM events 
	WHERE dt_start BETWEEN date('$start') AND DATE('$end')
	ORDER BY dt_start ASC
EOF;
		return $this->db->query( $query );
	}
	
	/**
	 * Search from the front end
	 */
	function search_events($q)
	{		
		$sql = "SELECT * FROM events WHERE (title LIKE '%" . $this->db->escape_like_str($q) . "%'";
		$sql .= " OR body LIKE '%" . $this->db->escape_like_str($q) . "%')";
		$sql .= " AND dt_start >= NOW()";

		return $this->db->query( $sql );		
	}
	
	/**
	 * 
	 */
	function add_event( $data )
	{
		// required
		$this->db->set('title', $data['title']);
		$this->db->set('venue', $data['venue']);
		$this->db->set('category', $data['category']);
		$this->db->set('audience', $data['audience']);
		$this->db->set('body', $data['body']);
		$this->db->set('submitter_id', $data['submitter_id']);
		$this->db->set('dt_start', $data['dt_start']);
		$this->db->set('dt_end', $data['dt_end']);
		$this->db->set('created_on', 'NOW()', false );
		$this->db->set('updated_on', 'NOW()', false );
		
		$this->db->insert('events');
		return $this->db->insert_id();
	}
	
	/**
	 * 
	 */
	function update_event( $id, $data )
	{
		$this->db->set('title', $data['title']);
		$this->db->set('venue', $data['venue']);
		$this->db->set('category', $data['category']);
		$this->db->set('audience', $data['audience']);
		$this->db->set('body', $data['body']);
		$this->db->set('dt_start', $data['dt_start']);
		$this->db->set('dt_end', $data['dt_end']);
		$this->db->set('updated_on', 'NOW()', false );
		
		$this->db->where('id', $id );
		$this->db->update('events' );
		
	}
	
	/**
	 * 
	 */
	function delete_event( $data )
	{
		$this->db->where('id', $data['id']);
		$this->db->delete( 'events' );
	}
	
}