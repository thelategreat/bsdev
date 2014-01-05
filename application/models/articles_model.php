<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class articles_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();
    $this->db->db_select();

    $this->site_db = $this->config->item('site_db');
    $this->prod_db = $this->config->item('prod_db');
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

    $q = "SELECT a.id, a.title, fnStripTags(a.body) as body, 
        a.excerpt, ac.category, a.publish_on, 
        a.author, ast.status, gt.name as `group`,
        'article' as object_type,
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

    return $this->db->query( $q )->result();
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

  	/** 
      Get a single article as referenced by its ID
  	  @param Article ID
  	  @returns Article result
  	 */
    function load($id){
        $article = new articles_model();
        $sql = "SELECT a.id, title, body, excerpt, ac.category, publish_on, author,
                owner, ast.status, a.group as group_id, gt.name as group_name, gt.name AS section,
                u.firstname, u.lastname, u.nickname 
                FROM articles a
                LEFT JOIN article_categories ac ON a.category = ac.id 
                LEFT JOIN article_statuses ast ON a.status = ast.id 
                LEFT JOIN group_tree gt ON a.group = gt.id 
                LEFT JOIN users u ON a.owner = u.username 
                WHERE a.id = $id";
        $result = $this->db->query($sql)->row_array();
        foreach ($result as $key => $value){
            $article->$key = $value;
        }

        return $article;
    
    
    }
    
    function render(){
        $data = new stdClass();
        $data->item = $this;
        $this->html = $this->load->view('layouts/item_article.php', $data, true);
    }
  	 
  	 function get_article( $id )
	{
    $this->db->db_select();
		$q = "
			SELECT a.id, title, body, ac.category, updated_on, author, ast.status, publish_on, gt.name AS `section`, excerpt,
        'article' as object_type
				FROM articles as a, article_categories as ac, article_statuses as ast, group_tree as gt
				WHERE a.category = ac.id AND a.status = ast.id AND a.group = gt.id";

		$q .= " AND a.id = " . $this->db->escape(intval($id));

    $result = $this->db->query( $q )->result();

    if ($result) {
       return $result[0]; 
     } 
     return false;
		//return $this->db->query( $q );
	}

  /**
    Get the tags associated to an article
    @param Article ID
    @returns tag array
    */
  function get_article_tags( $id ) 
  {
    $this->db->db_select();
    $sql = "SELECT name, slug FROM articles_tag_map
          LEFT JOIN articles_tags ON articles_tags.id = articles_tag_id
          WHERE articles_tag_map.articles_id = $id";

    $res = $this->db->query( $sql );
    if ($res) return $res->result();

    return false;
  }

  /**
    Get the articles in a given group 
    */
  function get_articles_by_group( $group, $limit = NULL, $page = 1 )
  {
    $q = <<<SQL
    SELECT a.id, title, fnStripTags(body) as plaintext_body, body, excerpt, ac.category, publish_on, author, 
          ast.status, gt.name as `group`
    FROM articles a
    LEFT JOIN group_tree gt ON a.group = gt.id 
    LEFT JOIN article_categories ac ON a.category = ac.id 
    LEFT JOIN article_statuses ast ON a.status = ast.id 
    WHERE 
      gt.id = '$group' OR gt.parent_id = '$group'
    ORDER BY a.publish_on DESC
SQL;

    if( $limit ) {
      $q .= " LIMIT $limit";
      $q .= " OFFSET " . ($limit * ($page-1));
    }

    return $this->db->query( $q )->result();
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

  /**
    Venue select dropdown builder
    -- what the hell is this doing in a model anyway?
    */
  function venue_select( $default = 1 )
  {
    $s = '<select name="venue" id="venue-sel">';
		$res = $this->db->query("SELECT * FROM venues ORDER BY id");
		foreach( $res->result() as $row ) {
			$s .= '<option value="' . $row->id . '" ';
			if( $default == $row->id ) {
				$s .= " selected ";
			}
			$s .= '>' . $row->name. '</option>';
		}
		$s .= '</select>';
    return $s;
  }

    /** 
      Add a film association to an article
      @param required Article ID
      @param requried Film ID
      @return boolean Success
     */
    function add_film( $article_id, $film_id)
    {
        $this->db->where('articles_id', $article_id);
        $this->db->where('films_id', $film_id);
        $query = $this->db->get('articles_films');
        if ( $query->num_rows() > 0 ) {
            return false; // This item is already associated
        }

        $this->db->set('films_id', $film_id);
        $this->db->set('articles_id', $article_id);
        $this->db->insert('articles_films');

        return true;
    }
    
    /** 
      Remove a film association to an article
      @param required Film ID
      @param requried Event ID
      @return boolean Success
     */
    function remove_film( $film_id, $event_id )
    {
        $this->db->where('articles_id', $film_id);
        $this->db->where('films_id', $event_id);
        $query = $this->db->delete('articles_films');

        return true;
    }
    
    /* Add an article association to an article
     * @param required Article ID
     * @param requried Event ID
     * @return boolean Success
     */
    function add_associated_article( $article_id, $associated_article_id )
    {
        $this->db->where('articles_id', $article_id);
        $this->db->where('associated_article_id', $associated_article_id);
        $query = $this->db->get('articles_articles');
        if ( $query->num_rows() > 0 ) {
            return false; // This item is already associated
        }

        $this->db->set('associated_article_id', $associated_article_id);
        $this->db->set('articles_id', $article_id);
        $this->db->insert('articles_articles');

        return true;
    }
    
    /* Remove an article association to an article
     * @param required Article ID
     * @param requried Event ID
     * @return boolean Success
     */
    function remove_associated_article( $article_id, $associated_article_id )
    {
        $this->db->where('articles_id', $article_id);
        $this->db->where('associated_article_id', $associated_article_id);
        $query = $this->db->delete('articles_articles');

        return true;
    }

    /* Add an event association to an article
     * @param required Article ID
     * @param requried Event ID
     * @return boolean Success
     */
    function add_event( $article_id, $event_id )
    {
        $this->db->where('articles_id', $article_id);
        $this->db->where('events_id', $event_id);
        $query = $this->db->get('articles_events');
        if ( $query->num_rows() > 0 ) {
            return false; // This item is already associated
        }

        $this->db->set('events_id', $event_id);
        $this->db->set('articles_id', $article_id);
        $this->db->insert('articles_events');

        return true;
    }
    
    /* Add an event association to an article
     * @param required Article ID
     * @param requried Event ID
     * @return boolean Success
     */
    function remove_event( $article_id, $event_id )
    {
        $this->db->where('articles_id', $article_id);
        $this->db->where('events_id', $event_id);
        $query = $this->db->delete('articles_events');

        return true;
    }

    
    /**
      Get the articles related to an article 
      @param Article ID
      @return Array of events with theumbnails or false
    */
    function get_associated_articles( $article_id )
    {
        $sql = "SELECT * FROM articles_articles aa 
            LEFT JOIN articles a ON aa.associated_article_id = a.id
            WHERE aa.articles_id = ?";
        $result = $this->db->query($sql, $article_id)->result();

        if (!$result) {
          return false;
        }

        foreach ($result as &$r) {
          $r->type = 'article';
          $img = $this->articles_model->get_article_media( $r->articles_id );
          if ($img) {
            $r->image = '/media/' . $img->uuid;
          } else {
            $r->image = '/img/image_not_found.jpg';
          }
        }
        return $result;
    }

    /**
      Get the events related to an article 
      @param Article ID
      @return Array of events with theumbnails or false
    */
    function get_associated_events( $article_id )
    {
    	$this->load->model('event_model');
    	
        $sql = "SELECT * FROM articles_events ae
            LEFT JOIN events e ON ae.events_id = e.id
            WHERE ae.articles_id = ?";
        $result = $this->db->query($sql, $article_id)->result();

        if (!$result) {
	        return false;
        }

        foreach ($result as &$r) {
          $r->type = 'event';
	        $img = $this->event_model->get_event_media( $r->events_id );
          if ($img) {
            $r->image = '/media/' . $img->uuid;
          } else {
            $r->image = '/img/image_not_found.jpg';
          }
        }
        return $result;
    }

    /**
      Get the films related to an article 
      @param Article ID
      @return Array of events with theumbnails or false
    */
    function get_associated_films( $article_id )
    {
      $this->load->model('films_model');
      
        $sql = "SELECT films_id FROM articles_films af
            LEFT JOIN films f ON af.films_id = f.id
            WHERE af.articles_id = ?";
        $result = $this->db->query($sql, $article_id)->result();

        if (!$result) {
          return false;
        }

        $results = array();
        foreach ($result as &$r) {
          $results[] = $this->films_model->get_film($r->films_id);
        }

        return $results;
    }
    

    /**
      Get the media for an event 
     */
    function get_article_media( $id )
    {
      $sql = "SELECT m.uuid, slot FROM 
          media_map as mm, 
          media as m 
          WHERE mm.path = '/article/" . intval($id) . 
          "' AND m.id = mm.media_id ORDER BY mm.sort_order";
      $result = $this->db->query($sql)->result();

      if ($result) {
        return $result[0];
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
        $this->db->where('articles_id', $article_id);
        $this->db->where('products_id', $product_id);
        $query = $this->db->get('articles_products');
        if ( $query->num_rows() > 0 ) {
            return false; // This item is already associated
        }

        $this->db->set('articles_id', $article_id);
        $this->db->set('products_id', $product_id);
        $this->db->insert('articles_products');

        return true;
    }
    
    /** 
      Remove a product association to an article
      @param required Article ID
      @param requried Product ID
      @return boolean Success
     */
    function remove_product( $article_id, $product_id )
    {
        $this->db->where('articles_id', $article_id);
        $this->db->where('products_id', $product_id);
        $query = $this->db->delete('articles_products');

        return true;
    }

    /**
     Get the products associated with an article
     @param required Article ID
     @return product result
     */
    function get_associated_products( $article_id )
    {
        $sql = "SELECT p.*, pub.name as publisher FROM {$this->site_db}.articles_products ap
            LEFT JOIN {$this->prod_db}.products p ON ap.products_id = p.id
            LEFT JOIN {$this->prod_db}.publishers pub ON p.publisher_id = pub.id
            WHERE ap.articles_id = ?";
        $result = $this->db->query($sql, $article_id)->result();
        
        if (count($result) == 0 || $result === false) {
            return array();
        }

        foreach ($result as &$r) {
          /* There may be a more efficient way to do this, but there are multiple contributors
          per product (possibly) and a join needs catch all the contributors and then concat them
          into a single field */
          $sql = "SELECT GROUP_CONCAT(name SEPARATOR '|') as name FROM 
                {$this->prod_db}.products_contributors pc 
                LEFT JOIN {$this->prod_db}.contributors c ON pc.contributors_id = c.id
                WHERE pc.products_id = {$r->id}";
          $cont_result = $this->db->query($sql)->result();
          $r->contributor = $cont_result[0]->name;

	        $r->type = 'product';
          $r->thumbnail = $this->get_product_image_by_ean($r->ean);
		    // Reference used for front-end details insertion
		    $r->ref = $r->ean . '_' . str_replace(' ', '-', str_replace('-', '\-', $r->title));
        }
        return $result;
    }

    /**
      Get the product image file reference by the product EAN
      Or return the 'image not found' image if the file doesn't exist
    */
    function get_product_image_by_ean($ean) 
    {
      if (strlen($ean) == 13) {
        $prefix = $ean[12];
        $path = "/product/$prefix/$ean" . ".jpg";

        $curpath = getcwd();
        if (is_file($curpath . $path)) return $path;

        return $this->config->item('image_not_found_image');
      }
    }
    
}
