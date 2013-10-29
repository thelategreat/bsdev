<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class films_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();
    $this->db->db_select();

    $this->site_db = $this->config->item('site_db');
    $this->prod_db = $this->config->item('prod_db');
  }

/**
	Get the media for a film 
 */
function get_film_media( $id )
{
	$sql = "SELECT media.uuid, slot FROM 
			media_map LEFT JOIN media ON media_map.media_id = media.id
			WHERE media_map.path = '/films/{$id}'";
	$result = $this->db->query($sql)->result();

	if ($result) {
		return ($result);
	}

	return $result;
}

/**
	Get an individual film by ID
	@param ID
  @param Load associated films - if this came from a call for the associated films of some parent film then 
        it should be set to false otherwise it will nest endlessly
*/
function get_film( $id, $get_associates = true ) 
{
	if (!is_numeric($id)) return false;
	$sql = "SELECT *, title AS name FROM films WHERE id = '$id'";
	$query = $this->db->query($sql);
	$result = $query->result();

	if ($result) {
		$return = $result[0];
		$return->type = 'film';
		$return->object_type = 'film';
		$return->media = self::get_film_media( $id );
		$return->showtimes = self::get_upcoming_showtimes( $id );
    $return->associated = new stdClass();
    $return->associated->articles = self::get_associated_articles( $id );
    $return->associated->products = self::get_associated_products( $id );
    $return->associated->events   = self::get_associated_events( $id );
    if ($get_associates) {
      $return->associated->films = self::get_associated_films( $id );
    }
		return $return;
	}

	return false;
}

function get_upcoming_showtimes( $id ) 
{
	$sql = "SELECT * FROM events_times WHERE films_id = '{$id}' 
			AND start_time > date_sub(NOW(), INTERVAL 2 WEEK)
			ORDER BY start_time 
			";
	$query = $this->db->query($sql);
	$results = $query->result();

	return $results;
}

	/**
   Add an article association to an article
   @param required Film ID
   @param requried Article ID
   @return boolean Success
  */
  function add_associated_article( $films_id, $associated_article_id )
  {
      $this->db->where('films_id', $films_id);
      $this->db->where('articles_id', $associated_article_id);
      $query = $this->db->get('films_articles');
      if ( $query->num_rows() > 0 ) {
          return false; // This item is already associated
      }

      $this->db->set('articles_id', $associated_article_id);
      $this->db->set('films_id', $films_id);
      $this->db->insert('films_articles');

      return true;
  }
    
    /**
     Remove an article association to an article
     @param required Films ID
     @param requried Article ID
     @return boolean Success
    */
    function remove_associated_article( $films_id, $associated_article_id )
    {
        $this->db->where('films_id', $films_id);
        $this->db->where('articles_id', $associated_article_id);
        $query = $this->db->delete('films_articles');

        return true;
    }


    /**
      Get the articles related to an article 
      @param Article ID
      @return Array of articles with thembnails or false
    */
    function get_associated_articles( $films_id )
    {
        $this->load->model('articles_model');
   
        $sql = "SELECT * FROM films_articles fa 
            LEFT JOIN articles a ON fa.articles_id = a.id
            WHERE fa.films_id = ?";
        $result = $this->db->query($sql, $films_id )->result();

        if (!$result) {
          return false;
        }

        foreach ($result as &$r) {
          $r->type = 'article';
          $img = $this->articles_model->get_article_media( $r->articles_id );
          if ($img) {
            $r->image = $this->config->item('media_path') . $img->uuid;
          } else {
            $r->image = $this->config->item('image_not_found_image');
          }
        }
        return $result;
    }

    /** 
    	Add a product association to an film 
      	@param required Film ID
      	@param requried Product ID
      	@return boolean Success
     */
    function add_product( $films_id, $product_id )
    {
        $this->db->where('films_id', $films_id);
        $this->db->where('products_id', $product_id);
        $query = $this->db->get('films_products');
        if ( $query->num_rows() > 0 ) {
            return false; // This item is already associated
        }

        $this->db->set('films_id', $films_id);
        $this->db->set('products_id', $product_id);
        $this->db->insert('films_products');

        return true;
    }

    /** 
      Remove a product association to a film 
      @param required Film ID
      @param requried Product ID
      @return boolean Success
     */
    function remove_product( $film_id, $product_id )
    {
        $this->db->where('films_id', $film_id);
        $this->db->where('products_id', $product_id);
        $query = $this->db->delete('films_products');

        return true;
    }

    /** 
    	Add an event association to a film 
     	@param required Film ID
     	@param requried Event ID
     	@return boolean Success
     */
    function add_event( $films_id, $event_id )
    {
        $this->db->where('films_id', $films_id);
        $this->db->where('events_id', $event_id);
        $query = $this->db->get('films_events');
        if ( $query->num_rows() > 0 ) {
            return false; // This item is already associated
        }

        $this->db->set('events_id', $event_id);
        $this->db->set('films_id', $films_id);
        $this->db->insert('films_events');

        return true;
    }
    
    /**
    	Remove an event association to a film
     	@param required Article ID
     	@param requried Event ID
     	@return boolean Success
     */
    function remove_event( $films_id, $event_id )
    {
        $this->db->where('films_id', $films_id);
        $this->db->where('events_id', $event_id);
        $query = $this->db->delete('films_events');

        return true;
    }

    /** 
      Add a film association to a film 
      @param required Film ID
      @param requried Associated Film ID
      @return boolean Success
     */
    function add_associated_film( $film_id, $associated_film_id )
    {
        $this->db->where('films_id', $film_id);
        $this->db->where('associated_films_id', $associated_film_id);
        $query = $this->db->get('films_films');
        if ( $query->num_rows() > 0 ) {
            return false; // This item is already associated
        }

        $this->db->set('films_id', $film_id);
        $this->db->set('associated_films_id', $associated_film_id);
        $this->db->insert('films_films');

        return true;
    }
    
    /** 
      Remove a film association to a film 
      @param required Film ID
      @param requried Associated Film ID
      @return boolean Success
     */
    function remove_associated_film( $film_id, $associated_film_id )
    {
        $this->db->where('films_id', $film_id);
        $this->db->where('associated_films_id', $associated_film_id);
        $query = $this->db->delete('films_films');

        return true;
    }



    /**
      Get the events related to an article 
      @param Article ID
      @return Array of events with theumbnails or false
    */
    function get_associated_events( $films_id )
    {
        $sql = "SELECT * FROM films_events fe
            LEFT JOIN events e ON fe.events_id = e.id
            WHERE fe.films_id = ?";
        $result = $this->db->query($sql, $films_id)->result();

        if (!$result) {
	        return false;
        }

        foreach ($result as &$r) {
          $r->type = 'event';
	        $img = $this->event_model->get_event_media( $r->events_id );
          if ($img) {
            $r->image = $this->config->item('media_path') . $img->uuid;
          } else {
            $r->image = $this->config->item('image_not_found_image');
          }
        }
        return $result;
    }

    /**
     Get the products associated with a film 
     @param required Film ID
     @return product result
     */
    function get_associated_products( $film_id )
    {
        $sql = "SELECT p.*, pub.name as publisher FROM {$this->site_db}.films_products fp
            LEFT JOIN {$this->prod_db}.products p ON fp.products_id = p.id
            LEFT JOIN {$this->prod_db}.publishers pub ON p.publisher_id = pub.id
            WHERE fp.films_id= ?";
        $result = $this->db->query($sql, $film_id)->result();
        
        if (count($result) == 0 || $result === false) {
            return false; 
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
      Get the films related to a film
      @param Film ID
      @return Array of events with theumbnails or false
    */
    function get_associated_films( $films_id )
    {
      $this->load->model('films_model');
      
        $sql = "SELECT associated_films_id FROM films_films ff
            LEFT JOIN films f ON ff.associated_films_id = f.id
            WHERE ff.films_id = ?";
        $result = $this->db->query($sql, $films_id)->result();

        if (!$result) {
          return false;
        }

        $results = array();
        foreach ($result as &$r) {
          $results[] = $this->films_model->get_film( $r->associated_films_id, false );
        }

        return $results;
    }
    
    
    /**
      Get the product image file reference by the product EAN
      Or return the 'image not found' image if the file doesn't exist

      This is a duplicate of the function in articles_model. They really should be moved somewhere central
      but time is short.
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
