<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

// cart library is autoloaded
class Cart extends MY_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::__construct();
		
		$this->config->load('site_config');
		$this->load->model('order_model');
		$this->load->model('products_model');
		$this->load->helper('form');		
	}


	function index()
	{	
    if( $this->input->post("checkout")) {
      redirect("/cart/checkout");
    }
    for( $i = 1; $i <= $this->cart->total_items(); $i++ ) {
			$item = $this->input->post($i);
			if( $item ) {
				$this->cart->update( array('rowid' => $item['rowid'], 'qty' => $item['qty']));
			}
		}
				
		$data = array( 'cart' => $this->cart );
		$pg_data = $this->get_page_data('Bookshelf - cart', 'home' );
  	$pg_data['content'] = $this->load->view('cart/cart_list', $data, true);
		$this->load->view('layouts/standard_page', $pg_data );
	}
	
	function additem()
	{
    $id = $this->uri->segment(3);
    if( $id ) {
      $item = $this->products_model->get_product( $id );
      if( $item->num_rows() > 0 ) {
        $item = $item->row();

        $this->cart->insert( 
          array( 
            'id' => $item->id, 
            'qty' => 1, 
            'price' => $item->bs_price, 
            'name' => $item->title 
            //'options' => array('Format' => 'Hardcover')
          )
        );
      }
    }
		redirect('/cart');
	}

  function checkout()
  {
		if( !$this->auth->logged_in()) {
			$this->session->set_flashdata('login_redir', '/cart/checkout' );
			redirect('/profile/login');
			exit();
    }

    if( $this->input->post('shiptype')) {
      $this->session->set_userdata('shiptype',$this->input->post('shiptype'));
    }

    // try and grab the saved info from the user account
    if( !$this->session->userdata('shipto')) {
      $this->db->where('username',$this->auth->username());
      $userinfo = $this->db->get('users');
      if( $userinfo->num_rows() == 1 ) {
        $row = $userinfo->row();
        if( $row->shipto && trim($row->shipto) != "" ) {
          $this->session->set_userdata('shipto', $row->shipto );
        }
        if( $row->billto && trim($row->billto) != "" ) {
          $this->session->set_userdata('billto', $row->billto );
        }
        if( $row->ccno && trim($row->ccno) != "" ) {
          $ccno = $row->ccno;
          $ccname = $row->ccname;
          $ccexp = $row->ccexp;
          // validate credit card
          $cctype = $this->check_cc( $ccno );
          if( $cctype != false && strlen($ccname) && strlen($ccexp) == 5 ) {
            $this->session->set_userdata('ccno', $ccno);
            $this->session->set_userdata('ccno_disp', $cctype . ':***' . substr($ccno,-4) );
            $this->session->set_userdata('ccname', $ccname);
            $this->session->set_userdata('ccexp', $ccexp);
          }
          $this->session->set_userdata('ccno', $row->ccno );
        }
      }
    }

    if( $this->cart->total_items() == 0 ) {
      redirect("/cart");
    }

    //
    if( ($shipto = $this->input->post('shipto'))) {
      $this->session->set_userdata('shipto', $shipto );
    }
    
    // 
    if( ($billto = $this->input->post('billto'))) {
      $this->session->set_userdata('billto', $billto );
    }  
    // 
    if( $this->input->post('ccno')) {
      $ccno = $this->input->post('ccno');
      $ccname = $this->input->post('ccname');
      $ccexp = $this->input->post('ccexp');
      // validate credit card
      $cctype = $this->check_cc( $ccno );
      if( $cctype != false && strlen($ccname) && strlen($ccexp) == 5 ) {
        $this->session->set_userdata('ccno', $ccno);
        $this->session->set_userdata('ccno_disp', $cctype . ':***' . substr($ccno,-4) );
        $this->session->set_userdata('ccname', $ccname);
        $this->session->set_userdata('ccexp', $ccexp);
      }
    }  
    // gabe info the from the session 
    $order_info = $this->get_order_info();

    // now figure out what page we need to be on
    if( !$order_info['shipto']) {
      $this->shipto( );
    }
    else if( !$order_info['billto'] || !$order_info['ccno'] || !$order_info['ccname'] || !$order_info['ccexp']) {
      $this->billing( );
    }
    else {
      $this->review( $order_info );
    }
  }

  function order( )
  {
    if( $this->cart->total_items() == 0 ) {
      redirect("/cart");
    }

    $order_info = $this->get_order_info();

    $order_num = $this->order_model->add_order( $order_info, $this->cart->contents() );

    $this->cart->destroy();

 		$data = array( 'cart' => $this->cart, 'order_info' => $order_info, 'order_num' => $order_num );
		$pg_data = $this->get_page_data('Bookshelf - Order Confirmation', 'home' );
  	$pg_data['content'] = $this->load->view('cart/co_complete', $data, true);
		$this->load->view('layouts/standard_page', $pg_data );
  }

  function shipto( )
  {
    if( $this->cart->total_items() == 0 ) {
      redirect("/cart");
    }

    $order_info = $this->get_order_info();
    $data = array( 'cart' => $this->cart, 'order_info' => $order_info );
    $pg_data = $this->get_page_data('Bookshelf - Shipping Information', 'home' );
  	$pg_data['content'] = $this->load->view('cart/co_shipto', $data, true);
		$this->load->view('layouts/standard_page', $pg_data );
  }

  function billing( )
  {
    if( $this->cart->total_items() == 0 ) {
      redirect("/cart");
    }

    $order_info = $this->get_order_info();
    $data = array( 'cart' => $this->cart, 'order_info' => $order_info );
		$pg_data = $this->get_page_data('Bookshelf - Billing Information', 'home' );
  	$pg_data['content'] = $this->load->view('cart/co_billing', $data, true);
		$this->load->view('layouts/standard_page', $pg_data );
  }

  private function review( $order_info )
  {
 		$data = array( 'cart' => $this->cart, 'order_info' => $order_info, 'ship_options' => $this->order_model->get_shipping_options() );
		$pg_data = $this->get_page_data('Bookshelf - Your Order', 'home' );
  	$pg_data['content'] = $this->load->view('cart/co_review', $data, true);
		$this->load->view('layouts/standard_page', $pg_data );
  }


  private function get_order_info()
  {
    $shiptype = $this->session->userdata('shiptype');
    if( !$shiptype ) {
      $shiptype = 'cpr';
    }

    return array(
      'user_id' => $this->auth->userid(),
      'ship_via' => $shiptype,
      'ship_cost' => $this->order_model->calc_shipping_cost($shiptype,'',''),
      'tax1' => 0.13,
      'tax2' => 0.0,
      'shipto' => $this->session->userdata('shipto'),
      'billto' => $this->session->userdata('billto'),
      'ccno' => $this->session->userdata('ccno'),
      'ccno_disp' => $this->session->userdata('ccno_disp'),
      'ccname' => $this->session->userdata('ccname'),
      'ccexp' => $this->session->userdata('ccexp'),
    );  
  }


  // move this somewhere special
  private function check_cc($cc, $extra_check = false)
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
