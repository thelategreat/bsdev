<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
*/
class Bugs_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();
  }

	function get_bugs( $filter, $page, $page_size )
	{
		// FIXME WHERE ... AND (... LIKE OR ... LIKE)
		$this->db->select('bugs.id, summary, description, created_on, submitted_by, bugs.status, type, assigned_to, (select count(*) from bugs_comments where bugs_comments.bug_id = bugs.id) as comment_count');
		if( $filter ) {
			$terms = explode(' ', $filter );
			foreach( $terms as $term ) {
				$parts = explode( ':', $term );
				if( count($parts) == 2 ) {
					if( $parts[0] == 'status' ) {
						$this->db->where( 'status', $parts[1] );
					}
				} else {
					$this->db->or_like(array('summary' => $term));
					$this->db->or_like(array('description' => $term));
				}
			}
		}
		$this->db->join('bugs_statuses', 'bugs.status = bugs_statuses.status');
		$this->db->order_by('bugs_statuses.sort_order, created_on ASC');
		$this->db->limit( $page_size, ($page - 1) * $page_size );
		$result =  $this->db->get('bugs');
		//echo $this->db->last_query();
		//exit;
		return $result;
	}

	function add_bug( $summary, $description, $type, $user )
	{
		$this->db->set('summary', $summary);
		$this->db->set('description', $description);
		$this->db->set('type', $type);
		$this->db->set('submitted_by', $user);
		
		$this->db->set('created_on', "NOW()", false);
		$this->db->set('status', 'new');
		$this->db->set('assigned_to', $user );
		$this->db->insert("bugs");	
		
		// update activity
		$this->add_activity($this->db->insert_id(), 'created', $user );
	}

	function update_bug( $bug_id, $summary, $description, $type, $status, $assigned_to, $user )
	{
		$activity = '';
		$res = $this->db->get_where('bugs', array('id' => $bug_id ));
		$row = $res->row();
		if( $row->summary != $summary ) {
			$this->add_activity($bug_id, "update summary", $user );
		}
		if( $row->description != $description ) {
			$this->add_activity($bug_id, "update description", $user );
		}
		if( $row->type != $type ) {
			$this->add_activity($bug_id,"type change", $user );
		}
		if( $row->status != $status ) {
			$this->add_activity($bug_id, "status change", $user );
		}
		if( $row->assigned_to != $assigned_to ) {
			$this->add_activity( $bug_id, 'reassigned', $user );
		}
		
		$this->db->where('id', $bug_id);
		$this->db->set('summary', $summary);
		$this->db->set('description', $description);
		$this->db->set('type', $type);
		$this->db->set('status', $status);
		$this->db->set('assigned_to', $assigned_to );
		$this->db->update("bugs");		
	}

	function get_comments( $bug_id  )
	{
		$this->db->where('bug_id', $bug_id );
		$this->db->order_by('created_on', 'DESC');
		return $this->db->get('bugs_comments');
	}

	function add_comment( $bug_id, $comment, $user )
	{
		$this->db->set('bug_id', $bug_id );
		$this->db->set('comment', $comment );
		$this->db->set('submitted_by', $user );
		$this->db->set('created_on', "NOW()", false);
		$this->db->insert('bugs_comments');	
		
		$this->add_activity( $bug_id, 'commented', $user );	
	}

	function get_statuses()
	{
		$this->db->order_by('sort_order');
		return $this->db->get('bugs_statuses');
	}

	function get_activity( $page, $page_size )
	{
		$this->db->order_by('activity_date', 'DESC');
		$this->db->limit( $page_size, ($page - 1) * $page_size );
		return $this->db->get('bugs_activity');
	}

	function add_activity( $bug_id, $msg, $user ) 
	{
		$this->db->set('bug_id', $bug_id);
		$this->db->set('activity_date', 'NOW()', false );
		$this->db->set('user', $user);
		$this->db->set('activity', $msg );
		$this->db->insert('bugs_activity');		
	}

}