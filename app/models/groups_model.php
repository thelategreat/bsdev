<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require 'abstract_tree_model.php';

class Groups_model extends abstract_tree_model
{

  function __construct()
  {
    parent::__construct('group_tree');
  }

	function get_group_tree( $parent = 0, $recurse = true )
	{		
		return $this->get_tree('id, parent_id, deletable, name, active');
	}
	
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
		foreach( $groups as $row ) {
			$row->children = $this->get_tree( $flds, $row->id, false );
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
          $top = $group->children;
        }
    }
		return $groups;
	}

  function find_node( $id, $groups )
  {
    foreach( $groups as $group ) {
      if( $group->id == $id ) {
        return $group;
      }
    }
    return null;
  }

}