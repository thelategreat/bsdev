<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class item_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();
    $this->load->model('media_model');
    $this->load->model('products_model');
  }


    function load( $id ){ 
    
    }
    
    function load_by_type($type, $id) {
        switch (strtolower(trim($type))) {
            case 'article':
                $this->load->model('articles_model');
                $item = articles_model::load($id);
                return $item;
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


    }
}