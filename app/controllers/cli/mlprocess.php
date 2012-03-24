<?php

/**
 * This is the message queue processor
 * It is meant to be run from CLI only
 * call from cli or cron with:
 * $> php index.php cli mlprocess process
 */

function msg( $msg )
{
  echo $msg . "\n";
}


class MlProcess extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    // prevent this from running on the web
    if(!$this->input->is_cli_request()) {
      exit();
    }

    $this->load->model('maillist_model');
    $this->load->library('email');
  }

  public function process()
  {
    $job = $this->maillist_model->get_next_job();
    if( $job->num_rows() == 0 ) {
      msg( "No jobs" );
      exit();
    }
    // we only want first 1
    $job = $job->row();
    msg( 'Running job #' . $job->id . " list id: " . $job->ml_list_id );
    $subsc = $this->maillist_model->get_list_subscribers( $job->ml_list_id );
    msg( 'Subscribers: ' . $subsc->num_rows());
    
    // create the mail and send

    foreach( $subsc->result() as $subsc ) {
      // CI style
      $this->email->clear();
      $this->email->from( $this->config->item('email_from'), $this->config->item('email_from_name') );
      $this->email->to($subsc->fullname . ' <' . $subsc->email . '>');
      $this->email->subject( $job->subject );
      $this->email->message( $job->text_fmt );
      $this->email->set_alt_message( $job->text_plain );
      $this->email->send();
      $this->email->print_debugger();
      /*
      // PHP style
      $boundary = uniqid('jk');
      $headers = "Mime-Version: 1.0\r\n";
      $headers .= "From: " . $job->from . "\r\n";
      $headers .= "To: " . $subsc->fullname . " <" . $subsc->email . ">\r\n";
      $headers .= "Content-type: multipart/alternative; boundary=$boundary\r\n";
      
      $message = $job->text_plain;
      $message .= "\r\n\r\n--$boundary\r\n";
      $message .= "Content-type: text/html; charset=utf-8\r\n\r\n";
      $message .= $job->text_fmt;
      $message .= "\r\n\r\n--$boundary--";
      msg( $headers . $message );
      // mail( '',$job->subject,$message,$headers);
      msg( "========" );
       */
    }
    
  }
}
