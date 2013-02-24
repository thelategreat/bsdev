<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class articles_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();
  }

	// used by admin
	function get_article_list( $category = NULL, $page = 1, $limit = NULL, $query = '' )
	{

		$q =<<<EOF
  SELECT a.id, title, fnStripTags(body) as body, excerpt, ac.category, publish_on, author,
			 owner, ast.status, gt.name as group_name, u.firstname, u.lastname, u.nickname
	FROM articles as a, article_categories as ac, article_statuses as ast, group_tree as gt, users as u
	WHERE a.category = ac.id AND a.status = ast.id AND a.group = gt.id AND u.username = a.owner
EOF;

	if( $category ) {
		$q .= " AND (ac.category = " . $this->db->escape($category) . " OR ac.category = 'General')";
	}

	if( strlen($query) > 0 ) {
		$terms = explode(' ', $query );
    foreach( $terms as $term ) {
      # note braces
			$q .= " AND (a.title LIKE '%" . $this->db->escape_like_str( $term) . "%'";
			$q .= " OR a.excerpt LIKE '%" . $this->db->escape_like_str( $term) . "%'";
			$q .= " OR a.body LIKE '%" . $this->db->escape_like_str( $term) . "%')";
		}
	}

	$q .= " ORDER BY ast.id ASC, title ASC, publish_on DESC ";

		if( $limit ) {
			$q .= " LIMIT $limit";
			$q .= " OFFSET " . (($page - 1) * $limit);
		}

		return $this->db->query( $q );
	}

	function get_published_article_list( $group = NULL, $limit = NULL, $page = 1 )
	{
		$q =<<<EOF
SELECT a.id, title, fnStripTags(body) as body, excerpt, ac.category, publish_on, author, ast.status, gt.name as `group`
	FROM articles as a, article_categories as ac, article_statuses as ast, group_tree as gt
	WHERE a.category = ac.id AND a.status = ast.id AND a.status >= 3 AND gt.id = a.group AND publish_on <= NOW()
EOF;

			if( $group ) {
				$q .= " AND (a.group = $group)";
			}

			$q .= " ORDER BY publish_on DESC";

			if( $limit ) {
				$q .= " LIMIT $limit";
				$q .= " OFFSET " . ($limit * ($page-1));
			}

			//echo '<pre>' . $q . '</pre><br/>';

			return $this->db->query( $q );
	}

  function get_published_articlesz( $group, $limit = NULL, $page = 1 )
  {
    if( $group <= 0 ) {
      $group = 1;
    }

    $q = "SELECT a.id, a.title, fnStripTags(a.body) as body, a.excerpt, ac.category, a.publish_on, a.author, ast.status, gt.name as `group`,
    		(SELECT COUNT(*)
    				FROM comments
    				WHERE table_ref = 'articles'
    				AND table_id = a.id) AS comment_count,
    		ast.id AS status_id
    		FROM articles AS a,
    		group_tree AS  gt,
    		article_categories AS ac,
    		article_statuses AS ast
    		WHERE
    			a.group = gt.id 
    		AND a.category = ac.id
    		AND a.status = ast.id 
    		AND a.status >= 3 
    		AND gt.id = $group";

    $q .= " ORDER BY status_id ASC, a.publish_on DESC";

    if( $limit ) {
      $q .= " LIMIT $limit";
      $q .= " OFFSET " . ($limit * ($page-1));
    }

    return $this->db->query( $q );
  }


  function get_published_articles( $group, $limit = NULL, $page = 1 )
  {
    if( $group <= 0 ) {
      $group = 1;
    }

    $q = "SELECT a.id, a.title, fnStripTags(a.body) as body, a.excerpt, ac.category, a.publish_on, a.author, ast.status, gt.name as `group`,
    		(SELECT COUNT(*)
    				FROM comments
    				WHERE table_ref = 'articles'
    				AND table_id = a.id) AS comment_count,
    		ast.id AS status_id
    		FROM articles AS a,
    		group_tree AS gt,
    		group_tree AS gt2,
    		article_categories AS ac,
    		article_statuses AS ast
    		WHERE
			a.group = gt2.id
			AND gt2.lft between gt.lft and gt.rgt
    		AND a.category = ac.id
    		AND a.status = ast.id 
    		AND a.status >= 3 
    		AND gt.id = $group";
    
    $q .= " ORDER BY status_id ASC, a.publish_on DESC";

    if( $limit ) {
      $q .= " LIMIT $limit";
      $q .= " OFFSET " . ($limit * ($page-1));
    }

    return $this->db->query( $q );
  }


  function get_published_articles_al( $group, $limit = NULL, $page = 1 )
  {
    $q =<<<SQL
    select a.id, title, fnStripTags(body) as body, excerpt, ac.category, publish_on, author, ast.status, gt.name as `group`
    from articles as a, group_tree as gt, article_categories as ac, article_statuses as ast
      where a.group = gt.id
        and a.category = ac.id
        and a.status = ast.id
        and a.status = 3
        and (gt.id = $group OR gt.parent_id = $group)
SQL;

    $q .= " ORDER BY a.publish_on DESC";

    if( $limit ) {
      $q .= " LIMIT $limit";
      $q .= " OFFSET " . ($limit * ($page-1));
    }

    return $this->db->query( $q );
  }

  	/* Get a single article as referenced by its ID
  	 * @param Article ID
  	 * @returns Article result
  	 */
	function get_article( $id )
	{
		$q = "
			SELECT a.id, title, body, ac.category, updated_on, author, ast.status, publish_on, gt.name AS `group`, excerpt
				FROM articles as a, article_categories as ac, article_statuses as ast, group_tree as gt
				WHERE a.category = ac.id AND a.status = ast.id AND a.group = gt.id";

		$q .= " AND a.id = " . $this->db->escape(intval($id));
		return $this->db->query( $q );
	}

  function get_articles_by_group( $group, $limit = NULL, $page = 1 )
  {
    $q =<<<SQL
    select a.id, title, fnStripTags(body) as body, excerpt, ac.category, publish_on, author, ast.status, gt.name as `group`
    from articles as a, group_tree as gt, article_categories as ac, article_statuses as ast
      where a.group = gt.id
        and a.category = ac.id
        and a.status = ast.id
        and (gt.id = $group OR gt.parent_id = $group)
SQL;

    $q .= " ORDER BY a.publish_on DESC";

    if( $limit ) {
      $q .= " LIMIT $limit";
      $q .= " OFFSET " . ($limit * ($page-1));
    }

    return $this->db->query( $q );
  }

	function category_select( $default = 0 )
	{
		$s = '<select name="category" id="category-sel">';
		$res = $this->db->query("SELECT * FROM article_categories ORDER BY id");
		foreach( $res->result() as $row ) {
			$s .= '<option value="' . $row->id . '" ';
			if( $default == $row->id ) {
				$s .= " selected ";
			}
			$s .= '>' . $row->category . '</option>';
		}
		$s .= '</select>';

		return $s;
	}

	function group_select( $default = 0 )
	{
		$s = '<select name="group" id="group-sel">';
		$res = $this->db->query("SELECT * FROM article_groups ORDER BY id");
		foreach( $res->result() as $row ) {
			$s .= '<option value="' . $row->id . '" ';
			if( $default == $row->id ) {
				$s .= " selected ";
			}
			$s .= '>' . $row->group . '</option>';
		}
		$s .= '</select>';

		return $s;
	}

	function status_select( $default = 0 )
	{
		$s = '<select name="status">';
		$res = $this->db->query("SELECT * FROM article_statuses ORDER BY id");
		foreach( $res->result() as $row ) {
			$s .= '<option value="' . $row->id . '" ';
			if( $default == $row->id ) {
				$s .= " selected ";
			}
			$s .= '>' . $row->status . '</option>';
		}
		$s .= '</select>';

		return $s;
	}

	function priority_select( $default = 2 )
	{
		$priorities = array( "1" => "high", "2" => "med", "3" => "low");
		$s = '<select name="display_priority">';
		foreach( $priorities as $k => $v ) {
			$s .= '<option value="' . $k . '" ';
			if( $default == $k ) {
				$s .= " selected ";
			}
			$s .= '>' . $v . '</option>';
		}
		$s .= '</select>';

		return $s;
	}

  function venue_select( $default = 1 )
  {
    $s = '<select name="venue" id="venue-sel">';
		$res = $this->db->query("SELECT * FROM venues ORDER BY id");
		foreach( $res->result() as $row ) {
			$s .= '<option value="' . $row->id . '" ';
			if( $default == $row->id ) {
				$s .= " selected ";
			}
			$s .= '>' . $row->venue . '</option>';
		}
		$s .= '</select>';
    return $s;
  }

    /* Add an event association to an article
     * @param required Article ID
     * @param requried Event ID
     * @return boolean Success
     */
    function add_event( $article_id, $event_id )
    {
        $this->db->where('article_id', $article_id);
        $this->db->where('event_id', $event_id);
        $query = $this->db->get('article_events');
        if ( $query->num_rows() > 0 ) {
            return false; // This item is already associated
        }

        $this->db->set('event_id', $event_id);
        $this->db->set('article_id', $article_id);
        $this->db->insert('article_events');

        return true;
    }
    
    /* Add an event association to an article
     * @param required Article ID
     * @param requried Event ID
     * @return boolean Success
     */
    function remove_event( $article_id, $event_id )
    {
        $this->db->where('article_id', $article_id);
        $this->db->where('event_id', $event_id);
        $query = $this->db->delete('article_events');

        return true;
    }

    

    function get_events( $article_id )
    {
    	$this->load->model('event_model');
    	
        $sql = "SELECT * FROM article_events ae
            LEFT JOIN events e ON ae.event_id = e.id
            WHERE ae.article_id = ?";
        $result = $this->db->query($sql, $article_id)->result_array();

        if (count($result) == 0 || $result === false) {
	        return array();
        }

        foreach ($result as &$r) {
	        $r['type'] = 'event';
	        $img = $this->event_model->get_event_media( $r['event_id'] );
            if ($img->num_rows() > 0) {
                $r['thumbnail'] = '/media/' . $img->row()->uuid;
            } else {
                $r['thumbnail'] = '/img/image_not_found.jpg';
            }
        }
        return $result;
    }

    /* Add a product association to an article
     * @param required Article ID
     * @param requried Product ID
     * @return boolean Success
     */
    function add_product( $article_id, $product_id )
    {
        $this->db->where('article_id', $article_id);
        $this->db->where('product_id', $product_id);
        $query = $this->db->get('article_products');
        if ( $query->num_rows() > 0 ) {
            return false; // This item is already associated
        }

        $this->db->set('article_id', $article_id);
        $this->db->set('product_id', $product_id);
        $this->db->insert('article_products');

        return true;
    }
    
    /* Add a product association to an article
     * @param required Article ID
     * @param requried Product ID
     * @return boolean Success
     */
    function remove_product( $article_id, $product_id )
    {
        $this->db->where('article_id', $article_id);
        $this->db->where('product_id', $product_id);
        $query = $this->db->delete('article_products');

        return true;
    }

    function get_products( $article_id )
    {
        $sql = "SELECT * FROM article_products ap
            LEFT JOIN products p ON ap.product_id = p.id
            WHERE ap.article_id = ?";
        $result = $this->db->query($sql, $article_id)->result_array();
        
        if (count($result) == 0 || $result === false) {
            return array();
        }

        foreach ($result as &$r) {
	        $r['type'] = 'product';
	        if ( strlen($r['ean']) == 13 ) {
		        $prefix = $r['ean'][12];
		        $path = '/product/' . $prefix . '/' . $r['ean'] . '.jpg';
		        $r['thumbnail'] = $path;
		    }
		    // Reference used for front-end details insertion
		    $r['ref'] = $r['ean'] . '_' . str_replace(' ', '-', str_replace('-', '\-', $r['title']));
        }
        return $result;
    }
}
