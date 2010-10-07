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
		return $this->db->query('SELECT * FROM bugs ORDER BY created_on');
	}


	function init_tables( $force = FALSE )
	{
		if( !$force ) {
			$res = $this->db->query("SHOW TABLES LIKE 'bugs'");
			if( $res->num_rows() > 0 ) {
				return;
			}
		}
		
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