<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class maillist_model extends Model
{
  function maillist_model()
  {
    parent::Model();
  }

	function get_templates()
	{
		return $this->db->query('SELECT id, name FROM ml_templates ORDER BY name');
	}

	function get_template( $id )
	{
		$this->db->where('id', $id );
		return $this->db->get('ml_templates');
	}

	function add_template( $data )
	{
		$this->db->insert('ml_templates', $data );
	}

	function update_template( $data )
	{
		$this->db->where('id', $data['id'] );
		$this->db->update('ml_templates', $data );
	}

	function get_messages()
	{
		$q = "SELECT mlm.id, mlm.subject, mlm.from, mlm.status, mlm.send_on, ml.name 
					FROM ml_messages as mlm, ml_list as ml 
					WHERE mlm.ml_list_id = ml.id";
					
		return $this->db->query( $q );
	}

	function get_message(  $id )
	{
		return $this->db->query("SELECT * FROM ml_messages WHERE id = " . $id );		
	}

	function add_message( $data )
	{ 
		$this->db->insert('ml_messages', $data );		
	}

	function update_message( $data )
	{
		$this->db->update('ml_messages', $data );
	}

	function get_mllist_select( $dflt = NULL )
	{
		$res = $this->db->get('ml_list');
		$s = '';
		foreach( $res->result() as $row ) {
			$s .= "<option value='$row->id'";
			if( $dflt and $dflt == $row->id ) {
				$s .= " selected ";
			}
			$s .= ">$row->name</option>";
		}
		return $s;
	}

}