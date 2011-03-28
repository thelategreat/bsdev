<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class home_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();
  }

	function get_top_level_groups()
	{
		$query =<<<EOF
		SELECT * FROM group_tree WHERE parent_id = 1 ORDER BY sort_order
EOF;

		return $this->db->query( $query );
	}

}
