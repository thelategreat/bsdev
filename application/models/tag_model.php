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
		$this->db->where("${table_name}_id", $iid );
		$this->db->delete( "${table_name}_tag_map" );

		foreach( $tags as $tag ) {
			$tag = trim($tag);
			if ($tag == '') continue;
			
			$tid = $this->db->query("SELECT id FROM ${table_name}_tags WHERE name = ?", $tag);
			if( $tid->num_rows()) {
				$tid = $tid->row()->id;
			} else {
				$slug = $this->db->escape(slugify($tag));
				$tag = $this->db->escape($tag);

				$this->db->query("INSERT INTO ${table_name}_tags (name, slug) VALUES ($tag, $slug)");

				$sql = "SELECT id FROM ${table_name}_tags WHERE name = {$tag}";
				$query = $this->db->query($sql);

				if( $query->num_rows()) {
					$result = $query->row();
					$tid = $result->id;	
				}
			}

			$this->db->query("INSERT INTO ${table_name}_tag_map (${table_name}_id, 
								${table_name}_tag_id) VALUE ($iid, $tid)");

		}						
	}
	
	/**
	 * Delete tags for a given record
	 * @param string Name of table
	 * @param int Id of the item
	 */
	function delete_tags( $table_name, $iid ) 
	{
		$this->db->where("{$table_name}_id", $iid);
		$this->db->delete("{$table_name}_tag_map");
	}
	
	function search( $table_name, $tag ) {
		$tag = $this->db->escape($tag);
		
		$q = "SELECT {$table_name}_id as id FROM {$table_name}_tag_map 
				LEFT JOIN {$table_name}_tags ON {$table_name}_tag_map.{$table_name}_tag_id = {$table_name}_tags.id
				WHERE slug = {$tag}";
		$query = $this->db->query($q);
		
		return $query->result();
	}
	
	/**
	 * return tags for a given id
	 *
	 * @param $table_name the name of the main table
	 * @param $id the id of the object that has been tagged
	 */
	function get_tags( $table_name, $id )
	{
		$tags = array();
		$q = "SELECT a.name 
			FROM {$table_name}_tags as a
			LEFT JOIN ${table_name}_tag_map as b
			ON a.id = b.{$table_name}_tag_id
			WHERE a.id = b.${table_name}_tag_id 
			AND b.{$table_name}_id = $id";
		$query = $this->db->query( $q );

		foreach( $query->result() as $row ) {
			$tags[] = trim($row->name);
		}
		asort( $tags );
		
		return $tags;
		//		return implode(' ', $tags);
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
		$this->db->query( "CREATE TABLE IF NOT EXISTS `${table_name}_tags` (
		  			`id` int(11) NOT NULL AUTO_INCREMENT,
		  			`name` varchar(50) DEFAULT NULL,
		  			`slug` varchar(50) DEFAULT NULL,
		  			PRIMARY KEY (`id`),
		  			UNIQUE KEY `${table_name}_tag_name` (`name`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8");

		// this is the map
		$this->db->query( "CREATE TABLE IF NOT EXISTS `${table_name}_tag_map` (
		  			`id` int(11) NOT NULL AUTO_INCREMENT,
		  			`${table_name}_id` int(11) NOT NULL,
		  			`${table_name}_tag_id` int(11) NOT NULL,
		  			PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8");
	}

}