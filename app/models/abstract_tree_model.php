<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This requires a table with the following fields:
 * CREATE TABLE ... (
 *   `id` int(11) NOT NULL AUTO_INCREMENT,
 *   `parent_id` int(11) NOT NULL DEFAULT '0',
 *   `sort_order` int(11) DEFAULT NULL,
 *   `deletable` binary(1) NOT NULL DEFAULT '0',
 *
 *      ... insert other fields specific to this domain
 *
 *   PRIMARY KEY (`id`)
 * ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
 *
 * also a root node is required with a parent_id of 0
 * INSERT INTO ... (parent_id, sort_order, ...) VALUES (0, 1, ...);
 */
abstract class abstract_tree_model extends Model
{
	function __construct( $db_name, $tree_item_field_name = 'name' )
  {
    parent::__construct();
		$this->tree_item_field_name = $tree_item_field_name;
		$this->db_name = $db_name;
	}

	protected function _get_tree( $flds = '*', $parent = 0, $recurse = true )
	{		
		//id, parent_id, deletable, 
		$q = "SELECT $flds FROM $this->db_name WHERE parent_id = $parent ORDER BY sort_order";
		$res = $this->db->query( $q );
		
		$ra = array();
		foreach( $res->result() as $row ) {
			$ra[] = $row;
		}
		
		// walk though the rows grabbing children
		if( $recurse ) {
			foreach( $ra as $row ) {
				$row->children = $this->_get_tree( $flds, $row->id, $recurse );
			}
		}
		
		return $ra;
	}
	
	// get one row
	function get( $id )
	{
		$this->db->where('id', $id );
		return $this->db->get($this->db_name)->row();		
	}

	function add( $data )
	{
		$max = $this->db->query("SELECT MAX(sort_order) as m FROM $this->db_name")->row();
		$data['sort_order'] = $max->m + 1;
		$this->db->insert($this->db_name, $data );				
	}

	function update( $id, $data )
	{
		$this->db->where('id', $id );
		$this->db->update( $this->db_name, $data );		
	}

	function rm( $id )
	{
		// delete kids too
		$this->db->where('parent_id', $id );
		$this->db->delete($this->db_name);
		// and the parent
		$this->db->where('id', $id );
		$this->db->delete($this->db_name);
	}

	function move_up( $id )
	{
		$this->db->where('id', $id);
		$data = $this->db->get( $this->db_name )->row();
		
		if( $data ) {
			$swap = $this->db->query("SELECT id, sort_order FROM $this->db_name WHERE parent_id = $data->parent_id AND sort_order < $data->sort_order ORDER BY sort_order DESC LIMIT 1")->row();
			if( $swap ) {
				$this->db->query("UPDATE $this->db_name SET sort_order = $swap->sort_order WHERE id = $data->id");
				$this->db->query("UPDATE $this->db_name SET sort_order = $data->sort_order WHERE id = $swap->id");
			}
		}		
	}

	function move_down( $id )
	{
		$this->db->where('id', $id);
		$data = $this->db->get($this->db_name)->row();
		if( $data ) {
			$swap = $this->db->query("SELECT id, sort_order FROM $this->db_name WHERE parent_id = $data->parent_id AND sort_order > $data->sort_order ORDER BY sort_order DESC LIMIT 1")->row();
			if( $swap ) {
				$this->db->query("UPDATE $this->db_name SET sort_order = $swap->sort_order WHERE id = $data->id");
				$this->db->query("UPDATE $this->db_name SET sort_order = $data->sort_order WHERE id = $swap->id");
			}
		}		
	}

	function mk_nested_select( $selected = 0, $offset = 0, $show_root = true )
	{
		$data = $this->_get_tree();
		
		if( $show_root ) {
			return $this->_mk_nested_select( $data, $selected, $offset );			
		} else {
			return $this->_mk_nested_select( $data[0]->children , $selected, $offset );			
		}
	}

	protected function _mk_nested_select( $data, $selected = 0, $offset = 0 )
	{
		$s = '';
		$spcs = '';
		$fld_name = $this->tree_item_field_name;
		
		for( $i = 0; $i < $offset; $i++ ) {
			$spcs .= '&nbsp;';
		}
				
		foreach( $data as $item ): 
			$s .= '<option value="' . $item->id . '"';
			if( $item->id == $selected ) {
				$s .= " selected ";
			}
			$s .= '>' . $spcs . $item->$fld_name . '</option>';
			if( count($item->children) ) {
				$s .= $this->_mk_nested_select( $item->children, $selected, $offset + 4 );
			} 
		endforeach;
		
		return $s;
	}

}
