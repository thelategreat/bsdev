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
		
		$item = $this->products_model->get_product( (int)$id );
		if( $item->num_rows() > 0 ) {
			$item = $item->row();
      $img_path = 'product/' . substr($item->ean, -1) . '/' . $item->ean . '.jpg';
			if( !file_exists( $img_path )) {
				$item->image = '/img/image_not_found.jpg';
			} else {
	      $item->image = '/' . $img_path;				
			}
		} else {
			redirect('/browse');
		}
		
		$view_data = array(
			'item' => $item
		);
		
    $pg_data = $this->get_page_data('Bookshelf - Product', 'product' );
    $pg_data['content'] = $this->load->view('product/product_view', $view_data, true);
    $this->load->view('layouts/standard_page', $pg_data );
	}
	
}