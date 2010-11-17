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
		$flds = 'id, parent_id, deletable, name, active';
		$groups_result = $this->get_tree( $flds, 1, false );
		foreach( $groups_result as $group ):
			$groups[] = $group; //array($group->name, $group->id, $this->get_items( $group->id ));
		endforeach;		
		
		// always one level deep
		foreach( $groups as $row ) {
			$row->children = $this->get_tree( $flds, $row->id, false );
		}
		// parent are from here to root
		$parents = $this->get_parents( $section );
		// so reverse it
		$parents = array_reverse( $parents );
		// and lop iff the first two elements
		array_shift($parents);
		array_shift($parents);
		// then visit each node in the path and fill in the children
		foreach( $parents as $elem ) {
			$group = $this->find_leaf( $elem['id'], $groups );
			if( $group )	{
				$group->children = $this->get_tree( $flds, $group->id, false );
			}
		}
		return $groups;
	}
	
	// depth first search
	function find_leaf( $id, $groups )
	{
		foreach( $groups as $group ) {
			if( count($group->children)) {
				return $this->find_leaf( $id, $group->children );				
			}
			if( $group->id == $id ) {
				return $group;
			}
		}
		return null;
	}
		
}