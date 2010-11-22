<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

include("admin_controller.php");

class Wiki extends Admin_Controller
{

	function __construct()
	{
		parent::__construct();
    $this->load->model('wiki_model');
    $this->load->helper('textile');
	}

	function _remap()
	{
    $page_name = $this->uri->segment(3);
    if( !$page_name ) {
      $page_name = 'Index';
    }
    
    $editing = false;
    if( $this->uri->segment(4,'') == 'edit' ) {
      $editing = true;
    }
    if( $this->uri->segment(4,'') == 'history' ) {
      $this->history( $page_name );
      return;
    }

    if( $this->input->post('save')) {
      $id = $this->input->post('id');
      $title = $this->input->post('title');
      $body = $this->input->post('bodytext');
      if( $id == -1 ) {
        $this->wiki_model->add_page( $title, $body, $this->session->userdata('logged_user') );
      } else {
        $this->wiki_model->update_page( $id, $title, $body, $this->session->userdata('logged_user') );
      }
      $editing = false;
    }

    $page = $this->wiki_model->get_page( $page_name );
    
    if( !$page ) {
      $page = new StdClass();
      $page->id = -1;
      $page->title = $page_name;
      $page->body = '';
      $editing = true;
    } else {
      if( !$editing ) {
        $page->body = textile_text($page->body);
      }
    }


		$pg_data = array(
      'page' => $page,
      'errors' => ''
			);

    if( $editing ) {
      $this->gen_page('Admin - Wiki', 'admin/wiki/page_edit', $pg_data );
    } else {
      $this->gen_page('Admin - Wiki', 'admin/wiki/page_view', $pg_data );
    }
	}

  function history( $page_name )
  {
    $page = $this->wiki_model->get_page( $page_name );
    if( $page ) {
      $revisions = $this->wiki_model->get_revisions( $page->id );
      $revisions = $revisions->result();
    } else {
      $revisions = array();
      $page = new StdClass();
      $page->title = $page_name;
    }

    $pg_data = array(
      'page' => $page,
      'revisions' => $revisions,
      'errors' => ''
      );

    $this->gen_page('Admin - Wiki', 'admin/wiki/page_history', $pg_data );
  }

}
