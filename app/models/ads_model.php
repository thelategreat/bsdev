<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ads_model extends Model
{

  function __construct()
  {
    parent::Model();
  }

	function get_ad_list( $category = NULL, $page = 1, $limit = NULL )
	{
		return $this->db->query("SELECT * FROM ads ORDER BY start_date DESC");
	}

	function get_ads_for_section( $section, $format = 'vertical', $count = 3 )
	{
		$query =<<< EOF
			select ads.id, ads.title, ads.url, media.uuid 
				FROM ads, media_map, media 
				WHERE media_map.path = CONCAT('/ads/', ads.id) 
					AND media.id = media_map.media_id LIMIT $count;
EOF;
		return $this->db->query($query);
	}

}