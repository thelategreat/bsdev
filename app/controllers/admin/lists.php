<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Lists extends Admin_Controller 
{
  /**
   * CTOR
   *
   * @return void
   */
  function __construct()
  {
    parent::__construct();
    $this->load->model('lists_model');
  }

  function index()
  {
    $lists = $this->lists_model->get_lists();
    
    $view_data = array( 'lists' => $lists );

    $this->gen_page('Admin - Lists', 'admin/lists/lists', $view_data );
  }

  function add()
	{
		$list = new stdClass();
		$list->id = -1;
    $list->name = '';
    $list->items = array();
		
		$view_data = array( 'list' => $list );
		
		$this->gen_page('Admin - Lists', 'admin/lists/list_edit', $view_data );		
	}

	/**
	 *
	 */
	function edit()
	{
		$id = (int)$this->uri->segment(4);
		if( !$id ) {
			redirect('/admin/lists');
		}
		
		$list = $this->lists_model->get_list( $id );
		
		if( !$list ) {
			redirect('/admin/lists');			
		}
				
		$view_data = array( 'list' => $list );
		
		$this->gen_page('Admin - Polls', 'admin/lists/list_edit', $view_data );
	}

  // ajax
  function addtolist()
  {
		$ret = array('ok' => false, 'msg' => 'Unable to add to list' );
    
    $list_id = (int)$this->input->post('listid');
    $url = $this->input->post('url');

    if( $list_id && strlen($url)) {
      $this->lists_model->add_to_list( $list_id, $url );
      $ret['ok'] = true;
      $ret['msg'] = "Added to: $list_id";
    } else {
      $ret['msg'] = 'No data';
    }
    
		echo json_encode( $ret );
  }

	/**
	 *
	 */
	function rm()
	{
		$id = (int)$this->uri->segment(4);
		if( !$id ) {
			redirect('/admin/lists');
		}
		
		$this->lists_model->rm_list( $id );
		redirect('/admin/lists');
	}

  // ajax
	function save()
	{		
		$error = '';
		$id = $this->input->post('id');
		
    $name = $this->input->post('name');
		$items = $this->input->post('items');
    if( $id && $name ) {
			$items = explode('||', $items );
      if( $id == -1 ) {
        $owner = $this->session->userdata('logged_user', NULL );
				$this->lists_model->add_list( $name, $owner, $items );
			} else {
				$this->lists_model->update_list( $id, $name, $items );
			}
		}		
				
		echo json_encode(array('error'=>$error));
	}

} //  
