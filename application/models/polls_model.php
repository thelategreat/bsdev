<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class polls_model extends CI_Model
{
	/**
	 *
	 */
  function __construct()
  {
    parent::__construct();
  }

	/**
	 *
	 */
	function get_poll_list()
	{
		$q = "SELECT * FROM polls ORDER BY poll_date DESC";
		$res = $this->db->query( $q );
		return $res;
	}

  function get_current_poll()
  {
    $poll = $this->get_poll_list()->row();
    return $this->get_poll( $poll->id );
  }

	/**
	 *
	 */
	function get_poll( $id )
	{
		$q = "SELECT * FROM polls WHERE id = " . intval($id);
		$res = $this->db->query( $q );
		$poll = NULL;
		if( $res->num_rows() ) {
			$row = $res->row();
			$poll = new stdClass();
			$poll->id = $row->id;
			$poll->question = $row->question;
			$poll->poll_date = $row->poll_date;
			$poll->poll_end_date = $row->poll_end_date;
			$poll->answers = array();
			$res = $this->db->query("SELECT * FROM poll_answers WHERE poll_id = " . $poll->id . " ORDER BY sort_order");
      foreach( $res->result() as $row ) {
        $ans = new stdClass;
        $ans->id = $row->id;
        $ans->answer = $row->answer;
        $ans->count = $row->count;
				$poll->answers[] = $ans;
			}
		}
		return $poll;
	}

	/**
	 *
	 */
	function add_poll( $question, $answers )
	{
		$this->db->set('question', $question );
		$this->db->set('poll_date', 'NOW()', FALSE );
		$this->db->set('poll_end_date', 'NOW()', FALSE );
		$this->db->insert('polls');
		$id = $this->db->insert_id();
		
		for( $i = 0; $i < count($answers); $i++ ) {
			if( strlen(trim($answers[$i]))) {
				$this->db->set('poll_id', $id );
				$this->db->set('answer', $answers[$i]);
				$this->db->set('count', 0 );
				$this->db->set('sort_order', $i + 1);
				$this->db->insert('poll_answers');
			}
		}
	}
	
	/**
	 *
	 */
	function update_poll( $id, $question, $start, $end, $answers )
	{
		$this->db->set('question', $question );
		$this->db->set('poll_date', $start );
		$this->db->set('poll_end_date', $end );
		$this->db->where( 'id', $id );
		$this->db->update('polls');
		
		// lock?
		$this->db->where( 'poll_id', $id );
		$this->db->delete( 'poll_answers' );
		for( $i = 0; $i < count($answers); $i++ ) {
			if( strlen(trim($answers[$i]))) {
				$this->db->set('poll_id', $id );
				$this->db->set('answer', $answers[$i]);
				$this->db->set('count', 0 );
				$this->db->set('sort_order', $i + 1);
				$this->db->insert('poll_answers');
			}
		}
	}
	
	/**
	 *
	 */
	function rm_poll( $id )
	{
		//
		$this->db->where('poll_id', intval($id));
		$this->db->delete('poll_answers');
		//
		$this->db->where('id', intval($id));
		$this->db->delete('polls');
	}


  function vote( $answer_id )
  {
    $this->db->query("UPDATE poll_answers SET count = count + 1 WHERE id = $answer_id");
  }
}

?>
