<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Order_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();
  }

  function get_orders( $page = 1, $limit = NULL )
  {
    $this->db->order_by( 'order_dt', 'desc' );
    if( $limit ) {
      $this->db->limit( $limit, ($page - 1) * $limit );
    }

    return $this->db->get('orders');
  }

  function get_orders_for_user( $user_id ) 
  {
    $this->db->where('user_id', (int)$user_id );
    $this->db->order_by('order_dt', 'desc');
    $res = $this->db->get('orders');
    //echo $this->db->last_query();
    //echo $res->num_rows();
    return $res;
  }


  function calc_shipping_cost( $ship_code, $weight, $postal, $country = 'ca' )
  {
    switch( $ship_code ) {
    case 'ppr':
      return 24.41;
    case 'xpr':
      return 15.58;
    default:
      return 9.72;
    }
  }

  function get_shipping_options()
  {
    return array( 
      'cpr' => 'Regular Parcel (3 days)',
      'xpr' => 'Xpresspost (2 days)',
      'ppr' => 'Priority Post (next day)' 
    );
  }

  function add_order( $order_info, $items )
  {
    $order_num = $this->gen_order_number();
    $this->db->set('order_no', $order_num );
    $this->db->set('order_dt', 'now()', false);
    $this->db->set('shipto', $order_info['shipto']);
    $this->db->set('billto', $order_info['billto']);
    $this->db->set('shipvia', $order_info['ship_via']);
    $this->db->set('shipcost', $order_info['ship_cost']);
    $this->db->set('ccno', $order_info['ccno']);
    $this->db->set('ccname', $order_info['ccname']);
    $this->db->set('ccexp', $order_info['ccexp']);
    $this->db->set('tax1', $order_info['tax1']);
    $this->db->set('tax2', $order_info['tax2']);
    $this->db->set('subtotal', '0.00');
    $this->db->set('state', 'new');
    $this->db->set('user_id', $order_info['user_id']);
    $this->db->insert('orders');

    $order_id = $this->db->insert_id();

    foreach( $items as $item ) {
      $this->db->set('order_id', $order_id );
      $this->db->set('item_id', $item['id']);
      $this->db->set('qty', $item['qty']);
      $this->db->set('price', $item['price']);
      $this->db->set('name', $item['name']);
      $this->db->insert('order_line');
    }

    return $order_num;
  }

  function gen_order_number()
  {
    // YYMMDD-HHMMSS-RANDOM
    return date('ymd-His') . '-' . sprintf("%04d",rand(1,9999));
  }

  /* 
   * Returns the name of the card if valid numericly or false.
   * This does not check if the card is valid for purchase.
   */
  function check_cc($cc, $extra_check = false)
  {
    $cards = array(
      "visa" => "(4\d{12}(?:\d{3})?)",
      "amex" => "(3[47]\d{13})",
      "jcb" => "(35[2-8][89]\d\d\d{10})",
      "maestro" => "((?:5020|5038|6304|6579|6761)\d{12}(?:\d\d)?)",
      "solo" => "((?:6334|6767)\d{12}(?:\d\d)?\d?)",
      "mastercard" => "(5[1-5]\d{14})",
      "switch" => "(?:(?:(?:4903|4905|4911|4936|6333|6759)\d{12})|(?:(?:564182|633110)\d{10})(\d\d)?\d?)",
    );
    $names = array("Visa", "American Express", "JCB", "Maestro", "Solo", "Mastercard", "Switch");
    $matches = array();
    $pattern = "#^(?:".implode("|", $cards).")$#";
    $result = preg_match($pattern, str_replace(" ", "", $cc), $matches);
    if($extra_check && $result > 0){
      $result = (validatecard($cc))?1:0;
    }
    return ($result>0)?$names[sizeof($matches)-2]:false;
  }

}

?>
