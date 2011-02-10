<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

include("admin_controller.php");

class Comments extends Admin_Controller
{

	function __construct()
	{
		parent::__construct();
    $this->load->model('comments_model');
    $this->load->helper('textile');
	}

	function index()
	{
		$page_size = $this->config->item('list_page_size');
		$page = 1;
		
		// 4th seg is page number, if present
		if( $this->uri->segment(4) && is_numeric($this->uri->segment(4))) {
			$page = $this->uri->segment(4);
			if( $page < 1 ) {
				$page = 1;
			}
		}
		
		$comments = $this->comments_model->get_comments_queue( $page, $page_size );
				
		$view_data = array( 
			'comments' => $comments,
			'pager' => mk_pager( $page, $page_size, $comments->num_rows(), '/admin/comments/index' ),
			);
		
		$this->gen_page('Admin - Comments', 'admin/comments/comments_list', $view_data );		
	}
	
	function approve()
	{
		$id = (int)$this->uri->segment(4);
		if( $id ) {
			$this->db->set('approved', '1');
			$this->db->where('id', $id );
			$this->db->update('comments');
		}
		redirect('/admin/comments');
	}
	
	function remove()
	{
		$id = (int)$this->uri->segment(4);
		if( $id) {
			$this->db->where('id', $id );
			$this->db->delete('comments');
		}		
		redirect('/admin/comments');
	}
	
}