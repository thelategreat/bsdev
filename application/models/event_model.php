<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 */
class event_model extends CI_Model
{
	/**
	 *
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 *
	 */
	function get_event_old( $id )
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
		$query = "SELECT
					events.id,
					events.title,
					events.dt_start,
					media.uuid
				FROM
					events
				LEFT JOIN media_map ON media_map.path = concat('/event/', events.id)
				LEFT JOIN media ON media.id = media_map.media_id
				WHERE
					TRUE
				AND dt_start > now()
				ORDER BY
				    events.dt_start
				LIMIT $count";

		return $this->db->query( $query );
	}

	/**
	 *
	 */
	function get_events( $filter )
	{
		$query = "SELECT e.id, e.created_on, e.updated_on, e.submitter_id, e.title, e.venue, e.dt_start, e.dt_end, e.body, e.rating, ec.category, e.audience, e.event_ref, e.venue_ref ";
		$query .= " FROM events AS e, event_categories as ec WHERE e.category = ec.id ";
		if (isset($filter['type'])) {
			$query .= " AND ec.category = '{$filter['type']}' ";
		}

		switch( $filter['view']) {
			case 'day':
			$start = sprintf('%04d-%02d-%02d', $filter['year'],$filter['month'],$filter['day']);
			$query .= " AND dt_start BETWEEN DATE('$start') AND DATE_ADD(DATE('$start'), INTERVAL + 1 day)";
			break;
			case 'week':
			//$start = date('Y-m-d', strtotime($filter['year'] . 'W' . $filter['week'] . '0'));
			$start = sprintf('%04d-%02d-%02d', $filter['year'],$filter['month'],$filter['day']);
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
		$term = $this->db->escape_like_str($q);
		$offs = (($page - 1) * $limit);

		$sql =<<<EOF
			SELECT e.id, e.title, e.dt_start, e.audience, ea.audience as audience_name, e.category, ec.category AS category_name
			FROM events as e, event_audience as ea, event_categories as ec
				WHERE (title LIKE '%$term%' OR body LIKE '%$term%')
				AND ea.id = e.audience AND ec.id = e.category
				LIMIT $limit OFFSET $offs
EOF;

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

	function get_future_dates( $category, $title )
	{
		$sql = "SELECT * FROM events WHERE category = " . 
			$this->db->escape($category) . 
			" AND title = " . 
			$this->db->escape($title) . 
			" AND dt_start >= NOW()";

		return $this->db->query($sql)->result();

	}

	function get_extra_info( $event )
	{
		$extra = array();
		switch( $event->category ) {
			// film
			case 1:
			$res = $this->db->query('SELECT director, country, year, rating, running_time, imdb_link FROM films WHERE id = ' . $event->event_ref );
			foreach( $res->result_array() as $row ) {
				foreach( $row as $k => $v ) {
					$extra[$k] = $v;
				}
			}
			break;
		}

		return $extra;
	}

	/** 
		Get Categories - Retrieve event categories
		@param int or array of ints of category IDs
		@return result
	*/
	function get_categories( $id = false )
	{
		$sql = "SELECT * FROM event_categories WHERE true";
		if ( $id ) {
			if (is_array($id)) {
				$id = implode(',', $id);
			}
			$sql .= " AND id IN ({$id})";
		} 
		$sql .= " ORDER BY category ASC";
		return $this->db->query($sql);
	}

	function get_audiences( )
	{
		return $this->db->query("SELECT * FROM event_audience");
	}

	/* Gets the details about a specific event - 
	 * used in the JSON callback for movie details so this includes a join on the films table */
	function get_event($id) {
		$sql = "SELECT
					*
				FROM
					events
				LEFT JOIN event_categories ON event_categories.id = events .category
				LEFT JOIN event_audience ON events.audience = event_audience.id
				LEFT JOIN media_map ON media_map.path = concat('/event/', events.id)
				LEFT JOIN media ON media.id = media_map.media_id
				LEFT JOIN films ON films.id = events .event_ref
				WHERE
						events .id = '$id'";
		
		$result = $this->db->query($sql);
		$return = $result->row();

		$sql = "SELECT
					articles.id,
					articles.title,
					articles.author,
					articles.excerpt
				FROM
					articles_events
				LEFT JOIN articles ON articles_events.articles_id = articles.id
				WHERE
					events_id = {$id}";
		$result = $this->db->query($sql)->result();
		$return->associated_essays = $result;

		return $return;
	}

}
