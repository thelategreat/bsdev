<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class maillist_model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
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

	function get_template_select( $dflt = NULL )
	{
		$res = $this->db->get('ml_templates');
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

	function update_subscriptions( $email, $list_ids )
	{
		// this comes from the front end so it's a bit wierd
		// first we need to check if this user is in our user list
		$this->db->where('email', $email );
		$res = $this->db->get('ml_subscr');
		if( $res->num_rows() == 0 ) {
			$this->db->insert('ml_subscr', array('email' => $email, 'pref_format' => 'HTML', 'status' => 'active'));
			
			$this->db->where('email', $email );
			$res = $this->db->get('ml_subscr');
		}
		
		$id = $res->row()->id;
		if( $id ) {
			// now we nuke thier old subscriptions and add the new ones
			$this->db->where('subscr_id', $id );
			$this->db->delete('ml_subscr_list_map');
		
			foreach( $list_ids as $lid ) {
				$this->db->insert('ml_subscr_list_map', array('subscr_id' => $id, 'list_id' => $lid));
			}
		} else {
			error('id was zero');
		}
	}
	
	function get_subscriptions( $email ) 
	{
		$q = "select l.id, l.name from ml_subscr as s, ml_subscr_list_map as slm, ml_list as l 
			WHERE s.id = slm.subscr_id AND
			slm.list_id = l.id AND
			s.email = " . $this->db->escape($email);
			
			return $this->db->query( $q );
			
	}
}