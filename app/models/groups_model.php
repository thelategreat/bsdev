<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Groups_model extends Model
{

  function __construct()
  {
    parent::Model();
		$this->db_name = 'group_tree';
  }

	function get( $id )
	{
		$this->db->where('id', $id );
		return $this->db->get($this->db_name)->row();		
	}

	function get_group_tree( $parent = 0, $recurse = true )
	{
		$q = "SELECT id, parent_id, deletable, name FROM group_tree WHERE parent_id = $parent ORDER BY sort_order";
		$res = $this->db->query( $q );
		$ra = array();
		foreach( $res->result() as $row ) {
			$ra[] = $row;
		}
		
		if( $recurse ) {
			foreach( $ra as $row ) {
				$row->children = $this->get_group_tree( $row->id, $recurse );
			}
		}
		
		return $ra;
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

	function mk_nested_select( $selected = 0, $offset = 0 )
	{
		$data = $this->get_group_tree();
		return $this->private_mk_nested_select( $data[0]->children , $selected, $offset );
	}

	private function private_mk_nested_select( $data, $selected = 0, $offset = 0 )
	{
		$s = '';
		$spcs = '';
		for( $i = 0; $i < $offset; $i++ ) {
			$spcs .= '&nbsp;';
		}
		foreach( $data as $item ): 
			$s .= '<option value="' . $item->id . '"';
			if( $item->id == $selected ) {
				$s .= " selected ";
			}
			$s .= '>' . $spcs . $item->name . '</option>';
			if( count($item->children) ) {
				$s .= $this->private_mk_nested_select( $item->children, $selected, $offset + 4 );
			} 
		endforeach;
		
		return $s;
	}


}