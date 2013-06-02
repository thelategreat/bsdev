<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product extends MY_Controller
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::__construct();
		$this->load->model('products_model');
	}


	function index()
	{
		redirect('/browse');
	}

	function view( $id )
	{
		$this->db->db_select();		
		$item = $this->products_model->getProduct( (int)$id );

		if( $item !== false ) {
      		$img_path = 'product/' . substr($item->ean, -1) . '/' . $item->ean . '.jpg';
			if( !file_exists( $img_path )) {
				$item->image = '/img/image_not_found.jpg';
			} else {
	      $item->image = '/' . $img_path;				
			}
		} else {
			redirect('/browse');
		}

		/* If there's no defined sell price use the list price */
		if ($item->sell_price == 0) $item->sell_price = $item->list_price;
		//new dBug($item);

		$nav['main'] = 'section';
		$nav['sub'] = false;
		$view_data = array(
			'item' => $item,
			'nav' => $nav
		);
		
    $pg_data = $this->get_page_data('Bookshelf - Product', 'product' );

    $pg_data['content'] = $this->load->view('product/product_view', $view_data, true);
    $this->load->view('layouts/standard_page', $pg_data );
	}
	
}