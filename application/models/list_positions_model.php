<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class list_positions_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();
  }

  function get_list_positions ()
	{
		$q = "SELECT * FROM list_positions ORDER BY name ASC";
		$res = $this->db->query( $q );
		return $res;
	}

}

