<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Browse extends MY_Controller
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::Controller();
		$this->load->model('products_model');
	}

	/**
	 * Home page
	 *
	 * @return void
	 **/
	function _remap( $what )
	{
		$page = 1;
		$page_size = 10;
    $products = array();
		$page = $this->uri->segment(3);
		if( !$page || (int)$page == 0 ) {
			$page = 1;
		}

		$res = $this->products_model->product_search( NULL, $page, $page_size );

    foreach( $res->result() as $product ) {
      $img_path = 'product/' . substr($product->ean, -1) . '/' . $product->ean . '.jpg';
			if( !file_exists( $img_path )) {
				$product->image = '/img/image_not_found.jpg';
			} else {
	      $product->image = '/' . $img_path;				
			}
      $products[] = $product;
    }

		$pagination = '<table style="width: 100%;"><tr>';
		if( $page > 1 ) {
			$prev_page = $page - 1;
			$pagination .= "<td><a href='/browse/books/$prev_page' title='...prev page'><img src='/img/big_feature_left_arrow.png'/></a></td>";
		} else {
			$pagination .= '<td/>';
		}
		if( count($products) == $page_size ) {
			$next_page = $page + 1;
			$pagination .= "<td align='right'><a href='/browse/books/$next_page' title='next page...'><img src='/img/big_feature_right_arrow.png'/></a></td>";			
		} else {
			$pagination .= '<td/>';			
		}
		$pagination .= '</tr></table>';


    $view_data = array(
			'pagination' => $pagination,
      'products' => $products
      );

    $pg_data = $this->get_page_data('Bookshelf - Home', 'home' );

    $pg_data['content'] = $this->load->view('product/product', $view_data, true);

    $this->load->view('layouts/standard_page', $pg_data );
	}

}
