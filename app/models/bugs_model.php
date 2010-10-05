<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bugs_model extends Model
{

  function __construct()
  {
    parent::Model();
  }

	function get_bugs( $filter, $page, $page_size )
	{
		return $this->db->query('SELECT * FROM bugs ORDER BY created_on');
	}

}