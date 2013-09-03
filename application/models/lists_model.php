<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class lists_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();
    $this->load->model('media_model');
    $this->load->model('products_model');
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
      $list->can_delete = $row->can_delete;
			$list->items = array();
			$res = $this->db->query("SELECT * FROM list_items WHERE list_id = " . $list->id . " ORDER BY sort_order");
			foreach( $res->result() as $row ) {
				$list->items[] = $row;
      }
		}
		return $list;
	}

  function get_item_by_type($type, $id) {
    switch (strtolower(trim($type))) {
      case 'article':
        $sql = "SELECT a.id, title, body, excerpt, ac.category, publish_on, author,
              owner, ast.status, a.group as group_id, gt.name as group_name,
              u.firstname, u.lastname, u.nickname 
              FROM articles a
              LEFT JOIN article_categories ac ON a.category = ac.id 
              LEFT JOIN article_statuses ast ON a.status = ast.id 
              LEFT JOIN group_tree gt ON a.group = gt.id 
              LEFT JOIN users u ON a.owner = u.username 
              WHERE a.id = $id";
        break;
        
      case 'product':
        $product = $this->products_model->getProduct($id);
        $product->object_type = $type;
        return $product;
      break;

      default: 
        return false;
      break;


    }

    if (isset($sql)) {
      $res = $this->db->query($sql);
      if ($res->num_rows()) {
        $item = $res->row();
        $item->media = $this->media_model->get_media_for_path("/articles/$item->id", 'general', 1);
        $item->object_type = $type; // The list stores the list object type
        return $item;
      }
    } 
  }

  /**
    Gets all the items in a named list
  */
  function get_list_items_by_name( $name )
  {
    $q = "SELECT * FROM 
            list_items
          LEFT JOIN lists ON list_items.list_id = lists.id 
          WHERE LOWER(TRIM(name)) = " . $this->db->escape(strtolower(trim($name))) . 
          ' ORDER BY sort_order';
    $res = $this->db->query( $q );
    
    $data = array();
    foreach( $res->result() as $row ) {
      $item = $this->get_item_by_type($row->type, $row->data_id);
      

      /*  
 		$q = "
      SELECT a.id, title, body, excerpt, ac.category, publish_on, author, 
            owner, ast.status, a.group as group_id, gt.name as group_name, 
            u.firstname, u.lastname, u.nickname
    	FROM articles as a, article_categories as ac, article_statuses as ast, group_tree as gt, users as u
      WHERE a.category = ac.id AND a.status = ast.id AND a.group = gt.id AND u.username = a.owner
      AND a.id = $row->data_id";

     $res = $this->db->query( $q );
      if( $res->num_rows() ) {
        $ritem = $res->row();
			  $ritem->media = $this->media_model->get_media_for_path("/articles/$ritem->id", 'general', 1);
        $data[] = $ritem;
      }*/
      if ($item) $data[] = $item;
    }
    return $data;
  }

  function get_list_items_by_name_old( $name, $aslist = false )
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
    Add a list. Usually (always?) called from the ajax callback /admin/lists/save
    @param List name
    @param List creator's name
    @param Is list deletable (bool)
    @param List items - array of list name, type, record ID
   */
	function add_list( $name, $creator, $can_delete, $items )
	{
    $this->db->trans_start();

    $this->db->set('name', $name );
    $this->db->set('creator', $creator );
    if( $can_delete !== NULL ) {
      $this->db->set('can_delete', $can_delete );
    }  
    $this->db->insert('lists');
		$id = $this->db->insert_id();

		for( $i = 0; $i < count($items); $i++ ) {
				$this->db->set('list_id', $id );
        $this->db->set('data_id', $items[$i]['id']);
				$this->db->set('title', $items[$i]['title']);
				$this->db->set('type', $items[$i]['type'] );
				$this->db->set('sort_order', $i + 1);
				$this->db->insert('list_items');
    }

    $this->db->trans_complete();
	}
	
	/**
    Update a list. Usually (always?) called from the ajax callback /admin/lists/save
    @param List ID
    @param List name
    @param Is list deletable (bool)
    @param List items - array of list name, type, record ID
	 */
	function update_list( $id, $name, $can_delete, $items )
	{
		$this->db->set('name', $name );
    if( $can_delete !== NULL ) {
      $this->db->set('can_delete', $can_delete );
    }  
    $this->db->where( 'id', $id );
		$this->db->update('lists');
		
    // Save the list items
		$this->db->where( 'list_id', $id );
		$this->db->delete( 'list_items' );
		for( $i = 0; $i < count($items); $i++ ) {
      $this->db->set('list_id', $id );
      $this->db->set('data_id', $items[$i]['id']);
			$this->db->set('title', $items[$i]['title']);
			$this->db->set('type', $items[$i]['type']);
			$this->db->set('sort_order', $i + 1);
			$this->db->insert('list_items');
    }
	}
	
	/**
	 *
	 */
  function rm_list( $id )
	{
    //
		$this->db->where('id', intval($id));
    $this->db->delete('lists');
    $this->db->where('list_id', intval($id));
    $this->db->delete('list_items');
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

