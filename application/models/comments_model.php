<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class comments_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();
  }

	function get_comments_count( $type, $table_id )
	{
		$this->db->select('count(*)');
		return $this->db->get_where( 'comment', array('table_ref' => $type, 'table_id' => $table_id));
	}
	
	function get_comments( $type, $table_id )
	{
		$this->db->select('comments.id, comments.comment_date, comments.comment, comments.approved, comments.votes, users.id as user_id, users.firstname, users.lastname');
		$this->db->join('users', 'author_id = users.id');
		$this->db->where('approved', 1 );
		$this->db->order_by('comment_date', 'ASC');
		return $this->db->get_where( 'comments', array('table_ref' => $type, 'table_id' => $table_id) );
	}

	function get_comments_queue( $page, $page_size )
	{
		$this->db->select('comments.id, comments.comment_date, comments.comment, comments.approved, comments.votes, users.id as user_id, users.firstname, users.lastname');
		$this->db->join('users', 'author_id = users.id');
    $this->db->order_by('comment_date', 'DESC');
//    $this->db->where('approved', 0);
//    $this->db->or_where('approved', 2);
		return $this->db->get( 'comments' );
	}

	function add_comment( $type, $table_id, $user_id, $comment )
	{
		$this->db->set( 'table_ref', $type );
		$this->db->set( 'table_id', $table_id );
		$this->db->set( 'author_id', $user_id );
		$this->db->set( 'comment', $comment );
		$this->db->insert('comments');
	}
	
	function approve( $id )
	{
		$this->db->set('approved', '1');
		$this->db->where('id', $id );
		$this->db->update('comments');		
	}

  function deny( $id )
  {
		$this->db->set('approved', '2');
		$this->db->where('id', $id );
		$this->db->update('comments');	
  }

	function rm( $id )
	{
		$this->db->where('id', $id );
		$this->db->delete('comments');		
	}

  function vote( $id, $vote )
  {
    $this->db->query("UPDATE comments SET votes = votes + " . (int)$vote . ' WHERE id = ' . (int)$id );
  }

}

?>
