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
		$this->db->where('events.id', intval($id) );
		$this->db->join('event_audience', 'events.audience = event_audience.id');
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

	function get_next_events( $count = 10 )
	{
		$query = "SELECT * FROM events WHERE dt_start >= NOW() ";

		$query .= " ORDER BY dt_start ASC LIMIT $count";
		
		$query =<<< EOF
		select events.id, events.title, events.dt_start, media.uuid 
			FROM events, media_map, media 
			WHERE media_map.path = CONCAT('/event/', events.id) 
				AND media.id = media_map.media_id 
				AND events.dt_start >= NOW()
				ORDER BY events.dt_start LIMIT $count;
EOF;
		
		return $this->db->query( $query );
	}
	
	/**
	 * 
	 */
	function get_events( $filter )
	{
		$query = "SELECT e.id, e.created_on, e.updated_on, e.submitter_id, e.title, e.venue, e.dt_start, e.dt_end, e.body, e.rating, ec.category, e.audience, e.event_ref, e.venue_ref "; 
		$query .= " FROM events AS e, event_categories as ec WHERE e.category = ec.id ";
		
		switch( $filter['view']) {
			case 'day':
			$start = sprintf('%04d-%02d-%02d', $filter['year'],$filter['month'],$filter['day']);
			$query .= " AND dt_start BETWEEN DATE('$start') AND DATE_ADD(DATE('$start'), INTERVAL + 1 day)";
			break;
			case 'week':
			$start = date('Y-m-d', strtotime($filter['year'] . 'W' . $filter['week'] . '0'));
			$query .= " AND dt_start BETWEEN DATE('$start') AND DATE_ADD(DATE('$start'), INTERVAL + 1 week)";
			break;
			default:
			$start = sprintf('%04d-%02d-%02d', $filter['year'],$filter['month'],1);
			$query .= " AND dt_start BETWEEN DATE('$start') AND DATE_ADD(DATE('$start'), INTERVAL + 1 month)";
		}
		
		/*
		$start = sprintf('%04d-%02d-%02d', $filter['year'],$filter['month'],$filter['day']);
		$end = sprintf('%04d-%02d-%02d', $filter['year'],$filter['month'],$filter['day']);
		$view = $this->db->escape_str($filter['view']);
		
		$query =<<<EOF
SELECT * FROM events 
	WHERE dt_start BETWEEN date('$start') AND DATE_ADD(DATE('$end'), INTERVAL + 1 $view)
	ORDER BY dt_start ASC
EOF;

		*/
		
		$query .= " ORDER BY dt_start ASC";
		
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
	function search_events($q, $page = 1, $limit = 10 )
	{		
		$sql = "SELECT * FROM events WHERE (title LIKE '%" . $this->db->escape_like_str($q) . "%'";
		$sql .= " OR body LIKE '%" . $this->db->escape_like_str($q) . "%')";
		//$sql .= " AND dt_start >= NOW()";

		$sql .= " LIMIT " . $limit;
		$sql .= " OFFSET " . (($page - 1) * $limit);

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
		$this->db->set('event_ref', $data['event_ref']);
		$this->db->set('venue_ref', $data['venue_ref']);
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
		$this->db->set('event_ref', $data['event_ref']);
		$this->db->set('venue_ref', $data['venue_ref']);
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
	
	function get_categories( )
	{
		return $this->db->query("SELECT * FROM event_categories");
	}

	function get_audiences( )
	{
		return $this->db->query("SELECT * FROM event_audience");		
	}
	
}