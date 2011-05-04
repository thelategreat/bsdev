<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Orders extends Admin_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::__construct();
    $this->load->model('order_model');
	}
	
	/**
	 *
	 */
	function index()
  {
  	$page_size = $this->config->item('list_page_size');
		$page = 1;
		$query = '';
		
		// 4th seg is page number, if present
		if( $this->uri->segment(4) && is_numeric($this->uri->segment(4))) {
			$page = $this->uri->segment(4);
			if( $page < 1 ) {
				$page = 1;
			}
    }

		// seg 5 and beyond are search terms
		$i = 5;
		while( $this->uri->segment($i) ) {
			// CI thing with _
			$query .= str_replace('_',' ',$this->uri->segment($i)) . ' ';
			$i++;
		}

		if( $this->input->post('q')) {
			$query = $this->input->post('q');
		}
	
    $orders = $this->order_model->get_orders( $page, $page_size );
    
		$view_data = array( 
			'orders' => $orders,
			'pager' => mk_pager( $page, $page_size, $orders->num_rows(), '/admin/orders/index', $query ),
			'query' => $query
		);
		
		$this->gen_page('Admin - Orders', 'admin/orders/orders_list', $view_data );
  }

  function edit()
  {
		$this->gen_page('Admin - Orders', 'n/a' );
  }

}
