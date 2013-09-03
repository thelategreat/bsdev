<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 */

class Products_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();

    // Connect the products database which is separate from everything else
    $this->dbp = $this->load->database('prod', true);
    $this->db->db_select();
    
    $this->site_db = $this->config->item('site_db');
    $this->prod_db = $this->config->item('prod_db');
}

/* Select a single product by EAN
	@param int EAN code
	@returns Product object (NOT db result) or FALSE if nothing is found */
function getProduct($id) {
	$this->dbp->db_select();

	$sql = "SELECT products.*,
					record_statuses.name as record_status,
					record_statuses.id as record_status_id,
					record_types.name as record_type,
					record_types.id as record_types_id,
					record_sources.id as record_source_id,
					record_sources.name as record_source,
					publishers.name as publisher_name,
					publishers.id as publisher_id,
					series.id as series_id,
					series.name as series_name,
					format_codes.id as format_code_id,
					format_codes.code as format,
					product_category.id as product_category_id,
					product_category.name as category,
					onix_bind_codes.text as onix_bind_code,
					audiences.name as audience,	
					products.pages as num_pages,			
					GROUP_CONCAT(c.name SEPARATOR '|') as contributor, pub.name as publisher,
					GROUP_CONCAT(bisac.text SEPARATOR '|') as bisac_text


					FROM products
			LEFT JOIN products_contributors pc ON pc.products_id = products.id
			LEFT JOIN contributors c ON pc.contributors_id = c.id 
			LEFT JOIN publishers pub ON pub.id = products.publisher_id
			LEFT JOIN record_statuses ON record_status_id = record_statuses.id
			LEFT JOIN record_types ON record_types_id = record_types.id
			LEFT JOIN record_sources ON record_source_id = record_sources.id
			LEFT JOIN publishers ON publisher_id = publishers.id
			LEFT JOIN series ON series_id = series.id
			LEFT JOIN format_codes ON format_code_id = format_codes.id
			LEFT JOIN product_category ON category_id = product_category.id
			LEFT JOIN onix_bind_codes ON onix_bind_codes_id = onix_bind_codes.id
			LEFT JOIN audiences ON audiences_id = audiences.id
			LEFT JOIN products_bisac ON products_bisac.products_id = products.id 
			LEFT JOIN bisac ON products_bisac.bisac_id = bisac.id
			WHERE products.id = $id ";

	$result = $this->dbp->query($sql);
	
	if ($result->num_rows() == 0) { 
		$this->dbp->db_select();
		return false;
	}
	
	$item = $result->row();
	$sql = "SELECT othertext_codes.name as type, othertext.text as text 
			FROM products
			LEFT JOIN product_othertext ON products.id = product_othertext.products_id 
			LEFT JOIN othertext ON othertext.id = product_othertext.othertext_id 
			LEFT JOIN othertext_codes ON othertext_codes.id = othertext_codes_id
			WHERE products.id = {$item->id}";

	$item->othertext = $this->dbp->query($sql)->result();

	// Find the contributors
	$sql = "SELECT
				contributors.id,
				contributors.name,
				contributor_types.name as type
			FROM
				products
			LEFT JOIN products_contributors ON products_contributors.products_id = products.id
			LEFT JOIN CONTRIBUTORS ON CONTRIBUTORS .id = products_contributors.contributors_id
			LEFT JOIN contributor_types ON contributors.contributor_types_id = contributor_types.id
			WHERE
				products.id = {$id}
			ORDER BY contributors.name";
	$item->contributors = $this->dbp->query($sql)->result();


	// Find the subjects
	$sql = "SELECT
				bisac.text AS text
			FROM
				products
			LEFT JOIN products_bisac ON products_bisac.products_id = products.id 
			LEFT JOIN bisac ON products_bisac.bisac_id = bisac.id
			WHERE products.id = $id 
			ORDER BY text";
	$item->subject = $this->dbp->query($sql)->result();


	// Find the suppliers
	$sql = "SELECT
				prodsuppliers.*,
				CASE WHEN LOWER(prodsuppliers.ship_days) = 'in stock' THEN
					0
				ELSE 
					prodsuppliers.ship_days
				END as ship_days,
				supplier_codes.code as supplier_code,
				supplier_codes.description as supplier_description
			FROM
				products_prodsuppliers pp
			LEFT JOIN products ON products.id = pp.products_id
			LEFT JOIN prodsuppliers ON prodsuppliers.id = pp.prodsuppliers_id
			LEFT JOIN supplier_codes ON supplier_code_id = supplier_codes.id
			WHERE
				products_id = {$id}";
	$item->suppliers = $this->dbp->query($sql)->result();

	// Find the minimum shipping time (could be done by a query but this should work)
	$days = array();
	foreach ($item->suppliers as $it) {
		$days[] = $it->ship_days;
	}
	$item->ship_days = min($days);

	$this->db->db_select();

	$sql = "SELECT articles.title, 
					articles.id,
					articles.excerpt,
					articles.author, 
					articles.display_priority
			FROM articles_products
			LEFT JOIN articles ON articles_products.articles_id = articles.id
			WHERE products_id = {$id}";
	$item->associated_essays = $this->db->query($sql)->result();

	$this->db->db_select();
	return $item;
}

function searchProduct($terms, $limit) {
	$this->dbp->db_select();

	if ($terms) foreach ($terms as &$term) {
		$term = strtolower($this->dbp->escape_like_str($term));
		$term = str_replace('*', '%', $term);
		$term = str_replace('?', '_', $term);
	}

	$keys = array();
	if ($terms) foreach ($terms as $key=>$val) {
		if ($val != null) $keys[$key] = true;
	}

	$sql = "SELECT 'product' as type, products.*, c.name as contributor, pub.name as publisher FROM products 
			LEFT JOIN products_contributors pc ON pc.products_id = products.id
			LEFT JOIN contributors c ON pc.contributors_id = c.id 
			LEFT JOIN publishers pub ON pub.id = products.publisher_id
			WHERE true ";
	if (isset($keys['title'])) {
		$sql .= " AND LOWER(products.title) LIKE '{$terms['title']}'";
	}
	if (isset($keys['contributor'])) {
		$sql .= " AND LOWER(c.name) LIKE '{$terms['contributor']}'";
	}
	if (isset($keys['publisher'])) {
		$sql .= " AND LOWER(pub.name) LIKE '{$terms['publisher']}'";
	}
	if (isset($keys['ean'])) {
		$sql .= " AND ean LIKE '{$terms['ean']}'";
	}

	// This is used for an OR search across all terms
	if (isset($keys['all'])) {
		$sql .= " AND ( LOWER(products.title) LIKE '%{$terms['all']}%' 
						OR LOWER(c.name) LIKE '%{$terms['all']}%'
						OR LOWER(pub.name) LIKE '%{$terms['all']}%'
						OR ean LIKE '%{$terms['all']}%' )";
	}
	$sql .= " ORDER BY title
				LIMIT $limit";
	
	$query = $this->dbp->query($sql);
	$this->db->db_select();

	return $query->result();
}
	

	function getOptions($table, $key, $field, $null=true) {
		$this->dbp->db_select();
		$sql = "SELECT $key, $field FROM $table";
		$query = $this->dbp->query($sql);
		$results =  $query->result_array();
		
		$out = array();
		if ($null) {
			$out[' '] = '';
		}
		foreach ($results as $result) {
			$out[$result[$key]] = $result[$field];
		}

		$this->db->db_select();
		return $out;

	}
	// this is used in the admin area
	function product_list( $query, $page = 1, $page_size = 25 )
	{

		$this->db->db_select();
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