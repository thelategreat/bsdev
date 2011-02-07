<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 */

class Products_model extends Model
{

  function __construct()
  {
    parent::__construct();
	}
	
	// this is used in the admin area
	function product_list( $query, $page = 1, $page_size = 25 )
	{
		$this->db->select('id, ean, title, contributor ');
		
		if( strlen($query) ) {
			$terms = explode(' ', $query );
			foreach( $terms as $term ) {
				if( strlen(trim($term)) > 0 ) {
					$this->db->or_like( array('title' => $term) );
				}
			}
		}
		
		$this->db->order_by( 'title' );
		$this->db->limit( $page_size, ($page - 1) * $page_size );
		return $this->db->get('products');
	}
	
	// this comes form the front end
	function product_search( $query, $page = 1, $page_size = 10 )
	{
		//$this->db->query("SELECT * FROM products LIMIT $page_size");
    $this->db->order_by( 'bnc_sales_total', 'DESC' );
		$this->db->limit( $page_size, ($page - 1) * $page_size );
		return $this->db->get('products');
	}
	
	function get_product( $id )
	{
		$this->db->where(array('id'=>$id));
		return $this->db->get( 'products' );
	}
	
	function get_product_by_ean( $ean )
	{
		$this->db->where(array('ean'=>$ean));
		return $this->db->get( 'products' );		
	}
	
}