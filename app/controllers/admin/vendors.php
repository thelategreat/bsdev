<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Vendors extends Admin_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
    parent::__construct();
    $this->url = '/admin/vendors';
    $this->load->model('vendor_model');
	}

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
	
    $vendors = $this->vendor_model->get_vendors( $page, $page_size );
    
		$view_data = array( 
			'vendors' => $vendors,
			'pager' => mk_pager( $page, $page_size, $vendors->num_rows(), '/admin/vendors/index', $query ),
			'query' => $query
		);
		
		$this->gen_page('Admin - Vendors', 'admin/vendors/vendors_list', $view_data );
  }

	function add()
	{
		if( $this->input->post('cancel')) {
			redirect($this->url);			
		}
		
		$this->form_validation->set_error_delimiters('<span class="form-error">','</span>');
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		
		if( $this->form_validation->run()) {
			$data = array();
			$data['name'] = $this->input->post('name');
			$data['vtype'] = $this->input->post('vtype');
      $data['status'] = $this->input->post('status');
			$data['currency'] = $this->input->post('currency');
			$data['commision'] = $this->input->post('commision');
			$data['order_method'] = $this->input->post('order_method');
			$data['cs_method'] = $this->input->post('cs_method');
      $this->vendor_model->add( $data ); 
			redirect($this->url);
		}

    $data = array();

		$this->gen_page('Admin - Vendors', 'admin/vendors/vendor_add', $data );		
	}



  function edit()
  {
    $id = $this->uri->segment(4);
    if( !$id ) {
      redirect("/admin/vendors");
    }

		if( $this->input->post('cancel')) {
			redirect($this->url);			
		}
		
		$this->form_validation->set_error_delimiters('<span class="form-error">','</span>');
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		
		if( $this->form_validation->run()) {
			$data = array();
			$data['name'] = $this->input->post('name');
			$data['vtype'] = $this->input->post('vtype');
      $data['status'] = $this->input->post('status');
			$data['currency'] = $this->input->post('currency');
			$data['commision'] = $this->input->post('commision');
			$data['order_method'] = $this->input->post('order_method');
			$data['cs_method'] = $this->input->post('cs_method');
      $this->vendor_model->update( $id, $data ); 
			redirect($this->url);
		}


    $vendor = $this->vendor_model->get_vendor( $id );

    $data = array(
      'vendor' => $vendor
    );

		$this->gen_page('Admin - Vendors', 'admin/vendors/vendor_edit', $data );		
  }


}
