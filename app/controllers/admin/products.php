<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

/*
create table product (
	id int(11) NOT NULL AUTO_INCREMENT,
	prod_type int(11) NOT NULL,
	
	id_number varchar(20), 
	title varchar(240), 
	contributor varchar(240), 
	con_type varchar(60), 
	con_type_text varchar(240), 
	con_country varchar(50), 
	publisher varchar(75), 
	publishing_date varchar(20), 
	bs_sub_code varchar(25), 
	bisac_code varchar(75), 
	bisac_text varchar(240), 
	series varchar(150), 
	bs_binding_code varchar(1), 
	binding_code varchar(2), 
	binding_detail_code varchar(4), 
	binding_text varchar(50), 
	pages varchar(4), 
	size varchar(50), 
	othertext_ann varchar(20), 
	othertext_rev varchar(2), 
	list_price varchar(0), 
	tbm_cover varchar(1), 
	record_source varchar(4), 
	bs_rec varchar(10), 
	bs_oh varchar(30), 
	bs_oo varchar(30), 
	bs_days varchar(10), 
	bs_sales varchar(30), 
	bs_price varchar(60), 
	bnc_sales varchar(60), 
	bnc_cover_size varchar(30), 
	no_cover varchar(10),
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
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
			$prev_page = "<a class='small' href='/admin/products/index/".($page-1)."'>⇐ prev</a>";
		}
		if( $prods->num_rows() == $page_size ) {
			$next_page = "<a class='small' href='/admin/products/index/".($page+1)."'>next ⇒</a>";
		}
		
		$view_data = array( 
			'products' => $prods,
			'prev_page' => $prev_page,
			'next_page' => $next_page,
			'query' => $query
		);
		
		$this->gen_page('Admin - Products', 'admin/products/products_index', $view_data );
	}

	function edit()
	{
		redirect('/admin/products');
	}


	function add()
	{
		redirect('/admin/products');
	}


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
			redirect('/admin/products');
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

					// do the import
					$this->import_cols( $headers, $cols );
				}
				$line_count++;
			}
			fclose( $fh );
		}
		
		// gen the schema
		//echo '<pre>' . $this->gen_schema( 'products', $headers ) . '</pre>';			
	}
		
	
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
	
	private function cleaner( $str )
	{
		$str = trim($str);
		// sometimes excel decides to quote everything
		$str = preg_replace('/^"/', '', $str );
		$str = preg_replace( '/"$/', '', $str );
		$str = $this->db->escape( $str );
		return $str;
	}
	
	private function import_cols( $headers, $cols )
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