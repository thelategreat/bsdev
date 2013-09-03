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
    $this->load->model('articles_model');
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
    $list->can_delete = true;
    $list->items = array();
		
		$view_data = array( 'list' => $list, 'adding' => true );
		
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
				
		$view_data = array( 'list' => $list, 'adding' => false );
		
		$this->gen_page('Admin - Lists', 'admin/lists/list_edit', $view_data );
	}

  // ajax
  function search()
  {
		$ret = array('ok' => true, 'msg' => '', 'data' => array() );

    $q = $this->input->post('q');
    if( $q && strlen( $q )) {
      $page = (int)$this->input->post('page');
      $res = $this->articles_model->get_article_list(NULL, $page, 10, $q );
      $data = array();
      foreach( $res->result() as $row ) {
        $data[] = array('id' => $row->id, 'title' => $row->title );
      }
      $ret['data'] = $data;
    }

    echo json_encode( $ret );
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

  /**
    Ajax function to save a list from the admin area
    @param List ID
    @param List name
    @param List items - array with title, type, id
  */ 
	function save()
	{		
		$error = '';
		$id = $this->input->post('id');
    $name = $this->input->post('name');
    $items = $this->input->post('items');
    $can_delete = NULL;
    if( $this->session->userdata('logged_user_role') == 'admin' ) {
      $can_delete = $this->input->post('can_delete');  
    }

    $success = true;
    if( $id && $name ) {
			$items = json_decode( $items, true ); 
      if( $id == -1 ) {
        $owner = $this->session->userdata('logged_user', NULL );
				$success = $this->lists_model->add_list( $name, $owner, $can_delete, $items );
			} else {
				$success = $this->lists_model->update_list( $id, $name, $can_delete, $items );
			}
		}		
		
    $out = array();
    if ($success !== true) {
      $out['success'] = 'true';
    }	else {
      $out['success'] = 'false';
      $out['error'] = $success;
    }	

		echo json_encode($out);
	}

} //  
