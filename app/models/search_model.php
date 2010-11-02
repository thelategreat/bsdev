<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

/**
 * 
 */
class search_model extends Model
{
	/**
	 * 
	 */
	function __constructor()
	{
		parent::Model();
	}

	function search( $query, $page = 1, $limit = 10 )
	{
		$term = $this->db->escape_like_str($query );
		$offs = (($page - 1) * $limit);
		/*
		$sql =<<<EOF
			SELECT e.id, e.title, e.dt_start, e.audience, ea.audience as audience_name, e.category, ec.category AS category_name
			FROM events as e, event_audience as ea, event_categories as ec 
				WHERE (title LIKE '%$term%' OR body LIKE '%$term%')
				AND ea.id = e.audience AND ec.id = e.category
				LIMIT $limit OFFSET $offs
EOF;
		*/
		$sql =<<<EOF
		select id, title, created_on, 'event' as type from events where title like '%$term%'
			union select id, title, created_on, 'article' as type from articles where (title like '%$term%' OR body LIKE '%$term%')
			ORDER BY created_on DESC
			LIMIT $limit OFFSET $offs
EOF;
		return $this->db->query( $sql );		
	}		
}