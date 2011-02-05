<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ads_model extends Model
{

  function __construct()
  {
    parent::Model();
  }

	// used by admin end
	function get_ads( $category = NULL, $page = 1, $limit = NULL )
	{
		$query =<<< EOF
			select ads.id, ads.title, ads.url, ads.start_date, ads.end_date, ads.clicks, ads.owner, media.uuid 
				FROM ads, media_map, media 
				WHERE media_map.path = CONCAT('/ads/', ads.id) 
					AND media.id = media_map.media_id ORDER BY start_date
EOF;

		if( $limit ) {
			$query .= " LIMIT $limit";
      $query .= " OFFSET " . ($limit * ($page-1));
		}

		return $this->db->query( $query );
	}

	// used by front end
	function get_ad_list( $category = NULL, $page = 1, $limit = NULL )
	{
		$query =<<< EOF
			select ads.id, ads.title, ads.url, ads.start_date, ads.end_date, ads.clicks, ads.owner, media.uuid 
				FROM ads, media_map, media 
				WHERE media_map.path = CONCAT('/ads/', ads.id) 
					AND NOW() BETWEEN ads.start_date AND ads.end_date
					AND media.id = media_map.media_id ORDER BY start_date
EOF;

		if( $limit ) {
			$query .= " LIMIT $limit";
		}
			
		return $this->db->query( $query );
	}

	function get_ads_for_section( $section, $format = 'vertical', $count = 3 )
	{
		// grab random records
		$query =<<< EOF
			select ads.id, ads.title, ads.url, media.uuid 
				FROM ads, media_map, media 
				WHERE media_map.path = CONCAT('/ads/', ads.id) 
					AND NOW() BETWEEN ads.start_date AND ads.end_date
					AND media.id = media_map.media_id ORDER BY rand() LIMIT $count;
EOF;
		return $this->db->query($query);
	}

	function get_ad( $id )
	{
		return $this->db->get_where('ads', array('id' => (int)$id));		
	}
	
	/**
	 * Returns the ad url or root if not found
	 * TODO this should really track the dates in the db as well
	 */
	function register_click( $id )
	{
		$res = $this->db->get_where('ads', array('id' => (int)$id));
		if( $res->num_rows()) {
			$row = $res->row();
			$url = $row->url;
			$this->db->query("UPDATE ads SET clicks = clicks + 1 WHERE id = $id");
			if( strlen(trim($url)) ) {
				return $url;
			}
		}
		return '/';
	}

}