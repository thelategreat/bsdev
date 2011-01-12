<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'abstract_tree_model.php' );

class Pages_model extends abstract_tree_model
{

  function __construct()
  {
    parent::__construct('pages','title');
  }

	function get_pages_tree( $parent = 0, $recurse = true )
	{		
		// need to handle links differntly, put in a separate field
		return $this->get_tree('id, title, active, parent_id, page_type, deletable, body');
	}
	
	function get_page( $title )
	{
		$this->db->where('title', $title );
		return $this->db->get($this->table_name)->row();
	}

  function get_page_by_id( $id )
  {
    $this->db->where('id', $id );
    return $this->db->get($this->table_name)->row();
  }


}