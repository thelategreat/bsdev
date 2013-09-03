<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This is a hybrid adjacancy list/nested list tree
 *
 * This requires a table with the following fields:
 * CREATE TABLE ... (
 *   `id` int(11) NOT NULL AUTO_INCREMENT,
 *   `parent_id` int(11) NOT NULL DEFAULT '0',
 *   `lft` int(11) DEFAULT '0',
 *   `rgt` int(11) DEFAULT '0',
 *   `sort_order` int(11) DEFAULT NULL,
 *   `deletable` binary(1) NOT NULL DEFAULT '0',
 *
 *      ... insert other fields specific to this domain
 *
 *   PRIMARY KEY (`id`)
 * ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
 *
 * also a root node is required with a parent_id of 0
 * INSERT INTO ... (id, parent_id, sort_order, ...) VALUES (1, 0, 1, ...);
 */
abstract class abstract_tree_model extends CI_Model
{
	/**
	 * $table_name - the table name
	 * $tree_item_field_name - the field name from the table to display in the tree
	 */
	function __construct( $table_name, $tree_item_field_name = 'name' )
  {
    parent::__construct();
		// this is the field name is used to display the tree
		$this->tree_item_field_name = $tree_item_field_name;
		$this->table_name = $table_name;
	}

	function get_tree( $flds = '*', $parent = 0, $recurse = true, $parent_tree = array() )
	{		
		$q = "SELECT $flds FROM $this->table_name WHERE parent_id = $parent ORDER BY sort_order";
		$res = $this->db->query( $q );
		
		$ra = array();		
		foreach( $res->result() as $row ) {
			$row->parent_tree = array_merge($parent_tree, array($row->parent_id));
			$ra[] = $row;
		}
		
		// walk though the rows grabbing children
		foreach( $ra as $row ) {
			if( $recurse ) {
				$row->children = $this->get_tree( $flds, $row->id, $recurse, $row->parent_tree );
			} else {
				$row->children = array();
			}

			$row->depth = count($row->children);
		}
		
		return $ra;
	}
	
	// get one row
	function get( $id )
	{
		$this->db->where('id', $id );
		return $this->db->get($this->table_name)->row();		
	}

	function get_items( $parent = 1 )
	{
		$query = "SELECT * FROM $this->table_name WHERE parent_id = $parent ORDER BY sort_order";
		
		return $this->db->query( $query );
	}

	function add( $data )
	{
		$max = $this->db->query("SELECT MAX(sort_order) as m FROM $this->table_name")->row();
		$data['sort_order'] = $max->m + 1;
		$this->db->insert($this->table_name, $data );
    $this->build_mptt_tree( 1, 1 );
	}

	function update( $id, $data )
	{
		$this->db->where('id', $id );
		$this->db->update( $this->table_name, $data );		
	}

	function rm( $id )
	{
		// walk the tree and nuke on our way back up
		$tree = $this->get_tree('id, parent_id', $id );
		foreach( $tree as $node ) {
			if( $node->children ) {
				$this->rm( $node->id );
			}			
		}
		// the kids
		$this->db->where('parent_id', $id );
		$this->db->delete($this->table_name);
		// and this node
		$this->db->where('id', $id );
		$this->db->delete($this->table_name);
    $this->build_mptt_tree( 1, 1 );
	}

	function move_up( $id )
	{
		$this->db->where('id', $id);
		$data = $this->db->get( $this->table_name )->row();
		
		if( $data ) {
			$swap = $this->db->query("SELECT id, sort_order FROM $this->table_name WHERE parent_id = $data->parent_id AND sort_order < $data->sort_order ORDER BY sort_order DESC LIMIT 1")->row();
			if( $swap ) {
				$this->db->query("UPDATE $this->table_name SET sort_order = $swap->sort_order WHERE id = $data->id");
				$this->db->query("UPDATE $this->table_name SET sort_order = $data->sort_order WHERE id = $swap->id");
			}
      $this->build_mptt_tree( 1, 1 );
		}		
	}

	function move_down( $id )
	{
		$this->db->where('id', $id);
		$data = $this->db->get($this->table_name)->row();
		if( $data ) {
			$swap = $this->db->query("SELECT id, sort_order FROM $this->table_name WHERE parent_id = $data->parent_id AND sort_order > $data->sort_order ORDER BY sort_order DESC LIMIT 1")->row();
			if( $swap ) {
				$this->db->query("UPDATE $this->table_name SET sort_order = $swap->sort_order WHERE id = $data->id");
				$this->db->query("UPDATE $this->table_name SET sort_order = $data->sort_order WHERE id = $swap->id");
			}
      $this->build_mptt_tree( 1, 1 );
		}		
	}

	function get_parents( $id )
	{
		$parent_ids = array();
		do {
			$res = $this->db->query("SELECT id, parent_id, name FROM $this->table_name WHERE id = $id");
			if( $res->num_rows() ) {
				$row = $res->row();
				$parent_ids[] = array('id' => $row->id, 'parent_id' => $row->parent_id, 'name' => $row->name);
				$id = $row->parent_id;
			}
		} while( $res->num_rows() );
		return $parent_ids;
	}

	function mk_nested_select( $selected = 0, $offset = 0, $show_root = true, $depth = -1 )
	{
		$data = $this->get_tree();
		
		if( $show_root ) {
			return $this->_mk_nested_select( $data, $selected, $offset, $depth );
		} else {
			return $this->_mk_nested_select( $data[0]->children , $selected, $offset, $depth );
		}
	}

	protected function _mk_nested_select( $data, $selected = 0, $offset = 0, $depth = -1 )
	{
		$s = '';
		$spcs = '';
		$fld_name = $this->tree_item_field_name;
		
		for( $i = 0; $i < $offset; $i++ ) {
			$spcs .= '&nbsp;';
		}
				
		foreach( $data as $item ) {
			$s .= '<option value="' . $item->id . '"';
			if( $item->id == $selected ) {
				$s .= " selected ";
			}
			$s .= '>' . $spcs . $item->$fld_name . '</option>';
      if( ($depth == -1 && count($item->children)) || ($depth > -1 && $offset < $depth * 4 && count($item->children)) ) {
        $s .= $this->_mk_nested_select( $item->children, $selected, $offset + 4, $depth );
      }
    }

		return $s;
	}

  // create modified preorder traversal tree from adjacency list (aka, nested list)
  function build_mptt_tree( $parent_id, $left )
  {
    $right = $left + 1;

    $res = $this->db->query("SELECT id FROM $this->table_name WHERE parent_id = $parent_id");
    foreach( $res->result() as $row ) {
      $right = $this->build_mptt_tree( $row->id, $right );
    }

    $this->db->query("UPDATE $this->table_name SET lft=$left, rgt=$right WHERE id=$parent_id");
    return $right+1;
  }

  protected function lock_table()
  {
    $this->db->query("LOCK TABLE $this->table_name WRITE");
  }

  protected function unlock_table()
  {
    $this->db->query("UNLOCK TABLES");    
  }

}
