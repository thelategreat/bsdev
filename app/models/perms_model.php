<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class perms_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();
  }


	function get_all_roles()
	{
		return $this->db->query("SELECT id, role FROM user_roles");
	}

	function get_roles()
	{
		return $this->db->query("SELECT id, role FROM user_roles WHERE id <> 1");
	}

	function get_routes()
	{
		return $this->db->query("SELECT id, route FROM routes");
	}


}