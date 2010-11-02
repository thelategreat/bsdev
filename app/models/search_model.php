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

		$sql =<<<EOF
		SELECT id, title, updated_on, dt_start, 'event' AS type 
				FROM events 
				WHERE  (title LIKE '%$term%' OR body LIKE '%$term%')
		UNION 
				SELECT id, title, updated_on, NULL as dt_start, 'article' AS type 
					FROM articles 
					WHERE (title LIKE '%$term%' OR body LIKE '%$term%')
			ORDER BY updated_on DESC
			LIMIT $limit OFFSET $offs
EOF;
		return $this->db->query( $sql );		
	}		
}