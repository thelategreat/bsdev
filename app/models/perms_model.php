<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class perms_model extends Model
{

  function perms_model()
  {
    parent::Model();
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