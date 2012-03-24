<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class lists_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();
  }

  function get_lists()
	{
		$q = "SELECT * FROM lists ORDER BY name ASC";
		$res = $this->db->query( $q );
		return $res;
	}

  function get_list( $id )
	{
		$q = "SELECT * FROM lists WHERE id = " . intval($id);
		$res = $this->db->query( $q );
		$list = NULL;
		if( $res->num_rows() ) {
			$row = $res->row();
			$list = new stdClass();
			$list->id = $row->id;
      $list->name = $row->name;
			$list->items = array();
			$res = $this->db->query("SELECT * FROM list_items WHERE list_id = " . $list->id . " ORDER BY sort_order");
			foreach( $res->result() as $row ) {
				$list->items[] = $row->url;
      }
		}
		return $list;
	}

  function get_list_items_by_name( $name, $aslist = false )
	{
		$q = "SELECT * FROM lists WHERE LOWER(name) = '" . $this->db->escape(strtolower($name)) ."'";
		$res = $this->db->query( $q );
		$items = array();
		if( $res->num_rows() ) {
			$row = $res->row();
			$res = $this->db->query("SELECT * FROM list_items WHERE list_id = " . $list->id . " ORDER BY sort_order");
			foreach( $res->result() as $row ) {
				$items[] = $row->url;
      }
      return $items;
    }
    // if we want the actual data rather than just the urls
    // NOTE this is a bit primative
    if( $aslist ) {
      $blobs = array();
      for( $i = 0; $i < count($items); $i++ ) {
        $tmp = split($items[$i], '/');
        switch( $tmp[0] ) {
        case "article":
          $row = $this->db->query("SELECT * FROM article WHERE id = " . $tmp[2] )->row();
          $blobs[] = $row;
          break;
        }
      }
      $items = $blobs;
    }

		return $items;
	}

	/**
	 *
	 */
	function add_list( $name, $creator, $items )
	{
    $this->db->set('name', $name );
    $this->db->set('creator', $creator );
		$this->db->insert('lists');
		$id = $this->db->insert_id();
	
		for( $i = 0; $i < count($items); $i++ ) {
			if( strlen(trim($items[$i]))) {
				$this->db->set('list_id', $id );
				$this->db->set('url', $items[$i]);
				$this->db->set('type', '' );
				$this->db->set('sort_order', $i + 1);
				$this->db->insert('list_items');
			}
    }
	}
	
	/**
	 *
	 */
	function update_list( $id, $name, $items )
	{
		$this->db->set('name', $name );
		$this->db->where( 'id', $id );
		$this->db->update('lists');
		
    // lock?
		$this->db->where( 'list_id', $id );
		$this->db->delete( 'list_items' );
		for( $i = 0; $i < count($items); $i++ ) {
			if( strlen(trim($items[$i]))) {
				$this->db->set('list_id', $id );
				$this->db->set('url', $items[$i]);
				$this->db->set('type', '' );
				$this->db->set('sort_order', $i + 1);
				$this->db->insert('list_items');
			}
    }
	}
	
	/**
	 *
	 */
  function rm_list( $id )
	{
		/*
		$this->db->where('poll_id', intval($id));
		$this->db->delete('poll_answers');
    */
    //
		$this->db->where('id', intval($id));
		$this->db->delete('lists');
	}

  /**
   *
   */
  function add_to_list( $list_id, $url )
  {
    $this->db->set('list_id', $list_id );
    $this->db->set('url', $url );
    $this->db->insert('list_items');
  }


  function lists_select()
  {
    $s = '<select name="lists" id="lists-sel">';
    $lists = $this->get_lists();
    foreach( $lists->result() as $row ) {
      $s .= '<option value="' . $row->id . '">' . $row->name . '</option>';
    }
    $s .= '</select>';
    return $s;
  }
}

