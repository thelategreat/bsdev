<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class templates_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();
  }

  function get_templates()
	{
		$q = "SELECT * FROM templates ORDER BY name ASC";
		$result = $this->db->query( $q )->result();
		return $result;
	}
}

