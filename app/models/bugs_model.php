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
		$this->db->select('id, summary, description, created_on, submitted_by, status, type, assigned_to, (select count(*) from bugs_comments where bugs_comments.bug_id = bugs.id) as comment_count');
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
		$this->db->order_by('created_on');
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

	function init_tables( $force = FALSE )
	{
		// see if the table is there first
		if( !$force ) {
			$res = $this->db->query("SHOW TABLES LIKE 'bugs'");
			if( $res->num_rows() > 0 ) {
				return;
			}
		}
		
		// watch for AUTO_INCREMENT at end if cutting from SequelPro
		$query = <<<EOQ
		CREATE TABLE `bugs` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `summary` varchar(256) DEFAULT NULL,
		  `description` text,
		  `created_on` timestamp NULL DEFAULT NULL,
		  `submitted_by` varchar(64) DEFAULT NULL,
		  `status` varchar(64) DEFAULT NULL,
		  `type` varchar(64) DEFAULT NULL,
		  `assigned_to` varchar(64) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8		
EOQ;
	
		$this->db->query( "DROP TABLE IF EXISTS bugs" );
		$this->db->query( $query );
	}

}