<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class comments_model extends Model
{

  function __construct()
  {
    parent::Model();
  }

	function get_comments_count( $type, $table_id )
	{
		$this->db->select('count(*)');
		return $this->db->get_where( 'comment', array('table_ref' => $type, 'table_id' => $table_id));
	}
	
	
	function get_comments( $type, $table_id )
	{
		$this->db->join('users', 'author_id = users.id');
		$this->db->order_by('comment_date', 'ASC');
		return $this->db->get_where( 'comments', array('table_ref' => $type, 'table_id' => $table_id) );
	}
	
	function add_comment( $type, $table_id, $user_id, $comment )
	{
		$this->db->set( 'table_ref', $type );
		$this->db->set( 'table_id', $table_id );
		$this->db->set( 'author_id', $user_id );
		$this->db->set( 'comment', $comment );
		$this->db->insert('comments');
	}
	
}

?>