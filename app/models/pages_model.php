<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Pages_model extends Model
{
	function __construct() 
	{
		parent::Model();
		$this->load->database();
	}
	
	
	function getPageTitles( $parent = 0, $recurse = true )
	{
		$q = "SELECT id, title, active, parent_id, page_type FROM pages WHERE parent_id = $parent ORDER BY sort_order";
		$res = $this->db->query( $q );
		$ra = array();
		foreach( $res->result() as $row ) {
			$ra[] = $row;
		}
		
		if( $recurse ) {
			foreach( $ra as $row ) {
				$row->children = $this->getPageTitles( $row->id, $recurse );
			}
		}
		
		return $ra;
	}
	
	function add_page( $data )
	{
		$max = $this->db->query('SELECT MAX(sort_order) as m FROM pages')->row();
		$data['sort_order'] = $max->m + 1;
		$this->db->insert('pages', $data );		
	}
	
	function move_up( $id )
	{
		$this->db->where('id', $id);
		$page = $this->db->get('pages')->row();
		if( $page ) {
			$swap = $this->db->query("SELECT id, sort_order FROM pages WHERE parent_id = $page->parent_id AND sort_order < $page->sort_order ORDER BY sort_order DESC LIMIT 1")->row();
			if( $swap ) {
				//echo $swap->id;
				$this->db->query("UPDATE pages SET sort_order = $swap->sort_order WHERE id = $page->id");
				$this->db->query("UPDATE pages SET sort_order = $page->sort_order WHERE id = $swap->id");
			}
		}		
	}

	function move_down( $id )
	{
		$this->db->where('id', $id);
		$page = $this->db->get('pages')->row();
		if( $page ) {
			$swap = $this->db->query("SELECT id, sort_order FROM pages WHERE parent_id = $page->parent_id AND sort_order > $page->sort_order ORDER BY sort_order DESC LIMIT 1")->row();
			if( $swap ) {
				//echo $swap->id;
				$this->db->query("UPDATE pages SET sort_order = $swap->sort_order WHERE id = $page->id");
				$this->db->query("UPDATE pages SET sort_order = $page->sort_order WHERE id = $swap->id");
			}
		}		
	}
	
	function get_page( $title )
	{
		$this->db->where('title', $title );
		return $this->db->get('pages')->row();
	}
	
}