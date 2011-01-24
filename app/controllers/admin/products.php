<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");


/*
Issues:
othertext utf - 9781559393409,9781439156957
othertext has double entry (see above)
multiple contributors are the same 9781926708133
ampersand has extra ; in title, perhaps 9781551119250 (count 486)
othertext all bold and centered? 9781550416510
othertext seems cutoff somehow 9780938317456
othertext bold issue 9780553385465,9780552159692,9780385661638,9780385660761
othetext href issue 9780195419092
othertext tag removed with no space inserted ?? 9781443100144 "swept up in thefight for"
othertext with div tags 9781401931179
othertext transform & tags 9781895636659
*/

class Products extends Admin_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::__construct();
		$this->base_url = '/admin/products';
		$this->load->model('products_model');
	}
	
	/**
	 *
	 */
	function index()
	{
		$page_size = $this->config->item('list_page_size');
		$page = 1;

		if( $this->uri->segment(4) && is_numeric($this->uri->segment(4))) {
			$page = $this->uri->segment(4);
			if( $page < 1 ) {
				$page = 1;
			}
		}		
		$query = NULL;
		
		if( $this->input->post('q')) {
			$query = $this->input->post('q');
		}
		
		$prods = $this->products_model->product_list( $query, $page, $page_size );

		// pagination
		$next_page = '';
		$prev_page = '';
		if( $page > 1 ) {
			$prev_page = "<a class='small' href='$this->base_url/index/".($page-1)."'>⇐ prev</a>";
		}
		if( $prods->num_rows() == $page_size ) {
			$next_page = "<a class='small' href='$this->base_url/index/".($page+1)."'>next ⇒</a>";
		}
		
		$view_data = array( 
			'products' => $prods,
			'prev_page' => $prev_page,
			'next_page' => $next_page,
			'query' => $query
		);
		
		$this->gen_page('Admin - Products', 'admin/products/products_index', $view_data );
	}

	// TODO
	function edit()
	{
		redirect( $this->base_url );
	}

	// TODO
	function add()
	{
		redirect( $this->base_url );
	}

	// public call, does the import perhaps
	function import()
	{
		$conf['upload_path'] = '../tmp';
		$conf['allowed_types'] = 'txt|tab|gz';
		$conf['max_size'] = 10000;
		
		$this->load->library('upload', $conf );
		if( !$this->upload->do_upload()) {
			echo $this->upload->display_errors();
		} else {
			$fdata = $this->upload->data();
			$fname = $fdata['full_path'];
			$this->do_import( $fname );
			@unlink( $fname );			
			redirect( $this->base_url );
		}
	}
			
	private function do_import( $fname )
	{		
		$pinfo = pathinfo($fname);

		if( $pinfo['extension'] == 'gz' ) {
			system("/usr/bin/gunzip $fname");
			$fname = substr($fname, 0, -3 );
		}

		$line_count = 0;
		$fh = @fopen( $fname, 'r' );
		$headers = array();
		
		if( $fh ) {
			while(($line = fgets($fh)) !== false ) {
				$cols = explode("\t", $line );
				if( $line_count == 0 ) {
					// grab the field names
					for( $i = 0; $i < count($cols); $i++ ) {
						$headers[] = array(strtolower($cols[$i]),0);
					}
				}
				else {
					
					// get the max field sizes so we can gen the schema for the table
					// this went thru a bunch of iterations so i needed a way to calc
					// the spec easily
					for( $i = 0; $i < count($cols); $i++ ) {
						if( $headers[$i][1] < strlen(trim($cols[$i]))) {
							$headers[$i][1] = strlen(trim($cols[$i]));
						}
					}

					// do the import. comment this out if we are gen a schema
					$this->import_cols( $headers, $cols, false );
				}
				$line_count++;
			}
			fclose( $fh );
		}
		
		// gen the schema
		//echo '<pre>' . $this->gen_schema( 'products', $headers ) . '</pre>';			
	}
		
	/**
	 * generate a schema based on names and lengths found in headers
	 */
	private function gen_schema( $table_name, $headers )
	{
		$s = "CREATE TABLE `$table_name` ( \n";
		$s .= "  id int(11) NOT NULL AUTO_INCREMENT,\n";
		$s .= "  prod_type int(11) NOT NULL,\n";
		
		for( $i = 0; $i < count($headers); $i++ ) {
			$len = $headers[$i][1];
			if( $len == 0 ) {
				$len = 10;
			} else {
				$len = (int)((int)$len * 1.1);
			}
			$s .= "  " . strtolower(trim($headers[$i][0])) . ' varchar(' . $len . "),\n";
		}
		$s .= "  PRIMARY KEY (`id`)\n) ENGINE=InnoDB DEFAULT CHARSET=utf8;\n";
		return $s;
	}
	
	/**
	 * cleans out junk, quoted strings, and db-escapes
	 */
	private function cleaner( $str )
	{
		$str = trim($str);
		// sometimes excel decides to quote everything
		$str = preg_replace('/^"/', '', $str );
		$str = preg_replace( '/"$/', '', $str );
		$str = $this->db->escape( $str );
		return $str;
	}
	
	/**
	 * does the actual import of columns based on names, ean must be the first col
	 * but the rest can be in any order
	 */
	private function import_cols( $headers, $cols, $do_insert = true )
	{
			$isbn = trim($cols[0]);
			$res = $this->db->query("SELECT id FROM products WHERE ean = '${isbn}'");
			if( $res->num_rows() > 0 ) {
				$query = "UPDATE products SET ";
				for( $i = 0; $i < count($cols); $i++) {
					$query .= ($headers[$i][0] . '=' . $this->cleaner($cols[$i]) . ', ');
				}
				$row = $res->row();
				$query = substr($query,0,-2) . ' WHERE id = ' . $row->id;
				$this->db->query( $query );
				//echo $query . '<br/>';
				//break;
				
			} else {
				// this because when import othertext, for instance, the record may not exist
				// and we don't want to create it here
				if( $do_insert ) {
					$query = "INSERT INTO products (prod_type, ";
					$values = '1, ';
					for( $i = 0; $i < count($cols); $i++ ) {
						$query .= $headers[$i][0] . ', ';
						$values .= ($this->cleaner($cols[$i]) . ", ");
					}
					$query = substr($query,0,-2) . ') VALUES (' . substr($values,0,-2) . ')';
					//echo $query . '<br/>';
					//break;
					$this->db->query( $query );
				}
		}		
	}
	
}