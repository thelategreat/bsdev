<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bugs_model extends Model
{

  function __construct()
  {
    parent::Model();
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
		$this->db->join('bug_statuses', 'bugs.status = bug_statuses.status');
		$this->db->order_by('bug_statuses.sort_order, created_on');
		$this->db->limit( $page_size, ($page - 1) * $page_size );
		$result =  $this->db->get('bugs');
		//echo $this->db->last_query();
		//exit;
		return $result;
	}


	function get_comments( $bug_id  )
	{
		$this->db->where('bug_id', $bug_id );
		$this->db->order_by('created_on', 'DESC');
		return $this->db->get('bugs_comments');
	}

	function get_statuses()
	{
		$this->db->order_by('sort_order');
		return $this->db->get('bug_statuses');
	}

}