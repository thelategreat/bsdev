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
		parent::Controller();
		
		$this->config->load('site_config');
		
		$this->load->helper('form');		
	}


	function index()
	{	
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
	
	function addfake()
	{
		$this->cart->insert( 
			array( 
				'id' => 'sku_123ABC', 
				'qty' => 1, 
				'price' => 39.95, 
				'name' => 'The Girl With The Dragon Tattoo', 
				'options' => array('Format' => 'Hardcover')
			)
		);
		
		redirect('/cart');
	}
	
}