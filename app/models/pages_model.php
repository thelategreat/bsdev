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
		return $this->get_tree('id, title, active, parent_id, page_type, deletable, body', $parent);
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
  
  /**
   * Get the sequence of parents of this page leading to the root
   * @param Starting page object
   * @returns Array of ids pointing to pages in chain to root
   */
  function get_parent_tree( $page ) 
  {
  		if (!$page) return false;
  		
	  $parent_tree = array();
	  while (isset($page->parent_id) && $page->parent_id != 0) {
	    array_push($parent_tree, $page->parent_id);	  
	  	$page =	$this->get_page_by_id($page->parent_id);
	  } 

	  $page->parent_tree = array_reverse($parent_tree);
	  return $page;	  
  }


}