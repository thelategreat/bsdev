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
		Get the media for an event 
	 */
	function get_event_media( $id )
	{
		$sql = "SELECT m.uuid FROM 
				media_map as mm, 
				media as m 
				WHERE mm.path = '/event/" . intval($id) . 
				"' AND m.id = mm.media_id ORDER BY mm.sort_order";
		$result = $this->db->query($sql)->result();

		if ($result) {
			return $result[0];
		}

		return $result;
	}

	/**
	Gets a list of upcoming events
	@param (optional) Maximum number of events
	@param (optional) Latest date of event
	@return Array of event objects
	*/
	function get_upcoming_events( $count = 10, $end_date = false )
	{
		$query = "SELECT
					events_times.*, events.*, 
					event_categories.name as category,
					event_audiences.name as audience
				FROM
					events_times
					LEFT JOIN events ON events.id = events_times.events_id
					LEFT JOIN event_categories ON events.categories_id = event_categories.id
					LEFT JOIN event_audiences ON events.audiences_id = event_audiences.id
					LEFT JOIN ratings ON events.ratings_id = ratings.id
				WHERE
					TRUE
				AND films_id IS NULL
				AND events_times.start_time > NOW() ";
				if ($end_date) {
					$query .= " AND events_times.start_time < '$end_date' ";
				}
				$query .= "
				ORDER BY
					events_times.start_time
				LIMIT $count";

		return $this->db->query( $query )->result();
	}

	/**
	Gets a list of upcoming films
	@param (optional) Maximum number of events
	@param (optional) Latest date of event
	@return Array of event objects
	*/
	function get_upcoming_films( $count = 10, $end_date = false)
	{
		$query = "SELECT
					*
				FROM
					events_times
					LEFT JOIN films ON films.id = events_times.films_id
				WHERE
					TRUE
				AND events_id IS NULL
				AND events_times.start_time > NOW() ";
				if ($end_date) {
					$query .= " AND events_times.start_time < '$end_date' ";
				}
				$query .= "
				ORDER BY
					events_times.start_time
				LIMIT $count";

		return $this->db->query( $query )->result();
	}

	/**
	 *
	 */
	function get_events( $filter )
	{
		$query = "SELECT e.*, ratings.rating as rating, ec.name as category
					FROM events e
					LEFT JOIN event_categories ec ON e.categories_id = ec.id
					LEFT JOIN ratings ON e.ratings_id = ratings.id
					LEFT JOIN event_audiences ON e.audiences_id = event_audiences.id ";


		if (isset($filter['type'])) {
			$query .= " AND ec.name = '{$filter['type']}' ";
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
			SELECT e.id, e.title, e.dt_start, e.audience, ea.audience as audience_name, e.category, ec.name AS category_name
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
		Search for an event by title
		@param term
		@return result
	*/
	function searchEventsByTitle($term, $limit = 25)
	{
		$sql = "SELECT *, 'event' as type 
				FROM events WHERE LOWER(title) LIKE '%" . $this->db->escape_like_str($term) . "%'
				ORDER BY title
				LIMIT $limit
				";
		$result = $this->db->query($sql)->result();

		return $result;
	}

	/**
		Search for a film by title 
		@param term
		@return result
	*/
	function searchFilmsByTitle($term, $limit = 25)
	{
		$sql = "SELECT *, 'film' as type 
				FROM films WHERE LOWER(title) LIKE '%" . $this->db->escape_like_str($term) . "%'
				ORDER BY title
				LIMIT $limit
				";
		$result = $this->db->query($sql)->result();

		return $result;
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
		$sql .= " ORDER BY name ASC";
		return $this->db->query($sql);
	}

	/** 
		Get Audiences - Retrieve event audiences 
		@param int or array of ints of category IDs
		@return result
	*/
	function get_audiences( $id = false )
	{
		$sql = "SELECT * FROM event_audiences WHERE true";
		if ( $id ) {
			if (is_array($id)) {
				$id = implode(',', $id);
			}
			$sql .= " AND id IN ({$id})";
		} 
		$sql .= " ORDER BY name ASC";
		return $this->db->query($sql);
	}

	/** 
		Get Venues - Retrieve event venues 
		@param int or array of ints of category IDs
		@return result
	*/
	function get_venues( $id = false )
	{
		$sql = "SELECT * FROM venues WHERE true";
		if ( $id ) {
			if (is_array($id)) {
				$id = implode(',', $id);
			}
			$sql .= " AND id IN ({$id})";
		} 
		$sql .= " ORDER BY name ASC";
		return $this->db->query($sql);
	}

	/* Gets the details about a specific event - 
	 * used in the JSON callback for movie details so this includes a join on the films table */
	function get_event($id) {
		$sql = "SELECT
					*, events.title as title,
					dt_start as date_start, dt_end as date_end, 
					event_categories.name as category,
					event_audiences.name as audience,
					ratings.name as rating
				FROM
					events
				LEFT JOIN event_categories ON event_categories.id = events .categories_id
				LEFT JOIN event_audiences ON events.audiences_id = event_audiences.id
				LEFT JOIN media_map ON media_map.path = concat('/event/', events.id)
				LEFT JOIN media ON media.id = media_map.media_id
				LEFT JOIN films ON films.id = events .event_ref
				LEFT JOIN ratings ON events.ratings_id = ratings.id
				WHERE
						events .id = '$id'";
	
		$result = $this->db->query($sql);
		$return = $result->row();

		if (!$return) return false;

		$sql = "SELECT 
					* 
				FROM
					venues 
				WHERE id = '{$return->venues_id}'";
		$venue_result = $this->db->query($sql);
		$return->venue = $venue_result->row();

		$sql = "SELECT start_time, end_time
				FROM events_times WHERE events_id = '$id'";
		$times_result = $this->db->query($sql);
		$times = $times_result->result();
		$event_times = array();
		if ($times) foreach ($times as $time) {
			$d = date('Y-m-d', strtotime($time->start_time));
			$event_times[$d] = $time;
		}
		$return->dates = $event_times;

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

	/**
	   Get a film ID based on the events_times ID
	   @param event_times ID
	   @return film ID or false
	*/
	function get_film_id_by_event_time_id( $id ) {
		if (!is_numeric($id)) return false;

		$sql = "SELECT films_id FROM events_times WHERE id = '$id'";
		$result = $this->db->query($sql);

		$ret = $result->row();

		if ($ret) {
			return ($ret->films_id);
		}
		return false;
	}

	/**
		Get all event details based on events_times ID
		This is used for the event edit page after a calendar event is clicked 
		@param event_times ID
		@return event object or false
	*/
	function get_event_by_event_time_id( $id ) {
		if (!is_numeric($id)) return false;

		$sql = "SELECT events.*, 
					events_times.start_time, 
					events_times.end_time 
			FROM events_times 
			LEFT JOIN events ON events_times.events_id = events.id
			WHERE events_times.id = '$id'";
		$result = $this->db->query($sql);

		$ret = $result->row();

		if ($ret) {
			return $ret; 
		}
		return false;
	}

	/**
		Get film info
		@param Film ID
		@return Film data object or false
	*/
	function get_film($film_id) {
		$sql = "SELECT * FROM films WHERE id = '{$film_id}'";
		$result = $this->db->query($sql);

		$ret = $result->row();

		return $ret;
	}


	function get_calendar_events($start, $end) {
		$start = date('Y-m-d', $start);
		$end = date('Y-m-d', $end);

		$sql = "SELECT
					et.id, 
					films.title, 
					et.start_time AS start,
					et.end_time AS end
				FROM
					FILMS
				LEFT JOIN events_times et ON et.films_id = films.id
				WHERE
					start_time >= '$start'
				AND start_time <= '$end'";

		$film_events = $this->db->query($sql)->result();

		foreach ($film_events as &$it) {
			$it->allDay = false;
			$it->className = 'film';
			$it->color = '#0B3';
		}


		$sql = "SELECT
					et.id, 
					events.title, 
					et.start_time AS start,
					et.end_time AS end
				FROM
					events
				LEFT JOIN events_times et ON et.events_id = events.id
				WHERE
					start_time >= '$start'
				AND start_time <= '$end'";

		$event_events = $this->db->query($sql)->result();

		foreach ($event_events as &$it) {
			$it->allDay = false;
			$it->className = 'event';
			$it->color = '#30B';
		}

		$result = array_merge($film_events, $event_events);

		return $result;
	}

}
