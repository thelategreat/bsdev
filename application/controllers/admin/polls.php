<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

include("admin_controller.php");

class Polls extends Admin_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::__construct();
		$this->load->model('polls_model');
	}
	
	/**
	 *
	 */
	function index()
	{
		$polls = $this->polls_model->get_poll_list();
		
		$view_data = array( 'polls' => $polls );
		
		$this->gen_page('Admin - Polls', 'admin/polls/polls_index', $view_data );
	}

	/**
	 *
	 */
	function add()
	{
		$poll = new stdClass();
		$poll->id = -1;
		$poll->question = '';
		$poll->poll_date = '';
		$poll->poll_end_date = '';
		$poll->answers = array();
		
		$view_data = array( 'poll' => $poll );
		
		$this->gen_page('Admin - Polls', 'admin/polls/poll_edit', $view_data );		
	}

	/**
	 *
	 */
	function edit()
	{
		$id = (int)$this->uri->segment(4);
		if( !$id ) {
			redirect('/admin/polls');
		}
		
		$poll = $this->polls_model->get_poll( $id );
		
		if( !$poll ) {
			redirect('/admin/polls');			
		}
				
		$view_data = array( 'poll' => $poll );
		
		$this->gen_page('Admin - Polls', 'admin/polls/poll_edit', $view_data );
	}

  /**
   *
   */
  function view()
  {
		$id = (int)$this->uri->segment(4);
		if( !$id ) {
			redirect('/admin/polls');
		}
		
		$poll = $this->polls_model->get_poll( $id );
		
		if( !$poll ) {
			redirect('/admin/polls');			
    }

		$view_data = array( 'poll' => $poll );
		
		$this->gen_page('Admin - Polls', 'admin/polls/poll_view', $view_data );
  }

	/**
	 *
	 */
	function rm()
	{
		$id = (int)$this->uri->segment(4);
		if( !$id ) {
			redirect('/admin/polls');
		}
		
		$this->polls_model->rm_poll( $id );
		redirect('/admin/polls');
	}

  /**
   * ajax
   */
  function vote()
  {
    $id = $this->input->post('id');
    $this->polls_model->vote( $id );
    echo json_encode(array('ok'=>true,'msg'=>'your vote has been registered'));
  }

	/**
	 *
	 */
	function save()
	{		
		$error = '';
		$id = $this->input->post('id');
		
		$ques = $this->input->post('question');
		$start = $this->input->post('poll_date');
		$end = $this->input->post('poll_end_date');
		$ans = $this->input->post('answers');
		if( $id && $ques && $ans ) {
			$ans = explode('||', $ans );
			if( $id == -1 ) {
				$this->polls_model->add_poll( $ques, $ans );
			} else {
				$this->polls_model->update_poll( $id, $ques, $start, $end, $ans );
			}
		}		
				
		echo json_encode(array('error'=>$error));
	}
}

?>
