<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

include("admin_controller.php");

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
		$this->load->model('articles_model');
	}

	/**
	 *
	 */
	function index()
	{
		//$result = $this->products_model->getProduct(9780001837119);
		//$result = $this->products_model->searchProduct('penguin');
		//new dBug($result);die;

		/*
		$page_size = $this->config->item('list_page_size');
		$page = 1;
		$query = '';

		// 4th seg is page number
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

		//echo '[' . $query . ']';
		$prods = $this->products_model->product_list( $query, $page, $page_size );
		//echo '[' . $this->db->last_query() . ']';

		$view_data = array(
			'products' => $prods,
			'pager' => mk_pager( $page, $page_size, $prods->num_rows(), "$this->base_url/index", $query ),
			'query' => $query
		);

		$this->gen_page('Admin - Products', 'admin/products/products_list', $view_data );
		*/
	
		$query = $this->input->post('q');
		$prods = array();
		$total = 0;

		if ($query) $prods = $this->products_model->searchProduct($query, 100);
		
		if (isset($prods) && $prods !== false) {
			$total = count($prods);
		}

		$page_size = $this->config->item('list_page_size');
		$page = 1;

		$view_data = array(
			'products' => $prods,
			'pager' => mk_pager( $page, $page_size, $total, "$this->base_url/index", $query ),
			'q' => $query
		);

		$this->gen_page('Admin - Products', 'admin/products/index', $view_data);
	}

	// TODO
	function edit($id = false)
  {
    $id = (int)$id;

    if( !$id ) {
		redirect($this->base_url);
    }

    if( $this->input->post('cancel')) {
      redirect($this->base_url);
    }
	
	if( $this->input->post('save')) {
			$this->form_validation->set_error_delimiters('<span class="form_error">','</span>');
			$this->form_validation->set_rules('title','Title','trim|required');
			$this->form_validation->set_rules('ean','EAN','trim|required|min_length[12]|max_length[13]');
			$this->form_validation->set_rules('bs_price','Sale Price','trim|required|numeric');
			if( $this->form_validation->run()) {
        $this->db->set('title', $this->input->post('title'));
        redirect($this->base_url);
      }
    }

    $product = $this->products_model->getProduct( $id );

    if ($product == false) {
    	redirect($this->base_url);
    }

    $options['record_statuses'] = $this->products_model->getOptions('record_statuses', 'id', 'name');
    $options['record_types'] = $this->products_model->getOptions('record_types', 'id', 'name');
    $options['record_sources'] = $this->products_model->getOptions('record_sources', 'id', 'name');
    $options['format_codes'] = $this->products_model->getOptions('format_codes', 'id', 'code');
    $options['product_category'] = $this->products_model->getOptions('product_category', 'id', 'name');
    

    $view_data = array(
      'product' => $product,
      'options' => $options
    );
		$this->gen_page('Admin - Products', 'admin/products/product_edit', $view_data );
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
		$conf['max_size'] = 20000;

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

	// do_schema = true for just the schema calc
	private function do_import( $fname, $do_schema = false )
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

					// do the import.
					if( $do_schema == false ) {
						$this->import_cols( $headers, $cols, true );
					}
				}
				$line_count++;
			}
			fclose( $fh );
		}

		// gen the schema
		if( $do_schema ) {
			echo '<pre>' . $this->gen_schema( 'products', $headers ) . '</pre>';
			exit;
		}
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
	private function import_cols( $headers, $cols, $do_insert = true, $do_update = true )
	{
			$isbn = trim($cols[0]);
			$res = $this->db->query("SELECT id FROM products WHERE ean = '${isbn}'");
			if( $res->num_rows() > 0 ) {
				if( $do_update ) {
					$query = "UPDATE products SET ";
					for( $i = 0; $i < count($cols); $i++) {
						$query .= ($headers[$i][0] . '=' . $this->cleaner($cols[$i]) . ', ');
					}
					$row = $res->row();
					$query = substr($query,0,-2) . ' WHERE id = ' . $row->id;
					$this->db->query( $query );
					//echo $query . '<br/>';
					//break;
				}
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


    /* In case the way product images are referenced this abstract image references */
    private function get_product_img( $ean ) {
        if ( strlen($ean) != 13 ) return false;

        $prefix = $ean[12];
        $path = '/product/' . $prefix . '/' . $ean . '.jpg';

        return $path;
    }

    function article_products_browser() {
		$is_ajax = true;
        $article_id = $this->input->post('article_id');

		if( !$article_id) {
            return false;
        }

        $products = $this->articles_model->get_products( $article_id );
        
		$view_data = array(
			'article_id' => $article_id,
			'errors' => '',
            'files' => $products
			);

		$this->load->view('admin/products/article_products_browser', $view_data );
    }
}
