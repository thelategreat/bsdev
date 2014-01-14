<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class list_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();
    $this->load->model('media_model');
    $this->load->model('products_model');
    $this->load->model('item_model');
    
    $this->items = array();
  }


    function load( $id ){       
        $q = "SELECT * FROM lists WHERE id = " . intval($id);
        $res = $this->db->query( $q );
        $list = NULL;
        if( $res->num_rows() ) {
            $row = $res->row();
            $list = new list_model();
            $list->id = $row->id;
            $list->name = $row->name;
            $list->can_delete = $row->can_delete;
            $list->get_items();
        }
        return $list;
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
      $item = item_model::load_by_type($row->type, $row->data_id);
      
      if ($item) $data[] = $item;
    }
    return $data;
  }
  
    function get_items(){
  
        $res = $this->db->query("SELECT * FROM list_items WHERE list_id = " . $this->id . " ORDER BY sort_order");
        foreach( $res->result() as $row ) {
            $this->items[] = item_model::load_by_type($row->type, $row->data_id);
        }
    }
  
  

 

}

