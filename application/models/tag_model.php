<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * generic tag model.
 * this is meant to be inherited and added tagging support to a model.
 */
class Tag_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * save tags for a given id
	 *
	 * @param $table_name the name of the main table
	 * @param $iid the primary_key id of the object in the main table that is being tagged
	 * @param $tags a string with space separated tags
	 */
	function save_tags( $table_name, $iid, $tags )
	{
		// locking??
		$tags = explode(' ', $tags);
		$this->db->where("${table_name}_id", $iid );
		$this->db->delete( "${table_name}_tag_map" );
		
		foreach( $tags as $tag ) {
			$tid = $this->db->query("SELECT id FROM ${table_name}_tags WHERE name = '$tag'");
			if( $tid->num_rows()) {
				$tid = $tid->row()->id;
			} else {
				$slug = slugify($tag);
				$this->db->query("INSERT INTO ${table_name}_tags (name, slug) VALUES ('$tag', '$slug')");
				$tid = $this->db->query("SELECT id FROM ${table_name}_tags WHERE name = '$tag'");
				if( $tid->num_rows()) {
					$tid = $tid->row()->id;
				}
			}
			$this->db->query("INSERT INTO ${table_name}_tag_map (${table_name}_id, ${table_name}_tag_id) VALUE ($iid, $tid)");
		}						
	}
	
	/**
	 * return tags for a given id
	 *
	 * @param $table_name the name of the main table
	 * @param $id the id of the object that has been tagged
	 */
	function get_tags( $table_name, $id )
	{
		$tags = '';
		$tra = array();
		$q = "SELECT a.name 
			FROM ${table_name}_tags as a, ${table_name}_tag_map as b 
			WHERE a.id = b.${table_name}_tag_id 
			AND b.${table_name}_id = $id";
		$t = $this->db->query( $q );
		foreach( $t->result() as $row ) {
			$tra[] = $row->name;
		}
		asort( $tra );
		foreach( $tra as $tag ) {
			$tags .= $tag . " ";			
		}
		
		return trim($tags);		
	}

	/**
	 * create the tag tables for a main table
	 *
	 * @param $table_name the name of the main table
	 * @param $drop drop existing tables first (default false)
	 */
	function create_tag_tables( $table_name, $drop = false )
	{
		if( $drop ) {
			$this->db->query("DROP TABLE IF EXISTS ${table_name}_tags");
			$this->db->query("DROP TABLE IF EXISTS ${table_name}_tag_map");
		}
		// this is our tag table
		$this->db->query( "CREATE TABLE `${table_name}_tags` (
		  			`id` int(11) NOT NULL AUTO_INCREMENT,
		  			`name` varchar(50) DEFAULT NULL,
		  			`slug` varchar(50) DEFAULT NULL,
		  			PRIMARY KEY (`id`),
		  			UNIQUE KEY `${table_name}_tag_name` (`name`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8");

		// this is the map
		$this->db->query( "CREATE TABLE `${table_name}_tag_map` (
		  			`id` int(11) NOT NULL AUTO_INCREMENT,
		  			`${table_name}_id` int(11) NOT NULL,
		  			`${table_name}_tag_id` int(11) NOT NULL,
		  			PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8");
	}

}