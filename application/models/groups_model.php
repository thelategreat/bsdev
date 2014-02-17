<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require 'abstract_tree_model.php';

class Groups_model extends abstract_tree_model
{

  function __construct()
  {
    parent::__construct('group_tree');

    //$this->build_mptt_tree( 1, 1 );
  }

	function get_group_tree( $parent = 0, $recurse = true )
	{
		return $this->get_tree('id, parent_id, deletable, name, active, route');
	}
	
  /*
	function get_menu_tree( $section )
	{
    if( empty($section)) {
      $section = 0;
    }

		$flds = 'id, parent_id, deletable, name, active';
		$groups_result = $this->get_tree( $flds, 1, false );
		foreach( $groups_result as $group ):
			$groups[] = $group; //array($group->name, $group->id, $this->get_items( $group->id ));
		endforeach;		
		
		// always one level deep
		foreach( $groups as &$row ) {
			$row->children = $this->get_tree( $flds, $row->id, false );
      $row->child_ids = $this->get_child_ids($row->children);
		}

    // grab the parents
    $parents = $this->get_parents( $section );
    $parents = array_reverse( $parents );
    // lop off root
    array_shift($parents);
    // walk down the parent path, filling in the children
    $top = $groups;
    foreach( $parents as $level ) {
        $group = $this->find_node( $level['id'], $top );
        if( $group ) {
          $group->children = $this->get_tree( $flds, $group->id, false );
          //$group->child_ids = $this->get_child_ids($group->children);
          $top = $group->children;
        }
    }

		return $groups;
	}*/

  function find_node( $id, $groups )
  {
    foreach( $groups as $group ) {
      if( $group->id == $id ) {
        return $group;
      }
    }
    return null;
  }
  
  function get_group_by_id( $id )
  {
    $this->db->where('id', $id );
    return $this->db->get($this->table_name)->row();
  }
  
  /**
   * Get the sequence of parents of this page leading to the root
   * @param Starting page object
   * @returns Array of ids pointing to pages in chain to root
   */
  function get_parent_tree( $group ) 
  {
    $cur_group = $group;

  	if (!$group) return false;
  	  
  	$parent_tree = array();

  	while (isset($cur_group->parent_id) && $cur_group->parent_id != 0 && $cur_group->parent_id != 1) {
  		array_push($parent_tree, $cur_group->parent_id);
  		$cur_group = $this->get_group_by_id($group->parent_id);
  	} 

  	$group->parent_tree = array_reverse($parent_tree);
  	
  	return $group;	  
  }

  /**
    Export the list of dynamic routes to the dynamic_routes.php file which gets loaded on initialization
    */
  function export_routes() {
    $this->db->where('route IS NOT NULL');
    $query = $this->db->get( 'group_tree' );
    $result = $query->result();

    $handle = @fopen(APPPATH . '/config/dynamic_routes.php', 'w');

    if (!$handle) {
      show_error("Unable to save dynamic route paths to " . APPPATH . "config/dynamic_routes.php");
    }

    fwrite ($handle, "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n\n");

    foreach( $result as $row )
    {
        fwrite($handle, "\$route[ '{$row->route}' ] = '/section/view/{$row->id}';\n");
        fwrite($handle, "\$route[ '{$row->route}/:any' ] = '/section/view/{$row->id}';\n");
    }

    fclose($handle);
  }

}