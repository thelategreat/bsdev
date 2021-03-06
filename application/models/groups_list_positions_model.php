<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class groups_list_positions_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();
    $this->load->model('lists_model');
  }

  function delete_all_group_lists ($id)
	{
		$q = "DELETE FROM groups_list_positions WHERE groups_id = '$id'";
		$this->db->query( $q );
	}
	
	function get_group_lists($group_id) {
		$q = "SELECT groups_list_positions.*, list_positions.name 
				FROM groups_list_positions 
				LEFT JOIN list_positions ON list_positions_id = list_positions.id WHERE groups_id = '$group_id'";
		$res = $this->db->query($q);
		return $res;
	}

	/**
	Returns the lists for a particular group with the list items for each list position.
	Result is an associative array with array keys identifying the list positions.
	@param Group ID
	@return Associative array of lists for a particular group
	*/
	function get_group_named_lists($group_id) {
		$q = "SELECT groups_list_positions.*, LOWER(list_positions.name) AS name FROM groups_list_positions 
			LEFT JOIN list_positions ON list_positions.id = groups_list_positions.list_positions_id
			WHERE groups_id = '$group_id'";
		$res = $this->db->query($q);
		$results = $res->result();
		
		$output = array();
		foreach ($results as &$r) {
			$r->data = false;
			$r->data = $this->lists_model->get_list($r->lists_id);
			$output[$r->name] = $r;
		}

		return $output;
	}
	
	function insert_group_list($group_id, $listkey, $listval) {
		$q = "INSERT INTO groups_list_positions (groups_id, list_positions_id, lists_id) VALUES ('$group_id', '$listkey', '$listval')";
		$this->db->query( $q );
	}

}

