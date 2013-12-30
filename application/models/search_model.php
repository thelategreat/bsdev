<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

/**
 * 
 */
class search_model extends CI_Model
{
	/**
	 * 
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('products_model');
	}

	/**
	 * Search function for callback routines
	 * Only returns distinct titles, not multiple titles for events on different dates
	 * @param string 	query 	required
	 * @param array 	type	optional
	 * @param int		limit	optional
	 *
	 * TODO: implement type filtering
	 */ 
	
	function search_callback( $query, $type, $limit = 25) 
	{
return false;
		$this->db->db_select();

		$terms = explode( ' ', $query );
				
		/*
		$event_sql = "SELECT id, title, 'event' AS type, NULL AS author
					FROM events
					WHERE  ";

		foreach( $terms as $term ) {
			$term = $this->db->escape_like_str( $term );
			$event_sql .= "(title LIKE '%" . $this->db->escape_like_str($term) . "%' OR body LIKE '%". $this->db->escape_like_str($term) . "%') AND ";
		}
		$event_sql = substr($event_sql, 0, -4);
		$event_sql .= " GROUP BY title ";
		*/


		$article_sql = "
			SELECT id, title, 'article' AS type, author
				FROM articles 
				WHERE true AND 
				LOWER(title) LIKE '%" . $this->db->escape_like_str(strtolower($query)) . "%' OR 
				LOWER(body) LIKE '%" . $this->db->escape_like_str(strtolower($query)) . "%'
				GROUP BY title 
				ORDER BY title
				";

		$sql = $article_sql . " LIMIT " . $limit;


		$articles = $this->db->query($sql)->result();

		$events = $this->event_model->searchEventsByTitle($query, $limit);
		$products = $this->products_model->searchProduct(array('all'=>$query), $limit);
		$films = $this->event_model->searchFilmsByTitle($query, $limit);

		$return = array_merge($films, array_merge($articles, array_merge($events, $products)));


		return $return;	
		
	}
	
	function search( $query, $which = 'all', $page = 1, $limit = 10 )

	{
		// CI turns spaces into _ in the URL
		$terms = preg_split( "/[\s,_]/", $query );
		
		$term = $this->db->escape_like_str($query );
		$offs = (($page - 1) * $limit);

		$sql = "";
		
		$event_sql =<<<EOE
			SELECT id, title, updated_on, dt_start, 'event' AS type, NULL AS author
					FROM events
					WHERE  
EOE;

		foreach( $terms as $term ) {
			$event_sql .= "(title LIKE '%" . $this->db->escape_like_str($term) . "%' OR body LIKE '%". $this->db->escape_like_str($term) . "%') AND ";
		}
		$event_sql = substr($event_sql, 0, -4);


		$article_sql =<<<EOA
			SELECT id, title, updated_on, NULL as dt_start, 'article' AS type, author
				FROM articles 
				WHERE status = 3 AND 
EOA;
		foreach( $terms as $term ) {
			$article_sql .= "(title LIKE '%" . $this->db->escape_like_str($term) . "%' OR body LIKE '%". $this->db->escape_like_str($term) . "%') AND ";
		}
		$article_sql = substr($article_sql, 0, -4);

		$product_sql =<<<EOP
			SELECT id, title, NULL AS updated_on, NULL as dt_start, 'book' AS type, contributor as author 
				FROM products 
				WHERE 
EOP;
		foreach( $terms as $term ) {
			$product_sql .= "(title LIKE '%" . $this->db->escape_like_str($term) . "%' OR contributor LIKE '%" . $this->db->escape_like_str($term) . "%') AND ";
		}
		$product_sql = substr($product_sql, 0, -4);


		switch( $which ) {
			case 'events':
			$sql = $event_sql;
			break;
			
			case 'articles':
			$sql = $article_sql;
			break;

			case 'books':
			$sql = $product_sql;
			break;

			
			default:
			$sql .= $event_sql . " UNION " . $article_sql . " UNION " . $product_sql;
		}
		
			
		$sql .= " ORDER BY updated_on DESC, title ASC LIMIT $limit OFFSET $offs";

		//echo '[' . $which . ']';
		//echo $sql;
		return $this->db->query( $sql );		
	}		
}
