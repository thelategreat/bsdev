<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');   

class Comment extends MY_Controller 
{
	/**
	 * CTOR
	 *
	 * @return void
	 **/
	function __construct()
	{
		parent::__construct();
		$this->load->model('comments_model');
	}
	
	/**
	 * Home page
	 *
	 * @return void
	 **/
	function index()
	{
		redirect('/');
	}
	
	function add()
	{		
		$comment = $this->input->post('comment');
		$table = $this->input->post('type');
		$table_id = (int)$this->input->post('id');
		$user_id = $this->session->userdata('logged_user_id');
		
		if( $comment && $table && $table_id && $user_id && strlen(strip_tags($comment)) > 0 && strlen(trim($table)) > 0 ) {
			$this->comments_model->add_comment( trim($table), $table_id, $user_id, strip_tags($comment) );
		}
		
		if( $this->input->post('redir')) {
			redirect($this->input->post('redir'));			
		}
		redirect('/');
	}

  // ajax
  function vote()
  {
    $id = $this->input->post('id');
    $vote = $this->input->post('vote');
    if( $id && $vote ) {
      $this->comments_model->vote( $id, $vote );
      echo json_encode(array('ok'=>true,'msg'=>'thanks for voting!'));
    } else { 
      echo json_encode(array('ok'=>false,'msg'=>'unable to register your vote'));
    }
  }

}

?>
