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

	function search( $query, $which = 'all', $page = 1, $limit = 10 )
	{
		$term = $this->db->escape_like_str($query );
		$offs = (($page - 1) * $limit);

		$sql = "";
		
		$event_sql =<<<EOE
			SELECT id, title, updated_on, dt_start, 'event' AS type 
					FROM events 
					WHERE  (title LIKE '%$term%' OR body LIKE '%$term%')
EOE;

		$article_sql =<<<EOA
			SELECT id, title, updated_on, NULL as dt_start, 'article' AS type 
				FROM articles 
				WHERE (title LIKE '%$term%' OR body LIKE '%$term%') AND status = 3
EOA;

		switch( $which ) {
			case 'events':
			$sql .= $event_sql;
			break;
			
			case 'articles':
			$sql .= $article_sql;
			break;
			
			default:
			$sql .= $event_sql . " UNION " . $article_sql;
		}
		
			
		$sql .= " ORDER BY updated_on DESC LIMIT $limit OFFSET $offs";

		return $this->db->query( $sql );		
	}		
}