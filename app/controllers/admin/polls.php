<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Polls extends Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::Controller();
		$this->auth->restrict_role(array('admin','editor'));
		$this->load->helper('url','form');
		$this->load->library('tabs');
		$this->load->library('form_validation');
		$this->load->model('polls_model');
	}
	
	/**
	 *
	 */
	function index()
	{
		$polls = $this->polls_model->get_poll_list();
		
		$view_data = array( 'polls' => $polls );
		
		$pg_data = array(
			'title' => 'Admin - Polls',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'footer' => $this->load->view('layouts/admin_footer', '', true),
			'content' => $this->load->view('admin/polls/polls_index', $view_data, true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
	}

	/**
	 *
	 */
	function add()
	{
		$poll = new stdClass();
		$poll->id = -1;
		$poll->question = '';
		$poll->answers = array();
		
		$view_data = array( 'poll' => $poll );
		
		$pg_data = array(
			'title' => 'Admin - Polls',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'footer' => $this->load->view('layouts/admin_footer', '', true),
			'content' => $this->load->view('admin/polls/poll_edit', $view_data, true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
	}

	/**
	 *
	 */
	function edit()
	{
		$poll = $this->polls_model->get_poll( $this->uri->segment(4) );
		
		$view_data = array( 'poll' => $poll );
		
		$pg_data = array(
			'title' => 'Admin - Polls',
			'nav' => $this->load->view('layouts/admin_nav', '', true),
			'footer' => $this->load->view('layouts/admin_footer', '', true),
			'content' => $this->load->view('admin/polls/poll_edit', $view_data, true)
		);
		$this->load->view('layouts/admin_page', $pg_data );
	}

	/**
	 *
	 */
	function rm()
	{
		$this->polls_model->rm_poll( $this->uri->segment(4) );
		redirect('/admin/polls');
	}

	/**
	 *
	 */
	function save()
	{		
		$error = '';
		$id = $this->input->post('id');
		$ques = $this->input->post('question');
		$ans = $this->input->post('answers');
		if( $id && $ques && $ans ) {
			$ans = explode('||', $ans );
			if( $id == -1 ) {
				$this->polls_model->add_poll( $ques, $ans );
			} else {
				$this->polls_model->update_poll( $id, $ques, $ans );
			}
		}		
				
		echo json_encode(array('error'=>$error));
	}
}

?>